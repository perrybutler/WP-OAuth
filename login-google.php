<?php

require_once 'session.php';
require_once 'login-common.php';

error_reporting(E_ALL | E_STRICT);

class LoginGoogle extends AbstractLoginCommon {
    public function __construct($wpoa) {
        parent::__construct('Google',
                "https://accounts.google.com/o/oauth2/auth?",
                "https://accounts.google.com/o/oauth2/token?",
                "https://www.googleapis.com/plus/v1/people/me?",
                $wpoa);
    }


    function getOAuthCodeExtendArray($array){
        $array['scope'] = 'email';
        return $array;
    }

    function getOAuthIdentityParameters(){
        $params = array(
                'access_token' => WPOA_Session::get_token(),
                );
        return $params;
    }

    function getOAuthIdentityFillArray($result_obj, $oauth_identity){
        $oauth_identity[WPOA_Session::USER_ID] = $result_obj['id'];
        $oauth_identity[WPOA_Session::USER_NAME] = $result_obj['displayName'];
        $oauth_identity[WPOA_Session::USER_EMAIL] = $result_obj['emails'][0]['value'];

        return $oauth_identity;
    }

    function getOauthIdentityPostTreatment($result_obj) {
        return $this->verify_domain($result_obj);
    }

    function verify_domain($base_result)
    {
        $allowedConfig = get_option('wpoa_google_check_domain');
        if (!$allowedConfig) {
            return $base_result;
        }

        $allowedConfig = str_replace(" ", "", $allowedConfig);
        $key = "domain";
        $value = "Gmail";
        if (array_key_exists($key, $base_result))
        {
            $value = $base_result[$key];
            $allowed = explode(",", $allowedConfig);
            if ($value && in_array($value, $allowed))
            {
                return $base_result;
            }
        }
        $this->wpoa->wpoa_end_login("Sorry, we couldn't log you in. Your ". $key ." is not allowed : " . $value);
        return $null;
    }
}


$google = new LoginGoogle($this);
$google->run();
