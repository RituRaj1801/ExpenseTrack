<?php
defined('BASEPATH') or exit('No direct script access allowed');

// PHPMailer Imports
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Composer Autoloader for PHPMailer (adjust path if needed)
require APPPATH . '../phpmailer/vendor/autoload.php';

class Email_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Sends an OTP email using a dynamic email template.
     * 
     * @param array $params - Must include template_id, email, data[]
     * @return array - Standardized response (status, code, message)
     */
    public function send_otp($params)
    {
        $template_id = $params['template_id'] ?? '';
        $email       = $params['email'] ?? '';
        $data        = $params['data'] ?? [];

        // 1. Basic Validation
        if (empty($template_id) || empty($email)) {
            return [
                'status'      => false,
                'status_code' => 400,
                'message'     => 'Required parameters "template_id" or "email" are missing.'
            ];
        }

        // 2. Fetch Email Template
        $this->db->where('template_id', $template_id);
        $template = $this->db->get('email_template')->row_array();

        if (!$template) {
            return [
                'status'      => false,
                'status_code' => 404,
                'message'     => "Email template with ID '{$template_id}' not found."
            ];
        }

        // 3. Replace Placeholders
        $subject   = $this->_replace_placeholders($template['email_subject'], $data);
        $body      = $this->_replace_placeholders($template['email_body'], $data);
        $alt_body  = $this->_replace_placeholders($template['email_alt_body'], $data);

        // 4. Send Email via PHPMailer
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = PHP_MAILER_EMAIL_ID;
            $mail->Password   = PHP_MAILER_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom(PHP_MAILER_EMAIL_ID, PHP_MAILER_USERNAME);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $alt_body;

            $mail->send();

            return [
                'status'      => true,
                'status_code' => 200,
                'message'     => "✅ Mail sent successfully to {$email}."
            ];
        } catch (Exception $e) {
            return [
                'status'      => false,
                'status_code' => 500,
                'message'     => "❌ Mailer Error: {$mail->ErrorInfo}"
            ];
        }
    }

    /**
     * Replaces placeholders like {{username}} in a string
     * 
     * @param string $template
     * @param array $data
     * @return string
     */
    private function _replace_placeholders($template, $data)
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }
    public function get_user_details($params)
    {
        $search_params = array_keys($params);
        $eData = $this->db->where($search_params[0], $params[$search_params[0]])->get('users')->row_array();
        if ($eData) {
            return [
                'status'      => true,
                'status_code' => 200,
                'message'     => "User Found.",
                "data" => $eData
            ];
        } else return [
            'status'      => false,
            'status_code' => 404,
            'message'     => "User Not Found."
        ];
    }
}
