<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/includes/config.php';

$userId = $_SESSION['user_id'];
$newEmail = trim($_POST['email'] ?? '');

// Validate input
if (empty($newEmail) || !filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

try {
    // Check if email is already used by someone else
    $stmt = $dbh->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
    $stmt->execute([$newEmail, $userId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email is already taken by another user.']);
        exit;
    }

    // Update the email
    $stmt = $dbh->prepare("UPDATE users SET email = ?, updated_at = NOW() WHERE user_id = ?");
    $stmt->execute([$newEmail, $userId]);

    echo json_encode(['success' => true, 'message' => 'Email updated successfully.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
