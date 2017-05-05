<?php

include_once 'session.php';
include_once 'login-common.php';

error_reporting(E_ALL | E_STRICT);

class LoginReddit extends AbstractLoginCommon {
    public function __construct($wpoa) {
        parent::__construct('Instagram',
                "https://api.instagram.com/oauth/authorize/?",
                "https://api.instagram.com/oauth/access_token?",
                "https://api.instagram.com/v1/users/self/",
                $wpoa);
		$this->ignoreExpiresIn = true;
    }

    function getOAuthCodeExtendArray($array) {
        $array['scope'] = 'basic';
        return $array;
    }

    function getOAuthIdentityParameters(){
        return array("access_token" => WPOA_Session::get_token());
    }

    function getOAuthIdentityFillArray($result_obj, $oauth_identity){
		$data = $result_obj['data'];
        $oauth_identity[WPOA_Session::USER_ID] = $data['id'];
        $oauth_identity[WPOA_Session::USER_NAME] = $data['username'];
        // TODO : Instagram does not send email. If they implement it, we must update the following line.
        $oauth_identity[WPOA_Session::USER_EMAIL] = $data['username'] . "@example.com";

        return $oauth_identity;
    }
}

$login = new LoginReddit($this);
$login->run();