<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UnauthorizedAccessException extends Exception {
    public function __construct($message = "Unauthorized access", $code = 403, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}