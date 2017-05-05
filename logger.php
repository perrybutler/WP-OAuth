<?php

class Logger {
    private $debug;
	const BASE_NAME = "WPOA: ";

    private function __construct() {
		error_log(Logger::BASE_NAME . "Creating logger");
        $this->debug = get_option('wpoa_debug_plugin') ? true : false;
        $this->debug = true ;
    }


    public function dump($value) {
        if ($this->debug === true) {
            ob_start();
            var_dump($value);
            $log = ob_get_clean();
            ob_end_flush();
            error_log(Logger::BASE_NAME . $log);
        }
    }

    public function print_stack_trace($value) {
        if ($this->debug === true) {
            ob_start();
            debug_print_backtrace();
            $log = ob_get_clean();
            ob_end_clean();
            error_log(Logger::BASE_NAME . $log);
        }
    }

    public function log($message)
    {
        if ($this->debug === true) {
            error_log(Logger::BASE_NAME . $message);
        }
    }

    public static function Instance(){
        static $logger = null;
        if ($logger === null) {
            $logger = new Logger();
        }
        return $logger;
    }
}
