<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('log_audit_action')) {
    function log_audit_action($userId, $action, $description = null) {
        $CI =& get_instance();
        $CI->load->model('AuditLogModel');
        $CI->AuditLogModel->addAuditLog($userId,'', $action, $description);
    }
}

if (!function_exists('send_notification')) {
    function send_notification($userId, $title, $message) {
        $CI =& get_instance();
        $CI->load->model('AuditLogModel');
        $CI->AuditLogModel->addNotification($userId, $title, $message);
    }
}
