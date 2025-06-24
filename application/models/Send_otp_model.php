<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require APPPATH . '../phpmailer/vendor/autoload.php'; // Composer autoload

class Send_otp_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct(); // <<< THIS IS CRUCIAL FOR MODELS TOO!
        // You can load other libraries/helpers/database here if needed for the model
        // e.g., $this->load->database();
    }

    /**
     * Generates a numeric password (OTP)
     * @param int $length The length of the OTP. Default to 6.
     * @return string The generated OTP.
     */
    public function generate_numeric_password($length = 6)
    {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= mt_rand(0, 9);
        }
        return $otp;
    }

    /**
     * Sends OTP to the specified email (dummy implementation)
     * In a real application, you'd use an email library or API here.
     * @param string $email The recipient's email address.
     * @param string $otp The OTP to send.
     * @return bool True on success, false on failure.
     */
    public function send_otp($email, $otp)
    {
        $mail = new PHPMailer(true);
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = PHP_MAILER_EMAIL_ID;
            $mail->Password   = PHP_MAILER_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Sender and Receiver
            $mail->setFrom(PHP_MAILER_EMAIL_ID, 'Rituraj Kumar');
            $mail->addAddress($email, 'My 2nd Account');

            // Email body
            $mail->isHTML(true);
            $mail->Subject = '✅ Test Email from Localhost (CI3)';
            $mail->Body    = "
                <h2>Hello from Rituraj!</h2>
                <p>Otp For login is $otp</p>
            ";
            $mail->AltBody = "
                <h2>Hello from Rituraj!</h2>
                <p>Otp For login is $otp</p>
            ";

            $mail->send();
            echo "✅ Message has been sent successfully to $email";
        } catch (Exception $e) {
            echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    // Add any other model methods here
}
