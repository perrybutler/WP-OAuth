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

error_reporting(E_ALL | E_STRICT);

class LoginAzure extends AbstractLoginCommon {
	public function __construct($wpoa) {
		$tenant = get_option('wpoa_windowsazure_tenant_id');
		parent::__construct('Windows Azure', 
			"https://login.microsoftonline.com/$tenant/oauth2/authorize?", 
			"https://login.microsoftonline.com/$tenant/oauth2/token", 
			"https://graph.windows.net/$tenant/me?",
			$wpoa);
		$this->useBearer = true;
	}

	
  function getOAuthCodeExtendArray($array){
	  $array['scope'] = 'https%3A%2F%2Fgraph.microsoft.com%2Fmail.read';
	  $array['resource'] = "https://graph.windows.net";
	  return $array;
  }

  function getOAuthTokenExtendArray($array) {
	  $array['resource'] = "https://graph.windows.net";
	  return $array;
  }
  
  function getOAuthIdentityParameters(){
	$params = array(
		'api-version' => "1.6",
	);
	return $params;
  }
  
  function getOAuthIdentityFillArray($result_obj, $oauth_identity){
	$oauth_identity[WPOA_Session::USER_ID] = $result_obj['objectId'];
	$oauth_identity[WPOA_Session::USER_NAME] = $result_obj['displayName'];
	$oauth_identity[WPOA_Session::USER_EMAIL] = $result_obj['userPrincipalName'];

	return $oauth_identity;
  }
}


$azure = new LoginAzure($this);
$azure->run();