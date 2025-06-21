<?php
session_start();
include 'db_connect.php';

// If not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    // Save current URL and POST data for after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    $_SESSION['post_data'] = $_POST;
    header("Location: login.php");
    exit();
}

// Fixed travel cost
$travel_expenses = 2500;

// Predefined activities
$activities = [
    'Spa and Wellness' => 1000,
    'Scuba Diving' => 1500,
    'Sunset Cruise'=>1000,
    'Meditation and Exercise'=>300,
    'Trekking' => 800,
    'Cultural Tour' => 1200,
    "Kid's Play Zone"=>1200,
    'Pottery Class'=>2000,
    'Volleyball'=>700,
    'Animal Petting Zoo'=>1600,
    'Bonfire & Campfire'=>900,
    'Puppet Show'=>500
];

// Predefined food options
$food_options = [
    "Breakfast" => 500,
    "Lunch" => 700,
    "Dinner" => 900
];

$total_cost = 0;
$selected_activities = [];
$selected_food = [];
$nights = 0;
$room_total = 0;
$activity_total = 0;
$food_total = 0;

// Fetch available rooms
$rooms_result = $conn->query("SELECT room_id, room_type, price FROM rooms");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['room_id'];
    $room_query = $conn->query("SELECT price, room_type FROM rooms WHERE room_id = $room_id");
    $room_data = $room_query->fetch_assoc();
    $room_price = $room_data['price'];
    $room_type = $room_data['room_type'];

    $checkin = strtotime($_POST['checkin']);
    $checkout = strtotime($_POST['checkout']);

    if ($checkin && $checkout && $checkout > $checkin) {
        $nights = round(($checkout - $checkin) / (60 * 60 * 24));
        $room_total = $room_price * $nights;
    }

    $today = strtotime(date("Y-m-d"));
    $error = "";

    if ($checkin < $today) {
        $error = "⚠️ Check-in date cannot be in the past.";
    } elseif ($checkout < $today) {
        $error = "⚠️ Check-out date cannot be in the past.";
    } elseif ($checkout <= $checkin) {
        $error = "⚠️ Check-out date must be after check-in date.";
    }

    // Activities
    $selected_activities = $_POST["activities"] ?? [];
    foreach ($selected_activities as $activity) {
        if (isset($activities[$activity])) {
            $activity_total += $activities[$activity];
        }
    }

    // Food options
    $selected_food = $_POST["food"] ?? [];
    foreach ($selected_food as $food) {
        if (isset($food_options[$food])) {
            $food_total += $food_options[$food] * $nights; // per night
        }
    }

    $total_cost = $room_total + $travel_expenses + $activity_total + $food_total;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Package Calculator</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #3a6186, #89253e);
      color: #f0f0f0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      padding: 20px 0;
    }
    .calculator-container {
      max-width: 900px;
      margin: 50px auto;
      background: #1f2937; /* dark slate */
      padding: 35px 40px;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.6);
      border: 1px solid #4f46e5; /* soft indigo border */
    }
    h2 {
      color: #a5b4fc; /* light indigo */
      text-shadow: 1px 1px 6px rgba(0,0,0,0.6);
      font-weight: 700;
    }
    label.form-label {
      color: #c7d2fe;
      font-weight: 600;
    }
    select.form-select,
    input.form-control {
      background: #374151; /* dark gray-blue */
      color: #e0e7ff;
      border: 1px solid #4f46e5;
      transition: border-color 0.3s ease;
    }
    select.form-select:focus,
    input.form-control:focus {
      background: #4f46e5;
      color: white;
      border-color: #818cf8;
      box-shadow: 0 0 8px #818cf8;
      outline: none;
    }
    .form-check-label {
      color: #dbeafe;
      font-weight: 500;
    }
    .form-check-input:checked {
      background-color: #6366f1;
      border-color: #6366f1;
    }
    .alert-info {
      background-color: #2563eb; /* bright blue */
      color: #e0e7ff;
      border: none;
    }
    .summary-box {
      background-color: #4b5563; /* medium slate gray */
      padding: 25px 30px;
      border-radius: 15px;
      color: #e0e7ff;
      box-shadow: inset 0 0 15px #3b82f6aa;
      border: 1px solid #3b82f6;
    }
    .summary-box h5 {
      color: #bfdbfe;
      font-weight: 700;
      text-shadow: 0 0 6px #3b82f6;
    }
    .summary-box p {
      font-size: 1.1rem;
      margin-bottom: 8px;
    }
    .summary-box hr {
      border-color: #60a5fa;
      margin: 1.2rem 0;
    }
    .summary-box h4 {
      color: #93c5fd;
      font-weight: 800;
      text-shadow: 0 0 8px #2563ebcc;
    }
    button.btn-primary {
      background-color: #6366f1;
      border: none;
      font-weight: 600;
      padding: 10px 24px;
      font-size: 1.1rem;
      transition: background-color 0.3s ease;
    }
    button.btn-primary:hover {
      background-color: #4338ca;
    }
    button.btn-outline-secondary {
      color: #a5b4fc;
      border-color: #a5b4fc;
      font-weight: 600;
      padding: 10px 20px;
      font-size: 1rem;
    }
    button.btn-outline-secondary:hover {
      background-color: #a5b4fc;
      color: #1f2937;
      border-color: #a5b4fc;
    }
    a.btn-secondary {
      background-color: #ec4899; /* pink */
      color: white !important;
      font-weight: 600;
      padding: 10px 22px;
      font-size: 1rem;
      border-radius: 0.35rem;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }
    a.btn-secondary:hover {
      background-color: #db2777;
      color: white !important;
      text-decoration: none;
    }
    .text-center form button.btn-success {
      background-color: #10b981; /* emerald */
      border: none;
      font-weight: 700;
      padding: 12px 26px;
      font-size: 1.15rem;
      box-shadow: 0 0 15px #059669cc;
      transition: background-color 0.3s ease;
    }
    .text-center form button.btn-success:hover {
      background-color: #059669;
      box-shadow: 0 0 20px #10b981cc;
    }
    .text-muted {
      color: #cbd5e1 !important; /* lighter, more visible gray */
      font-weight: 400;
    }
  </style>
