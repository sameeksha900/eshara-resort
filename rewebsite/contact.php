<?php
session_start();
$host = "localhost";  // Change if needed
$user = "root";  // Change if using a different user
$pass = "";  // Change if using a password
$dbname = "resort_booking";  // Change this to your database name

// Connect to the database
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    // Insert data into the database
    $sql = "INSERT INTO contact_messages (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Your message has been sent successfully!";
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eshara Resort | Contact</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">

    <style>
        /* Background Styling */
        body {
            background-color: #001F3F; /* Deep Royal Blue */
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }

        /* Navbar Styling */
        .navbar {
            background: rgba(0, 31, 63, 0.95);
            border-bottom: 3px solid #fff;
        }

        .navbar-brand {
            font-weight: bold;
            color: #fff !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: #fff !important;
            font-weight: 600;
            transition: 0.3s;
        }

        .nav-link:hover {
            color: #ccc !important;
            transform: scale(1.1);
        }

        /* Contact Section */
        .contact-container {
            background: rgba(255, 255, 255, 0.1); /* Light Transparent White */
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0px 4px 15px rgba(255, 255, 255, 0.2);
            border: 2px solid #fff;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid #fff;
            color: #fff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.3);
            border-color: #ccc;
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.4);
        }

        .btn-primary {
            background: #fff;
            color: #001F3F;
            border: none;
            transition: all 0.3s ease;
            font-weight: bold;
            padding: 12px;
        }

        .btn-primary:hover {
            background: #ccc;
            transform: scale(1.05);
            color: #000;
        }

        /* Footer */
        .footer {
            background: rgba(0, 31, 63, 0.9);
            border-top: 3px solid #fff;
            color: #fff;
            padding: 15px 0;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">Eshara Resort</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
                    <li class="nav-item"><a class="nav-link" href="activities.php">Activities</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contact Section -->
    <section class="container py-5 mt-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Contact Us</h2>
            <p class="text-light">Have any questions? Reach out to us!</p>
        </div>

        <!-- Display Success/Error Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="contact-container">
                    <form method="POST" action="contact.php">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Name</label>
                            <input type="text" class="form-control shadow-sm" id="name" name="name" required placeholder="John Doe">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control shadow-sm" id="email" name="email" required placeholder="example@mail.com">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label fw-bold">Message</label>
                            <textarea class="form-control shadow-sm" id="message" name="message" rows="4" required placeholder="Your message here..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 shadow">Send Message</button>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="contact-container">
                    <h5 class="fw-bold">Our Address</h5>
                    <p>123 Eshara Resort ,Baga Beach</p>

                    <h5 class="fw-bold">Phone</h5>
                    <p>+1 234 567 890</p>

                    <h5 class="fw-bold">Email</h5>
                    <p>info@luxuryresort.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer text-center">
        <p class="mb-0">&copy; 2025 Luxury Resort. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Go Back Button -->
    <div class="text-center my-4">
        <button onclick="history.back()" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
            ⬅️ Go Back
        </button>
    </div>
</body>
</html>
