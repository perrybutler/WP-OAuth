<?php

include_once 'session.php';
include_once 'login-common.php';

error_reporting(E_ALL | E_STRICT);

class LoginGitHub extends AbstractLoginCommon {
    public function __construct($wpoa) {
        parent::__construct('Github',
                "https://github.com/login/oauth/authorize?",
                "https://github.com/login/oauth/access_token",
                "https://api.github.com/user",
                $wpoa);
        $this->ignoreExpiresIn = true;
    }

    function getOAuthCodeExtendArray($array){
        $array['scope'] = 'user:email';
        return $array;
    }

    function getOAuthIdentityParameters(){
        return array(
                'access_token' => WPOA_Session::get_token()
                );
    }

    function getOAuthIdentityFillArray($result_obj, $oauth_identity){
        $oauth_identity[WPOA_Session::USER_ID] = $result_obj['id'];
        $oauth_identity[WPOA_Session::USER_NAME] = $result_obj['name'];
        $oauth_identity[WPOA_Session::USER_EMAIL] = $result_obj['email'];

        return $oauth_identity;
    }

    function getOauthIdentityPostTreatment($result_obj) {
        $url = $this->getUrlUser() . "/emails";
        $emails = $this->getRequest($url , $this->id . ": Get Email");
        Logger::Instance()->dump($email);
        $result_obj['email'] = $emails[0]['email'];
        return $result_obj;
    }
}

$login = new LoginGitHub($this);
$login->run();