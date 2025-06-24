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
        // This is a placeholder. In a real application, you would integrate
        // with an email sending library (e.g., PHPMailer, CI Email Library)
        // or an SMS gateway API.

        $mail = new PHPMailer(true); // Enable exceptions

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';         // Set the SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_email@gmail.com';   // Your Gmail address
            $mail->Password   = 'your_app_password';      // App Password from Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('your_email@gmail.com', 'Your Name');
            $mail->addAddress('recipient@example.com', 'Recipient Name');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Test Mail from PHPMailer';
            $mail->Body    = 'This is a <b>test email</b> sent using PHPMailer with SMTP.';
            $mail->AltBody = 'This is a test email sent using PHPMailer with SMTP.';

            $mail->send();
            echo '✅ Message has been sent';
        } catch (Exception $e) {
            echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    // Add any other model methods here
}
