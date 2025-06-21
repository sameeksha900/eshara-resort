<?php
session_start();
include 'db_connect.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_query = "%{$search}%";

// Search logic
if (!empty(trim($_GET['search'] ?? ''))) {
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_type LIKE ? OR amenities LIKE ?");
    $stmt->bind_param("ss", $search_query, $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM rooms");
}

// Handle Add Room form
if (isset($_POST['add_room'])) {
    $room_type = $_POST['room_type'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $max_people = $_POST['max_people'];
    $amenities = $_POST['amenties'];
    $availability = $_POST['availability'];
    $no_of_beds = $_POST['no_of_beds'];

    $image_name = $_FILES['room_image']['name'];
    $image_tmp = $_FILES['room_image']['tmp_name'];
    $upload_path = 'uploads/' . basename($image_name);

    if (move_uploaded_file($image_tmp, $upload_path)) {
        $stmt = $conn->prepare("INSERT INTO rooms (room_type, description, price, image, max_people, amenities, availability, no_of_beds) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsisii", $room_type, $description, $price, $image_name, $max_people, $amenities, $availability, $no_of_beds);
        $stmt->execute();
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM rooms WHERE room_id=$id");
    header("Location: manage_rooms.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.75);
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            margin-top: 40px;
            margin-bottom: 40px;
        }
        h2, h3, h4 {
            font-weight: 700;
            color: #f1c40f;
        }
        form .form-control, form .form-select {
            border-radius: 0.375rem;
            border: none;
            padding: 10px 15px;
            background-color: #2c3e50;
            color: #ecf0f1;
            transition: background-color 0.3s ease;
        }
        form .form-control::placeholder {
            color: #bdc3c7;
        }
        form .form-control:focus, form .form-select:focus {
            background-color: #34495e;
            color: #fff;
            box-shadow: 0 0 8px #f1c40f;
            outline: none;
        }
        .btn-primary {
            background-color: #f1c40f;
            border: none;
            color: #2c3e50;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #d4ac0d;
            color: #fff;
        }
        .btn-success {
            background-color: #27ae60;
            border: none;
            font-weight: 600;
        }
        .btn-success:hover {
            background-color: #1e8449;
        }
        .btn-secondary {
            background-color: #7f8c8d;
            border: none;
            color: #ecf0f1;
        }
        .btn-secondary:hover {
            background-color: #95a5a6;
            color: #fff;
        }
        .table {
            background-color: #34495e;
            color: #ecf0f1;
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead {
            background-color: #2c3e50;
            color: #f1c40f;
        }
        .table tbody tr:hover {
            background-color: #2ecc71;
            color: #2c3e50;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .table td, .table th {
            vertical-align: middle;
            border: none;
            padding: 12px 15px;
        }
        img {
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        a.btn {
            font-weight: 600;
            border-radius: 6px;
            padding: 6px 12px;
        }
        /* Responsive tweaks */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            .table {
                font-size: 0.85rem;
            }
            form .form-control, form .form-select {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<div class="container">

    <h2 class="mb-4 text-center">Manage Rooms</h2>

    <!-- Search Form -->
    <form method="GET" class="row g-2 mb-4 justify-content-center">
        <div class="col-md-5 col-sm-12">
            <input type="text" name="search" class="form-control" placeholder="Search by Room Type or Amenities" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary px-4">Search</button>
        </div>
        <div class="col-auto">
            <a href="manage_rooms.php" class="btn btn-secondary px-4">Reset</a>
        </div>
    </form>

    <!-- Add Room Form -->
    <div class="bg-dark p-4 rounded shadow mb-5">
        <h4 class="mb-3 text-center">Add New Room</h4>
        <form method="post" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="room_type" class="form-control" placeholder="Room Type" required>
                </div>
                <div class="col-md-6">
                    <input type="number" name="price" class="form-control" placeholder="Price" required min="0" step="0.01">
                </div>
                <div class="col-12">
                    <textarea name="description" class="form-control" placeholder="Description" rows="3" required></textarea>
                </div>
                <div class="col-md-6">
                    <input type="number" name="max_people" class="form-control" placeholder="Max People" required min="1" max="50">
                </div>
                <div class="col-md-6">
                    <input type="file" name="room_image" class="form-control" required accept="image/*">
                </div>
                <div class="col-md-6">
                    <input type="text" name="amenties" class="form-control" placeholder="Amenities (comma separated)" required>
                </div>
                <div class="col-md-6">
                    <input type="number" name="availability" class="form-control" placeholder="Availability" required min="0">
                </div>
                <div class="col-md-6">
                    <input type="number" name="no_of_beds" class="form-control" placeholder="Number of Beds" required min="1" max="10">
                </div>
                <div class="col-12 text-center mt-3">
                    <button type="submit" name="add_room" class="btn btn-success px-5">Add Room</button>
                </div>
            </div>
        </form>
    </div>

    <h3 class="mb-4 text-center">All Rooms</h3>
    <div class="table-responsive">
        <table class="table table-striped table-hover text-center align-middle rounded">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Type</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Max People</th>
                    <th>Image</th>
                    <th>Amenities</th>
                    <th>Availability</th>
                    <th>No. of Beds</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['room_id'] ?></td>
                        <td><?= htmlspecialchars($row['room_type']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>₹<?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['max_people'] ?></td>
                        <td><img src="uploads/<?= htmlspecialchars($row['image']) ?>" width="80" alt="Room Image"></td>
                        <td><?= htmlspecialchars($row['amenities']) ?></td>
                        <td><?= $row['availability'] ?></td>
                        <td><?= $row['no_of_beds'] ?></td>
                        <td>
                            <a href="edit_room.php?id=<?= $row['room_id'] ?>" class="btn btn-primary btn-sm mb-1 w-100">Edit</a>
                            <a href="manage_rooms.php?delete=<?= $row['room_id'] ?>" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to delete this room?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary px-5">Back to Dashboard</a>
    </div>

</div>

</body>
</html>
