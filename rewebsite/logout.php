<?php
session_start();
include('db_connect.php');

// OPTIONAL: Remove any session-specific data from the database.
// Example: deleting from a hypothetical `user_sessions` table.
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // If you store login sessions or tokens in DB, delete them
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // OPTIONAL: Log logout time in audit table (if you have one)
    // $conn->query("INSERT INTO audit_logs (user_id, action) VALUES ($user_id, 'logout')");
}

// Destroy session
session_unset();
session_destroy();

// Redirect with success message
session_start(); // restart to store session message
$_SESSION['success'] = "You have been logged out successfully.";
header("Location: login.php");
exit();
?>
