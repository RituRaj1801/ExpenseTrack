<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    private $EMAIL_REGEX = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    private $STRONG_PASSWORD_REGEX = "/^(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[\W_]).{6,}$/";
    private $PHONE_REGEX = "/^[6-9][0-9]{9}$/";
    public Email_model $Email_model;
    public $form_validation;
    public $encryption;
    public function __construct()
    {
        parent::__construct();
    }

    /* CREATE USER ID */
    function get_user_id($user_name)
    {
        $prefix = "USR";
        $timestamp = time();
        $lastChar = strtoupper(substr($user_name, 0, 1));
        $numbers = rand(100, 999);
        return $prefix . $timestamp . $numbers . $lastChar;
    }
    /* CREATE USER ID */


    /* SEND LOGIN OTP */
    public function signup()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $RES = ['status' => false, 'status_code' => 400, 'message' => ''];
            $input_data = get_all_input_data();
            $ip_address =  $_SERVER['REMOTE_ADDR'];
            $this->load->library('form_validation');
            $this->form_validation->set_data($input_data);

            $this->form_validation->set_rules(
                'action',
                'Action',
                'trim|required|in_list[send_otp,verify_otp]',
                [
                    'required' => 'Action is required.',
                    'in_list' => 'Action is invalid.'
                ]
            );
            if ($this->form_validation->run() === true) {
                $action = $input_data['action'] ?? "";
                if ($action === 'send_otp') {
                    $this->form_validation->set_rules(
                        'user_email',
                        'Email Address',
                        'trim|required|valid_email',
                        [
                            'required' => 'Email is required.',
                            'valid_email' => 'Enter a valid email.'
                        ]
                    );
                    if ($this->form_validation->run() === true) {
                        $email = $input_data['user_email'];
                        $this->load->model("Email_model");
                        $checkUser = $this->Email_model->get_user_details(["user_email" => $email]);
                        if ($checkUser['status'] === false) {
                            $otp = rand(100000, 999999);
                            $params = [
                                "template_id" => "login_otp",
                                "email" => $email,
                                "data" => ["otp" => $otp]
                            ];
                            $MAIL_RES = $this->Email_model->send_otp($params);
                            if ($MAIL_RES['status'] === true) {
                                $cookies_enc_data = [
                                    "name" => "USER_SIGNUP_SESSION",
                                    "data" => [
                                        "email"       => $email,
                                        "otp"         => $otp,
                                        "ip_address"  => $ip_address,
                                        "expiry"      => time() + 300 // 5 minutes
                                    ],
                                    "expire" => time() + 300 // 5 minutes
                                ];
                                $session_data = $this->_encrypt_session_data($cookies_enc_data);
                                $RES = ["status" => true, "status_code" => 200, "status_key" => "OTP_SENT_SUCCESSFULLY", "message" => "OTP sent successfully.", "data" => $session_data];
                            } else $RES = ['status' => false, 'status_code' => 401, "status_key" => "EMAIL_SEND_FAILD", 'message' => "Failed to send email. Please try again."];
                        } else $RES = ['status' => false, 'status_code' => 402, "status_key" => "EMAIL_EXISTS", 'message' => "Email already exists."];
                    } else $RES = ['status' => false, 'status_code' => 415, "status_key" => "INVALID_EMAIL", 'message' => validation_errors()];
                } else {
                    $this->form_validation->set_rules(
                        "user_name",
                        "User Name",
                        "trim|required|alpha_numeric_spaces",
                        [
                            "required" => "User Name is required.",
                            "alpha_numeric_spaces" => "User Name must be alphanumeric with spaces."
                        ]
                    );
                    $this->form_validation->set_rules(
                        'user_email',
                        'Email Address',
                        'trim|required|valid_email',
                        [
                            'required' => 'Email is required.',
                            'valid_email' => 'Enter a valid email.'
                        ]
                    );
                    $this->form_validation->set_rules(
                        "user_phone",
                        "User Phone",
                        "trim|required|numeric|exact_length[10]",
                        [
                            "required" => "User Phone is required.",
                            "numeric" => "User Phone must be numeric.",
                            "exact_length" => "User Phone must be 10 digits."
                        ]
                    );
                    $this->form_validation->set_rules(
                        "password",
                        "Password",
                        "trim|required|min_length[6]",
                        [
                            "required" => "Password is required.",
                            "min_length" => "Password must be at least 6 characters."
                        ]
                    );
                    $this->form_validation->set_rules(
                        "confirm_password",
                        "Confirm Password",
                        "trim|required|matches[password]",
                        [
                            "required" => "Confirm Password is required.",
                            "matches" => "Passwords do not match."
                        ]
                    );
                    $this->form_validation->set_rules(
                        'gender',
                        'Gender',
                        'trim|required|in_list[M,F]',
                        [
                            'required' => 'Provide the gender.',
                            'in_list' => "invalid value for gender.",
                        ]
                    );
                    $this->form_validation->set_rules(
                        'otp',
                        'OTP',
                        'trim|required|numeric|exact_length[6]',
                        [
                            'required' => 'OTP is required.',
                            'numeric' => 'OTP must be numeric.',
                            'exact_length' => 'OTP must be 6 digits.'
                        ]
                    );
                    if ($this->form_validation->run() === true) {
                        $user_name = $input_data['user_name'];
                        $user_email = $input_data['user_email'];
                        $user_phone = $input_data['user_phone'];
                        $password = $input_data['password'];
                        $gender = $input_data['gender'];
                        $confirm_password = $input_data['confirm_password'];
                        $otp = $input_data['otp'];
                        if (isset($_COOKIE['USER_SIGNUP_SESSION']) && !empty($_COOKIE['USER_SIGNUP_SESSION'])) {
                            $d = $this->encryption->decrypt($_COOKIE['USER_SIGNUP_SESSION']);
                            $session_data = json_decode($d, true);
                            if ($ip_address === $session_data['ip_address']) {
                                if ($user_email === $session_data['email'] && $otp == $session_data['otp']) {
                                    $user_id = $this->get_user_id($user_name);
                                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                                    $user_data = [
                                        'user_id'    => $user_id,
                                        'user_name'  => $user_name,
                                        'user_phone' => $user_phone,
                                        'user_email' => $user_email,
                                        'gender'     => $gender,
                                        'password'   => $hashed_password,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ];
                                    if ($this->db->insert('users', $user_data)) $RES = ['status' => true, 'status_code' => 201, "status_key"  => "REGISTRATION_SUCCESSFUL", 'message' => '✅ Registration successful.'];
                                    else $RES = ['status' => false, 'status_code' => 500, "status_key" => "FAILED_TO_SAVE_USER", 'message' => 'Failed to save user. Please try again.'];
                                } elseif ($otp === $session_data['otp']) $RES = ['status' => false, 'status_code' => 402, "status_key" => "EMAIL_MISMATCH", 'message' => "Your email has been changed. Please dont change the email ."];
                                else $RES = ['status' => false, 'status_code' => 402, "status_key" => "OTP_MISMATCH", 'message' => "OTP doesn't match."];
                            } else $RES = ['status' => false, 'status_code' => 402, "status_key" => "IP_ADDRESS_MISMATCH", 'message' => "Session has been tempered."];
                        } else $RES = ['status' => false, 'status_code' => 402, "status_key" => "SESSION_EXPIRED", 'message' => "Your session has expired. Please request a new OTP."];
                    } else $RES = ['status' => false, 'status_code' => 422, "status_key" => "INVALID_FAILED", 'message' => validation_errors()];
                }
            } else $RES = ['status' => false, 'status_code' => 422, "status_key" => "INVALID_ACTION", 'message' => validation_errors()];
            echo json_encode($RES);
            exit();
        }
        $this->load->view('pages/user/signup_form');
    }

    /* SEND LOGIN OTP */

    /* LOGIN FORM */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $RES = ['status' => false, 'status_code' => 400, 'message' => ''];
            $email = trim($this->input->post('user_email'));
            $password = trim($this->input->post('password'));

            // Validate email
            if (empty($email)) {
                $RES['message'] = "Email is required.";
            } elseif (!preg_match($this->EMAIL_REGEX, $email)) {
                $RES['message'] = "Invalid email format.";
            }

            // Validate password
            if (empty($password)) {
                $RES['message'] = "Password is required.";
            }

            // Stop if any errors
            if (!empty($RES['message'])) {
                echo json_encode($RES);
                return;
            }

            // Check DB
            $this->load->model("Email_model");
            $user = $this->Email_model->get_user_details(['user_email' => $email]);
            if ($user['status'] === true) {
                if (password_verify($password, $user['data']['password'])) {  // Use password_verify() if hashed
                    $RES = ['status' => true, 'status_code' => 200, 'message' => '✅ Login successful.',];
                    $session_data_array = [
                        "user_id" => $user['data']['user_id'],
                        // "username" => $user['data']['user_name'],
                        // "email" => $user['data']['user_email'],
                        // "phone" => $user['data']['user_phone'],
                        // "ip_address" => $_SERVER['REMOTE_ADDR'],
                    ];
                    $session_data_raw = json_encode($session_data_array);
                    $encrypted_session_data = $this->encryption->encrypt($session_data_raw);
                    $this->input->set_cookie([
                        'name'   => 'USER_ACTIVITY',
                        'value'  => $encrypted_session_data,
                        'expire' => 60 * 60 * 24 * 365, // 5 minutes
                        'path'   => '/',
                        'secure' => false, // only send on HTTPS
                        'httponly' => true, // not accessible via JavaScript
                        'samesite' => 'Lax' // or 'Strict'
                    ]);
                } else $RES['message'] = "Incorrect password.";
            } else $RES['message'] = "Email not found.";

            echo json_encode($RES);
            exit();
        } else {
            if (isset($_COOKIE['USER_ACTIVITY']) && !empty($_COOKIE['USER_ACTIVITY'])) {
                $session_data_raw = $this->encryption->decrypt($_COOKIE['USER_ACTIVITY']);
                $session_data = json_decode($session_data_raw, true);
                if (isset($session_data['user_id']) && !empty($session_data['user_id'])) {
                    redirect('homepage');
                } else $this->load->view('pages/user/login_form');
            } else $this->load->view('pages/user/login_form');
        }
    }
    /* LOGIN FORM */


    public function homepage()
    {
        if (isset($_COOKIE['USER_ACTIVITY']) && !empty($_COOKIE['USER_ACTIVITY'])) {
            $session_data_raw = $this->encryption->decrypt($_COOKIE['USER_ACTIVITY']);
            $session_data = json_decode($session_data_raw, true);
            if (isset($session_data['user_id']) && !empty($session_data['user_id'])) {
                $user_data = $this->db->select('*')->from('users')->where('user_id', $session_data['user_id'])->get()->row_array();
                if ($user_data) {
                    $data['user_data'] = $user_data;
                    $data['total_spend'] = $this->db->select_sum('amount')
                        ->from('expense')
                        ->where(
                            [
                                'user_id' => $session_data['user_id'],
                                "txn_type" => "debit",
                                "created_at >=" => date('Y-m-01'),
                                "created_at <=" => date('Y-m-t')
                            ]
                        )->get()->row_array();
                    // echo "<pre>";
                    // echo json_encode($data);die;
                    $this->load->view('pages/user/homepage', $data);
                } else redirect('login');
            } else redirect('login');
        } else redirect('login');
    }
    public function forget_password()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $RES = ['status' => false, 'status_code' => 400, 'message' => ''];
            $input_data = get_all_input_data();
            $action = $input_data['action'] ?? "";
            if ($action === "send_otp") {
                $email = $input_data['email'] ?? "";
                if (!empty($email) && preg_match($this->EMAIL_REGEX, $email)) {
                    $this->load->model("Email_model");
                    $user = $this->Email_model->get_user_details(['user_email' => $email]);
                    if ($user['status'] === true) {
                        $otp = random_int(100000, 999999);
                        $enc_data = [
                            "name" => "FORGET_ACCOUNT_PASSWORD",
                            "data" => [
                                "email" => $email,
                                "otp" => $otp
                            ],
                            "expiry" => 300
                        ];
                        $this->_encrypt_session_data($enc_data);
                        $forget_password_data = [
                            "APP_NAME" => "ExpenseTrack",
                            "USER_NAME" => $user['data']['user_name'],
                            "OTP_CODE" => $otp
                        ];
                        $this->Email_model->send_otp(["email" => $email, "data" => $forget_password_data, "template_id" => "forget_password_otp"]);
                        $RES = ['status' => true, 'status_code' => 200, 'message' => '✅ OTP sent successfully.',];
                    } else $RES = ['status' => false, 'status_code' => 400, 'status_key' => "EMAIL_NOT_FOUND", 'message' => "Email not found."];
                } else $RES = ['status' => false, 'status_code' => 400, 'status_key' => "INVALID_EMAIL", 'message' => "Invalid action type."];
            } else if ($action === "verify_otp") {
                if (isset($_COOKIE['FORGET_ACCOUNT_PASSWORD']) && !empty($_COOKIE['FORGET_ACCOUNT_PASSWORD'])) {
                    $session_data_raw = $this->encryption->decrypt($_COOKIE['FORGET_ACCOUNT_PASSWORD']);
                    $session_data = json_decode($session_data_raw, true);
                    if (isset($session_data['email']) && !empty($session_data['email']) && isset($session_data['otp']) && !empty($session_data['otp'])) {
                        $session_email = $session_data['email'];
                        $session_otp = $session_data['otp'];
                        $email = $input_data['email'] ?? "";
                        $otp = $input_data['otp'] ?? "";
                        $new_password = $input_data['new_password'] ?? "";
                        $cnf_new_password = $input_data['cnf_new_password'] ?? "";
                        if ($session_email === $email) {
                            if ($session_otp == $otp) {
                                if (strlen(trim($new_password)) >= 6) {
                                    if ($new_password === $cnf_new_password) {
                                        $update_data = [
                                            "password" => password_hash($new_password, PASSWORD_DEFAULT)
                                        ];
                                        $this->db->where('user_email', $email)->update('users', $update_data);
                                        $RES = ['status' => true, 'status_code' => 200, "status_key" => "VALIDATION_PASSED", 'message' => '✅ Password changed successfully.'];
                                    } else $RES = ['status' => false, 'status_code' => 400, 'status_key' => "PASSWORD_MISMATCH", 'message' => "Password mismatch."];
                                } else $RES = ['status' => false, 'status_code' => 400, 'status_key' => "WEAK_PASSWORD", 'message' => "Please choice a strong password."];
                            } else $RES = ['status' => false, 'status_code' => 400, 'status_key' => "INCORRECT_OTP", 'message' => "Incorrect OTP."];
                        } else $RES = ['status' => false, 'status_code' => 400, 'status_key' => "CHANGED_DATA", 'message' => "Please don't change the email."];
                    } else $RES = ['status' => false, 'status_code' => 400, 'status_key' => "TEMPERED_DATA", 'message' => "Invalid request. Plz try again later."];
                } else $RES = ['status' => false, 'status_code' => 400, 'status_key' => "INVALID_REQUEST", 'message' => "Please request for OTP first."];
            } else $RES = ['status' => false, 'status_code' => 400, 'status_key' => "INVALID_ACTION_TYPE", 'message' => "Invalid action type."];
            echo json_encode($RES);
            exit();
        } else $this->load->view("pages/user/forget_password");
    }
    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        } else $this->load->view("pages/user/contact");
    }


    private function _encrypt_session_data($params)
    {
        $name = $params['name'] ?? "";
        $data = $params['data'] ?? [];
        $expiry = $params['expiry'] ?? 300;
        $encrypted_data = $this->encryption->encrypt(json_encode($data));
        $this->input->set_cookie([
            'name'   => $name,
            'value'  => $encrypted_data,
            'expire' => $expiry,
            'path'   => '/',
            'secure' => false, // only send on HTTPS
            'httponly' => true, // not accessible via JavaScript
            'samesite' => 'Lax' // or 'Strict'
        ]);
        return $encrypted_data;
    }
    private function _decrypt_session_data($params)
    {
        $name = $params['name'] ?? "";
        $encrypted_session_data = $this->input->cookie($name, true);
        $decrypted_session_data = $this->encryption->decrypt($encrypted_session_data);
        $session_data = json_decode($decrypted_session_data, true);
        return $session_data;
    }
}
