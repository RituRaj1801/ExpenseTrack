<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    function create_user()
    {
        print_r(get_all_input_data());
        die;
        $user_name = "Rituraj";
        $prefix = "USR";
        $timestamp = time(); // e.g., 20250622194736
        $lastChar = substr($user_name, 0, 1);
        $numbers = rand(100, 999); // 3 digits
        echo  $prefix . $timestamp . $numbers . $lastChar;
    }
    public function user_register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $RES = ['status' => false, 'status_code' => 400, 'message' => ''];
            $this->load->library('form_validation');
            // Set validation rules
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|regex_match[/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/]');
            $this->form_validation->set_rules('phone', 'Phone', 'regex_match[/^[6-9][0-9]{9}$/]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');


            if ($this->form_validation->run() == FALSE) {
                $RES['message'] = 'Validation Failed';
                $RES['errors'] = [
                    'username' => form_error('username'),
                    'email'    => form_error('email'),
                    'phone'    => form_error('phone'),
                    'password' => form_error('password'),
                ];
                echo json_encode($RES);
                return;
            }

            $username = $this->input->post('username', true);
            $email    = $this->input->post('email', true);
            $phone    = $this->input->post('phone', true);
            $password = $this->input->post('password', true);
            $this->load->model("Send_otp_model");
            // $otp = $this->Send_otp_model->generate_numeric_password();
            // $this->Send_otp_model->send_otp($email, $otp);
            // $session_data = [
            //     'username' => $username,
            //     'email'    => $email,
            //     'phone'    => $phone,
            //     'password' => $password,
            //     'otp'      => $otp
            // ];
            // $session_id = md5(json_encode($session_data));
            // // $this->load->helper('cookie');
            // set_cookie('session_id', $session_id, time() + 300);
            $RES = ['status' => true, 'status_code' => 200, 'message' => 'Session Set Successfully'];
            echo json_encode($RES);
            exit();
        } else {
            $this->load->view('pages/user/signup_form');
        }
    }
    // public function verify_otp_for_signup()
    // {
    //     $this->load->helper('cookie');


    //     if (!$xyz || !isset($xyz['signup_otp'])) {
    //         echo json_encode(['status' => false, 'message' => 'Session expired. Please sign up again.']);
    //         return;
    //     }

    //     if ($submitted_otp == $xyz['signup_otp']) {
    //         $this->db->insert('users', [
    //             'username' => $xyz['signup_username'],
    //             'email'    => $xyz['signup_email'],
    //             'phone'    => $xyz['signup_phone'],
    //             'password' => $xyz['signup_password'],
    //             'created_at' => date('Y-m-d H:i:s')
    //         ]);

    //         $this->session->unset_userdata('xyz');

    //         echo json_encode(['status' => true, 'message' => 'Signup successful.']);
    //     } else {
    //         echo json_encode(['status' => false, 'message' => 'Incorrect OTP.']);
    //     }
    // }
}
