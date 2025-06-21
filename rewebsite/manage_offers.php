<?php
include 'db_connect.php';

// Add Offer
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_offer'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $discount = $_POST['discount_percent'];
    $valid_from = $_POST['valid_from'];
    $valid_until = $_POST['valid_until'];

    $stmt = $conn->prepare("INSERT INTO offers (title, description, discount_percent, valid_from, valid_until) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $title, $description, $discount, $valid_from, $valid_until);
    $stmt->execute();
}

// Delete Offer
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM offers WHERE offer_id = $delete_id");
}

// Fetch Offers
$offers = $conn->query("SELECT * FROM offers");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Offers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee);
        }

        .custom-box {
            background-color: #ffffff;
            border: 3px solid #6c63ff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .custom-table th, .custom-table td {
            border: 2px solid #6c63ff !important;
        }

        .custom-table thead {
            background-color: #6c63ff;
            color: white;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .form-label {
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4 text-center fw-bold text-dark">🎉 Manage Resort Offers</h2>

    <!-- Add Offer Form -->
    <form method="POST" class="mb-5 custom-box">
        <h4 class="mb-3 text-primary">Add New Offer</h4>
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control border-primary" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control border-primary" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Discount Percent (%)</label>
            <input type="number" name="discount_percent" class="form-control border-primary" required min="0" max="100">
        </div>
        <div class="mb-3">
            <label class="form-label">Valid From</label>
            <input type="date" name="valid_from" class="form-control border-primary" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Valid Until</label>
            <input type="date" name="valid_until" class="form-control border-primary" required>
        </div>
        <button type="submit" name="add_offer" class="btn btn-success">➕ Add Offer</button>
    </form>

    <!-- Display Offers -->
    <div class="custom-box">
        <h4 class="mb-3 text-primary">Current Offers</h4>
        <?php if ($offers && $offers->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered custom-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Discount (%)</th>
                            <th>Valid From</th>
                            <th>Valid Until</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($offer = $offers->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $offer['offer_id'] ?></td>
                            <td><?= htmlspecialchars($offer['title']) ?></td>
                            <td><?= htmlspecialchars($offer['description']) ?></td>
                            <td><?= $offer['discount_percent'] ?>%</td>
                            <td><?= $offer['valid_from'] ?></td>
                            <td><?= $offer['valid_until'] ?></td>
                            <td>
                                <a href="?delete_id=<?= $offer['offer_id'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this offer?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">No offers found.</p>
        <?php endif; ?>
        <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
