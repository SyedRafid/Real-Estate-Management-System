<?php
header('Content-Type: application/json');
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['buildingName'], $data['buildingAddress'], $data['layoutData'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit;
}

$buildingName = trim($data['buildingName']);
$buildingAddress = trim($data['buildingAddress']);
$layoutData = $data['layoutData'];

try {
    // 1. Insert building
    $stmt = $dbh->prepare("INSERT INTO buildings (building_name, location, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$buildingName, $buildingAddress]);
    $building_id = $dbh->lastInsertId();

    // 2. Insert floors and flats
    foreach ($layoutData as $floorNumber => $flats) {
        $stmt = $dbh->prepare("INSERT INTO floors (building_id, floor_number) VALUES (?, ?)");
        $stmt->execute([$building_id, (int)$floorNumber]);
        $floor_id = $dbh->lastInsertId();

        foreach ($flats as $flat) {
            $flatLabel = $flat['label'];
            $status = $flat['status'];
            $stmt = $dbh->prepare("INSERT INTO flats (floor_id, flat_lable, status) VALUES (?, ?, ?)");
            $stmt->execute([$floor_id, $flatLabel, $status]);
        }
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
