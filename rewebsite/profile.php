<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=profile.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $gender = $_POST['gender'] ?? '';

    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, gender = ? WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("sssi", $full_name, $phone, $gender, $user_id);
        if ($stmt->execute()) {
            $msg = "Profile updated successfully!";
        } else {
            $msg = "Failed to update profile.";
        }
    } else {
        $msg = "Failed to prepare statement.";
    }
}

// Fetch user details
$stmt = $conn->prepare("SELECT full_name, email, phone, gender FROM users WHERE user_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
} else {
    die("Failed to prepare SELECT statement: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Background gradient */
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        /* Form container styling */
        .form-container {
            max-width: 700px;
            width: 100%;
            background: #f7f9fc;
            padding: 40px 50px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            transition: box-shadow 0.3s ease;
        }
        .form-container:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
        }

        /* Heading styling */
        h2 {
            color: #5a2a83;
            font-weight: 700;
            text-align: center;
            margin-bottom: 35px;
            border-bottom: 4px solid #764ba2;
            display: inline-block;
            padding-bottom: 8px;
            letter-spacing: 1px;
        }

        /* Labels */
        label {
            font-weight: 600;
            color: #5a2a83;
        }

        /* Inputs */
        .form-control {
            border-radius: 12px;
            border: 2px solid #b4a7d6;
            padding: 10px 15px;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 8px rgba(118, 75, 162, 0.5);
            outline: none;
        }

        /* Select box */
        select.form-control {
            appearance: none;
            background-image:
                linear-gradient(45deg, transparent 50%, #764ba2 50%),
                linear-gradient(135deg, #764ba2 50%, transparent 50%),
                linear-gradient(to right, #b4a7d6, #b4a7d6);
            background-position:
                calc(100% - 20px) calc(1em + 2px),
                calc(100% - 15px) calc(1em + 2px),
                calc(100% - 25px) 0.5em;
            background-size: 5px 5px, 5px 5px, 1px 1.5em;
            background-repeat: no-repeat;
        }

        /* Buttons */
        .btn-primary {
            background-color: #764ba2;
            border: none;
            font-weight: 700;
            padding: 12px 30px;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(118, 75, 162, 0.5);
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #5a2a83;
        }

        .btn-outline-secondary {
            font-weight: 600;
            border-radius: 30px;
            padding: 10px 30px;
            border-color: #764ba2;
            color: #764ba2;
            transition: all 0.3s ease;
        }
        .btn-outline-secondary:hover {
            background-color: #764ba2;
            color: #fff;
        }

        /* Alert message */
        .alert-info {
            background-color: #e2daf8;
            border-left: 5px solid #764ba2;
            color: #3e2468;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="form-container shadow-sm">
    <h2>My Profile</h2>
    <?php if ($msg): ?>
        <div class="alert alert-info mt-3"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mt-4">
            <label class="form-label" for="full_name">Name</label>
            <input type="text" id="full_name" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required>
        </div>

        <div class="mt-3">
            <label class="form-label" for="phone">Phone</label>
            <input type="text" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
        </div>

        <div class="mt-3">
            <label class="form-label" for="gender">Gender</label>
            <select name="gender" id="gender" class="form-control" required>
                <option value="">Select Gender</option>
                <option value="Male" <?= (isset($user['gender']) && $user['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= (isset($user['gender']) && $user['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= (isset($user['gender']) && $user['gender'] == 'Other') ? 'selected' : '' ?>>Other</option>
            </select>
        </div>

        <div class="mt-3">
            <label class="form-label" for="email">Email address</label>
            <input type="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
    </form>

    <!-- Go Back Button INSIDE the box -->
    <div class="mt-4 text-center">
        <button onclick="history.back()" class="btn btn-outline-secondary">
            ⬅️ Go Back
        </button>
    </div>
</div>

</body>
</html>
