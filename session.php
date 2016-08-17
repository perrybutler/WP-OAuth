<?php

include_once "logger.php";

class WPOA_Session {
    const PROVIDER = 'provider';
    const USER_ID = 'user_id';
    const USER_NAME = 'user_name';
    const USER_EMAIL = 'user_email';

    public static function start()
    {
        session_start();
        Logger::Instance()->dump($_SESSION);
    }

    public static function get_id() {
        return $_SESSION['WPOA']['USER_ID'];
    }

    public static function set_id($value) {
        $_SESSION['WPOA']['USER_ID'] = $value;
    }

    public static function get_provider() {
        return $_SESSION['WPOA']['PROVIDER'];
    }

    public static function set_provider($value) {
        $_SESSION['WPOA']['PROVIDER'] = $value;
    }

    public static function get_email() {
        return $_SESSION['WPOA']['USER_EMAIL'];
    }

    public static function set_email($value) {
        $_SESSION['WPOA']['USER_EMAIL'] = $value;
    }

    public static function get_token() {
        return $_SESSION['WPOA']['ACCESS_TOKEN'];
    }

    public static function set_token($value) {
        $_SESSION['WPOA']['ACCESS_TOKEN'] = $value;
    }

    public static function get_expires_in() {
        return $_SESSION['WPOA']['EXPIRES_IN'];
    }

    public static function set_expires_in($value) {
        $_SESSION['WPOA']['EXPIRES_IN'] = $value;
    }

    public static function get_expires_at() {
        return $_SESSION['WPOA']['EXPIRES_AT'];
    }

    public static function set_expires_at($value) {
        $_SESSION['WPOA']['EXPIRES_AT'] = $value;
    }

    public static function get_last_url() {
        return $_SESSION['WPOA']['LAST_URL'];
    }


    public static function set_last_url($value) {
        $_SESSION['WPOA']['LAST_URL'] = $value;
    }

    public static function clear_last_url() {
        unset($_SESSION["WPOA"]["LAST_URL"]);
    }

    public static function save_last_url() {
        // try to obtain the redirect_url from the default login page. If no one exists, then get user's last page
        $redirect_url = esc_url($_GET['redirect_to']);
        if (!$redirect_url) {
            $redirect_url = strtok($_SERVER['HTTP_REFERER'], "?");
        }
        $_SESSION['WPOA']['LAST_URL'] = $redirect_url;
    }

    public static function get_result() {
        return $_SESSION["WPOA"]["RESULT"];
    }

    public static function set_result($value) {
        Logger::Instance()->log("SESSION : Set_result = " . $value);
        $_SESSION["WPOA"]["RESULT"] = $value;
    }


    public static function get_state() {
        return $_SESSION['WPOA']['STATE'];
    }

    public static function set_state($value) {
        $_SESSION['WPOA']['STATE'] = $value;
    }


    public static function clear() {
        Logger::Instance()->log("SESSION : Clearing session");
        unset($_SESSION["WPOA"]["PROVIDER"]);
        unset($_SESSION["WPOA"]["USER_ID"]);
        unset($_SESSION["WPOA"]["USER_EMAIL"]);
        unset($_SESSION["WPOA"]["ACCESS_TOKEN"]);
        unset($_SESSION["WPOA"]["EXPIRES_IN"]);
        unset($_SESSION["WPOA"]["EXPIRES_AT"]);
        unset($_SESSION["WPOA"]["LAST_URL"]);
        unset($_SESSION["WPOA"]["RESULT"]);
        unset($_SESSION['WPOA']['STATE']);
    }
}

WPOA_Session::start();
