<?php

include_once 'session.php';
include_once 'login-common.php';

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
        return array('api-version' => "1.6");
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
