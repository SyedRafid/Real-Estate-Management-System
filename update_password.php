<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/includes/config.php';

try {
    $userId = $_SESSION['user_id'];

    // Sanitize inputs
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validate inputs
    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        throw new Exception('All fields are required.');
    }

    if ($newPassword !== $confirmPassword) {
        throw new Exception('New password and confirmation do not match.');
    }

    // Fetch user's current hashed password
    $stmt = $dbh->prepare("SELECT password FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('User not found.');
    }

    // Verify old password
    if (!password_verify($oldPassword, $user['password'])) {
        throw new Exception('Old password is incorrect.');
    }

    // Hash and update new password
    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $updateStmt = $dbh->prepare("UPDATE users SET password = :password, updated_at = NOW() WHERE user_id = :user_id");
    $updateSuccess = $updateStmt->execute([
        'password' => $newHashedPassword,
        'user_id' => $userId
    ]);

    if (!$updateSuccess) {
        throw new Exception('Failed to update password.');
    }

    echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
