<?php

// start the user session for maintaining individual user states during the multi-stage authentication flow:
if (!isset($_SESSION)) {
    session_start();
}

function add_question_mark($key) {
		if ($key == false || rtrim($key) == '') {
			return $key;
		}

		if( strpos($key, '?' ) !== false) {
			return rtrim($key);
		}

		return rtrim($key) . '?';
}

# DEFINE THE OAUTH PROVIDER AND SETTINGS TO USE #
$_SESSION['WPOA']['PROVIDER'] = 'custom';
define('HTTP_UTIL', get_option('wpoa_http_util'));
define('CLIENT_ENABLED', get_option('wpoa_custom_api_enabled'));
define('CLIENT_ID', get_option('wpoa_custom_api_id'));
define('CLIENT_SECRET', get_option('wpoa_custom_api_secret'));
define('REDIRECT_URI', rtrim(site_url(), '/') . '/');
define('SCOPE', get_option('wpoa_custom_api_scope'));
define('URL_AUTH', add_question_mark(get_option('wpoa_custom_api_auth_url')));
define('URL_TOKEN', add_question_mark(get_option('wpoa_custom_api_token_url')));
define('URL_USER', get_option('wpoa_custom_api_user_url'));
# END OF DEFINE THE OAUTH PROVIDER AND SETTINGS TO USE #

// remember the user's last url so we can redirect them back to there after the login ends:
if (!$_SESSION['WPOA']['LAST_URL']) {
	// try to obtain the redirect_url from the default login page:
	$redirect_url = esc_url($_GET['redirect_to']);
	// if no redirect_url was found, set it to the user's last page:
	if (!$redirect_url) {
		$redirect_url = strtok($_SERVER['HTTP_REFERER'], "?");
	}
	// set the user's last page so we can return that user there after they login:
	$_SESSION['WPOA']['LAST_URL'] = $redirect_url;
}

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
			$url = URL_TOKEN;

			$response = wp_remote_post(
				$url, array(
					'body' => $params,
					'sslverify' => get_option('wpoa_http_util_verify_ssl')
				)
			);

			if ( is_wp_error( $response ) ) {
			   $error_message = $response->get_error_message();
				 error_log("token failed retrival - " . $result);

			   $wpoa->wpoa_end_login( $error_message );
				 return false;
			}

			$result = $response['body'];

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
	$result_obj = json_decode($result, false, 512); // PROVIDER SPECIFIC: Google encodes the access token result as json by default
	$access_token = $result_obj->access_token; // PROVIDER SPECIFIC: this is how Google returns the access token KEEP THIS PROTECTED!

	$expires_in = $result_obj->expires_in; // PROVIDER SPECIFIC: this is how Google returns the access token's expiration
	$expires_at = time() + $expires_in;
	// handle the result:
	if (!$access_token || !$expires_in) {
		// malformed access token result detected:
		$wpoa->wpoa_end_login("Sorry, we couldn't log you in. Malformed access token result detected. Please notify the admin or try again later.");
	}
	else {
		$_SESSION['WPOA']['ACCESS_TOKEN'] = $access_token;
		$_SESSION['WPOA']['EXPIRES_IN'] = $expires_in;
		$_SESSION['WPOA']['EXPIRES_AT'] = $expires_at;
		return true;
	}
}

function get_oauth_identity($wpoa) {
	// here we exchange the access token for the user info...
	// set the access token param:
	$params = array(
		'access_token' => $_SESSION['WPOA']['ACCESS_TOKEN'], // PROVIDER SPECIFIC: the access_token is passed to Google via POST param
	);
	$url_params = http_build_query($params);
	$result = "";
	// perform the http request:
	switch (strtolower(HTTP_UTIL)) {
		case 'curl':
			$url = URL_USER;

			$response = wp_remote_get(
				$url,
				array(
					'headers' => array(
						'Authorization' => 'Bearer ' . $_SESSION['WPOA']['ACCESS_TOKEN']
					)
				)
			);

			if ( is_wp_error( $response ) ) {
			   $error_message = $response->get_error_message();
				 error_log("userinfo failed retrival - " . $result);

			   $wpoa->wpoa_end_login( $error_message );
				 return false;
			}

			$result = $response['body'];

			$result_obj = json_decode($result, true);
			break;
		case 'stream-context':
			$url = rtrim(URL_USER, "?");
			$opts = array('http' =>
				array(
					'method'  => 'GET',
					// PROVIDER NORMALIZATION: Reddit/Github User-Agent
					'header'  => "Authorization: Bearer " . $_SESSION['WPOA']['ACCESS_TOKEN'] . "\r\n" . "x-li-format: json\r\n", // PROVIDER SPECIFIC: i think only LinkedIn uses x-li-format...
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
	/*
	Response example from Keycloack
	{
	  "name": "Admin User",
	  "sub": "88dd0538-5f32-4222-af07-efe5cd809038",
	  "preferred_username": "admin@example.com",
	  "given_name": "Admin",
	  "family_name": "User",
	  "email": "admin@example.com"
	}
	{
		"error_description":"Token invalid: Token audience doesn't match domain. Token ....",
		"error":"invalid_grant"
	}
	*/
	// Check if we got an error message
	if( isset($result_obj["error"]) || strlen($result_obj["error"]) > 0 ) {
		$message = strlen($result_obj["error_description"]) > 0 ? $result_obj["error_description"] : $result_obj["error"];
		$wpoa->wpoa_end_login("Sorry, we could not log you in. " . $message);
		return $oauth_identity;
	}

	$objtype = get_option('wpoa_custom_api_identity_id');
	if ($objtype == null || $objtype == false || $objtype == '') {
		$objtype = 'id';
	}
	$oauth_identity['id'] = $result_obj[$objtype];
	$objtype = get_option('wpoa_custom_api_identity_preferred_username');
	if ($objtype == null || $objtype == false || $objtype == '') {
		$objtype = 'preferred_username';
	}
	$oauth_identity['preferred_username'] = $result_obj[$objtype];

	if (!$oauth_identity['id']) {
		// $wpoa->wpoa_end_login("Sorry, we couldn't log you in. User identity was not found: " . $_SESSION['WPOA']['ACCESS_TOKEN']);
		$wpoa->wpoa_end_login("Sorry, we could not log you in. User identity '" . $objtype . "' was not found. Please notify the admin or try again later.");
	}
	return $oauth_identity;
}
# END OF AUTHENTICATION FLOW HELPER FUNCTIONS #
?>
