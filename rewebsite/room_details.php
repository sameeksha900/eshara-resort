<?php
session_start();
include 'db_connect.php';

if (!isset($_GET['room_id'])) {
    die('Room ID not provided.');
}

$room_id = intval($_GET['room_id']);
$room = $conn->query("SELECT * FROM rooms WHERE room_id = $room_id")->fetch_assoc();

$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';
$days = 0;
$total_price = 0;
$error = '';

if (!empty($checkin) && !empty($checkout)) {
    $start = DateTime::createFromFormat('Y-m-d', $checkin);
    $end = DateTime::createFromFormat('Y-m-d', $checkout);
    $today = new DateTime();

    if (!$start || !$end) {
        $error = "Invalid date format.";
    } elseif ($start >= $end) {
        $error = "Check-out date must be after check-in date.";
    } elseif ($start < $today) {
        $error = "Check-in date cannot be in the past.";
    } else {
        $interval = $start->diff($end);
        $days = $interval->days;
        $total_price = $room['price'] * $days;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($room['room_type']) ?> - Room Details | Eshara Resort</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: linear-gradient(to right, #4CAF50, #FFA500);
            background-size: cover;
            padding: 40px 0;
        }

        .resort-header {
            display: flex;
            justify-content: center;
            background: linear-gradient(to right, #4CAF50, #FFA500);
            padding: 15px 0;
            margin-bottom: 30px;
        }

        .resort-banner {
            display: flex;
            align-items: center;
            background: white;
            padding: 10px 25px;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        .resort-banner img.logo-img {
            height: 50px;
            margin-right: 15px;
        }

        .resort-title {
            font-size: 24px;
            color: #2e7d32;
            font-weight: 600;
            margin: 0;
        }

        .btn {
            background-color: #27ae60;
            color: #fff;
            border: none;
            padding: 12px 26px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #1e8449;
        }

        .btn-go-back {
            background-color: #e74c3c;
            color: white;
            padding: 10px 22px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .btn-go-back:hover {
            background-color: #c0392b;
        }

        .go-back-wrapper {
            text-align: center;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .container.d-flex {
                flex-direction: column !important;
            }
            .image-side, .booking-side {
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>

<div class="resort-header">
    <div class="resort-banner">
        <img src="images/logo.png" alt="Eshara Resort Logo" class="logo-img">
        <h1 class="resort-title">Eshara Resort</h1>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="container mt-4 p-4 bg-white rounded shadow text-center" style="max-width: 600px;">
        <h3 style="color: red;">Invalid Date Selection</h3>
        <p><?= htmlspecialchars($error) ?></p>
        <div class="go-back-wrapper">
            <a href="room_details.php?room_id=<?= $room_id ?>" class="btn-go-back">Go Back</a>
        </div>
    </div>

<?php elseif (empty($checkin) || empty($checkout)): ?>
    <!-- Show date selection form -->
    <div class="container d-flex justify-content-center align-items-start gap-4 mt-5" style="background: #fefefe; padding: 30px; border-radius: 16px; flex-wrap: wrap;">
        <div class="image-side" style="flex: 1; max-width: 45%;">
            <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    <?php
                    $images = explode(",", $room['image']);
                    foreach ($images as $index => $img):
                    ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="uploads/<?= trim($img) ?>" class="d-block w-100" alt="Room Image <?= $index + 1 ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>

        <div class="booking-side" style="flex: 1; max-width: 50%;">
            <h2><?= htmlspecialchars($room['room_type']) ?></h2>
            <p><strong>Price:</strong> ₹<?= htmlspecialchars($room['price']) ?> / night</p>
            <p><strong>Description:</strong> <?= htmlspecialchars($room['description']) ?></p>
            <p><strong>Amenities:</strong> <?= htmlspecialchars($room['amenities']) ?></p>
            <p><strong>Guests Allowed:</strong> <?= htmlspecialchars($room['max_people']) ?></p>

            <form method="get" action="">
                <input type="hidden" name="room_id" value="<?= $room_id ?>">
                <div class="input-field mb-3">
                    <label>Check In:</label>
                    <input type="date" name="checkin" required>
                </div>
                <div class="input-field mb-3">
                    <label>Check Out:</label>
                    <input type="date" name="checkout" required>
                </div>
                <button type="submit" class="btn">Check Now</button>
            </form>
        </div>
    </div>

<?php else: ?>
    <?php
        // Check booking conflicts
        $stmt = $conn->prepare("
            SELECT * FROM bookings 
            WHERE room_id = ? 
            AND status = 'confirmed'
            AND (check_in < ? AND check_out > ?)
        ");
        $stmt->bind_param("iss", $room_id, $checkout, $checkin);
        $stmt->execute();
        $result = $stmt->get_result();
    ?>

    <?php if ($result->num_rows > 0): ?>
        <div class="container mt-5 p-5 bg-white rounded shadow text-center" style="max-width: 600px;">
            <h2 style="color: red;">This room is already booked</h2>
            <p>for the selected dates:</p>
            <p><strong><?= date('d-m-Y', strtotime($checkin)) ?> to <?= date('d-m-Y', strtotime($checkout)) ?></strong></p>
            <div class="go-back-wrapper">
                <a href="rooms.php" class="btn-go-back">Go Back</a>
            </div>
        </div>
    <?php else: ?>
        <div class="container d-flex justify-content-center align-items-start gap-4 mt-5" style="background: #fefefe; padding: 30px; border-radius: 16px; flex-wrap: wrap;">
            <div class="image-side" style="flex: 1; max-width: 45%;">
                <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner rounded">
                        <?php
                        $images = explode(",", $room['image']);
                        foreach ($images as $index => $img):
                        ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="uploads/<?= trim($img) ?>" class="d-block w-100" alt="Room Image <?= $index + 1 ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>

            <div class="booking-side" style="flex: 1; max-width: 50%;">
                <h2><?= htmlspecialchars($room['room_type']) ?></h2>
                <p><strong>Price:</strong> ₹<?= htmlspecialchars($room['price']) ?> / night</p>
                <p><strong>Description:</strong> <?= htmlspecialchars($room['description']) ?></p>
                <p><strong>Amenities:</strong> <?= htmlspecialchars($room['amenities']) ?></p>
                <p><strong>Guests Allowed:</strong> <?= htmlspecialchars($room['max_people']) ?></p>
                <p><strong>Check-In:</strong> <?= htmlspecialchars($checkin) ?> | <strong>Check-Out:</strong> <?= htmlspecialchars($checkout) ?></p>
                <p><strong>Number of Days:</strong> <?= $days ?></p>
                <p><strong>Total Price:</strong> ₹<?= $total_price ?></p>

                <form action="booking.php" method="GET">
                    <input type="hidden" name="room_id" value="<?= $room_id ?>">
                    <input type="hidden" name="check_in" value="<?= $checkin ?>">
                    <input type="hidden" name="check_out" value="<?= $checkout ?>">
                    <button type="submit" class="btn btn-success">Book Now</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
