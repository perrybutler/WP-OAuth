<?php

include_once 'session.php';
include_once 'login-common.php';

error_reporting(E_ALL | E_STRICT);

class LoginBattlenet extends AbstractLoginCommon {
    public function __construct($wpoa) {
        parent::__construct('Battle.net',
                "https://us.battle.net/oauth/authorize?",
                "https://us.battle.net/oauth/token?",
                "https://us.api.battle.net/account/user/id?",
                $wpoa);
    }

    function getOAuthIdentityParameters(){
        return array("schema" => "openid",
                "access_token" => WPOA_Session::get_token());
    }

    function getOAuthIdentityFillArray($result_obj, $oauth_identity){
        $oauth_identity[WPOA_Session::USER_ID] = $result_obj['id'];
        // TODO : Battlenet only send id. If they implement name and email, we must update the following line.
        $oauth_identity[WPOA_Session::USER_NAME] = $result_obj['id'];
        $oauth_identity[WPOA_Session::USER_EMAIL] = $result_obj['id'] . "@example.net";

        return $oauth_identity;
    }
}

$login = new LoginBattlenet($this);
$login->run();
