<?php

class Logger {
    private $display;

    function __construct() {
        $debug = getoption('wpao_debug_plugin');    
    }

    
    public function dump($value) {
        if ($debug === true) {
            ob_start();
            var_dump($value);
            $log = ob_get_clean();
            ob_end_clean();
            error_log($log);
        }
    }

    public function print_stack_trace($value) {
        if ($debug === true) {
            ob_start();
		    debug_print_backtrace();
		    $log = ob_get_clean();
		    ob_end_clean();
		    error_log($log);
        }
    }

    public function log($message)
    {
        if ($debug === true) {
            error_log($message);
        }
    }
}

global $logger;

$logger = new Logger();