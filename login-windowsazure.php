<?php

include_once 'session.php';
include_once 'login-common.php';

# DEFINE THE OAUTH PROVIDER AND SETTINGS TO USE #
WPOA_Session::set_provider('Windows Azure');
define('HTTP_UTIL', get_option('wpoa_http_util'));
define('CLIENT_ENABLED', get_option('wpoa_windowsazure_api_enabled'));
define('CLIENT_ID', get_option('wpoa_windowsazure_api_id'));
define('CLIENT_SECRET', get_option('wpoa_windowsazure_api_secret'));
define('TENANT_ID', get_option('wpoa_windowsazure_tenant_id'));
define('REDIRECT_URI', rtrim(site_url(), '/') . '/');
define('SCOPE', 'https%3A%2F%2Fgraph.microsoft.com%2Fmail.read');
define('URL_AUTH', "https://login.microsoftonline.com/" . TENANT_ID . "/oauth2/authorize?");
define('URL_TOKEN', "https://login.microsoftonline.com/" . TENANT_ID . "/oauth2/token");
define('GRAPH', "https://graph.windows.net/");
define('URL_USER', "GRAPH" . TENANT_ID . "/me?");

# END OF DEFINE THE OAUTH PROVIDER AND SETTINGS TO USE #

WPOA_Session::save_last_url();

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
	if (WPOA_Session::get_state() == $_GET['state']) {
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
	if ((empty(WPOA_Session::get_expires_at())) || (time() > WPOA_Session::get_expires_at())) {
		// expired token; clear the state:
		$this->wpoa_clear_login_state();
		WPOA_Session::set_provider('Windows Azure');
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
		'response_mode' => 'query',
		'client_id' => CLIENT_ID,
		'scope' => SCOPE,
		'state' => uniqid('', true),
		'redirect_uri' => REDIRECT_URI,
		'resource' => GRAPH
	);
	WPOA_Session::set_state($params['state']);
	$url = URL_AUTH . http_build_query($params);
	$logger->log("AZURE : Get Oauth code from : " . $url);
	header("Location: $url");
	exit;
}

function get_oauth_token($wpoa) {
	$logger->log("AZURE : get_oauth_token");
	$params = array(
		'grant_type' => 'authorization_code',
		'client_id' => CLIENT_ID,
		'client_secret' => CLIENT_SECRET,
		'code' => $_GET['code'],
		'redirect_uri' => REDIRECT_URI,
		'resource' => GRAPH,
	);
	$url_params = http_build_query($params);
	switch (strtolower(HTTP_UTIL)) {
		case 'curl':
			$url = URL_TOKEN . $url_params;
			$url = URL_TOKEN;
			$curl = init_curl($url);
			// curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params); // TODO: for Google we use $params...
			// PROVIDER NORMALIZATION: Reddit requires sending a User-Agent header...
			// PROVIDER NORMALIZATION: Reddit requires sending the client id/secret via http basic authentication...
			
			$result = exec_curl($curl, "Get oauth token");
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
	$result_obj = json_decode($result, true); // PROVIDER SPECIFIC: Windows Live encodes the access token result as json by default
	$access_token = $result_obj['access_token']; // PROVIDER SPECIFIC: this is how Windows Live returns the access token KEEP THIS PROTECTED!
	$expires_in = $result_obj['expires_in']; // PROVIDER SPECIFIC: this is how Windows Live returns the access token's expiration
	$expires_at = time() + $expires_in;
	// handle the result:
	if (!$access_token || !$expires_in) {
		// malformed access token result detected:
		$wpoa->wpoa_end_login("Sorry, we couldn't log you in. Malformed access token result detected. Please notify the admin or try again later.");
	}
	else {
		WPOA_Session::set_token($access_token);
		WPOA_Session::set_expires_in($expires_in);
		WPOA_Session::set_expires_at($expires_at);
		return true;
	}
}

function get_oauth_identity($wpoa) {
	$logger->log("AZURE : get_oauth_identity");
	// here we exchange the access token for the user info...
	// set the access token param:
	$access_token = WPOA_Session::get_token();
	$logger->log("AZURE : token = " .$access_token);
	$params = array(
		"api-version" => "1.6",
	);
	$url_params = http_build_query($params);
	// perform the http request:
	switch (strtolower(HTTP_UTIL)) {
		case 'curl':
			$url = GRAPH . TENANT_ID . "/me?";
			$url .= $url_params;
			$curl = init_curl($url);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // TODO: does Windows Live require this?
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $access_token)); // PROVIDER SPECIFIC: do we have to do this for Windows Live?
			// curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // PROVIDER SPECIFIC: I think this is only for LinkedIn...
			
			$result = exec_curl($curl, "get oauth identity");
			$result_obj = json_decode($result, true);
			break;
		case 'stream-context':
			$url = rtrim(URL_USER, "?");
			$opts = array('http' =>
				array(
					'method'  => 'GET',
					// PROVIDER NORMALIZATION: Reddit/Github requires User-Agent here...
					'header'  => "Authorization: Bearer " . WPOA_Session::get_token() . "\r\n" . "x-li-format: json\r\n", // PROVIDER SPECIFIC: i think only LinkedIn uses x-li-format...
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
	$oauth_identity[WPOA_Session::PROVIDER] = WPOA_Session::get_provider();
	$oauth_identity[WPOA_Session::USER_ID] = $result_obj['objectId']; // PROVIDER SPECIFIC: Google returns the user's OAuth identity as id
	$oauth_identity[WPOA_Session::USER_NAME] = $result_obj['displayName'];
	$oauth_identity[WPOA_Session::USER_EMAIL] = $result_obj['userPrincipalName'];
	if (!$oauth_identity[WPOA_Session::USER_ID]) {
		$wpoa->wpoa_end_login("Sorry, we couldn't log you in. User identity was not found. Please notify the admin or try again later.");
	}
	return $oauth_identity;
}
# END OF AUTHENTICATION FLOW HELPER FUNCTIONS #
?>
