<?php
require_once "logger.php";

error_reporting(E_ALL | E_STRICT);

function init_curl($url) {
    Logger::Instance()->log("CURL : Init with url = " . $url);
    $curl = curl_init($url);
    $verify_ssl= get_option('wpoa_http_util_verify_ssl');
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, ($verify_ssl == 1 ? 1 : 0));
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, ($verify_ssl == 1 ? 2 : 0));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    //curl_setopt($curl, CURLOPT_VERBOSE, true);
    //curl_setopt($curl, CURLOPT_STDERR, fopen('C:\tmp\php\wordpress\wp-content\debug.log', 'a'));
    return $curl;
}


function exec_curl($curl, $message = "") {
    $result = curl_exec($curl);

    if(!$result) {
        $error = curl_error($curl);
        Logger::Instance()->log("CURL : error : " . $message);
        trigger_error($error);
    }

    curl_close($curl);
    Logger::Instance()->log("CURL : exec reult = " . $result);
    return $result;

}

abstract class AbstractLoginCommon {
    private $id;
    private $name;
    private $wpoa;

    private $urlAuth;
    private $urlToken;
    private $urlUser;

    protected function getUrlUser() {
        return $this->urlUser;
    }

    private $clientEnabled;
    private $clientId;
    private $clientSecret;

    private $redirectUri;

    private $requestMode;

    protected $useBearer = false;
    protected $ignoreExpiresIn = false;
    protected $sendIdAndSecretInHeader = false;

    protected function __construct($name, $urlAuth, $urlToken, $urlUser, $wpoa) {
        $this->id = $this->clean($name);
        $this->name = $name;
        $this->wpoa = $wpoa;

        $this->urlAuth = $urlAuth;
        $this->urlToken = $urlToken;
        $this->urlUser = $urlUser;
        $base = "wpoa_" . $this->id;
        $this->clientEnabled = get_option($base . '_api_enabled');
        $this->clientId = get_option($base . '_api_id');
        $this->clientSecret = get_option($base . '_api_secret');

        Logger::Instance()->log("CONSTRUCT STATE : enabled = $this->clientEnabled / id = $this->clientId / secret = $this->clientSecret");

        $this->redirectUri = rtrim(site_url(), '/') . '/';
        $this->requestMode = strtolower(get_option('wpoa_http_util'));
    }

    private function clean($value) {
        $result = strtolower($value);
        $result = str_replace(" ", "", $result);
        $result = str_replace(".", "", $result);
        return $result;
    }

    public function run() {
        WPOA_Session::save_last_url();
        WPOA_Session::set_provider($this->name);
        if (!$this->clientEnabled) {
            $this->wpoa->wpoa_end_login("This third-party authentication provider has not been enabled. Please notify the admin or try again later.");
        }
        elseif (!$this->clientId || !$this->clientSecret) {
            $this->wpoa->wpoa_end_login("This third-party authentication provider has not been configured with an API key/secret. Please notify the admin or try again later.");
        }
        elseif (isset($_GET['error_description'])) {
            $this->wpoa->wpoa_end_login($_GET['error_description']);
        }
        elseif (isset($_GET['error_message'])) {
            $this->wpoa->wpoa_end_login($_GET['error_message']);
        }
        elseif (isset($_GET['code'])) {
            if (WPOA_Session::get_state() == $_GET['state']) {
                $this->getOAuthToken();
                //         get the user's third-party identity and attempt to login/register a matching wordpress user account:
                $oauth_identity = $this->getOAuthIdentity();
                $this->wpoa->wpoa_login_user($oauth_identity);
            }
            else {
                // possible CSRF attack, end the login with a generic message to the user and a detailed message to the admin/logs in case of abuse:
                // TODO: report detailed message to admin/logs here...
                $this->wpoa->wpoa_end_login("Sorry, we couldn't log you in. Please notify the admin or try again later.");
            }
        }
        else {
            // pre-auth, start the auth process:
            if ((empty(WPOA_Session::get_expires_at())) || (time() > WPOA_Session::get_expires_at())) {
                // expired token; clear the state:
                $this->wpoa->wpoa_clear_login_state();
            }
            $this->getOAuthCode();
        }
    }

    private function getOAuthCode() {
        Logger::Instance()->log($this->id . ": Get code");
        $params = array(
                'response_type' => 'code',
                'client_id' => $this->clientId,
                'state' => uniqid('', true),
                'redirect_uri' => $this->redirectUri,
                );
        $params = $this->getOAuthCodeExtendArray($params);
        Logger::Instance()->dump($params);
        WPOA_Session::set_state($params['state']);
        $url = $this->urlAuth . http_build_query($params);
        Logger::Instance()->log($this->id . ": Get Oauth code from : " . $url);
        header("Location: $url");
        exit;
    }


