<?php
session_start();
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];

    $sql = "INSERT INTO users (full_name, email, password, phone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $full_name, $email, $password, $phone);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful. You may now login!";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration | Eshara Resort</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Quicksand', sans-serif;
            background: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px 35px;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .register-header {
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
            background-color: #fff8e1;
        }

        .form-control:focus {
            box-shadow: 0 0 0 2px #ffb34788;
            border-color: #ffb347;
        }

        .btn-theme {
            background-color: #00a8cc;
            color: white;
            font-weight: 600;
            border-radius: 12px;
            padding: 12px;
            transition: all 0.3s ease-in-out;
            width: 100%;
        }

        .btn-theme:hover {
            background-color: #007c99;
        }

        .alert {
            border-radius: 12px;
            font-size: 14px;
        }

        .login-msg {
            margin-top: 18px;
            text-align: center;
            font-size: 14px;
        }

        .text-link {
            color: #00a8cc;
            text-decoration: none;
        }

        .text-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="register-header">Create Your Account</div>

    <?php 
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger text-center'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success text-center'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
    ?>

    <form action="register.php" method="post" autocomplete="off">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" required placeholder="Enter your full name" autocomplete="off">
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required placeholder="Enter your email" autocomplete="off">
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="Create a password" autocomplete="new-password">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" required
                   placeholder="Enter your 10-digit phone number"
                   minlength="10" maxlength="10"
                   pattern="\d{10}" title="Please enter a 10-digit phone number"
                   autocomplete="off">
        </div>

        <button type="submit" class="btn btn-theme">Register</button>
    </form>

    <div class="login-msg">
        Already have an account? <a href="login.php" class="text-link">Login Here</a>
    </div>
</div>

</body>
</html>
