<?php
session_start();

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['otp'])) {
        throw new Exception('No OTP generated. Please generate an OTP first.');
    }

    if ($_SESSION['otp_time'] < time() - 300) {
        $_SESSION = [];
        session_destroy();
        throw new Exception('OTP expired. Please generate a new OTP.');
    }

    if ($_SESSION['otp_attempts'] >= 3) {
        $_SESSION = [];
        session_destroy();
        throw new Exception('Maximum attempts reached. Please generate a new OTP.');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    $otp = $_POST['otp'] ?? '';

    if (empty($otp)) {
        throw new Exception('OTP is required.');
    }

    if ($otp == $_SESSION['otp']) {
        $_SESSION = [];
        session_destroy();

        echo json_encode([
            'success' => true,
            'message' => 'OTP verified successfully!'
        ]);
    } else {
        $_SESSION['otp_attempts']++;
        $remaining = max(0, 3 - $_SESSION['otp_attempts']);
        throw new Exception('Invalid OTP. You have ' . $remaining . ' attempts left.');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
