<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('get_all_input_data')) {
    function get_all_input_data()
    {
        $params = array_merge($_GET, $_POST);
        $rawInput = file_get_contents("php://input");
        $jsonData = json_decode($rawInput, true);
        if (!is_array($jsonData)) $jsonData = [];
        return array_merge($params, $jsonData);
    }
}
