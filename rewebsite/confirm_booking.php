<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$room_id = intval($_POST['room_id']);
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];
$total_price = floatval($_POST['total_price']);
$guests = intval($_POST['guests'] ?? 1); // ✅ Fix: define guests
$payment_method = $_POST['payment_method'] ?? 'cash';

$status = 'confirmed';
$created_at = date('Y-m-d H:i:s');

// Optional: Get room name
$room_name = 'Unknown Room';
$room_stmt = $conn->prepare("SELECT room_type FROM rooms WHERE room_id = ?");
$room_stmt->bind_param("i", $room_id);
$room_stmt->execute();
$room_result = $room_stmt->get_result();
if ($row = $room_result->fetch_assoc()) {
    $room_type = $row['room_type'];
}
$room_stmt->close();

// Insert booking
$stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, check_in, check_out, guests, total_price, status, created_at, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisssdsss", $user_id, $room_id, $check_in, $check_out, $guests, $total_price, $status, $created_at, $payment_method);

$booking_success = $stmt->execute();
$booking_id = $conn->insert_id;

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation - Eshara Resort</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <style>
        body {
            background: linear-gradient(to right, #c6ffdd, #fbd786, #f7797d);
            font-family: 'Poppins', sans-serif;
            padding: 40px 10px;
        }
        .confirmation-box {
            max-width: 800px;
            margin: auto;
            padding: 35px;
            background: #ffffffcc;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(30px);}
            to {opacity: 1; transform: translateY(0);}
        }
        .receipt-box h3 {
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 1.9rem;
            color: #0d6efd;
        }
        .receipt-box p {
            font-size: 1.1rem;
            margin-bottom: 12px;
        }
        .receipt-box hr {
            margin: 30px 0;
            border-color: #e0e0e0;
        }
        .btn-home, .btn-view-history {
            margin-top: 15px;
            border-radius: 30px;
            font-weight: 600;
            padding: 10px 25px;
        }
        .receipt-actions {
            margin-top: 15px;
        }
        .receipt-actions button {
            margin: 5px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="confirmation-box">
    <h2 class="mb-4">🎉 Booking Confirmation</h2>

    <?php if ($booking_success): ?>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Booking Confirmed!',
            text: 'Your reservation has been successfully completed.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        </script>

        <div class="receipt-box" id="receipt-box">
            <h3>📄 Booking Receipt</h3>
            <p><strong>Booking ID:</strong> <?= htmlspecialchars($booking_id) ?></p>
            <p><strong>Room:</strong> <?= htmlspecialchars($room_type) ?> (ID: <?= htmlspecialchars($room_id) ?>)</p>
            <p><strong>Guests:</strong> <?= htmlspecialchars($guests) ?></p>
            <p><strong>Check-In:</strong> <?= htmlspecialchars($check_in) ?></p>
            <p><strong>Check-Out:</strong> <?= htmlspecialchars($check_out) ?></p>
            <p><strong>Total Price:</strong> ₹<?= number_format($total_price, 2) ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars(ucfirst($payment_method)) ?></p>
            <p><strong>Booked On:</strong> <?= htmlspecialchars($created_at) ?></p>
            <hr>
            <p>Thank you for booking with <strong>Eshara Resort</strong>!<br>Please show this receipt at check-in.</p>
        </div>

        <p class="mt-3">
            <?= stripos($payment_method, 'cash') !== false
                ? '💵 Please pay at the resort upon arrival.'
                : '✅ Thank you for your payment!' ?>
        </p>

        <div class="receipt-actions">
            <button class="btn btn-success" onclick="printReceipt()">🖨️ Print Receipt</button>
            <button class="btn btn-info" id="downloadBtn" onclick="downloadScreenshot()">📥 Download as PNG</button>
        </div>

    <?php else: ?>
        <div class="alert alert-danger mt-4">
            ❌ Booking failed. Please try again or contact support.
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="booking_history.php" class="btn btn-view-history btn-outline-primary">📖 View Booking History</a>
        <a href="index.php" class="btn btn-home btn-primary">🏠 Back to Home</a>
    </div>
</div>

<script>
function printReceipt() {
    window.print();
}
function downloadScreenshot() {
    html2canvas(document.querySelector('#receipt-box')).then(canvas => {
        let link = document.createElement('a');
        link.download = 'booking_receipt_<?= $booking_id ?>.png';
        link.href = canvas.toDataURL();
        link.click();
    });
}
</script>

</body>
</html>
