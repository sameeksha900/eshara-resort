<?php
session_start();
include 'db_connect.php';

$success_msg = "";
$error_msg = "";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page with return url
    header("Location: login.php?return_url=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = isset($_POST['room_id']) ? intval($_POST['room_id']) : 0;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    if ($room_id && $rating >= 1 && $rating <= 5 && !empty($comment)) {
        $comment_safe = $conn->real_escape_string($comment);

        // Include user_id in the insert
        $insert_sql = "INSERT INTO reviews (user_id, room_id, rating, comment, created_at) 
                       VALUES ($user_id, $room_id, $rating, '$comment_safe', NOW())";

        if ($conn->query($insert_sql) === TRUE) {
            $success_msg = "Thank you! Your review has been submitted successfully.";
        } else {
            $error_msg = "Oops! Something went wrong. Please try again later.";
        }
    } else {
        $error_msg = "Please fill out all fields correctly.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Leave a Review | Eshara Resort</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #a8dadc 0%, #f7cac9 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        header h1 {
            font-weight: 900;
            font-size: 1.8rem;
            color: #d81e5b;
            margin: 0;
            user-select: none;
        }
        .container {
            max-width: 700px;
            flex-grow: 1;
        }
        nav.navbar {
            background-color: rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        nav.navbar .navbar-brand,
        nav.navbar .nav-link {
            color: #1e3c72 !important;
            font-weight: 600;
        }
        nav.navbar .nav-link:hover {
            color: #764ba2 !important;
            text-decoration: underline;
        }
        .navbar-brand img {
            height: 40px;
            width: auto;
            margin-right: 10px;
            vertical-align: middle;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
        .go-back-btn {
            margin-top: 2rem;
            background: #d81e5b;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .go-back-btn:hover {
            background: #ad1457;
            color: white;
        }
        h3 {
            color: #d81e5b;
            font-weight: 900;
            letter-spacing: 1.2px;
            margin-bottom: 2rem;
            text-align: center;
        }
        form {
            background-color: #fff;
            padding: 30px 30px 35px 30px;
            border-radius: 15px;
            border: 3px solid #d81e5b;
            box-shadow: 0 8px 25px rgba(216, 30, 91, 0.25);
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
        }
        form:hover {
            box-shadow: 0 12px 40px rgba(216, 30, 91, 0.4);
            border-color: #ad1457;
        }
        label.form-label {
            color: #ad1457;
            font-weight: 700;
            letter-spacing: 0.03em;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #d81e5b;
            padding: 10px 14px;
            font-size: 1.05rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #ad1457;
            box-shadow: 0 0 8px rgba(173, 20, 87, 0.6);
            outline: none;
        }
        button.btn-primary {
            background: linear-gradient(90deg, #d81e5b 0%, #f48fb1 100%);
            border: none;
            font-weight: 700;
            font-size: 1.2rem;
            border-radius: 12px;
            padding: 12px;
            transition: background 0.3s ease;
        }
        button.btn-primary:hover {
            background: linear-gradient(90deg, #ad1457 0%, #f06292 100%);
        }
        .mb-3 {
            margin-bottom: 1.5rem !important;
        }
        footer {
            background-color: #d81e5b;
            color: white;
            text-align: center;
            padding: 1rem 1rem;
            margin-top: auto;
            font-weight: 600;
            letter-spacing: 0.05em;
            user-select: none;
        }
        @media (max-width: 576px) {
            form {
                padding: 20px 20px 25px 20px;
            }
            button.btn-primary {
                font-size: 1rem;
                padding: 10px;
            }
            header h1 {
                font-size: 1.4rem;
            }
            header img {
                height: 40px;
                margin-right: 10px;
            }
        }
        /* Success and error message styles */
        .alert-success {
            border-radius: 12px;
            padding: 15px 20px;
            font-weight: 700;
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
            margin-bottom: 20px;
            text-align: center;
        }
        .alert-error {
            border-radius: 12px;
            padding: 15px 20px;
            font-weight: 700;
            background-color: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="images/logo.png" alt="Eshara Resort Logo" />
            Eshara Resort
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
                <li class="nav-item"><a class="nav-link" href="activities.php">Activities</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mb-5" style="margin-top: 100px;">
    <h3>Leave a Review</h3>

    <div class="row justify-content-center">
        <div class="col-md-12">

            <?php if (!empty($success_msg)): ?>
                <div class="alert-success"><?= htmlspecialchars($success_msg) ?></div>
            <?php elseif (!empty($error_msg)): ?>
                <div class="alert-error"><?= htmlspecialchars($error_msg) ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="p-4 shadow-lg rounded bg-light">

                <!-- Select Room -->
                <div class="mb-3">
                    <label for="room_id" class="form-label fw-bold">Select Room:</label>
                    <select name="room_id" id="room_id" class="form-select" required>
                        <option value="">-- Select Room --</option>
                        <?php
                        $rooms_query = "SELECT room_id, room_type FROM rooms";
                        $rooms_result = $conn->query($rooms_query);
                        while ($room = $rooms_result->fetch_assoc()) {
                            $selected = (isset($_POST['room_id']) && $_POST['room_id'] == $room['room_id']) ? "selected" : "";
                            echo "<option value='{$room['room_id']}' $selected>{$room['room_type']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Rating -->
                <div class="mb-3">
                    <label for="rating" class="form-label fw-bold">Rating (1-5):</label>
                    <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" required
                           value="<?= isset($_POST['rating']) ? htmlspecialchars($_POST['rating']) : '' ?>" />
                </div>

                <!-- Review Comment -->
                <div class="mb-3">
                    <label for="comment" class="form-label fw-bold">Your Review:</label>
                    <textarea name="comment" id="comment" class="form-control" rows="4" required><?= isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : '' ?></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Submit Review</button>
            </form>
        </div>
    </div>

    <!-- Go Back button -->
    <button class="go-back-btn" onclick="history.back()">← Go Back</button>
</div>

<footer>
    &copy; <?= date('Y'); ?> Eshara Resort — All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
