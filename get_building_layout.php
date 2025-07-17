<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';
header('Content-Type: application/json');

$building_id = $_GET['building_id'] ?? null;
if (!$building_id) {
    echo json_encode(['success' => false, 'message' => 'No building selected.']);
    exit;
}

try {
    // Get floors
    $stmt = $dbh->prepare("SELECT floor_id, floor_number FROM floors WHERE building_id = ? ORDER BY floor_number");
    $stmt->execute([$building_id]);
    $floors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $layoutData = [];
    foreach ($floors as $floor) {

        $stmt2 = $dbh->prepare("SELECT flat_id, flat_lable AS label, status FROM flats WHERE floor_id = ? ORDER BY flat_lable");
        $stmt2->execute([$floor['floor_id']]);
        $layoutData[$floor['floor_number']] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode(['success' => true, 'layoutData' => $layoutData]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch layout.']);
}
