<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=booking_history.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT b.*, r.room_type 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.room_id 
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
");
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fcefd4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            margin-top: 60px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #d97706;
            font-weight: 700;
        }

        .table th {
            background-color: #ffcd38;
            color: #000;
            text-align: center;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
            color: #333;
        }

        .badge {
            font-size: 0.9rem;
        }

        .btn-outline-warning {
            display: block;
            margin: 30px auto 0;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .table {
                font-size: 0.9rem;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📖 Your Booking History</h2>
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Total Price</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Booked On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['room_type']) ?></td>
                                <td><?= $booking['check_in'] ?></td>
                                <td><?= $booking['check_out'] ?></td>
                                <td>₹<?= $booking['total_price'] ?></td>
                                <td>
                                    <?= strtolower($booking['payment_method']) === 'online' ? '💳 Online' : '💵 Cash' ?>
                                </td>
                                <td>
                                    <?php if ($booking['status'] === 'confirmed'): ?>
                                        <span class="badge bg-success">Confirmed ✅</span>
                                    <?php elseif ($booking['status'] === 'pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending ⏳</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Cancelled ❌</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $booking['created_at'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted text-center fs-5">You have no bookings yet.</p>
        <?php endif; ?>
        <a href="index.php" class="btn btn-outline-warning btn-lg rounded-pill px-4 shadow-sm">⟵ Go Back</a>
    </div>
</body>
</html>