</head>
<body>

<div class="container calculator-container">
  <h2 class="text-center mb-4">🌴 Package Cost Calculator</h2>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label"><strong>🏨 Select Room</strong></label>
      <select name="room_id" class="form-select" required>
        <option value="">-- Choose a Room --</option>
        <?php while ($room = $rooms_result->fetch_assoc()): ?>
          <option value="<?= $room['room_id'] ?>"
            <?= (isset($_POST['room_id']) && $_POST['room_id'] == $room['room_id']) ? 'selected' : '' ?>>
            <?= $room['room_type'] ?> — ₹<?= number_format($room['price']) ?>/night
          </option>
        <?php endwhile; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center">
                <?= $error ?>
            </div>
        <?php endif; ?>

      </select>
    </div>

    <div class="row mb-3">
      <div class="col">
        <label class="form-label"><strong>📅 Check-in Date</strong></label>
        <input type="date" name="checkin" class="form-control" required value="<?= $_POST['checkin'] ?? '' ?>">
      </div>
      <div class="col">
        <label class="form-label"><strong>📅 Check-out Date</strong></label>
        <input type="date" name="checkout" class="form-control" required value="<?= $_POST['checkout'] ?? '' ?>">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label"><strong>🚌 Travel Expense</strong></label>
      <label class="form-label"><strong>(We will pay at the resort travel amount)</strong></label>
      <div class="alert alert-info mb-0">
        <i class="bi bi-truck"></i> ₹<?= $travel_expenses ?> (fixed)
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label"><strong>🎯 Choose Activities</strong></label>
      <div class="row">
        <?php foreach ($activities as $activity => $price): ?>
          <div class="col-md-6">
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" name="activities[]" value="<?= htmlspecialchars($activity) ?>" <?= in_array($activity, $selected_activities) ? 'checked' : '' ?>>
              <label class="form-check-label">
                <i class="bi bi-star-fill text-warning"></i> <?= htmlspecialchars($activity) ?> <span class="text-muted">(₹<?= $price ?>)</span>
              </label>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label"><strong>🍽️ Choose Food Options</strong></label>
      <div class="row">
        <?php foreach ($food_options as $food => $price): ?>
          <?php 
            $time_label = ($food == 'Breakfast') ? 'morning' : (($food == 'Lunch') ? 'afternoon' : 'night');
          ?>
          <div class="col-md-4">
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" name="food[]" value="<?= $food ?>" <?= in_array($food, $selected_food) ? 'checked' : '' ?>>
              <label class="form-check-label">
                <i class="bi bi-egg-fried text-success"></i> <?= $food ?> <span class="text-muted">(₹<?= $price ?>/<?= $time_label ?>)</span>
              </label>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="summary-box mt-4">
        <h5 class="mb-3">📊 Cost Summary</h5>
        <p><strong>Number of Nights:</strong> <?= $nights ?></p>
        <p><strong>Room Cost:</strong> ₹<?= number_format($room_total, 2) ?></p>
        <p><strong>Travel Cost:</strong> ₹<?= number_format($travel_expenses, 2) ?></p>
        <p><strong>Activity Cost:</strong></p>
        <ul>
            <?php if (!empty($selected_activities)): ?>
                <?php foreach ($selected_activities as $activity): ?>
                    <li><?= htmlspecialchars($activity) ?> — ₹<?= number_format($activities[$activity]) ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No activities selected</li>
            <?php endif; ?>
            <li><strong>Total: ₹<?= number_format($activity_total, 2) ?></strong></li>
        </ul>
        <p><strong>Food Cost:</strong> ₹<?= number_format($food_total, 2) ?></p>
        <hr>
        <h4>Total: ₹<?= number_format($total_cost, 2) ?></h4>
    </div>


    <div class="mt-4 text-center">
      <button type="submit" class="btn btn-primary"><i class="bi bi-calculator-fill"></i> Calculate</button>
      <button type="button" onclick="window.print()" class="btn btn-outline-secondary ms-2">🖨️ Print Summary</button>
      <a href="index.php" class="btn btn-secondary ms-2">← Back to Home</a>
    </div>
  </form>

  <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <div class="text-center mt-4">
      <form method="POST" action="booking_package.php">
        <input type="hidden" name="room_id" value="<?= $room_id ?>">
        <input type="hidden" name="room_type" value="<?= htmlspecialchars($room_type) ?>">
        <input type="hidden" name="nights" value="<?= $nights ?>">
        <input type="hidden" name="room_total" value="<?= $room_total ?>">
        <input type="hidden" name="travel_expenses" value="<?= $travel_expenses ?>">
        <input type="hidden" name="activity_total" value="<?= $activity_total ?>">
        <input type="hidden" name="food_total" value="<?= $food_total ?>">
        <input type="hidden" name="total_cost" value="<?= $total_cost ?>">
        <input type="hidden" name="checkin" value="<?= $_POST['checkin'] ?>">
        <input type="hidden" name="checkout" value="<?= $_POST['checkout'] ?>">

        <?php foreach ($selected_activities as $activity): ?>
            <input type="hidden" name="activities[]" value="<?= htmlspecialchars($activity) ?>">
        <?php endforeach; ?>

        <?php foreach ($selected_food as $food): ?>
            <input type="hidden" name="food[]" value="<?= htmlspecialchars($food) ?>">
        <?php endforeach; ?>

        <input type="hidden" name="payment_method" value="cash">

        <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle"></i> Confirm Booking
        </button>
      </form>
    </div>
  <?php endif; ?>
  
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelector("form").addEventListener("submit", function(e) {
  const checkin = new Date(document.querySelector('input[name="checkin"]').value);
  const checkout = new Date(document.querySelector('input[name="checkout"]').value);
  if (checkout <= checkin) {
    alert("❗ Check-out date must be after check-in date.");
    e.preventDefault();
  }
});
document.querySelector("form").addEventListener("submit", function(e) {
  const today = new Date();
  today.setHours(0, 0, 0, 0); // Set to start of today for accurate comparison

  const checkinInput = document.querySelector('input[name="checkin"]');
  const checkoutInput = document.querySelector('input[name="checkout"]');

  const checkin = new Date(checkinInput.value);
  const checkout = new Date(checkoutInput.value);

  if (checkin < today) {
    alert("❗ Check-in date cannot be in the past.");
    e.preventDefault();
    return;
  }

  if (checkout < today) {
    alert("❗ Check-out date cannot be in the past.");
    e.preventDefault();
    return;
  }

  if (checkout <= checkin) {
    alert("❗ Check-out date must be after check-in date.");
    e.preventDefault();
    return;
  }
});

</script>

</body>
</html>
