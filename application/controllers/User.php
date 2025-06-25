<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // function create_user() - This is commented out and not causing the error
    // {
    //     print_r(get_all_input_data());
    //     die;
    //     $user_name = "Rituraj";
    //     $prefix = "USR";
    //     $timestamp = time(); // e.g., 20250622194736
    //     $lastChar = substr($user_name, 0, 1);
    //     $numbers = rand(100, 999); // 3 digits
    //     echo $prefix . $timestamp . $numbers . $lastChar;
    // }

    public function user_register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $RES = ['status' => false, 'status_code' => 400, 'message' => ''];

            $this->load->library('form_validation');
            $this->load->helper(["cookie", 'form']); // This loads helpers for the controller's scope

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
            $eData = $this->db->select('id')->where('user_email', $email)->get('users')->row();
            if (!$eData) {
                $this->load->model("Send_otp_model");
                $otp = $this->Send_otp_model->generate_numeric_password();
                $this->Send_otp_model->send_otp($email, $otp);

                $session_data = [
                    'username' => $username,
                    'email'    => $email,
                    'phone'    => $phone,
                    'password' => $password,
                    'otp'      => $otp
                ];
                $session_id = md5(json_encode($session_data));

                set_cookie('session_id', $session_id, time() + 300);

                $RES = ['status' => true, 'status_code' => 200, 'message' => 'Session Set Successfully'];
            } else $RES['message'] = 'Email Already Exists';
            echo json_encode($RES);
            exit();
        } else {
            $this->load->view('pages/user/signup_form');
        }
    }
}
