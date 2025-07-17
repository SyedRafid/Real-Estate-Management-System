<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $flatId = $data['flatId'] ?? null;

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'No data received.']);
        exit;
    }

    $firstName = $data['firstName'] ?? '';
    $lastName = $data['lastName'] ?? '';
    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? '';
    $address = $data['address'] ?? '';
    $price = $data['price'] ?? '';
    $paymentOption = $data['paymentOption'] ?? '';
    $payment = $data['payment'] ?? '';
    $note = $data['note'] ?? '';

    if (!$flatId) {
        echo json_encode(['success' => false, 'message' => 'No flat selected.']);
        exit;
    }

    // Start transaction
    $dbh->beginTransaction();

    // Insert into sales table
    $stmt = $dbh->prepare("INSERT INTO sales (flat_id, first_name, last_name, phone, email, address, price, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $success = $stmt->execute([$flatId, $firstName, $lastName, $phone, $email, $address, $price, $note]);

    if (!$success) {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to save sales data.']);
        exit;
    }

    $sale_id = $dbh->lastInsertId();

    // Update flat status
    $updateStmt = $dbh->prepare("UPDATE flats SET status = ? WHERE flat_id = ?");
    $updateSuccess = $updateStmt->execute([$paymentOption, $flatId]);

    if (!$updateSuccess) {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to save flat status data.']);
        exit;
    }

    // Insert into payment table
    $paymentStmt = $dbh->prepare("INSERT INTO payments (sale_id, amount) VALUES (?, ?)");
    $paymentSuccess = $paymentStmt->execute([$sale_id, $payment]);

    if (!$paymentSuccess) {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to save payment data.']);
        exit;
    }

    // Commit transaction if everything is successful
    $dbh->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    if ($dbh->inTransaction()) {
        $dbh->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
