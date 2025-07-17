<?php // Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user session exists
if (!isset($_SESSION['user_id'])) {
    // No session found, force logout
    header("Location: logout.php");
    exit;
} else {
    $userId = $_SESSION['user_id'];
}
?>