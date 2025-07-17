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
    $investorId = clean($_POST['investorId'] ?? '');

    if (!$firstName || !$lastName || !$email || !$phone || !$address || !$investorId) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }

    try {
        //fach original email
        $stmt = $dbh->prepare("SELECT email FROM investor WHERE in_id = ?");
        $stmt->execute([$investorId]);
        $originalEmail = $stmt->fetchColumn();
        if ($originalEmail === false) {
            echo json_encode(['success' => false, 'message' => 'Investor not found.']);
            exit;
        }
        if ($originalEmail !== $email) {
            // Check if email already exists
            $stmt = $dbh->prepare("SELECT COUNT(*) FROM investor WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                echo json_encode(['success' => false, 'message' => 'Email is already registered.']);
                exit;
            }
        }

        // Update investor
        $stmt = $dbh->prepare("UPDATE investor SET fName = ?, lName  = ?, phone = ?, email =?, address = ? WHERE in_id = ?");
        $stmt->execute([$firstName, $lastName, $phone, $email, $address, $investorId]);

        echo json_encode(['success' => true, 'message' => 'Investor Updated successfully!']);
    } catch (PDOException $e) {
        // Log error and return generic message
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
