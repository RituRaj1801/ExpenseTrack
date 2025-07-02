<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function decrypt_session_id()
    {
        $RES = ['status' => false, 'status_code' => 400, 'message' => ''];

        $input_data = get_all_input_data();
        $session_id = $input_data['session_id'] ?? '';

        if (empty($session_id)) {
            $RES['message'] = 'Session ID is missing.';
            echo json_encode($RES);
            return;
        }

        // Decode session ID from URL-encoded format (important)
        // $session_id = urldecode($session_id);

        // Try decrypting
        $decrypted_session = $this->encryption->decrypt($session_id);

        if ($decrypted_session === false) {
            $RES['status_code'] = 403;
            $RES['message'] = 'Decryption failed. Possibly due to invalid or tampered session.';
        } else {
            $decoded = json_decode($decrypted_session, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $RES = [
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Decryption successful',
                    'data' => $decoded
                ];
            } else {
                $RES['message'] = 'Decryption worked, but JSON decode failed.';
            }
        }

        echo json_encode($RES);
    }
}
