<?php


include_once 'session.php';
include_once 'login-common.php';


error_reporting(E_ALL | E_STRICT);

class LoginLinkedin extends AbstractLoginCommon {
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


$login = new LoginLinkedin($this);
$login->run();

function get_oauth_identity($wpoa) {
	// here we exchange the access token for the user info...
	// set the access token param:
	$params = array(
		'access_token' => WPOA_Session::get_token(), // PROVIDER SPECIFIC: the access token is passed to Github using this key name
	);
	$url_params = http_build_query($params);
	// perform the http request:
	switch (strtolower(HTTP_UTIL)) {
		case 'curl':
			$url = URL_USER . $url_params; // TODO: we probably want to send this using a curl_setopt...
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // PROVIDER SPECIFIC: Github requires the useragent for all api requests
			// PROVIDER NORMALIZATION: Reddit requires that we send the access token via a bearer header...
			//curl_setopt($curl, CURLOPT_HTTPHEADER, array('x-li-format: json')); // PROVIDER SPECIFIC: I think this is only for LinkedIn...
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($curl);
			$result_obj = json_decode($result, true);
			break;
		case 'stream-context':
			$url = rtrim(URL_USER, "?");
			$opts = array('http' =>
				array(
					'method' => 'GET',
					'user_agent' => $_SERVER['HTTP_USER_AGENT'], // PROVIDER NOTE: Github requires the useragent for all api requests
					'header'  => "Authorization: token " . WPOA_Session::get_token(),
				)
			);
			$context = $context  = stream_context_create($opts);
			$result = @file_get_contents($url, false, $context);
			if ($result === false) {
				$wpoa->wpoa_end_login("Sorry, we couldn't log you in. Could not retrieve user identity via stream context. Please notify the admin or try again later.");
			}
			$result_obj = json_decode($result, true);
			break;
	}
	// parse and return the user's oauth identity:
	$oauth_identity = array();
	$oauth_identity[WPOA_Session::PROVIDER] = WPOA_Session::get_provider();
	$oauth_identity[WPOA_Session::USER_ID] = $result_obj['id']; // PROVIDER SPECIFIC: this is how Github returns the user's unique id
	//$oauth_identity[WPOA_Session::USER_NAME] = $result_obj['email']; //PROVIDER SPECIFIC: this is how Github returns the email address
	if (!$oauth_identity[WPOA_Session::USER_ID]) {
		$wpoa->wpoa_end_login("Sorry, we couldn't log you in. User identity was not found. Please notify the admin or try again later.");
	}
	return $oauth_identity;
}
# END OF AUTHENTICATION FLOW HELPER FUNCTIONS #
?>
