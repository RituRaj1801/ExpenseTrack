<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('get_all_input_data')) {
    function get_all_input_data()
    {
        foreach ($_POST as $key => $value) {
            $_POST[$key] = trim($value);
        }
        foreach ($_GET as $key => $value) {
            $_GET[$key] = trim($value);
        }

        $params = array_merge($_GET, $_POST);
        $rawInput = file_get_contents("php://input");
        $jsonData = json_decode($rawInput, true);
        if (!is_array($jsonData)) {
            $jsonData = [];
        } else {
            foreach ($jsonData as $key => $value) $jsonData[$key] = trim($value);
        }
        return array_merge($params, $jsonData);
    }
}