    private function getOAuthToken() {
        $logMessage = $this->id . ": Get Token";
        Logger::Instance()->log($logMessage);
        $params = array(
                'grant_type' => 'authorization_code',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code' => $_GET['code'],
                'redirect_uri' => $this->redirectUri,
                );
        $params = $this->getOAuthTokenExtendArray($params);
        $url_params = http_build_query($params);
        switch ($this->requestMode) {
            case 'curl':
                $url = $this->urlToken;
                $curl = init_curl($url);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $url_params);
                // PROVIDER NORMALIZATION: PayPal requires Accept and Accept-Language headers, Reddit requires sending a User-Agent header
                // PROVIDER NORMALIZATION: PayPal/Reddit requires sending the client id/secret via http basic authentication
                if ($this->sendIdAndSecretInHeader === true) {
                    Logger::Instance()->log($logMessage . " / Sending ID/Secret in header");
                    curl_setopt($curl, CURLOPT_USERPWD, $this->clientId . ":" . $this->clientSecret);
                    $encoded = base64_encode($this->clientId . ":" . $this->clientSecret);
                    $header = array("Accept: application/json", 
                            "Accept-Language: en_US", 
                            "Content-Type: application/x-www-form-urlencoded");

                    curl_setopt($curl, CURLOPT_HTTPHEADER, $header); 
                }
                $result = exec_curl($curl, $logMessage);
                break;
            case 'stream-context':
                $url = rtrim($this->urlToken, "?");
                $opts = array('http' =>
                        array(
                            'method'  => 'POST',
                            'header'  => 'Content-type: application/x-www-form-urlencoded',
                            'content' => $url_params,
                            )
                        );
                $context = $context  = stream_context_create($opts);
                $result = @file_get_contents($url, false, $context);
                if ($result === false) {
                    $this->wpoa->wpoa_end_login("Sorry, we couldn't log you in. Could not retrieve access token via stream context. Please notify the admin or try again later.");
                }
                break;
        }

        $result_obj = json_decode($result, true);
        if (!$result_obj) {
            Logger::Instance()->log($this->id . ": Not a JSon item, parsing it");
            $result_obj = $this->explode($result);
            Logger::Instance()->dump($result_obj);
        }
        $access_token = $result_obj['access_token'];
        $expires_in = $result_obj['expires_in'];
        if (!$expires_in) {
            $expires_in = $result_obj['expires'];
        }

        $expires_at = time() + $expires_in;

        if (!$access_token || (!$this->ignoreExpiresIn && !$expires_in)) {
            // malformed access token result detected:
            $this->wpoa->wpoa_end_login("Sorry, we couldn't log you in. Malformed access token result detected. Please notify the admin or try again later.");
        }
        else {
            WPOA_Session::set_token($access_token);
            WPOA_Session::set_expires_in($expires_in);
            WPOA_Session::set_expires_at($expires_at);
            return true;
        }
    }

    private function explode($input) {
        $output = array();

        $items = explode( '&', $input );

        foreach( $items as $val ){
            $tmp = explode( '=', $val );
            $output[ $tmp[0] ] = $tmp[1];
        }

        return $output;
    }

    protected function getRequest($url, $message) {
        Logger::Instance()->log($message . "->" . $url);
        // here we exchange the access token for the user info...
        // set the access token param:
        $access_token = WPOA_Session::get_token();
        Logger::Instance()->log($message. ": token = " .$access_token);
        $params = $this->getOAuthIdentityParameters();
        $url_params = http_build_query($params);
        // perform the http request:
        switch ($this->requestMode) {
            case 'curl':
                $url = rtrim($url, "?") . "?";
                $url .= $url_params;
                $curl = init_curl($url);
                Logger::Instance()->log($message . ": Bearer $this->useBearer");
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                if ($this->useBearer === true) {
                    Logger::Instance()->log($message . ": Using Bearer");
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // TODO: does Windows Live require this?
                    $header = array("Authorization: Bearer " . $access_token, 
                            "x-li-format: json");
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                }


                $result = exec_curl($curl, "get oauth identity");
                $result_obj = json_decode($result, true);
                break;
            case 'stream-context':
                $url = rtrim($url, "?");
                $opts = array('http' =>
                        array(
                            'method'  => 'GET',
                            // PROVIDER NORMALIZATION: Reddit/Github requires User-Agent here...
                            'header'  => "Authorization: Bearer " . WPOA_Session::get_token() . "\r\n" . "x-li-format: json\r\n", // PROVIDER SPECIFIC: i think only LinkedIn uses x-li-format...
                            )
                        );
                $context = $context  = stream_context_create($opts);
                $result = @file_get_contents($url, false, $context);
                if ($result === false) {
                    $this->wpoa->wpoa_end_login("Sorry, we couldn't log you in. Could not retrieve user identity via stream context. Please notify the admin or try again later.");
                }
                $result_obj = json_decode($result, true);
                break;
        }
        return $result_obj;
    }

    private function getOAuthIdentity() {
        $result_obj = $this->getRequest($this->urlUser, $this->id . ": Get Identity");
	Logger::Instance()->log("Answer array : ");
	Logger::Instance()->dump($result_obj);
        $result_obj = $this->getOauthIdentityPostTreatment($result_obj);
        $oauth_identity = array();
        $oauth_identity[WPOA_Session::PROVIDER] = WPOA_Session::get_provider();
        $oauth_identity = $this->getOAuthIdentityFillArray($result_obj, $oauth_identity);
        if (!$oauth_identity[WPOA_Session::USER_ID]) {
            $this->wpoa->wpoa_end_login("Sorry, we couldn't log you in. User identity was not found. Please notify the admin or try again later.");
        }
        return $oauth_identity;
    }

    // Following function can be overloaded
    protected function getOAuthCodeExtendArray($array) {
        return $array;
    }

    protected function getOAuthIdentityParameters(){
        return array('access_token' => WPOA_Session::get_token());
    }
    
    protected function getOAuthTokenExtendArray($array) {
        return $array;
    }
    protected function getOauthIdentityPostTreatment($result_obj) {
        return $result_obj;
    }

    abstract function getOAuthIdentityFillArray($result_obj, $oauth_identity);
}
