<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['admin_success'])) {
    echo '<div class="alert alert-success text-center">' . $_SESSION['admin_success'] . '</div>';
    unset($_SESSION['admin_success']); // Remove it after displaying once
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch Summary Data
$users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'];
$revenue = $conn->query("SELECT SUM(total_price) AS total FROM bookings WHERE status='confirmed'")->fetch_assoc()['total'] ?? 0;

// Fetch New Bookings (Unconfirmed)
$new_bookings = $conn->query("SELECT * FROM bookings WHERE status='pending' ORDER BY created_at DESC LIMIT 5");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        /* Background and layout */
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8; /* Soft light blue/gray background */
        }

        .d-flex {
            min-height: 100vh;
        }

        /* Sidebar styling */
        nav.sidebar {
            width: 260px;
            background: #2c3e50;
            color: #ecf0f1;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 2rem;
            box-shadow: 2px 0 8px rgb(0 0 0 / 0.15);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        nav.sidebar h3 {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            letter-spacing: 1px;
            user-select: none;
        }

        nav.sidebar ul.nav {
            width: 100%;
            padding-left: 0;
        }

        nav.sidebar ul.nav li.nav-item {
            width: 100%;
        }

        nav.sidebar ul.nav li.nav-item a.nav-link {
            color: #bdc3c7;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            font-size: 1.05rem;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            user-select: none;
        }

        nav.sidebar ul.nav li.nav-item a.nav-link:hover,
        nav.sidebar ul.nav li.nav-item a.nav-link.active {
            background: #34495e;
            color: #ffffff;
            border-left: 4px solid #1abc9c;
            text-decoration: none;
        }

        nav.sidebar ul.nav li.nav-item a.nav-link svg,
        nav.sidebar ul.nav li.nav-item a.nav-link img {
            margin-right: 10px;
        }

        /* Main content */
        .container.p-4 {
            margin-left: 260px;
            padding-top: 2.5rem;
            padding-bottom: 2.5rem;
        }

        h2 {
            color: #34495e;
            font-weight: 700;
            margin-bottom: 2rem;
            user-select: none;
        }

        /* Cards */
        .card {
            border-radius: 12px;
            box-shadow: 0 8px 20px rgb(0 0 0 / 0.1);
            transition: transform 0.3s ease;
            cursor: default;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgb(0 0 0 / 0.15);
        }

        .card h4 {
            font-weight: 600;
            margin-bottom: 0.75rem;
            user-select: none;
        }

        .card h2 {
            font-weight: 700;
            font-size: 2.7rem;
            user-select: text;
        }

        .bg-primary {
            background: linear-gradient(135deg, #1abc9c, #16a085);
            color: #fff !important;
        }

        .bg-success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: #fff !important;
        }

        .bg-warning {
            background: linear-gradient(135deg, #f39c12, #f1c40f);
            color: #1e212d !important;
        }

        /* Notification panel */
        .mt-4 > h4 {
            user-select: none;
            color: #34495e;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .card.p-3 {
            border-radius: 12px;
            box-shadow: 0 6px 18px rgb(0 0 0 / 0.08);
            background-color: #ffffff;
        }
        .go-back-wrapper {
            text-align: center;
            margin-top: 20px; /* Optional spacing from top */
        }
        .btn-go-back {
            background-color: #ff5e57; /* Example color */
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }
        .btn-go-back:hover {
            background-color: #e04841; /* Darker shade on hover */
        }

        ul.list-group {
            max-height: 250px;
            overflow-y: auto;
            user-select: none;
        }

        ul.list-group li.list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            background-color: #f8f9fa;
            font-weight: 500;
            font-size: 1rem;
            color: #34495e;
            transition: background-color 0.2s ease;
            cursor: default;
        }

        ul.list-group li.list-group-item:hover {
            background-color: #e0f7fa;
        }

        ul.list-group li.list-group-item strong {
            font-weight: 600;
        }

        ul.list-group li.list-group-item a.btn {
            flex-shrink: 0;
        }

        /* Logout button */
        a.btn.btn-danger {
            margin-top: 2rem;
            width: 100%;
            font-weight: 600;
            border-radius: 12px;
            padding: 10px 0;
            box-shadow: 0 4px 15px rgb(231 76 60 / 0.4);
            transition: background-color 0.3s ease;
            user-select: none;
        }

        a.btn.btn-danger:hover {
            background-color: #c0392b;
            box-shadow: 0 6px 25px rgb(192 57 43 / 0.6);
            text-decoration: none;
        }

        /* Scrollbar styling for new bookings */
        ul.list-group::-webkit-scrollbar {
            width: 8px;
        }

        ul.list-group::-webkit-scrollbar-track {
            background: #f0f4f8;
            border-radius: 12px;
        }

        ul.list-group::-webkit-scrollbar-thumb {
            background: #1abc9c;
            border-radius: 12px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            nav.sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 1rem 0.5rem;
            }

            .container.p-4 {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar bg-dark text-light p-3">
            <h3 class="text-center">Admin Panel</h3>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="manage_bookings.php" class="nav-link text-light">📅 Manage Bookings</a></li>
                <li class="nav-item"><a href="manage_rooms.php" class="nav-link text-light">🛏️ Manage Rooms</a></li>
                <li class="nav-item"><a href="manage_reviews.php" class="nav-link text-light">⭐ Manage Reviews</a></li>
                <li class="nav-item"><a href="manage_offers.php" class="nav-link text-light">🎁 Manage Offers</a></li>
                <li class="nav-item"><a href="users.php" class="nav-link text-light">👤⚙️ Manage Users</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="container p-4">
            <h2 class="mb-4">Welcome, Admin</h2>

            <!-- Dashboard Cards -->
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card bg-primary p-4 text-center">
                        <h4>Total Users</h4>
                        <h2><?= $users ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success p-4 text-center">
                        <h4>Total Bookings</h4>
                        <h2><?= $bookings ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning p-4 text-center">
                        <h4>Total Revenue</h4>
                        <h2>₹<?= number_format($revenue, 2) ?></h2>
                    </div>
                </div>
            </div>

            <!-- Notifications Panel -->
            <div class="mt-5">
                <h4>🔔 New Booking Notifications</h4>
                <div class="card p-3">
                    <?php if ($new_bookings->num_rows > 0): ?>
                        <ul class="list-group">
                            <?php while ($booking = $new_bookings->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <div>
                                        <strong>Booking ID:</strong> <?= $booking['booking_id'] ?> | 
                                        <strong>Check-in:</strong> <?= date('M d, Y', strtotime($booking['check_in'])) ?> | 
                                        <strong>Guests:</strong> <?= $booking['guests'] ?>
                                    </div>
                                    <a href="manage_bookings.php" class="btn btn-sm btn-primary">View</a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p class="mb-0">No new bookings.</p>
                    <?php endif; ?>
                </div>
            </div>

            <a href="admin_logout.php" class="btn btn-danger">Logout</a>
            <div class="go-back-wrapper">
                <a href="index.php" class="btn btn-go-back">Go Back</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
