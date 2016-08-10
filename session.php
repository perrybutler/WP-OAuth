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

    public static function clear() {
        unset($_SESSION["WPOA"]["USER_ID"]);
		unset($_SESSION["WPOA"]["USER_EMAIL"]);
		unset($_SESSION["WPOA"]["ACCESS_TOKEN"]);
		unset($_SESSION["WPOA"]["EXPIRES_IN"]);
		unset($_SESSION["WPOA"]["EXPIRES_AT"]);
		//unset($_SESSION["WPOA"]["LAST_URL"]);
    }
}