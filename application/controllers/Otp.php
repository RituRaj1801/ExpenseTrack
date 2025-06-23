<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Make sure Composer's autoloader is accessible
require_once(FCPATH . 'phpmailer/vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Otp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function send_otp()
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'rituraj995kumar@gmail.com';    // your Gmail
            $mail->Password   = 'rrpshprvfmtnllzb';             // 16-digit app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    // SSL encryption
            $mail->Port       = 465;

            // Sender and Receiver
            $mail->setFrom('rituraj995kumar@gmail.com', 'Rituraj Kumar');
            $mail->addAddress('tuvtuv879@gmail.com', 'My 2nd Account');

            // Email body
            $mail->isHTML(true);
            $mail->Subject = '✅ Test Email from Localhost (CI3)';
            $mail->Body    = '
                <h2>Hello from Rituraj!</h2>
                <p>This is a <b>test email</b> sent using <i>PHPMailer</i> from <code>localhost</code> within <b>CodeIgniter 3</b>.</p>
                <p>If you received this, everything works! ✅</p>
            ';
            $mail->AltBody = 'Hello from Rituraj! This is a test email sent using PHPMailer from localhost.';

            $mail->send();
            echo '✅ Message has been sent successfully to tuvtuv879@gmail.com';
        } catch (Exception $e) {
            echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    function generate_numeric_password() {
    echo  rand(100000, 999999);
}
}
