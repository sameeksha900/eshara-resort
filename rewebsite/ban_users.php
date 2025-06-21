<?php
include 'db_connect.php';

if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Confirm it's reading the user_id
    // echo "Received user_id: " . $user_id;

    // Step 1: Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Step 2: Update status to 'banned'
        $update = $conn->prepare("UPDATE users SET status = 'banned' WHERE user_id = ?");
        $update->bind_param("i", $user_id);
        if ($update->execute()) {
            echo "<script>alert('User has been banned successfully.'); window.location.href='users.php';</script>";
        } else {
            echo "<script>alert('Failed to ban user. Database error.'); window.location.href='users.php';</script>";
        }
    } else {
        echo "<script>alert('User not found. Please check the ID.'); window.location.href='users.php';</script>";
    }
} else {
    echo "<script>alert('Invalid or missing user ID.'); window.location.href='users.php';</script>";
}
?>
