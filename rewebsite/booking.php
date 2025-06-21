<?php
session_start();
include 'db_connect.php';

// Get booking details from GET parameters
$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';

if (!$room_id || !$check_in || !$check_out) {
    echo "<div class='alert alert-danger text-center mt-5'>Missing booking details.</div>";
    exit();
}

// If not logged in, redirect to login and pass booking details
if (!isset($_SESSION['user_id'])) {
    $queryString = http_build_query($_GET);
    header("Location: login.php?redirect=booking.php&$queryString");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$user_stmt = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

/// Fetch room details first
$room_id = $_GET['room_id'] ?? 0;
$room_query = "SELECT * FROM rooms WHERE room_id = $room_id";
$room_result = $conn->query($room_query);

// Calculate number of days and total price
$days = (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);
if ($days <= 0) {
    echo "<div class='alert alert-danger text-center mt-5'>Invalid date selection.</div>";
    exit();
}
if ($room_result && $room_result->num_rows > 0) {
    $room = $room_result->fetch_assoc();
    $actual_price = floatval($room['price'])* $days; 

    // Fetch active offer (site-wide)
    $today = date('Y-m-d');
    $offer_query = "SELECT * FROM offers WHERE valid_from <= '$today' AND valid_until >= '$today' LIMIT 1";
    $offer_result = $conn->query($offer_query);

    $discount_percent = 0;
    if ($offer_result && $offer_result->num_rows > 0) {
        $offer = $offer_result->fetch_assoc();
        $discount_percent = floatval($offer['discount_percent']);
    }

    $discount_amount = ($actual_price * $discount_percent) / 100;
    $total_price = $actual_price - $discount_amount;

} else {
    echo "Room not found.";
}

$guests = isset($_POST['guests']) ? intval($_POST['guests']) : 1;

$room_stmt = $conn->prepare("SELECT max_people FROM rooms WHERE room_id = ?");
$room_stmt->bind_param("i", $room_id);
$room_stmt->execute();
$room_result = $room_stmt->get_result();

if ($room_result->num_rows > 0) {
    $room_details = $room_result->fetch_assoc(); // 👈 Use a different variable
    $max_people = intval($room_details['max_people']);
    
    if ($guests > $max_people) {
        echo "<div class='alert alert-danger text-center mt-4'>
            Number of guests exceeds the allowed maximum of $max_people for this room.
            <br><a href='javascript:history.back()' class='btn btn-sm btn-primary mt-2'>Go Back</a>
        </div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Room not found.</div>";
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Booking Checkout</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
<style>
  body {
    background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .checkout-card {
    background: #ffffffee;
    border-radius: 20px;
    padding: 40px 35px 35px;
    box-shadow:
      0 10px 15px rgba(102, 166, 255, 0.3),
      0 4px 6px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px;
    transition: transform 0.3s ease;
  }
  .checkout-card:hover {
    transform: translateY(-5px);
    box-shadow:
      0 15px 25px rgba(102, 166, 255, 0.5),
      0 8px 12px rgba(0, 0, 0, 0.15);
  }
  h4 {
    font-weight: 600;
    color: #0d47a1;
    margin-bottom: 25px;
    letter-spacing: 1px;
  }
  label {
    font-weight: 600;
    color: #333;
  }
  .form-control[readonly] {
    background-color: #e9f0ff;
    border: 1.5px solid #66a6ff;
    font-weight: 600;
    color: #0d47a1;
  }
  .form-control {
    border-radius: 8px;
    border: 1.5px solid #cbd5e1;
    padding: 12px 15px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
  }
  .form-control:focus {
    border-color: #0d47a1;
    box-shadow: 0 0 6px #66a6ffcc;
    outline: none;
  }
  small.form-text {
    color: #444;
    font-weight: 500;
  }
  .nav-tabs {
    border-bottom: 3px solid #66a6ff;
    margin-bottom: 25px;
  }
  .nav-tabs .nav-link {
    font-weight: 600;
    color: #66a6ff;
    border: none;
    border-radius: 50px;
    padding: 10px 25px;
    transition: all 0.3s ease;
  }
  .nav-tabs .nav-link:hover {
    background-color: #d7e7ff;
    color: #0d47a1;
  }
  .nav-tabs .nav-link.active {
    background-color: #0d47a1;
    color: white !important;
    box-shadow: 0 4px 12px #0d47a1aa;
  }
  .tab-content {
    padding-top: 10px;
    color: #444;
    font-size: 0.95rem;
  }
  .btn-primary {
    background: #0d47a1;
    border: none;
    font-weight: 700;
    padding: 12px 28px;
    border-radius: 50px;
    box-shadow: 0 6px 12px #0d47a1cc;
    transition: all 0.3s ease;
  }
  .btn-primary:hover {
    background: #1349c4;
    box-shadow: 0 8px 18px #1349c4cc;
  }
  .btn-danger {
    border-radius: 50px;
    font-weight: 600;
    padding: 12px 28px;
    transition: all 0.3s ease;
  }
  .btn-danger:hover {
    background-color: #b33a3a;
    box-shadow: 0 6px 12px #b33a3acc;
  }
  .alert {
    border-radius: 12px;
    font-weight: 600;
  }
  /* Responsive */
  @media (max-width: 576px) {
    .checkout-card {
      padding: 30px 20px 25px;
    }
    .nav-tabs .nav-link {
      padding: 8px 16px;
      font-size: 0.9rem;
    }
  }
</style>
</head>
<body>
  <div class="checkout-card shadow">
    <h4>Booking Checkout</h4>
    <form method="POST" action="confirm_booking.php" novalidate>
      <input type="hidden" name="room_id" value="<?= $room_id ?>">
      <input type="hidden" name="check_in" value="<?= htmlspecialchars($check_in) ?>">
      <input type="hidden" name="check_out" value="<?= htmlspecialchars($check_out) ?>">
      <input type="hidden" name="total_price" value="<?= $total_price ?>">

      <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" readonly>
        <input type="hidden" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>">
      </div>

      <div class="form-row">
        <div class="form-group col-md-4">
          <label>Room Price</label>
          <input type="text" class="form-control" value="<?= number_format($room['price'], 2) ?>" readonly>
        </div>
        <div class="form-group col-md-4">
          <label>No of Days</label>
          <input type="text" class="form-control" value="<?= $days ?>" readonly>
        </div>
        <div class="form-group">
        <label>Room Price per Day (₹)</label>
        <input type="text" class="form-control" value="₹<?= number_format($room['price'], 2) ?>" readonly>
      </div>

      <hr>

      <div class="form-group">
        <label>Actual Price (₹)</label>
        <input type="text" class="form-control" value="₹<?= number_format($actual_price, 2) ?>" readonly>
      </div>

      <div class="form-group">
        <label>Discount (<?= $discount_percent ?>%) (₹)</label>
        <input type="text" class="form-control" value="₹<?= number_format($discount_amount, 2) ?>" readonly>
      </div>

      <div class="form-group font-weight-bold">
        <label>Total Price to Pay (₹)</label>
        <input type="text" class="form-control" value="₹<?= number_format($total_price, 2) ?>" readonly>
      </div>
      
      <div class="form-group">
        <label>Number of Guests</label>
        <input type="number" class="form-control" name="guests" id="guests" min="1" max="<?= $room_details['max_people'] ?>" value="1" required>
            <small class="form-text text-muted">Max allowed guests: <?= $room_details['max_people'] ?></small>
      </div>
      
      <div class="mt-5">
  <h5 class="mt-4 mb-3" style="color:#0d47a1;">Select Payment Method</h5>
  <ul class="nav nav-tabs" id="paymentTabs" role="tablist" style="border-bottom: 3px solid #66a6ff;">
    <li class="nav-item">
      <a class="nav-link active" id="cash-tab" data-toggle="tab" href="#cashTab" role="tab" aria-controls="cashTab" aria-selected="true">Cash Payment</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="online-tab" data-toggle="tab" href="#onlineTab" role="tab" aria-controls="onlineTab" value="online" aria-selected="false">Pay Online</a>
    </li>
  </ul>

  <div class="tab-content" id="paymentTabsContent">
      <div class="tab-content" id="paymentTabsContent">
        <div class="tab-pane fade show active" id="cashTab" role="tabpanel" aria-labelledby="cash-tab">
          <p class="text-muted">Pay directly at the resort upon check-in.</p>
        </div>
        <div class="tab-pane fade" id="onlineTab" role="tabpanel" aria-labelledby="online-tab" value="online">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Card Holder's Name</label>
              <input type="text" class="form-control online-input" name="card_holder" placeholder="Card Holder's Name" autocomplete="cc-name" />
            </div>
            <div class="col-md-6 mb-3">
              <label>Card Number</label>
              <input type="text" class="form-control online-input" name="card_number" placeholder="Enter the 16 digit card number" minlength="16" maxlength="16" autocomplete="cc-number" />
            </div>
            <div class="col-md-4 mb-3">
              <label>CVV</label>
              <input type="password" class="form-control online-input" name="cvv" placeholder="123" minlength="3" maxlength="3" pattern="\d{3}" autocomplete="cc-csc" />
            </div>
            <div class="col-md-4 mb-3">
              <label>Valid From</label>
              <input type="month" class="form-control online-input" name="valid_from" autocomplete="cc-exp" />
            </div>
            <div class="col-md-4 mb-3">
              <label>Valid Till</label>
              <input type="month" class="form-control online-input" name="valid_till" autocomplete="cc-exp" />
            </div>
          </div>
        </div>
      </div>

      <!-- Cash Tab -->
    <div class="tab-pane fade show active" id="cashTab" role="tabpanel" aria-labelledby="cash-tab">
      <input type="radio" name="payment_method" id="payment_method" value="cash" checked hidden>
      <p class="mt-3">You can pay at the resort during check-in.</p>
    </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="rooms.php" class="btn btn-danger btn-lg px-4">Cancel</a>
        <button type="submit" class="btn btn-primary btn-lg px-5">Confirm your Booking</button>
      </div>
    </form>
  </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const cashTab = document.getElementById("cash-tab");
    const onlineTab = document.getElementById("online-tab");
    const paymentInput = document.getElementById("payment_method");
    const onlineInputs = document.querySelectorAll(".online-input");

    let alertPlaceholder = document.getElementById("formAlert");
    if (!alertPlaceholder) {
        alertPlaceholder = document.createElement("div");
        alertPlaceholder.id = "formAlert";
        form.insertBefore(alertPlaceholder, form.firstChild);
    }

    function setOnlineFieldsRequirement(isRequired) {
        onlineInputs.forEach(input => {
            input.disabled = !isRequired;
            if (!isRequired) input.value = "";
        });
    }

    function showAlert(message) {
        alertPlaceholder.innerHTML = `
          <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <strong>Error:</strong><br> ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>`;
    }

    function clearAlert() {
        alertPlaceholder.innerHTML = "";
    }

    cashTab.addEventListener("click", () => {
        paymentInput.value = "cash";
        setOnlineFieldsRequirement(false);
        clearAlert();
    });

    onlineTab.addEventListener("click", () => {
        paymentInput.value = "online";
        setOnlineFieldsRequirement(true);
        clearAlert();
    });

    // Default: cash tab active
    setOnlineFieldsRequirement(false);

    form.addEventListener("submit", function (e) {
        clearAlert();
        let errors = [];

        const guestsInput = document.getElementById("guests");
        const maxGuests = parseInt(guestsInput.getAttribute("max"));
        const guestValue = parseInt(guestsInput.value);

        if (guestValue > maxGuests) {
            errors.push(`Number of guests exceeds the maximum allowed (${maxGuests}).`);
        }

        if (paymentInput.value === "online") {
            const cardHolder = form.card_holder.value.trim();
            const cardNumber = form.card_number.value.trim();
            const cvv = form.cvv.value.trim();
            const validFrom = form.valid_from.value.trim();
            const validTill = form.valid_till.value.trim();

            if (!cardHolder) errors.push("Card Holder's Name is required.");
            if (!/^\d{16}$/.test(cardNumber)) errors.push("Card Number must be 16 digits.");
            if (!/^\d{3}$/.test(cvv)) errors.push("CVV must be exactly 3 digits.");
            if (!validFrom) errors.push("Valid From date is required.");
            if (!validTill) errors.push("Valid Till date is required.");
            if (validFrom && validTill && validFrom > validTill)
                errors.push("Valid From date cannot be after Valid Till date.");

            const now = new Date();
            const validTillDate = new Date(validTill + "-01");
            if (validTillDate < now)
                errors.push("Card is expired. Please use a valid card.");
        }

        if (errors.length > 0) {
            e.preventDefault();
            showAlert(errors.join("<br>"));
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
  });
</script>

</body>
</html>
