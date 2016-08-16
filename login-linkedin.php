<?php

include_once 'session.php';
include_once 'login-common.php';


error_reporting(E_ALL | E_STRICT);

class LoginLinkedin extends AbstractLoginCommon {
  public function __construct($wpoa) {
    parent::__construct('LinkedIn', 
      "https://www.linkedin.com/oauth/v2/authorization?", 
      "https://www.linkedin.com/oauth/v2/accessToken", 
      "https://api.linkedin.com/v1/people/~:(id,email-address,formatted-name)?",
      $wpoa);
    $this->useBearer = true;
  }

  
  function getOAuthCodeExtendArray($array){
    $array['scope'] = 'r_basicprofile r_emailaddress';
    return $array;
  }
  
  function getOAuthIdentityParameters(){
  return array();
  }
  
  function getOAuthIdentityFillArray($result_obj, $oauth_identity){
  $oauth_identity[WPOA_Session::USER_ID] = $result_obj['id'];
  $oauth_identity[WPOA_Session::USER_NAME] = $result_obj['formattedName'];
  $oauth_identity[WPOA_Session::USER_EMAIL] = $result_obj['emailAddress'];

  return $oauth_identity;
  }
}


$login = new LoginLinkedin($this);
$login->run();