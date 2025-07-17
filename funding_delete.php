<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$fun_id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

if (!$fun_id || $fun_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid funding ID.']);
    exit;
}

try {
    $dbh->beginTransaction();

    // Fetch image name from fundings
    $sqlFetch = "SELECT image FROM fundings WHERE fun_id = :fun_id";
    $stmtFetch = $dbh->prepare($sqlFetch);
    $stmtFetch->bindParam(':fun_id', $fun_id, PDO::PARAM_INT);
    $stmtFetch->execute();
    $funding = $stmtFetch->fetch(PDO::FETCH_ASSOC);

    if (!$funding) {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Funding record not found.']);
        exit;
    }

    $image = $funding['image'] ?? '';
    $imagePath = __DIR__ . '/img/uploads/' . $image;

    // Delete image file if it exists
    if (!empty($image) && file_exists($imagePath)) {
        if (!unlink($imagePath)) {
            $dbh->rollBack();
            echo json_encode(['success' => false, 'message' => 'Failed to delete the image file: ' . $image]);
            exit;
        }
    }

    // Delete funding record from DB
    $sqlDelete = "DELETE FROM fundings WHERE fun_id = :fun_id";
    $stmtDelete = $dbh->prepare($sqlDelete);
    $stmtDelete->bindParam(':fun_id', $fun_id, PDO::PARAM_INT);

    if ($stmtDelete->execute()) {
        $dbh->commit();
        echo json_encode(['success' => true]);
    } else {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Failed to delete the funding record from the database.']);
    }
} catch (Exception $e) {
    if ($dbh->inTransaction()) {
        $dbh->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
