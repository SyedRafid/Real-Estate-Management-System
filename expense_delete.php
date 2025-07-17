<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';

if (isset($_POST['id'])) {
    $expenseId = intval($_POST['id']);

    try {
        $dbh->beginTransaction();

        // Fetch image name
        $sqlFetch = "SELECT image FROM expense WHERE ep_id = :expenseId";
        $stmtFetch = $dbh->prepare($sqlFetch);
        $stmtFetch->bindParam(':expenseId', $expenseId, PDO::PARAM_INT);
        $stmtFetch->execute();
        $expense = $stmtFetch->fetch(PDO::FETCH_ASSOC);

        if ($expense) {
            $imagePath = 'img/uploads/' . $expense['image'];

            if (!empty($expense['image']) && file_exists($imagePath)) {
                if (!unlink($imagePath)) {
                    // Image deletion failed
                    $dbh->rollBack();
                    echo json_encode(['success' => false, 'message' => 'Failed to delete the image file.']);
                    exit;
                }
            }

            // Delete the database record
            $sqlDelete = "DELETE FROM expense WHERE ep_id = :expenseId";
            $stmtDelete = $dbh->prepare($sqlDelete);
            $stmtDelete->bindParam(':expenseId', $expenseId, PDO::PARAM_INT);

            if ($stmtDelete->execute()) {
                $dbh->commit();
                echo json_encode(['success' => true]);
            } else {
                $dbh->rollBack();
                echo json_encode(['success' => false, 'message' => 'Failed to delete the expense from the database.']);
            }
        } else {
            $dbh->rollBack();
            echo json_encode(['success' => false, 'message' => 'Expense not found']);
        }
    } catch (Exception $e) {
        $dbh->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
