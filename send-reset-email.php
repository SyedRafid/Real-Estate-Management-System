<?php
session_start();
require './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Generate a random OTP
$otp = rand(100000, 999999);

// Store OTP in session
$_SESSION['otp'] = $otp;
$_SESSION['otp_time'] = time();
$_SESSION['otp_attempts'] = 0;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email is required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'];
        $mail->Password   = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $_ENV['MAIL_PORT'];

        // Recipients
        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request - OTP Verification';
        $mail->Body = '
            <p>Dear User,</p>
            <p>We received a request to reset your account password. Please use the following One-Time Password (OTP) to proceed:</p>
            <h2 style="color:#2e6da4;">' . $otp . '</h2>
            <p><strong>Note:</strong> This OTP is valid for <strong>5 minutes</strong>. Do not share it with anyone.</p>
            <p>If you did not request a password reset, please ignore this message or contact support.</p>
            <p>Thank you,<br>Your Support Team</p>
        ';

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'OTP sent successfully!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Mail error: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
