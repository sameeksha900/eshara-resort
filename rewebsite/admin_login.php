<?php
session_start();
include 'db_connect.php'; // Include your database connection

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Protect against SQL injection
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if ($admin['password'] === $password) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_email'] = $admin['email'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Admin not found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin Login - Eshara Resort</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

<style>
  body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .login-box {
    background: linear-gradient(145deg, #0f2027, #203a43, #2c5364);
    padding: 40px 35px;
    border-radius: 18px;
    width: 380px;
    box-shadow:
      0 10px 30px rgba(0, 0, 0, 0.5),
      inset 0 -3px 10px rgba(255, 255, 255, 0.1);
    color: #e1e8f0;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .login-box:hover {
    transform: scale(1.05);
    box-shadow:
      0 15px 40px rgba(0, 0, 0, 0.7),
      inset 0 -3px 15px rgba(255, 255, 255, 0.15);
  }

  .login-box h2 {
    text-align: center;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 25px;
    color: #00d8ff;
    text-shadow: 0 0 10px #00d8ff;
  }

  .form-label {
    font-weight: 600;
    color: #a0c4ff;
  }

  .form-control {
    border-radius: 10px;
    border: none;
    background-color: #1f2f43;
    color: #d1d9e6;
    padding: 12px 16px;
    font-size: 1rem;
    box-shadow: inset 1px 1px 4px rgba(0, 0, 0, 0.7);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  .form-control::placeholder {
    color: #8ba9c9;
  }

  .form-control:focus {
    background-color: #2a436d;
    color: #fff;
    box-shadow:
      0 0 8px #00d8ff,
      inset 1px 1px 6px rgba(0, 0, 0, 0.9);
    outline: none;
  }

  .btn-primary {
    background-color: #00d8ff;
    border: none;
    font-weight: 700;
    padding: 12px 0;
    border-radius: 12px;
    font-size: 1.1rem;
    box-shadow: 0 0 15px #00d8ff80;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  .btn-primary:hover {
    background-color: #00b3e6;
    box-shadow: 0 0 25px #00b3e6cc;
  }

  .form-text {
    color: #7fa6d9;
    font-size: 0.85rem;
    margin-top: 5px;
  }

  .error-message {
    background-color: #ff4c4c;
    padding: 10px 15px;
    border-radius: 12px;
    text-align: center;
    color: white;
    font-weight: 600;
    margin-bottom: 20px;
    box-shadow: 0 0 10px #ff4c4caa;
  }

  .signup-text {
    text-align: center;
    margin-top: 25px;
    font-size: 0.9rem;
    color: #a0c4ff;
  }

  .signup-text a {
    color: #00d8ff;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .signup-text a:hover {
    text-decoration: underline;
    color: #00b3e6;
  }

</style>
</head>
<body>

  <div class="login-box" role="main">
    <h2>Admin Login</h2>

    <?php if ($error): ?>
      <div class="error-message"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="admin_login.php" method="post" autocomplete="off" novalidate>
      <div class="mb-4">
        <label for="email" class="form-label">Email address</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-control"
          placeholder="admin@example.com"
          required
          autocomplete="off"
        />
      </div>

      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-control"
          placeholder="Your secure password"
          required
          autocomplete="new-password"
        />
      </div>

      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="signup-text">
      Don’t have an account? <a href="register.php">Sign up here</a>
    </p>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>