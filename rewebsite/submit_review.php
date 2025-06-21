<?php
session_start();
include 'db_connect.php'; // Ensure database connection

// Handle non-logged-in users
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to submit a review.";
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize & assign values
    $user_id = $_SESSION['user_id'];
    $room_id = intval($_POST['room_id']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    // Validate rating
    if ($rating < 1 || $rating > 5) {
        $_SESSION['error'] = "Rating must be between 1 and 5.";
        header("Location: index.php");
        exit();
    }

    // Prepare and execute insert query
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, room_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");

    if ($stmt) {
        $stmt->bind_param("iiis", $user_id, $room_id, $rating, $comment);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Review submitted successfully!";
        } else {
            $_SESSION['error'] = "Error submitting review: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Database error: " . $conn->error;
    }

    $conn->close();
    header("Location: index.php");
    exit();
}
?>
