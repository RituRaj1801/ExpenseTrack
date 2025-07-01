<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('get_all_input_data')) {
    function get_all_input_data()
    {
        $post_data = [];
        $get_data = [];
        $CI = &get_instance();

        // POST with XSS filtering
        foreach ($_POST as $key => $value) {
            $post_data[$key] = trim($CI->input->post($key, true));
        }

        // GET with XSS filtering (corrected loop)
        foreach ($_GET as $key => $value) {
            $get_data[$key] = trim($CI->input->get($key, true));
        }

        // JSON with manual XSS filtering
        $rawInput = file_get_contents("php://input");
        $jsonData = json_decode($rawInput, true);
        if (!is_array($jsonData)) {
            $jsonData = [];
        } else {
            foreach ($jsonData as $key => $value) {
                $jsonData[$key] = trim($CI->security->xss_clean($value));
            }
        }

        // Merge GET, POST, and JSON data
        return array_merge($get_data, $post_data, $jsonData);
    }
}
