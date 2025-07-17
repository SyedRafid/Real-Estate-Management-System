<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/partials/_session.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id']) || !is_numeric($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    $buildingId = (int)$_POST['id'];

    // Start transaction
    $dbh->beginTransaction();

    // Get all floor_ids for the building
    $stmt = $dbh->prepare("SELECT floor_id FROM floors WHERE building_id = ?");
    $stmt->execute([$buildingId]);
    $floorIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($floorIds)) {
        // Get all flat_ids from these floors
        $placeholders = implode(',', array_fill(0, count($floorIds), '?'));
        $flatStmt = $dbh->prepare("SELECT flat_id FROM flats WHERE floor_id IN ($placeholders)");
        $flatStmt->execute($floorIds);
        $flatIds = $flatStmt->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($flatIds)) {
            // Check if any of these flats exist in sales
            $flatPlaceholders = implode(',', array_fill(0, count($flatIds), '?'));
            $salesCheckStmt = $dbh->prepare("SELECT COUNT(*) FROM sales WHERE flat_id IN ($flatPlaceholders)");
            $salesCheckStmt->execute($flatIds);
            $saleCount = $salesCheckStmt->fetchColumn();

            if ($saleCount > 0) {
                $dbh->rollBack();
                echo json_encode([
                    'success' => false,
                    'message' => 'Cannot delete: One or more flats have already been sold.'
                ]);
                exit;
            }

            // If no flats sold, proceed with deleting flats
            $deleteFlatsStmt = $dbh->prepare("DELETE FROM flats WHERE flat_id IN ($flatPlaceholders)");
            if (!$deleteFlatsStmt->execute($flatIds)) {
                $dbh->rollBack();
                echo json_encode(['success' => false, 'message' => 'Failed to delete flats.']);
                exit;
            }
        }
    }

    // Delete floors
    $floorStmt = $dbh->prepare("DELETE FROM floors WHERE building_id = ?");
    if (!$floorStmt->execute([$buildingId])) {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to delete floors.']);
        exit;
    }

    // Delete building
    $buildingStmt = $dbh->prepare("DELETE FROM buildings WHERE building_id = ?");
    if (!$buildingStmt->execute([$buildingId])) {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to delete building.']);
        exit;
    }

    $dbh->commit();
    echo json_encode(['success' => true, 'message' => 'Building and all related data deleted successfully.']);
} catch (Exception $e) {
    if ($dbh->inTransaction()) {
        $dbh->rollBack();
    }

    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
