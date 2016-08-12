<?php

class Logger {
    private $debug;

    private function __construct() {
        $this->debug = get_option('wpao_debug_plugin');
    }

    
    public function dump($value) {
        if ($this->debug === true) {
            ob_start();
            var_dump($value);
            $log = ob_get_clean();
            ob_end_clean();
            error_log($log);
        }
    }

    public function print_stack_trace($value) {
        if ($this->debug === true) {
            ob_start();
		    debug_print_backtrace();
		    $log = ob_get_clean();
		    ob_end_clean();
		    error_log($log);
        }
    }

    public function log($message)
    {
        if ($this->debug === true) {
            error_log($message);
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