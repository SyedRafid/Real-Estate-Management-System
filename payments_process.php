<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

try {
    $saleId = $_POST['sale_id'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $max = $_POST['max'] ?? '';

    if (!$saleId) {
        echo json_encode(['success' => false, 'message' => 'Missing required field: sale_id']);
        exit;
    }

    if (!$amount) {
        echo json_encode(['success' => false, 'message' => 'Missing required field: amount']);
        exit;
    }

    if (!$max) {
        echo json_encode(['success' => false, 'message' => 'Missing required field: max']);
        exit;
    }

    // Fetch flat_id from sales table
    $stmt = $dbh->prepare("SELECT flat_id FROM sales WHERE sale_id = ?");
    $stmt->execute([$saleId]);
    $flat = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$flat) {
        echo json_encode(['success' => false, 'message' => 'Invalid sale ID.']);
        exit;
    }

    $flatId = $flat['flat_id'];

    // Start transaction
    $dbh->beginTransaction();

    // Insert into payments table
    $paymentStmt = $dbh->prepare("INSERT INTO payments (sale_id, amount) VALUES (?, ?)");
    $paymentSuccess = $paymentStmt->execute([$saleId, $amount]);

    if (!$paymentSuccess) {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to save payment data.']);
        exit;
    }

    // If fully paid, update flat status
    if ((float)$amount >= (float)$max) {
        $updateStmt = $dbh->prepare("UPDATE flats SET status = 'Sold' WHERE flat_id = ?");
        $updateSuccess = $updateStmt->execute([$flatId]);

        if (!$updateSuccess) {
            $dbh->rollBack();
            echo json_encode(['success' => false, 'message' => 'Failed to update flat status.']);
            exit;
        }
    }

    // Commit transaction
    $dbh->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    if ($dbh->inTransaction()) {
        $dbh->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
