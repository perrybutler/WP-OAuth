<?php

// start the user session for maintaining individual user states during the multi-stage authentication flow:
if (!isset($_SESSION)) {
    session_start();
}

# DEFINE THE OAUTH PROVIDER AND SETTINGS TO USE #
$_SESSION['WPOA']['PROVIDER'] = 'generic';
define('HTTP_UTIL', get_option('wpoa_http_util'));
define('CLIENT_ENABLED', get_option('wpoa_generic_api_enabled'));
define('CLIENT_ID', get_option('wpoa_generic_api_id'));
define('CLIENT_SECRET', get_option('wpoa_generic_api_secret'));
define('REDIRECT_URI', rtrim(site_url(), '/') . '/');
define('SCOPE', get_option('wpoa_generic_scope')); // PROVIDER SPECIFIC: "user" is the minimum scope required to get the user's id from Github
define('URL_AUTH', get_option('wpoa_generic_url_auth'));
define('URL_TOKEN', get_option('wpoa_generic_url_token'));
define('URL_USER', get_option('wpoa_generic_url_user'));
# END OF DEFINE THE OAUTH PROVIDER AND SETTINGS TO USE #

// remember the user's last url so we can redirect them back to there after the login ends:
if (!$_SESSION['WPOA']['LAST_URL']) {$_SESSION['WPOA']['LAST_URL'] = strtok($_SERVER['HTTP_REFERER'], "?");}

# AUTHENTICATION FLOW #
// the oauth 2.0 authentication flow will start in this script and make several calls to the third-party authentication provider which in turn will make callbacks to this script that we continue to handle until the login completes with a success or failure:
if (!CLIENT_ENABLED) {
	$this->wpoa_end_login("This third-party authentication provider has not been enabled. Please notify the admin or try again later.");
}
elseif (!CLIENT_ID || !CLIENT_SECRET) {
	// do not proceed if id or secret is null:
	$this->wpoa_end_login("This third-party authentication provider has not been configured with an API key/secret. Please notify the admin or try again later.");
}
elseif (isset($_GET['error_description'])) {
	// do not proceed if an error was detected:
	$this->wpoa_end_login($_GET['error_description']);
}
elseif (isset($_GET['error_message'])) {
	// do not proceed if an error was detected:
	$this->wpoa_end_login($_GET['error_message']);
}
elseif (isset($_GET['code'])) {
	// post-auth phase, verify the state:
	if ($_SESSION['WPOA']['STATE'] == $_GET['state']) {
		// get an access token from the third party provider:
		get_oauth_token($this);
		// get the user's third-party identity and attempt to login/register a matching wordpress user account:
		$oauth_identity = get_oauth_identity($this);
		$this->wpoa_login_user($oauth_identity);
	}
	else {
		// possible CSRF attack, end the login with a generic message to the user and a detailed message to the admin/logs in case of abuse:
		// TODO: report detailed message to admin/logs here...
		$this->wpoa_end_login("Sorry, we couldn't log you in. Please notify the admin or try again later.");
	}
}
else {
	// pre-auth, start the auth process:
	if ((empty($_SESSION['WPOA']['EXPIRES_AT'])) || (time() > $_SESSION['WPOA']['EXPIRES_AT'])) {
		// expired token; clear the state:
		$this->wpoa_clear_login_state();
	}
	get_oauth_code($this);
}
// we shouldn't be here, but just in case...
$this->wpoa_end_login("Sorry, we couldn't log you in. The authentication flow terminated in an unexpected way. Please notify the admin or try again later.");
# END OF AUTHENTICATION FLOW #

# AUTHENTICATION FLOW HELPER FUNCTIONS #
function get_oauth_code($wpoa) {
	$params = array(
		'response_type' => 'code',
		'client_id' => CLIENT_ID,
		'scope' => SCOPE,
		'state' => uniqid('', true),
		'redirect_uri' => REDIRECT_URI,
	);
	$_SESSION['WPOA']['STATE'] = $params['state'];
	$url = URL_AUTH . http_build_query($params);
	header("Location: $url");
	exit;
}

