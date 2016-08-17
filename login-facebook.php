<?php

include_once 'session.php';
include_once 'login-common.php';

error_reporting(E_ALL | E_STRICT);

class LoginFacebook extends AbstractLoginCommon {
    public function __construct($wpoa) {
        parent::__construct('Facebook',
                "https://www.facebook.com/dialog/oauth?",
                "https://graph.facebook.com/oauth/access_token?",
                "https://graph.facebook.com/me?",
                $wpoa);
    }

    function getOAuthCodeExtendArray($array) {
        $array['scope'] = 'email';
        return $array;
    }

    function getOAuthIdentityParameters() {
        return array(
                'access_token' => WPOA_Session::get_token(),
                'fields' => "id,name,email"
                );
    }

    function getOAuthIdentityFillArray($result_obj, $oauth_identity) {
        $oauth_identity[WPOA_Session::USER_ID] = $result_obj['id'];
        $oauth_identity[WPOA_Session::USER_NAME] = $result_obj['name'];
        $oauth_identity[WPOA_Session::USER_EMAIL] = $result_obj['email'];
        return $oauth_identity;
    }
}

$login = new LoginFacebook($this);
$login->run();
