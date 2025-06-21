<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connect.php';

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Invalid room ID provided.</div>";
    exit();
}

$room_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-warning'>Room not found.</div>";
    exit();
}

$room = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Room - ID <?= $room['room_id'] ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #f1f8e9);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 50px 10px;
        }
        .edit-box {
            background: linear-gradient(145deg, #ffffff, #f0f0f0);
            padding: 40px 30px;
            border-radius: 18px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .edit-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.2);
        }
        h4 {
            font-weight: 700;
            color: #2c3e50;
            text-shadow: 1px 1px #ecf0f1;
            margin-bottom: 35px;
        }
        label {
            font-weight: 600;
            color: #34495e;
        }
        input.form-control,
        textarea.form-control {
            border-radius: 12px;
            border: 2px solid #90caf9;
            transition: all 0.3s ease-in-out;
        }
        input.form-control:focus,
        textarea.form-control:focus {
            border-color: #42a5f5;
            box-shadow: 0 0 10px rgba(66, 165, 245, 0.3);
        }
        textarea.form-control {
            resize: vertical;
        }
        .btn-success {
            background-color: #28a745;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 25px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
        }
        .btn-secondary {
            background-color: #6c757d;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 25px;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="edit-box mx-auto" style="max-width: 800px;">
        <h4 class="text-center">Edit Room - ID <?= $room['room_id'] ?></h4>

        <form method="POST" action="">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Room Type</label>
                    <input type="text" name="room_type" class="form-control" value="<?= htmlspecialchars($room['room_type']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" class="form-control" value="<?= $room['price'] ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Max People</label>
                    <input type="number" name="max_people" class="form-control" value="<?= $room['max_people'] ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Availability</label>
                    <input type="number" name="availability" class="form-control" value="<?= $room['availability'] ?>" required>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Amenities</label>
                    <textarea name="amenities" class="form-control" rows="4"><?= htmlspecialchars($room['amenities']) ?></textarea>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Number of Beds</label>
                    <input type="number" name="no_of_beds" class="form-control" value="<?= $room['no_of_beds'] ?>" required>
                </div>

                <div class="col-12 d-flex justify-content-between mt-4">
                    <button type="submit" name="update" class="btn btn-success">Update Room</button>
                    <a href="manage_rooms.php" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE rooms SET room_type = ?, price = ?, max_people = ?, amenities = ?, no_of_beds = ?, availability = ? WHERE room_id = ?");
    $stmt->bind_param(
        "sissiii",
        $_POST['room_type'],
        $_POST['price'],
        $_POST['max_people'],
        $_POST['amenities'],
        $_POST['no_of_beds'],
        $_POST['availability'],
        $room_id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Room updated successfully!'); window.location.href = 'manage_rooms.php';</script>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Update failed. Please try again.</div>";
    }
}
?>

</body>
</html>
