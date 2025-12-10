<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DatabaseException extends Exception {
    public function __construct($message = "Database operation failed", $code = 500, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}