<?php
session_start();
include('db_connect.php');

$redirect = $_GET['redirect'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $redirect = $_POST['redirect'] ?? '';

    $sql = "SELECT user_id, full_name, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['full_name'];
            $login_success = true;
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No account found with this email.";
        header("Location: login.php");
        exit();
    }

    if (isset($_SESSION['redirect_after_login'])) {
        $redirect = $_SESSION['redirect_after_login'];
        unset($_SESSION['redirect_after_login']);
        header("Location: $redirect");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login | Eshara Resort</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & SweetAlert -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Quicksand', sans-serif;
            background: linear-gradient(135deg, #ffa17f, #00223e);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #ffffff;
            color: #333;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 12px 28px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
        }

        .login-card h3 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 700;
            color: #00223e;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 15px;
            background-color: #fff8dc;
        }

        .form-control:focus {
            box-shadow: 0 0 0 2px #ffa17f;
            border-color: #ffa17f;
        }

        .btn-login {
            width: 100%;
            background-color: #ffa17f;
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            padding: 12px;
            border: none;
            transition: all 0.3s ease-in-out;
        }

        .btn-login:hover {
            background-color: #f07d54;
        }

        .text-link {
            color: #ffa17f;
            text-decoration: none;
        }

        .text-link:hover {
            text-decoration: underline;
        }

        .register-msg {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #333;
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3>User Login</h3>

    <?php 
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger text-center'>".$_SESSION['error']."</div>"; 
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success text-center'>".$_SESSION['success']."</div>"; 
            unset($_SESSION['success']);
        }
    ?>

    <form action="login.php" method="post" autocomplete="off">
        <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" autocomplete="off" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" autocomplete="new-password" required>
        </div>

        <button type="submit" class="btn btn-login mt-2">Login</button>
    </form>

    <div class="register-msg">
        Don't have an account? <a href="register.php" class="text-link">Register Here</a>
    </div>
</div>

<?php if (isset($login_success) && $login_success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Login Successful!',
    text: 'Welcome to Eshara Resort.',
    confirmButtonColor: '#ffa17f',
    confirmButtonText: 'Continue'
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = "<?= !empty($redirect) ? htmlspecialchars($redirect) : 'index.php' ?>";
    }
});
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
