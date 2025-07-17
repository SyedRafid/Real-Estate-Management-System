<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/includes/config.php';

$userId = $_SESSION['user_id'];

// Sanitize input
$fName = trim($_POST['fName'] ?? '');
$lName = trim($_POST['lName'] ?? '');

if (empty($fName) || empty($lName)) {
    echo json_encode(['success' => false, 'message' => 'Both first and last names are required.']);
    exit;
}

try {
    // Fetch current names
    $stmt = $dbh->prepare("SELECT fName, lName FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        exit;
    }

    // Check if changes are needed
    if ($fName === $user['fName'] && $lName === $user['lName']) {
        echo json_encode(['success' => false, 'message' => 'No changes detected.']);
        exit;
    }

    // Update names
    $stmt = $dbh->prepare("UPDATE users SET fName = ?, lName = ?, updated_at = NOW()  WHERE user_id = ?");
    $stmt->execute([$fName, $lName, $userId]);

    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
