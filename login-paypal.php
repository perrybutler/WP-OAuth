<?php

include_once 'session.php';
include_once 'login-common.php';

error_reporting(E_ALL | E_STRICT);

class LoginPaypal extends AbstractLoginCommon {
  public function __construct($wpoa) {
    $isSandbox = get_option('wpoa_paypal_api_sandbox_mode');
    $sandBoxOrNothing = $isSandbox ? "sandbox." : "";
    
    parent::__construct('PayPal', 
      "https://www." . $sandBoxOrNothing . "paypal.com/webapps/auth/protocol/openidconnect/v1/authorize?",
      "https://api." . $sandBoxOrNothing . "paypal.com/v1/identity/openidconnect/tokenservice?",
      "https://api." . $sandBoxOrNothing . "paypal.com/v1/identity/openidconnect/userinfo/",
      $wpoa);
    $this->useBearer = true;
    $this->sendIdAndSecretInHeader = true;
  }

  
  function getOAuthCodeExtendArray($array){
    $array['scope'] = 'openid profile email';
    return $array;
  }

  function getOAuthTokenExtendArray($array) {
    $array['client_id'] = null;
    $array['client_secret'] = null;
    $array['redirect_uri'] = null;
    
    return $array;
  }
  
  function getOAuthIdentityParameters(){
    return array("schema" => "openid",
                 "access_token" => WPOA_Session::get_token());
  }
  
  function getOAuthIdentityFillArray($result_obj, $oauth_identity){
    $oauth_identity[WPOA_Session::USER_ID] = $result_obj['user_id'];
    $oauth_identity[WPOA_Session::USER_NAME] = $result_obj['name'];
    $oauth_identity[WPOA_Session::USER_EMAIL] = $result_obj['email'];

    return $oauth_identity;
  }
}

$login = new LoginPaypal($this);
$login->run();