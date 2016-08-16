<?php

include_once 'session.php';
include_once 'login-common.php';

error_reporting(E_ALL | E_STRICT);

class LoginWindowsLive extends AbstractLoginCommon {
	public function __construct($wpoa) {
		parent::__construct('Windows Live', 
			"https://login.microsoftonline.com/common/oauth2/v2.0/authorize?", 
			"https://login.microsoftonline.com/common/oauth2/v2.0/token", 
			"https://outlook.office.com/api/v2.0/me?",
			$wpoa);
		$this->useBearer = true;
	}

	
  function getOAuthCodeExtendArray($array){
	  // TODO : There is certainly another scope where to retrieve only user name and email...
	  $array['scope'] = 'https://outlook.office.com/contacts.read';
	  return $array;
  }

  function getOAuthTokenExtendArray($array) {
	  return $array;
  }
  
  function getOAuthIdentityParameters(){
	$params = array(
		'api-version' => "1.6",
	);
	return array();
  }
  
  function getOAuthIdentityFillArray($result_obj, $oauth_identity){
	$oauth_identity[WPOA_Session::USER_ID] = $result_obj['Id'];
	$oauth_identity[WPOA_Session::USER_NAME] = $result_obj['DisplayName'];
	$oauth_identity[WPOA_Session::USER_EMAIL] = $result_obj['EmailAddress'];

	return $oauth_identity;
  }
}

$login = new LoginWindowsLive($this);
$login->run();