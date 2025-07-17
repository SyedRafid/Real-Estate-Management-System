<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/partials/_session.php';

try {
    // Validate request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id']) || !is_numeric($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }

    $saleId = (int)$_POST['id'];

    // Begin transaction
    $dbh->beginTransaction();

    // Fetch the flat ID from the sale
    $stmt = $dbh->prepare("SELECT flat_id FROM sales WHERE sale_id = :id");
    $stmt->execute(['id' => $saleId]);
    $flat = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$flat) {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Sale record not found']);
        exit;
    }

    $flatId = $flat['flat_id'];

    // Delete payments
    $stmt = $dbh->prepare("DELETE FROM payments WHERE sale_id = :id");
    if (!$stmt->execute(['id' => $saleId])) {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to delete payments']);
        exit;
    }

    // Delete the sale
    $stmt = $dbh->prepare("DELETE FROM sales WHERE sale_id = :id");
    if (!$stmt->execute(['id' => $saleId])) {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to delete sale']);
        exit;
    }

    // Change flat status to "available"
    $stmt = $dbh->prepare("UPDATE flats SET status = 'Available' WHERE flat_id = :id");
    $stmt->execute(['id' => $flatId]);

    if ($stmt->rowCount() > 0) {
        $dbh->commit();
        echo json_encode(['success' => true, 'message' => 'Investor deleted successfully']);
    } else {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to update flat status']);
    }
} catch (Exception $e) {
    if ($dbh->inTransaction()) {
        $dbh->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
