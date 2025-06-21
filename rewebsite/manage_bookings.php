<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Automatically mark bookings as completed if check-out date has passed
$conn->query("UPDATE bookings SET status = 'completed' WHERE check_out < CURDATE() AND status NOT IN ('completed')");

// Handle booking status update (excluding completed bookings)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $booking_id = intval($_POST['booking_id']);
    $status = $_POST['status'];

    // Prevent changing to completed manually
    if ($status !== 'completed') {
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE booking_id = ? AND status != 'completed'");
        $stmt->bind_param("si", $status, $booking_id);
        $stmt->execute();
    }
}

// Fetch all bookings sorted by check_out date descending
$result = $conn->query("SELECT b.booking_id, u.full_name, r.room_type, b.check_in, b.check_out, b.status 
                        FROM bookings b 
                        JOIN users u ON b.user_id = u.user_id
                        JOIN rooms r ON b.room_id = r.room_id
                        ORDER BY b.check_out DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Bookings</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background: linear-gradient(135deg, #2a9d8f, #264653);
            min-height: 100vh;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 1100px;
            margin-top: 60px;
            margin-bottom: 60px;
        }

        h3 {
            font-weight: 700;
            color: #f4a261;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
            margin-bottom: 30px;
            text-align: center;
            letter-spacing: 1.5px;
        }

        .card {
            background-color: #1b2838;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.5);
            padding: 20px;
        }

        table {
            background-color: #264653;
            border-radius: 10px;
            overflow: hidden;
        }

        thead tr {
            background: #f4a261;
            color: #1b2838;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 1px;
        }

        tbody tr {
            background-color: #2a9d8f;
            transition: background-color 0.3s ease;
        }

        tbody tr:nth-child(even) {
            background-color: #21867a;
        }

        tbody tr:hover {
            background-color: #e76f51;
            color: #fff;
            cursor: pointer;
        }

        tbody td, thead th {
            padding: 12px 15px;
            vertical-align: middle;
        }

        select.form-select-sm {
            min-width: 110px;
            font-weight: 600;
            color: #264653;
        }

        button.btn-sm {
            background-color: #f4a261;
            border: none;
            color: #1b2838;
            font-weight: 700;
            transition: background-color 0.3s ease;
        }

        button.btn-sm:hover {
            background-color: #e76f51;
            color: #fff;
        }

        a.btn-secondary {
            display: block;
            width: max-content;
            margin: 30px auto 0;
            background-color: #f4a261;
            border: none;
            color: #1b2838;
            font-weight: 700;
            padding: 10px 30px;
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            transition: background-color 0.3s ease;
            text-align: center;
        }

        a.btn-secondary:hover {
            background-color: #e76f51;
            color: #fff;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            tbody td, thead th {
                padding: 10px 8px;
                font-size: 0.9rem;
            }

            select.form-select-sm {
                min-width: 90px;
                font-size: 0.9rem;
            }

            button.btn-sm {
                padding: 4px 10px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Manage Bookings</h3>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover text-center align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Room Type</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['booking_id']) ?></td>
                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                <td><?= htmlspecialchars($row['room_type']) ?></td>
                                <td><?= htmlspecialchars($row['check_in']) ?></td>
                                <td><?= htmlspecialchars($row['check_out']) ?></td>
                                <td><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
                                <td>
                                    <?php if ($row['status'] == 'completed') { ?>
                                        <span class="text-warning fw-bold">Completed</span>
                                    <?php } else { ?>
                                        <form method="post" class="d-flex justify-content-center align-items-center gap-2">
                                            <input type="hidden" name="booking_id" value="<?= $row['booking_id'] ?>" />
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="confirmed" <?= $row['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                                <option value="cancelled" <?= $row['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                            </select>
                                            <button type="submit" name="update_status" class="btn btn-sm">Update</button>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
