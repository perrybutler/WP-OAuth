<?php
include_once "logger.php";

function init_curl($url) {
	$logger->log("CURL : Init with url = " . $url);
	$curl = curl_init($url);
	$verify_ssl= get_option('wpoa_http_util_verify_ssl');
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, ($verify_ssl == 1 ? 1 : 0));
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, ($verify_ssl == 1 ? 2 : 0));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			
	return $curl;
}

function exec_curl($curl, $message = "") {
    $result = curl_exec($curl);

    if(!$result) { 
      curl_error($curl);
      $logger->log("CURL : error : " . $message);
      trigger_error($error); 
    }
    curl_close($curl);
    $logger->log("CURL : exec reult = " . $result);
    return $result;
}