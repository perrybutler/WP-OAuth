<?php
/**
 * OAuth Provider: Office 365
 *
 * @package    WP-OAUTH
 * @version    1.0
 * @author     Nick Worth
 * @link       https://azure.microsoft.com/en-us/documentation/articles/active-directory-v2-protocols-oauth-code/
 */

# Start the user session for maintaining individual user states during the multi-stage authentication flow:
if (!isset($_SESSION)) session_start();

### DEFINE THE OAUTH PROVIDER AND SETTINGS TO USE ###
$_SESSION['WPOA']['PROVIDER'] = 'Office 365';
define('HTTP_UTIL', get_option('wpoa_http_util'));
define('CLIENT_ENABLED', get_option('wpoa_office365_api_enabled'));
define('CLIENT_ID', get_option('wpoa_office365_api_id'));
define('CLIENT_SECRET', get_option('wpoa_office365_api_secret'));
define('REDIRECT_URI', rtrim(site_url(), '/') . '/');
define('SCOPE', 'https://outlook.office.com/mail.read');

// PROVIDER SPECIFIC: value in the path of the request can be used to control who can sign into the application.
$tenant = (get_option('wpoa_office365_tenant') != '') ? get_option('wpoa_office365_tenant') : 'common';
define('URL_AUTH', "https://login.microsoftonline.com/".$tenant."/oauth2/v2.0/authorize?");
define('URL_TOKEN', "https://login.microsoftonline.com/".$tenant."/oauth2/v2.0/token");
define('URL_USER', "https://outlook.office.com/api/v2.0/me?");
### END OF DEFINE THE OAUTH PROVIDER AND SETTINGS TO USE ###

// Remember the user's last url so we can redirect them back to there after the login ends:
if (!$_SESSION['WPOA']['LAST_URL']) {$_SESSION['WPOA']['LAST_URL'] = strtok($_SERVER['HTTP_REFERER'], "?");}

### AUTHENTICATION FLOW ###
/* The oauth 2.0 authentication flow will start in this script and make several calls to the third-party authentication provider which in turn will make callbacks to this script that we continue to handle until the login completes with a success or failure: */
if (!CLIENT_ENABLED) {
	$this->wpoa_end_login("This third-party authentication provider has not been enabled. Please notify the admin or try again later.");
}
/* Do not proceed if id or secret is null */
elseif (!CLIENT_ID || !CLIENT_SECRET) {
	$this->wpoa_end_login("This third-party authentication provider has not been configured with an API key/secret. Please notify the admin or try again later.");
}
/* Do not proceed if an error was detected */
elseif (isset($_GET['error_description'])) {
	$this->wpoa_end_login($_GET['error_description']);
}
/* Do not proceed if an error was detected */
elseif (isset($_GET['error_message'])) {
	$this->wpoa_end_login($_GET['error_message']);
}
/* POST-auth phase, verify the state */
elseif (isset($_GET['code'])) {
	if ($_SESSION['WPOA']['STATE'] == $_GET['state']) {
		// get an access token from the third party provider:
		get_oauth_token($this);
		// get the user's third-party identity and attempt to login/register a matching wordpress user account:
		$oauth_identity = get_oauth_identity($this);
		$this->wpoa_login_user($oauth_identity);
	}
	/* Possible CSRF attack, end the login with a generic message 
	   to the user and a detailed message to the admin/logs in case of abuse */
	else {
		// TODO: report detailed message to admin/logs here...
		$this->wpoa_end_login("Sorry, we couldn't log you in. Please notify the admin or try again later.");
	}
}
/* PRE-auth, start the auth process */
else {
	if ((empty($_SESSION['WPOA']['EXPIRES_AT'])) || (time() > $_SESSION['WPOA']['EXPIRES_AT'])) {
		// expired token; clear the state:
		$this->wpoa_clear_login_state();
	}
	get_oauth_code($this);
}

/* NOTE: If we reach here something went wrong and was not accounted for */
$this->wpoa_end_login("Sorry, we couldn't log you in. The authentication flow terminated in an unexpected way. Please notify the admin or try again later.");
### END OF AUTHENTICATION FLOW ###

