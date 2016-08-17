<?php

include_once 'session.php';
include_once 'login-common.php';

error_reporting(E_ALL | E_STRICT);

class LoginReddit extends AbstractLoginCommon {
    public function __construct($wpoa) {
        parent::__construct('Reddit',
                "https://ssl.reddit.com/api/v1/authorize?",
                "https://ssl.reddit.com/api/v1/access_token?",
                "https://oauth.reddit.com/api/v1/me",
                $wpoa);
        $this->useBearer = true;
        $this->sendIdAndSecretInHeader = true;
    }


    function getOAuthCodeExtendArray($array) {
        $array['scope'] = 'identity';
        return $array;
    }

    function getOAuthTokenExtendArray($array) {
        $array['client_id'] = null;
        $array['client_secret'] = null;

        return $array;
    }

    function getOAuthIdentityParameters(){
        return array("schema" => "openid",
                "access_token" => WPOA_Session::get_token());
    }

    function getOAuthIdentityFillArray($result_obj, $oauth_identity){
        $oauth_identity[WPOA_Session::USER_ID] = $result_obj['id'];
        $oauth_identity[WPOA_Session::USER_NAME] = $result_obj['name'];
        // TODO : Reddit does not send email. If they implement it, we must update the following line.
        $oauth_identity[WPOA_Session::USER_EMAIL] = $result_obj['name'] . "@reddit.com";

        return $oauth_identity;
    }
}

$login = new LoginReddit($this);
$login->run();
