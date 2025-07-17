<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';
header('Content-Type: application/json');
try {
    $stmt = $dbh->query("SELECT building_id, building_name FROM buildings ORDER BY building_name");
    $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'buildings' => $buildings]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch buildings.']);
}
