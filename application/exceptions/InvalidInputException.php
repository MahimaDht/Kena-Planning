<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InvalidInputException extends Exception {
    public function __construct($message = "Invalid input provided", $code = 400, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}