<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/includes/config.php';

try {

    // Validate POST and user ID
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id']) || !is_numeric($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }

    $userId = (int)$_POST['id'];

    // Prevent deleting yourself
    if ($userId === $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
        exit;
    }

    // Check user type
    $stmt = $dbh->prepare("SELECT userType FROM users WHERE user_id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user['userType'] === 'superAdmin') {
        echo json_encode(['success' => false, 'message' => 'Cannot delete super admin account']);
        exit;
    }

    // Delete user
    $stmt = $dbh->prepare("DELETE FROM users WHERE user_id = :id");
    $deleted = $stmt->execute(['id' => $userId]);

    echo json_encode([
        'success' => $deleted,
        'message' => $deleted ? 'User deleted successfully' : 'Failed to delete user'
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
