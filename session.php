<?php

class WPOA_Session {
    public static function start()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public static function get_id() {
        return $_SESSION['WPOA']['USER_ID'];
    }

    public static function set_id($value) {
        start();
        $_SESSION['WPOA']['USER_ID'] = $value;
    }

    public static function get_provider() {
        return $_SESSION['WPOA']['PROVIDER'];
    }

    public static function set_provider($value) {
        start();
        $_SESSION['WPOA']['PROVIDER'] = $value;
    }
    
    public static function get_email() {
        return $_SESSION['WPOA']['USER_EMAIL'];
    }

    public static function set_email($value) {
        start();
        $_SESSION['WPOA']['USER_EMAIL'] = $value;
    }

    public static function get_token() {
        return $_SESSION['WPOA']['ACCESS_TOKEN'];
    }

    public static function set_token($value) {
        start();
        $_SESSION['WPOA']['ACCESS_TOKEN'] = $value;
    }
    
    public static function get_expires_in() {
        return $_SESSION['WPOA']['EXPIRES_IN'];
    }

    public static function set_expires_in($value) {
        start();
        $_SESSION['WPOA']['EXPIRES_IN'] = $value;
    }
    
    public static function get_expires_at() {
        return $_SESSION['WPOA']['EXPIRES_AT'];
    }

    public static function set_expires_at($value) {
        start();
        $_SESSION['WPOA']['EXPIRES_AT'] = $value;
    }
   
    public static function get_last_url() {
        return $_SESSION['WPOA']['LAST_URL'];
    }

    public static function save_last_url() {
        start();
        // try to obtain the redirect_url from the default login page:
	    $redirect_url = esc_url($_GET['redirect_to']);
	    // if no redirect_url was found, set it to the user's last page:
	    if (!$redirect_url) {
    		$redirect_url = strtok($_SERVER['HTTP_REFERER'], "?");
    	}
    	// set the user's last page so we can return that user there after they login:
    	$_SESSION['WPOA']['LAST_URL'] = $redirect_url;
    }
   
    public static function clear() {
        unset($_SESSION["WPOA"]["PROVIDER"]);
        unset($_SESSION["WPOA"]["USER_ID"]);
		unset($_SESSION["WPOA"]["USER_EMAIL"]);
		unset($_SESSION["WPOA"]["ACCESS_TOKEN"]);
		unset($_SESSION["WPOA"]["EXPIRES_IN"]);
		unset($_SESSION["WPOA"]["EXPIRES_AT"]);
		unset($_SESSION["WPOA"]["LAST_URL"]);
    }
}