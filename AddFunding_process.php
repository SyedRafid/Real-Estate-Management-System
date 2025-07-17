<?php
require_once __DIR__ . '/partials/_session.php';

header('Content-Type: application/json');
require_once __DIR__ . '/includes/config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    // Collect and sanitize POST data
    $investor = trim($_POST['investorSelect'] ?? '');
    $amount = floatval($_POST['amount'] ?? 0);
    $note = !empty($_POST['note']) ? trim($_POST['note']) : null;

    $image = null;

    if (empty($investor) || $amount <= 0) {
        throw new Exception('Please fill in all required fields with valid values.');
    }

    // Handle image upload (optional)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "img/uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $uniqueFileName = uniqid() . '.' . $imageFileType;
        $targetFile = $targetDir . $uniqueFileName;

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            throw new Exception('Uploaded file is not a valid image.');
        }

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            throw new Exception('Failed to upload the image.');
        }

        $image = $uniqueFileName;
    }

    // Insert into database
    $dbh->beginTransaction();

    $sql = "INSERT INTO fundings (in_id, amount, note, image)
            VALUES (:investor, :amount, :note, :image)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':investor', $investor, PDO::PARAM_STR);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':note', $note, PDO::PARAM_STR);
    $stmt->bindParam(':image', $image, PDO::PARAM_STR);

    if (!$stmt->execute()) {
        $dbh->rollBack();
        throw new Exception('Failed to insert funding entry.');
    }

    $dbh->commit();
    echo json_encode(['success' => true, 'message' => 'Funding entry successfully added.']);
} catch (Exception $e) {
    if ($dbh->inTransaction()) {
        $dbh->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
