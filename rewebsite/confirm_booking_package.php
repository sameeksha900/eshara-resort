<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=booking_package");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || empty($_POST['room_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$room_id = intval($_POST['room_id']);
$room_type = $_POST['room_type'];
$nights = intval($_POST['nights']);
$room_total = floatval($_POST['room_total']);
$travel_expenses = floatval($_POST['travel_expenses']);
$activity_total = floatval($_POST['activity_total']);
$food_total = floatval($_POST['food_total']);
$total_cost = floatval($_POST['total_cost']);
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];

$activities = $_POST['activities'] ?? [];
$food = $_POST['food'] ?? [];

$activities_str = !empty($activities) ? implode(", ", array_map('htmlspecialchars', $activities)) : 'None';
$food_str = !empty($food) ? implode(", ", array_map('htmlspecialchars', $food)) : 'None';

$payment_method = $_POST['payment_method'] ?? 'Not Specified';
$card_name = $_POST['card_name'] ?? '';
$card_number = $_POST['card_number'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$valid_from = $_POST['valid_from'] ?? '';
$valid_till = $_POST['valid_till'] ?? '';

// Prepare insert statement
$stmt = $conn->prepare("INSERT INTO booking_packages 
    (user_id, room_id, room_type, nights, checkin, checkout, room_total, travel_expenses, activity_total, food_total, total_cost, activities, food, payment_method, card_name, card_number, cvv, valid_from, valid_till) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param(
    "iissssddddsssssisss",
    $user_id,
    $room_id,
    $room_type,
    $nights,
    $checkin,
    $checkout,
    $room_total,
    $travel_expenses,
    $activity_total,
    $food_total,
    $total_cost,
    $activities_str,
    $food_str,
    $payment_method,
    $card_name,
    $card_number,
    $cvv,
    $valid_from,
    $valid_till
);

if ($stmt->execute()) {
    $booking_id = $stmt->insert_id;
    $stmt->close();
} else {
    die("Error: Could not complete booking. Please try again later.");
}

$date_time = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Booking Confirmation & Receipt</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
<style>
  body {
    min-height: 100vh;
    background:
      linear-gradient(135deg, #6bc1ff 0%, #b993d6 100%),
      url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1470&q=80') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 20px;
  }
  body::before {
    content: "";
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: -1;
  }
  .confirmation-message {
    margin-top: 40px;
    background: #d1e7dd;
    border: 1px solid #badbcc;
    color: #0f5132;
    padding: 20px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(15, 81, 50, 0.3);
    font-size: 1.3rem;
    font-weight: 600;
    max-width: 600px;
    width: 100%;
    text-align: center;
    animation: fadeInDown 0.6s ease forwards;
  }
  @keyframes fadeInDown {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
  }
  .receipt-box {
    max-width: 600px;
    width: 100%;
    background: #fff;
    border-radius: 15px;
    padding: 35px 40px;
    margin-top: 30px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    color: #333;
    text-align: center;
    position: relative;
    animation: fadeInUp 0.7s ease forwards;
  }
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  .receipt-box h3 {
    font-weight: 700;
    margin-bottom: 30px;
    font-size: 1.9rem;
    color: #0d6efd;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.15);
  }
  .receipt-box p {
    font-size: 1.1rem;
    margin-bottom: 12px;
  }
  .receipt-box hr {
    margin: 30px 0;
    border-color: #e0e0e0;
  }
  .btn-group {
    margin-top: 25px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
  }
  .btn {
    min-width: 150px;
    font-weight: 600;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }
  .btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
  }
  .btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
    box-shadow: 0 7px 20px rgba(10, 88, 202, 0.6);
  }
  .btn-success {
    background-color: #198754;
    border-color: #198754;
    box-shadow: 0 5px 15px rgba(25, 135, 84, 0.4);
  }
  .btn-success:hover {
    background-color: #157347;
    border-color: #146c43;
    box-shadow: 0 7px 20px rgba(20, 108, 67, 0.6);
  }
  .btn-info {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    box-shadow: 0 5px 15px rgba(13, 202, 240, 0.4);
  }
  .btn-info:hover {
    background-color: #31d2f2;
    border-color: #25cff2;
    box-shadow: 0 7px 20px rgba(37, 207, 242, 0.6);
  }
  @media (max-width: 576px) {
    .receipt-box {
      padding: 25px 20px;
    }
    .btn-group {
      flex-direction: column;
    }
    .btn {
      width: 100%;
      min-width: unset;
    }
  }
</style>
</head>
<body>

<div class="confirmation-message">
  <i class="bi bi-check-circle-fill"></i> Your package booking was <strong>successfully confirmed</strong>!
</div>

<div class="receipt-box" id="receipt-box">
  <h3><i class="bi bi-receipt-cutoff"></i> Booking Receipt</h3>

  <p><strong>Booking ID:</strong> <?= htmlspecialchars($booking_id) ?></p>
  <p><strong>Room Type:</strong> <?= htmlspecialchars($room_type) ?></p>
  <p><strong>Nights:</strong> <?= htmlspecialchars($nights) ?></p>
  <p><strong>Check In:</strong> <?= htmlspecialchars($checkin) ?></p>
  <p><strong>Check Out:</strong> <?= htmlspecialchars($checkout) ?></p>
  <p><strong>Activities:</strong> <?= htmlspecialchars($activities_str) ?></p>
  <p><strong>Food:</strong> <?= htmlspecialchars($food_str) ?></p>
  <p><strong>Total Price:</strong> ₹<?= number_format($total_cost, 2) ?></p>
  <p><strong>Payment Method:</strong> <?= htmlspecialchars($payment_method) ?></p>
  <p><strong>Date & Time:</strong> <?= $date_time ?></p>

  <hr>

  <p>
   <?php
    if (isset($payment_method)) {
      echo stripos($payment_method, 'cash') !== false
        ? 'Please pay at the resort upon arrival.'
        : 'Thank you for your payment!';
    } else {
      echo 'Payment method not specified.';
    }
  ?>

  </p>
  <p>Have a pleasant stay at our resort!</p>
  <p>You carry this Receipt for Confirmation...</p>

  <div class="btn-group">
    <button onclick="window.print()" class="btn btn-success me-2">
      <i class="bi bi-printer"></i> Print Receipt
    </button>
    <button id="downloadBtn" class="btn btn-info me-2">
      <i class="bi bi-download"></i> Download as PNG
    </button>
    <a href="index.php" class="btn btn-primary">
      <i class="bi bi-house-door"></i> Go to Home
    </a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
  document.getElementById('downloadBtn').addEventListener('click', function() {
    html2canvas(document.querySelector('#receipt-box')).then(canvas => {
      let link = document.createElement('a');
      link.download = 'booking_receipt_<?= $booking_id ?>.png';
      link.href = canvas.toDataURL();
      link.click();
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
