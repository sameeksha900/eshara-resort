<?php
include 'db_connect.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch reviews from the database with user and room details
$query = "SELECT reviews.review_id, users.full_name, rooms.room_type, reviews.rating, reviews.comment, reviews.created_at 
          FROM reviews
          JOIN users ON reviews.user_id = users.user_id
          JOIN rooms ON reviews.room_id = rooms.room_id
          ORDER BY reviews.review_id DESC";

$reviews = mysqli_query($conn, $query);

if (!$reviews) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Reviews</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

   <style>
       body {
           background: linear-gradient(to right, #e0f7fa, #fce4ec);
           font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
       }
       .container {
           max-width: 1100px;
       }
       .review-box {
           background-color: #ffffff;
           border: 2px solid #90caf9;
           border-radius: 16px;
           box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
           padding: 30px;
           transition: all 0.3s ease;
       }
       .review-box:hover {
           box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
       }
       h2, h4 {
           color: #2c3e50;
           font-weight: bold;
       }
       .table thead th {
           background-color: #1976d2;
           color: white;
           border: 1px solid #1565c0;
       }
       .table td, .table th {
           border: 1px solid #90caf9 !important;
           vertical-align: middle;
       }
       .btn-secondary {
           background-color: #6c757d;
           font-weight: 600;
           border-radius: 8px;
           padding: 10px 20px;
           transition: background-color 0.3s ease;
       }
       .btn-secondary:hover {
           background-color: #5a6268;
       }
       .rating-stars {
           color: #fbc02d;
           font-size: 1.2rem;
       }
   </style>
</head>
<body>

<div class="container py-5">
    <h2 class="text-center mb-5">Guest Reviews</h2>

    <!-- Review Table Card -->
    <div class="review-box">
        <h4 class="mb-4 text-center">All Submitted Reviews</h4>
        
        <?php if (mysqli_num_rows($reviews) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead>
                        <tr>
                            <th>Review ID</th>
                            <th>Guest Name</th>
                            <th>Room Type</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($review = mysqli_fetch_assoc($reviews)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($review['review_id']); ?></td>
                                <td><?= htmlspecialchars($review['full_name']); ?></td>
                                <td><?= htmlspecialchars($review['room_type']); ?></td>
                                <td>
                                    <span class="rating-stars">
                                        <?= str_repeat('★', intval($review['rating'])) ?>
                                        <?= str_repeat('☆', 5 - intval($review['rating'])) ?>
                                    </span><br>
                                    (<?= htmlspecialchars($review['rating']); ?>/5)
                                </td>
                                <td><?= htmlspecialchars($review['comment']); ?></td>
                                <td><?= htmlspecialchars($review['created_at']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted text-center">No reviews found.</p>
        <?php endif; ?>

        <div class="text-end mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
