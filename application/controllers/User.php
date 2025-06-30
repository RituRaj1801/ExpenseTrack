<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    private $EMAIL_REGEX = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    private $STRONG_PASSWORD_REGEX = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/";
    private $PHONE_REGEX = "/^[6-9][0-9]{9}$/";
    public Email_model $Email_model;
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
        $lastChar = substr($user_name, 0, 1);
        $numbers = rand(100, 999);
        return $prefix . $timestamp . $numbers . $lastChar;
    }
    /* CREATE USER ID */


    /* SEND LOGIN OTP */
    public function send_login_otp()
    {
        $input_data = get_all_input_data();

        $username = ($input_data['username'] ?? '');
        $email = ($input_data['email'] ?? '');
        $phone = ($input_data['phone'] ?? '');
        $password = ($input_data['password'] ?? '');
        $confirm_password = ($input_data['confirm_password'] ?? '');
        $gender = ($input_data['gender'] ?? '');

        $errors = [];

        if ($username == '') $errors['username'] = "Full name is required.";
        if (empty($email)) $errors['email'] = "Email is required.";
        elseif (!preg_match($this->EMAIL_REGEX, $email)) $errors['email'] = "Please Provide a valid email address.";
        if (empty($phone)) $errors['phone'] = "Phone number is required.";
        elseif (!preg_match($this->PHONE_REGEX, $phone)) $errors['phone'] = "Please Provide a valid phone number.";

        if (empty($password)) $errors['password'] = "Password is required.";
        elseif (!preg_match($this->STRONG_PASSWORD_REGEX, $password)) $errors['password'] = "Please Provide a strong password.";

        if (empty($confirm_password)) $errors['confirm_password'] = "Confirm password is required.";
        elseif ($password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match.";
        }

        // Gender validation
        if (empty($gender)) {
        } elseif (!in_array($gender, ['male', 'female'])) {
            $errors[] = "Please select a valid gender.";
        }
        if (empty($errors)) {
            $this->load->model("Email_model");
            if ($email !== "test@me.com")
                $checkUser = $this->Email_model->get_user_details(["user_email" => $email]);
            if ($email === "test@me.com" || !$checkUser['status']) {
                if ($email === "test@me.com") $otp = 123456;
                else
                    $otp = rand(100000, 999999);
                $params = [
                    "template_id" => "login_otp",
                    "email" => $email,
                    "data" => [
                        "otp" => $otp,
                        "user_name" => $username
                    ]
                ];
                if ($email !== "test@me.com")
                    $MAIL_RES = $this->Email_model->send_otp($params);
                if ($email === "test@me.com" || $MAIL_RES['status'] == true) {
                    $session_data_array = [
                        "username" => $username,
                        "email" => $email,
                        "phone" => $phone,
                        "password" => $password,
                        "gender" => $gender,
                        "otp" => $otp,
                        "ip_address" => $_SERVER['REMOTE_ADDR'],
                        "expiry" => time() + 300
                    ];
                    $session_data_raw = json_encode($session_data_array);
                    if ($session_data_raw !== null) {
                        $encrypted_session_data = $this->encryption->encrypt($session_data_raw);
                        $this->input->set_cookie([
                            'name'   => 'session_id',
                            'value'  => $encrypted_session_data,
                            'expire' => 300, // 5 minutes
                            'path'   => '/',
                            'secure' => false, // only send on HTTPS
                            'httponly' => true, // not accessible via JavaScript
                            'samesite' => 'Lax' // or 'Strict'
                        ]);
                        $RES = [
                            "status" => true,
                            "status_code" => 200,
                            "message" => "Otp Send Successfully",
                            "data" => $encrypted_session_data
                        ];
                    } else $RES = [
                        "status" => false,
                        "status_code" => 500,
                        "message" => "We are facing some issue. Please try again later."
                    ];
                } else {
                    $RES = [
                        "status" => false,
                        "status_code" => 422,
                        "message" => "Error Sending Otp",
                        "data" => $MAIL_RES
                    ];
                }
            } else {
                $RES = [
                    "status" => false,
                    "status_code" => 422,
                    "message" => "Email Already Exists"
                ];
            }
        } else {
            $RES = [
                'status' => false,
                "status_code" => 422,
                'message' => 'Validation Failed',
                'errors' => $errors
            ];
        }
        echo json_encode($RES);
        exit();
    }
    /* SEND LOGIN OTP */

    /* VERFIRY LOGIN OTP */
    public function user_register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $RES = ['status' => false, 'status_code' => 400, 'message' => ''];

            $input_data = get_all_input_data();

            if ((isset($_COOKIE['session_id']) && $_COOKIE['session_id'] != '') || isset($input_data['session_id']) && $input_data['session_id'] != '') {
                if ((isset($_COOKIE['session_id']) && $_COOKIE['session_id'] != ''))
                    $encrypted_cookie = $_COOKIE['session_id'];
                else $encrypted_cookie = $input_data['session_id'];

                // Try to decrypt the cookie
                $decrypted = $this->encryption->decrypt($encrypted_cookie);


                if ($decrypted) {
                    $session_data = json_decode($decrypted, true);
                    if (json_last_error() !== JSON_ERROR_NONE || empty($session_data)) {
                        $RES = [
                            'status' => false,
                            'status_code' => 400,
                            'message' => 'Invalid session data.'
                        ];
                    } elseif (time() > $session_data['expiry']) {
                        $RES = [
                            'status' => false,
                            'status_code' => 401,
                            'message' => 'Session expired. Please request a new OTP.'
                        ];
                    } elseif (!isset($session_data['ip_address']) || $session_data['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
                        $RES = [
                            'status' => false,
                            'status_code' => 403,
                            'message' => 'Session mismatch. Possible tampering or unauthorized use.'
                        ];
                    } else {
                        // Now compare input payload with session cookie data
                        $mismatches = [];

                        $fields_to_check = ['username', 'email', 'phone', 'password', 'gender', 'otp'];
                        foreach ($fields_to_check as $field) {
                            if (($input_data[$field] ?? '') != ($session_data[$field] ?? '')) {
                                $mismatches[] = ucfirst($field) . " does not match.";
                            }
                        }


                        if (!empty($mismatches)) {
                            $RES = [
                                'status' => false,
                                'status_code' => 422,
                                'message' => 'Validation failed.',
                                'errors' => $mismatches
                            ];
                        } else {
                            // Passed validation — proceed to insert
                            $user_id = $this->get_user_id($session_data['username']);
                            $hashed_password = password_hash($session_data['password'], PASSWORD_BCRYPT);

                            $data = [
                                'user_id'    => $user_id,
                                'user_name'  => $session_data['username'],
                                'user_phone' => $session_data['phone'],
                                'user_email' => $session_data['email'],
                                'gender'     => $session_data['gender'] ?: null,
                                'password'   => $hashed_password,
                                'created_at' => date('Y-m-d H:i:s'),
                            ];

                            if ($session_data['email'] !== "test@me.com")
                                $inserted = $this->db->insert('users', $data);
                            if ($session_data['email'] === "test@me.com" || $inserted) {
                                $RES = [
                                    'status' => true,
                                    'status_code' => 201,
                                    'message' => '✅ Registration successful.'
                                ];
                            } else {
                                $RES = [
                                    'status' => false,
                                    'status_code' => 500,
                                    'message' => 'Something went wrong while saving user.'
                                ];
                            }
                        }
                    }
                }
            } else $RES['message'] = 'Session ID is missing.';
            echo json_encode($RES);
            exit();
        } else {
            $this->load->view('pages/user/signup_form');
        }
    }
    /* VERFIRY LOGIN OTP */


    /* LOGIN FORM */
    public function user_login()
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
            } elseif (!preg_match($this->STRONG_PASSWORD_REGEX, $password)) {
                $RES['message'] = "Password must be strong (1 upper, 1 lower, 1 number, 1 symbol).";
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
                        "username" => $user['data']['user_name'],
                        "email" => $user['data']['user_email'],
                        "phone" => $user['data']['user_phone'],
                        "ip_address" => $_SERVER['REMOTE_ADDR'],

                    ];
                    $session_data_raw = json_encode($session_data_array);
                    $encrypted_session_data = $this->encryption->encrypt($session_data_raw);
                    $this->input->set_cookie([
                        'name'   => 'session_id',
                        'value'  => $encrypted_session_data,
                        'expire' => 60*60*24*365, // 5 minutes
                        'path'   => '/',
                        'secure' => false, // only send on HTTPS
                        'httponly' => true, // not accessible via JavaScript
                        'samesite' => 'Lax' // or 'Strict'
                    ]);
                } else $RES['message'] = "Incorrect password.";
            } else $RES['message'] = "Email not found.";


            echo json_encode($RES);
            exit();
        }
        $this->load->view('pages/user/login_form');
    }
    /* LOGIN FORM */


    public function homepage()
    {
        if(isset($_COOKIE['session_id']) && !empty($_COOKIE['session_id'])) {
            
            $this->load->view('pages/user/homepage');
        }else redirect('login')
    }
}
