<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    // public $encryption;
    public function __construct()
    {
        parent::__construct();
    }

    public function get_login_status()
    {
        $activity_data = ["status" => false, "status_code" => 400, "message" => ""];
        if (isset($_COOKIE['USER_ACTIVITY']) && !empty($_COOKIE['USER_ACTIVITY'])) {
            $session_data_raw = $this->encryption->decrypt($_COOKIE['USER_ACTIVITY']);
            $session_data = json_decode($session_data_raw, true);
            if (isset($session_data['login_status']) && $session_data['login_status'] === TRUE) {
                $activity_data =  [
                    "status" => true,
                    "status_code" => 200,
                    "message" => "You are already logged in.",
                    "data" => $session_data
                ];
            }
        }
        return $activity_data;
    }
}
