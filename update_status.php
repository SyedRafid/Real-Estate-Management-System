<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';

if (isset($_POST['in_id']) && isset($_POST['status'])) {
    $investorId = $_POST['in_id'];
    $newStatus = $_POST['status'];

    $sql = "UPDATE investor SET status = :newStatus WHERE in_id = :investorId";
    $query = $dbh->prepare($sql);
    $query->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
    $query->bindParam(':investorId', $investorId, PDO::PARAM_INT);

    if ($query->execute()) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid_input";
}