### AUTHENTICATION FLOW HELPER FUNCTIONS ###
function get_oauth_code($wpoa) {
	$params = array(
		'client_id' => CLIENT_ID,
		'redirect_uri' => REDIRECT_URI,
		'scope' => SCOPE,
		'response_type' => 'code',
		'response_mode' => 'query',
		'state' => uniqid('', true),
	);
	$_SESSION['WPOA']['STATE'] = $params['state'];
	$url = URL_AUTH . http_build_query($params);
	header("Location: $url");
	exit;
	#TODO: Add Error handling - https://azure.microsoft.com/en-us/documentation/articles/active-directory-protocols-oauth-code/#_error-response
}

/**
 * Gets the oauth token.
 *
 * @param      WPOA  $wpoa
 * @return     array
 */
function get_oauth_token($wpoa) {
	$params = array(
		'client_id' => CLIENT_ID,
		'client_secret' => CLIENT_SECRET,
		'code' => $_GET['code'],
		'redirect_uri' => REDIRECT_URI,
		'grant_type' => 'authorization_code',
	);
	$url_params = http_build_query($params);
	switch (strtolower(HTTP_UTIL)) {
		case 'curl':
			$url = URL_TOKEN;
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $url_params); // TODO: for Google we use $params...
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
	# PROVIDER SPECIFIC: Outlook REST encodes the access token result as json by default
	$result_obj = json_decode($result, true);
	# PROVIDER SPECIFIC: this is how Outlook REST returns the access token KEEP THIS PROTECTED!
	$access_token = isset($result_obj['access_token']) ? $result_obj['access_token'] : '';
	# PROVIDER SPECIFIC: this is how Outlook REST returns the access token's expiration
	$expires_in = isset($result_obj['expires_in']) ? $result_obj['expires_in'] : '';
	$expires_at = time() + $expires_in;
	# PROVIDER SPECIFIC: this is how Outlook REST returns the users data (keep for email matching)
	$id_token = isset($result_obj['id_token']) ? $result_obj['id_token'] : '';
	# Handle the result:
	if (!$access_token || !$expires_in) {
		// malformed access token result detected:
		$wpoa->wpoa_end_login("Sorry, we couldn't log you in. Malformed access token result detected. Please notify the admin or try again later.");
	} else {
		$_SESSION['WPOA']['ACCESS_TOKEN'] = $access_token;
		$_SESSION['WPOA']['EXPIRES_IN'] = $expires_in;
		$_SESSION['WPOA']['EXPIRES_AT'] = $expires_at;
		$_SESSION['WPOA']['ID_TOKEN'] = $id_token;
		return true;
	}
}

/**
 * Gets the oauth identity.
 *
 * @param      WPOA  $wpoa
 * @return     array
 */
function get_oauth_identity($wpoa) {
	// here we exchange the access token for the user info...
	// set the access token param:
	$params = array(
		'access_token' => $_SESSION['WPOA']['ACCESS_TOKEN'], // PROVIDER SPECIFIC: the access token is passed to Outlook REST using this key name
	);
	$url_params = http_build_query($params);
	// perform the http request:
	switch (strtolower(HTTP_UTIL)) {
		case 'curl':
			$url = rtrim(URL_USER, "?");
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // TODO: does Outlook REST require this?
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: bearer " . $_SESSION['WPOA']['ACCESS_TOKEN'])); // PROVIDER SPECIFIC: do we have to do this for Outlook REST?
			//curl_setopt($curl, CURLOPT_HTTPHEADER, array('x-li-format: json')); // PROVIDER SPECIFIC: I think this is only for LinkedIn...
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($curl);
			$result_obj = json_decode($result, true);
			break;
		case 'stream-context':
			$url = rtrim(URL_USER, "?");
			$opts = array('http' =>
				array(
					'method'  => 'GET',
					// PROVIDER NORMALIZATION: Reddit/Github requires User-Agent here...
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
	$oauth_identity['provider'] = (isset($_SESSION['WPOA']['PROVIDER'])) ? $_SESSION['WPOA']['PROVIDER'] : '';
	// PROVIDER SPECIFIC: Outlook REST returns the user's unique id
	$oauth_identity['id'] = (isset($result_obj['Id'])) ? $result_obj['Id'] : '';
	// PROVIDER SPECIFIC: Outlook REST returns the user's email address
	$oauth_identity['email'] = (isset($result_obj['EmailAddress'])) ? $result_obj['EmailAddress'] : ''; 
	if (!$oauth_identity['id']) {
		$wpoa->wpoa_end_login("Sorry, we couldn't log you in. User identity was not found. Please notify the admin or try again later.");
	}
	return $oauth_identity;
}
### END OF AUTHENTICATION FLOW HELPER FUNCTIONS ###

?>