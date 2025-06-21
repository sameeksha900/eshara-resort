<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$user_query = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Same initial checks
    if (empty($_POST['room_id'])) {
        header("Location: index.php");
        exit;
    }

    $room_id = intval($_POST['room_id']);
    $room_type = htmlspecialchars($_POST['room_type']);
    $nights = intval($_POST['nights']);
    $room_total = floatval($_POST['room_total']);
    $travel_expenses = floatval($_POST['travel_expenses']);
    $activity_total = floatval($_POST['activity_total']);
    $food_total = floatval($_POST['food_total']);
    $total_cost = floatval($_POST['total_cost']);
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $selected_activities = $_POST['activities'] ?? [];
    $selected_food = $_POST['food'] ?? [];

    // Get room capacity
    $room_query = $conn->prepare("SELECT max_people FROM rooms WHERE room_id = ?");
    $room_query->bind_param("i", $room_id);
    $room_query->execute();
    $room_result = $room_query->get_result();
    $room_data = $room_result->fetch_assoc();
    $max_people= $room_data ? intval($room_data['max_people']) : 1;

    // Validation logic ONLY if confirm_booking submitted
    if (isset($_POST['confirm_booking'])) {
        $guests = isset($_POST['guests']) ? intval($_POST['guests']) : 0;
        $payment_method = $_POST['payment_method'] ?? 'cash';

        if ($guests < 1 || $guests > $max_people) {
            $errors[] = "Number of guests must be between 1 and $max_people.";
        }

        if ($payment_method === 'online') {
            $valid_from = $_POST['valid_from'] ?? '';
            $valid_till = $_POST['valid_till'] ?? '';

            if (empty($valid_from) || empty($valid_till)) {
                $errors[] = "Please provide both 'Valid From' and 'Valid Till' dates for the card.";
            } elseif ($valid_till < $valid_from) {
                $errors[] = "'Valid Till' date cannot be earlier than 'Valid From' date.";
            }
        }

        if (!empty($errors)) {
            // Show errors and stop further processing
            echo '<div class="container my-5 error-container">';
            echo '<div class="alert alert-custom-danger"><h4>Validation Errors:</h4><ul>';
            foreach ($errors as $error) {
                echo "<li>" . htmlspecialchars($error) . "</li>";
            }
            echo '</ul><a href="javascript:history.back()" class="btn btn-go-back">Go Back</a></div></div>';
            exit;
        }
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Confirm Your Package Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        /* Background gradient for entire page */
        body {
            background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #222;
        }

        /* Container styling */
        .container.my-5 {
            background: #fff;
            border-radius: 15px;
            padding: 30px 40px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            max-width: 700px;
        }

        h2 {
            font-weight: 700;
            color: #064273;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }

        /* Card header background & text */
        .card-header.bg-primary {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            font-weight: 600;
            font-size: 1.2rem;
            letter-spacing: 0.05em;
        }

        /* Booking summary text */
        .card-body p {
            font-size: 1.05rem;
            margin-bottom: 0.6rem;
        }

        .card-body hr {
            border-top: 2px solid #2a5298;
        }

        /* Total cost style */
        .card-body h4 {
            color: #0d3b66;
            font-weight: 700;
        }

        /* Guests input */
        input[type=number] {
            border: 2px solid #2a5298;
            border-radius: 8px;
            padding: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        input[type=number]:focus {
            border-color: #f3a712;
            outline: none;
            box-shadow: 0 0 8px #f3a712aa;
        }
        .form-label {
            font-weight: 600;
            color: #064273;
        }
        .form-text {
            color: #4a4a4a;
        }

        /* Payment method radio buttons */
        .form-check-label {
            font-weight: 600;
            font-size: 1.05rem;
            color: #064273;
            cursor: pointer;
        }
        .form-check-input:checked + label {
            color: #f3a712;
            font-weight: 700;
        }

        /* Online payment fields box */
        #onlinePaymentFields {
            background: #e9f1fb;
            padding: 15px 20px;
            border-radius: 12px;
            border: 1px solid #9cc2f3;
            margin-bottom: 25px;
            box-shadow: inset 0 0 10px #9cc2f3aa;
        }
        #onlinePaymentFields label {
            color: #0d3b66;
            font-weight: 600;
        }
        #onlinePaymentFields input {
            border: 1.5px solid #2a5298;
            border-radius: 6px;
            padding: 8px;
            transition: border-color 0.3s ease;
        }
        #onlinePaymentFields input:focus {
            border-color: #f3a712;
            box-shadow: 0 0 6px #f3a712aa;
            outline: none;
        }

        /* Confirm & Cancel buttons */
        button.btn-success {
            background: linear-gradient(45deg, #f3a712, #f37021);
            border: none;
            font-size: 1.2rem;
            font-weight: 700;
            padding: 10px 25px;
            box-shadow: 0 4px 15px rgba(243, 119, 33, 0.6);
            transition: background 0.3s ease;
        }
        button.btn-success:hover {
            background: linear-gradient(45deg, #f37021, #f3a712);
        }
        a.btn-secondary {
            background: #064273;
            border: none;
            font-size: 1.2rem;
            font-weight: 700;
            padding: 10px 25px;
            margin-left: 10px;
            transition: background 0.3s ease;
            color: white !important;
        }
        a.btn-secondary:hover {
            background: #0d3b66;
            text-decoration: none;
            color: white !important;
        }

        /* Custom alert for errors */
        .alert-custom-danger {
            background: #ffeded;
            border: 2px solid #ff4d4d;
            color: #a30000;
            border-radius: 12px;
            padding: 20px 25px;
            box-shadow: 0 0 15px #ff4d4d88;
        }
        .alert-custom-danger h4 {
            font-weight: 700;
            margin-bottom: 15px;
        }
        .alert-custom-danger ul {
            padding-left: 20px;
            margin-bottom: 20px;
        }
        .alert-custom-danger li {
            margin-bottom: 8px;
        }

        /* Custom Go Back button */
        .btn-go-back {
            background: #a30000;
            border: none;
            color: white;
            font-weight: 700;
            padding: 8px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px #a3000044;
            transition: background 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-go-back:hover {
            background: #ff4d4d;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4 text-center">Confirm Your Package Booking</h2>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Booking Summary
        </div>
        <div class="card-body">
            <p><strong>User Name:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
            <p><strong>Room Type:</strong> <?= $room_type ?></p>
            <p><strong>Check-in:</strong> <?= htmlspecialchars($checkin) ?></p>
            <p><strong>Check-out:</strong> <?= htmlspecialchars($checkout) ?></p>
            <p><strong>Nights:</strong> <?= $nights ?></p>
            <p><strong>Room Capacity:</strong> <?= $max_people ?> guests</p>
            <p><strong>Room Cost:</strong> ₹<?= number_format($room_total, 2) ?></p>
            <p><strong>Travel Expense:</strong> ₹<?= number_format($travel_expenses, 2) ?></p>
            <p><strong>Activities:</strong> <?= count($selected_activities) > 0 ? implode(", ", $selected_activities) : 'None' ?></p>
            <p><strong>Food Options:</strong> <?= count($selected_food) > 0 ? implode(", ", $selected_food) : 'None' ?></p>
            <hr>
            <h4>Total Cost: ₹<?= number_format($total_cost, 2) ?></h4>
        </div>
    </div>

    <form method="post" action="confirm_booking_package.php">
        <!-- Hidden inputs to resend data -->
        <input type="hidden" name="room_id" value="<?= $room_id ?>">
        <input type="hidden" name="room_type" value="<?= htmlspecialchars($room_type) ?>">
        <input type="hidden" name="nights" value="<?= $nights ?>">
        <input type="hidden" name="room_total" value="<?= $room_total ?>">
        <input type="hidden" name="travel_expenses" value="<?= $travel_expenses ?>">
        <input type="hidden" name="activity_total" value="<?= $activity_total ?>">
        <input type="hidden" name="food_total" value="<?= $food_total ?>">
        <input type="hidden" name="total_cost" value="<?= $total_cost ?>">
        <input type="hidden" name="checkin" value="<?= htmlspecialchars($checkin) ?>">
        <input type="hidden" name="checkout" value="<?= htmlspecialchars($checkout) ?>">
        <?php foreach ($selected_activities as $activity): ?>
            <input type="hidden" name="activities[]" value="<?= htmlspecialchars($activity) ?>">
        <?php endforeach; ?>
        <?php foreach ($selected_food as $food): ?>
            <input type="hidden" name="food[]" value="<?= htmlspecialchars($food) ?>">
        <?php endforeach; ?>

        <div class="mb-3">
            <label for="guests" class="form-label">Number of Guests (max <?= $max_people ?>):</label>
            <input type="number" id="guests" name="guests" min="1" max="<?= $max_people ?>" required class="form-control" value="<?= isset($_POST['guests']) ? intval($_POST['guests']) : 1 ?>">
            <div class="form-text">Please enter the number of guests staying in this package.</div>
        </div>

        <div class="mb-4">
            <label class="form-label me-3">Payment Method:</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="payment_method" id="paymentCash" value="cash" <?= (!isset($_POST['payment_method']) || $_POST['payment_method'] === 'cash') ? 'checked' : '' ?>>
                <label class="form-check-label" for="paymentCash"><i class="bi bi-cash-stack"></i> Cash</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="payment_method" id="paymentOnline" value="online" <?= (isset($_POST['payment_method']) && $_POST['payment_method'] === 'online') ? 'checked' : '' ?>>
                <label class="form-check-label" for="paymentOnline"><i class="bi bi-credit-card"></i> Online Payment</label>
            </div>
        </div>
        
        <div class="tab-content" id="paymentTabsContent">
            <div class="tab-pane fade show active" id="paymentCash" >
            <p class="text-muted">Pay directly at the resort upon check-in.</p>
        </div>

        <div id="onlinePaymentFields" style="display: none;">
            <div class="mb-3">
                <label for="card_name" class="form-label">Card Holder Name</label>
                <input type="text" id="card_name" name="card_name" class="form-control" placeholder="Holder's Name">
            </div>
            <div class="mb-3">
                <label for="card_number" class="form-label">Card Number</label>
                <input type="text" id="card_number" name="card_number" class="form-control" maxlength="16" placeholder="Enter 16 digit card number">
            </div>
            <div class="mb-3">
                <label for="cvv" class="form-label">CVV</label>
                <input type="password" id="cvv" name="cvv" class="form-control" maxlength="3" pattern="\d{3}" placeholder="123">
            </div>
            <div class="mb-3">
                <label for="valid_from" class="form-label">Valid From (Card Date):</label>
                <input type="month" id="valid_from" name="valid_from" class="form-control" value="<?= isset($_POST['valid_from']) ? htmlspecialchars($_POST['valid_from']) : '' ?>">
            </div>
            <div class="mb-3">
                <label for="valid_till" class="form-label">Valid Till (Card Expiry):</label>
                <input type="month" id="valid_till" name="valid_till" class="form-control" value="<?= isset($_POST['valid_till']) ? htmlspecialchars($_POST['valid_till']) : '' ?>">
            </div>
            
        </div>

        <button type="submit" class="btn btn-primary">
            Confirm Booking
        </button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const paymentCash = document.getElementById('paymentCash');
    const paymentOnline = document.getElementById('paymentOnline');
    const onlineFields = document.getElementById('onlinePaymentFields');

    function toggleOnlinePayment() {
      if (paymentOnline.checked) {
        onlineFields.style.display = 'block';
      } else {
        onlineFields.style.display = 'none';
      }
    }

    paymentCash.addEventListener('change', toggleOnlinePayment);
    paymentOnline.addEventListener('change', toggleOnlinePayment);

    // Initialize on load
    toggleOnlinePayment();
  });
</script>

</body>
</html>
