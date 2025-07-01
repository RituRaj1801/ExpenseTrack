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
        $RES = ['status' => false, 'status_code' => 400, 'message' => ''];

        if (!empty($input_data['email'])) {
            $email = trim($input_data['email']);

            if (preg_match($this->EMAIL_REGEX, $email)) {
                $this->load->model("Email_model");
                $checkUser = $this->Email_model->get_user_details(["user_email" => $email]);

                // Check if user already exists
                if ($checkUser['status'] === false) {
                    $otp = rand(100000, 999999);

                    $params = [
                        "template_id" => "login_otp",
                        "email" => $email,
                        "data" => ["otp" => $otp]
                    ];
                    $MAIL_RES = $this->Email_model->send_otp($params);

                    if ($MAIL_RES['status'] === true) {
                        $session_data_array = [
                            "email"       => $email,
                            "otp"         => $otp,
                            "ip_address"  => $_SERVER['REMOTE_ADDR'],
                            "user_agent"  => $_SERVER['HTTP_USER_AGENT'],
                            "expiry"      => time() + 300 // 5 minutes
                        ];

                        $session_data_raw = json_encode($session_data_array);
                        $encrypted_session_data = $this->encryption->encrypt($session_data_raw);

                        $this->input->set_cookie([
                            'name'     => 'session_id',
                            'value'    => $encrypted_session_data,
                            'expire'   => 300,
                            'path'     => '/',
                            'secure'   => false,
                            'httponly' => true,
                            'samesite' => 'Lax'
                        ]);

                        $RES = [
                            "status"       => true,
                            "status_code"  => 200,
                            "message"      => "OTP sent successfully.",
                            "data"         => $encrypted_session_data
                        ];
                    } else {
                        $RES = [
                            "status"      => false,
                            "status_code" => 422,
                            "message"     => "Failed to send OTP. Please try again.",
                            "data"        => $MAIL_RES
                        ];
                    }
                } else {
                    $RES = [
                        "status"      => false,
                        "status_code" => 422,
                        "message"     => "Account already exists with this email. Please login."
                    ];
                }
            } else {
                $RES['message'] = "Invalid email format.";
            }
        } else {
            $RES['message'] = "Email is required.";
        }

        echo json_encode($RES);
        exit();
    }

    /* SEND LOGIN OTP */

    /* VERFIRY LOGIN OTP */
    public function user_register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $RES = ['status' => false, 'status_code' => 400, 'message' => ''];
            $errors = [];

            $input_data = get_all_input_data();
            $encrypted_cookie = $input_data['session_id'] ?? $_COOKIE['session_id'] ?? '';

            if ($encrypted_cookie !== '') {
                $decrypted = $this->encryption->decrypt($encrypted_cookie);

                if ($decrypted) {
                    $session_data = json_decode($decrypted, true);

                    // Session validation
                    if (json_last_error() !== JSON_ERROR_NONE || empty($session_data)) {
                        $RES['message'] = 'Invalid session data.';
                    } elseif (time() > $session_data['expiry']) {
                        $RES['message'] = 'Session expired. Please request a new OTP.';
                    } elseif ($session_data['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
                        $RES['message'] = 'IP address mismatch.';
                    } elseif ($session_data['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
                        $RES['message'] = 'User agent mismatch.';
                    } else {
                        // Session is valid, proceed to form validation
                        $username         = trim($input_data['username'] ?? '');
                        $email            = trim($input_data['email'] ?? '');
                        $phone            = trim($input_data['phone'] ?? '');
                        $password         = trim($input_data['password'] ?? '');
                        $confirm_password = trim($input_data['confirm_password'] ?? '');
                        $otp              = trim($input_data['otp'] ?? '');
                        $gender           = trim($input_data['gender'] ?? '');

                        if ($username === '') $errors['username'] = 'Full Name is required.';
                        if (!preg_match('/^[6-9]\d{9}$/', $phone)) $errors['phone'] = 'Valid 10-digit Indian phone number required.';
                        if (strlen($password) < 6) $errors['password'] = 'Password must be at least 6 characters.';
                        if ($password !== $confirm_password) $errors['confirm_password'] = 'Passwords do not match.';
                        if (!ctype_digit($otp) || strlen($otp) !== 6) $errors['otp'] = 'OTP must be a 6-digit number.';
                        if (!in_array($gender, ['male', 'female'])) $errors['gender'] = 'Gender must be male or female.';
                        if ($email !== $session_data['email']) $errors['email'] = 'Email does not match session.';

                        if ((int)$otp !== (int)$session_data['otp']) {
                            $errors['otp'] = 'Incorrect OTP.';
                        }

                        // Final result
                        if (!empty($errors)) {
                            $RES = [
                                'status'      => false,
                                'status_code' => 422,
                                'message'     => 'Validation failed',
                                'errors'      => $errors
                            ];
                        } else {
                            $user_id = $this->get_user_id($username);
                            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                            $user_data = [
                                'user_id'    => $user_id,
                                'user_name'  => $username,
                                'user_phone' => $phone,
                                'user_email' => $email,
                                'gender'     => $gender,
                                'password'   => $hashed_password,
                                'created_at' => date('Y-m-d H:i:s'),
                            ];

                            if ($this->db->insert('users', $user_data)) {
                                $RES = [
                                    'status'      => true,
                                    'status_code' => 201,
                                    'message'     => '✅ Registration successful.'
                                ];
                            } else {
                                $RES = [
                                    'status'      => false,
                                    'status_code' => 500,
                                    'message'     => 'Failed to save user. Please try again.'
                                ];
                            }
                        }
                    }
                } else {
                    $RES['message'] = 'Invalid session. Please request a new OTP.';
                }
            } else {
                $RES['message'] = 'Missing session. Please request OTP first.';
            }

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
        }
        $this->load->view('pages/user/login_form');
    }
    /* LOGIN FORM */


    public function homepage()
    {
        if (isset($_COOKIE['session_id']) && !empty($_COOKIE['session_id'])) {
            $this->load->view('pages/user/homepage');
        } else redirect('login');
    }
}
