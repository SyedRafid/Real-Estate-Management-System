<?php
header('Content-Type: application/json');

// Include DB config file
require_once __DIR__ . '/includes/config.php';

// Simple function to sanitize input
function clean($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = clean($_POST['firstName'] ?? '');
    $lastName = clean($_POST['lastName'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $repeatPassword = $_POST['repeatPassword'] ?? '';
    $userType = "admin";

    if (!$firstName || !$lastName || !$email || !$password || !$repeatPassword) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }

    if ($password !== $repeatPassword) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit;
    }

    // Hash password securely
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if email already exists
        $stmt = $dbh->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'Email is already registered.']);
            exit;
        }

        // Insert new user
        $stmt = $dbh->prepare("INSERT INTO users (fName, lName, email, password, userType) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $email, $passwordHash, $userType]);

        echo json_encode(['success' => true, 'message' => 'Account successfully created!']);
    } catch (PDOException $e) {
        // Log error and return generic message
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
