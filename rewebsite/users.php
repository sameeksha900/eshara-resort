<?php
include 'db_connect.php';
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            font-family: 'Segoe UI', sans-serif;
        }

        h3 {
            color: #343a40;
            font-weight: bold;
        }

        .table-container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            border: 2px solid #ff7e5f;
        }

        .table-bordered > :not(caption) > * > * {
            border: 2px solid #ff7e5f;
        }

        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #fff3e6;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <h3 class="mb-4 text-center">All Registered Users</h3>
    <div class="table-container">
        <div class="table-responsive">
            <table id="userTable" class="table table-striped table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Ban</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $row['user_id'] ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['gender']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <?php if ($row['status'] === 'banned') : ?>
                                <span class="badge bg-danger">Banned</span>
                            <?php else : ?>
                                <span class="badge bg-success">Active</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="ban_users.php?user_id=<?= $row['user_id'] ?>" class="btn btn-sm btn-danger">Ban</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
</div>

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#userTable').DataTable();
    });
</script>

</body>
</html>
