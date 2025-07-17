<?php
header('Content-Type: application/json');

require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';

// Simple function to sanitize input
function clean($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = clean($_POST['firstName'] ?? '');
    $lastName = clean($_POST['lastName'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $address = clean($_POST['address'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $status = 'active';


    if (!$firstName || !$lastName || !$email || !$phone || !$address) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }

    try {
        // Check if email already exists
        $stmt = $dbh->prepare("SELECT COUNT(*) FROM investor WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'Email is already registered.']);
            exit;
        }

        // Insert new investor
        $stmt = $dbh->prepare("INSERT INTO investor (fName, lName, phone, email, address, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $phone, $email, $address, $status]);

        echo json_encode(['success' => true, 'message' => 'Investor successfully created!']);
    } catch (PDOException $e) {
        // Log error and return generic message
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
