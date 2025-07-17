<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/partials/_session.php';

try {

    // Validate POST and investor ID
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id']) || !is_numeric($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }

    $investorId = (int)$_POST['id'];

    // Check if investor has related fundings
    $checkSql = "SELECT COUNT(*) FROM fundings WHERE in_id = :investorId";
    $checkStmt = $dbh->prepare($checkSql);
    $checkStmt->bindParam(':investorId', $investorId, PDO::PARAM_INT);
    $checkStmt->execute();
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Cannot delete investor: related fundings exist.'
        ]);
        exit;
    }

    // Delete investor
    $stmt = $dbh->prepare("DELETE FROM investor WHERE in_id = :id");
    $deleted = $stmt->execute(['id' => $investorId]);

    echo json_encode([
        'success' => $deleted,
        'message' => $deleted ? 'Investor deleted successfully' : 'Failed to delete investor'
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