function get_oauth_token($wpoa) {
	$params = array(
		'grant_type' => 'authorization_code',
		'client_id' => CLIENT_ID,
		'client_secret' => CLIENT_SECRET,
		'code' => $_GET['code'],
		'redirect_uri' => REDIRECT_URI,
	);
	$url_params = http_build_query($params);
	switch (strtolower(HTTP_UTIL)) {
		case 'curl':
			$url = URL_TOKEN . $url_params;
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
			// PROVIDER NORMALIZATION: Reddit requires sending a User-Agent header...
			// PROVIDER NORMALIZATION: Reddit requires sending the client id/secret via http basic authentication
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, (get_option('wpoa_http_util_verify_ssl') == 1 ? 1 : 0));
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, (get_option('wpoa_http_util_verify_ssl') == 1 ? 2 : 0));
			$result = curl_exec($curl);
			break;
		case 'stream-context':
			$url = rtrim(URL_TOKEN, "?");
			$opts = array('http' =>
				array(
					'method'  => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => $url_params,
				)
			);
			$context = $context  = stream_context_create($opts);
			$result = @file_get_contents($url, false, $context);
			if ($result === false) {
				$wpoa->wpoa_end_login("Sorry, we couldn't log you in. Could not retrieve access token via stream context. Please notify the admin or try again later.");
			}
			break;
	}
	// parse the result:
	$result_obj = json_decode($result, true); // PROVIDER SPECIFIC: Github encodes the access token result as a querystring by default
	$access_token = $result_obj['access_token']; // PROVIDER SPECIFIC: this is how Github returns the access token KEEP THIS PROTECTED!
	//$expires_in = $result_obj['expires_in']; // PROVIDER SPECIFIC: Github does not return an access token expiration!
	//$expires_at = time() + $expires_in; // PROVIDER SPECIFIC: Github does not return an access token expiration!
	// handle the result:
	if (!$access_token) {
		// malformed access token result detected:
		$wpoa->wpoa_end_login("Sorry, we couldn't log you in. Malformed access token result detected. Please notify the admin or try again later.");
	}
	else {
		$_SESSION['WPOA']['ACCESS_TOKEN'] = $access_token;
		//$_SESSION['WPOA']['EXPIRES_IN'] = $expires_in; // PROVIDER SPECIFIC: Github does not return an access token expiration!
		//$_SESSION['WPOA']['EXPIRES_AT'] = $expires_at; // PROVIDER SPECIFIC: Github does not return an access token expiration!
		return true;
	}
}

function get_oauth_identity($wpoa) {
	// here we exchange the access token for the user info...
	// set the access token param:
	$params = array(
		'access_token' => $_SESSION['WPOA']['ACCESS_TOKEN'], // PROVIDER SPECIFIC: the access token is passed to Github using this key name
	);
	$url_params = http_build_query($params);
	// perform the http request:
	switch (strtolower(HTTP_UTIL)) {
		case 'curl':
			$url = URL_USER . $url_params; // TODO: we probably want to send this using a curl_setopt...
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // PROVIDER SPECIFIC: Github requires the useragent for all api requests
			// PROVIDER NORMALIZATION: Reddit requires that we send the access token via a bearer header...
			//curl_setopt($curl, CURLOPT_HTTPHEADER, array('x-li-format: json')); // PROVIDER SPECIFIC: I think this is only for LinkedIn...
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($curl);
			$result_obj = json_decode($result, true);
			break;
		case 'stream-context':
			$url = rtrim(URL_USER, "?");
			$opts = array('http' =>
				array(
					'method' => 'GET',
					'user_agent' => $_SERVER['HTTP_USER_AGENT'], // PROVIDER NOTE: Github requires the useragent for all api requests
					'header'  => "Authorization: token " . $_SESSION['WPOA']['ACCESS_TOKEN'],
				)
			);
			$context = $context  = stream_context_create($opts);
			$result = @file_get_contents($url, false, $context);
			if ($result === false) {
				$wpoa->wpoa_end_login("Sorry, we couldn't log you in. Could not retrieve user identity via stream context. Please notify the admin or try again later.");
			}
			$result_obj = json_decode($result, true);
			break;
	}
	// parse and return the user's oauth identity:
	$oauth_identity = array();
	$oauth_identity['provider'] = $_SESSION['WPOA']['PROVIDER'];
	$oauth_identity['id'] = $result_obj['id']; // PROVIDER SPECIFIC: this is how Github returns the user's unique id
	//$oauth_identity['email'] = $result_obj['email']; //PROVIDER SPECIFIC: this is how Github returns the email address
	if (!$oauth_identity['id']) {
		$wpoa->wpoa_end_login("Sorry, we couldn't log you in. User identity was not found. Please notify the admin or try again later.");
	}
	return $oauth_identity;
}
# END OF AUTHENTICATION FLOW HELPER FUNCTIONS #
?>
