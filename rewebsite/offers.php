<?php
include 'db_connect.php';

// Fetch all current offers
$today = date('Y-m-d');
$query = "SELECT * FROM offers WHERE valid_from <= ? AND valid_until >= ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $today, $today);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Special Offers | Eshara Resort</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #b3e5fc, #fce4ec);
            font-family: 'Poppins', sans-serif;
        }

        .container {
            margin-top: 60px;
        }

        h2 {
            font-weight: 700;
            font-size: 2.8rem;
            color: #1a1a1a;
        }

        .offer-card {
            background: #ffffff;
            border: 2px solid #00acc1;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
            overflow: hidden;
        }

        .offer-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .offer-image {
            height: 220px;
            object-fit: cover;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }

        .discount-tag {
            display: inline-block;
            background-color: #00acc1;
            color: #fff;
            padding: 6px 16px;
            font-weight: 600;
            border-radius: 50px;
            font-size: 0.85rem;
        }

        .offer-title {
            font-weight: 600;
            font-size: 1.3rem;
            color: #222;
        }

        .offer-description {
            font-size: 0.95rem;
            color: #555;
        }

        .validity {
            font-size: 0.85rem;
            color: #777;
        }

        .go-back-btn {
            margin-top: 50px;
        }

        .go-back-btn button {
            background-color: #00acc1;
            color: white;
            font-weight: 500;
            padding: 10px 25px;
            border: none;
            border-radius: 50px;
            transition: background-color 0.3s ease;
        }

        .go-back-btn button:hover {
            background-color: #007c91;
        }

    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="text-center mb-5">🌴 Eshara Resort Offers</h2>
    <div class="row g-4">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($offer = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="offer-card">
                        <?php if (!empty($offer['image_url'])): ?>
                            <img src="<?= htmlspecialchars($offer['image_url']) ?>" class="w-100 offer-image" alt="Offer Image">
                        <?php endif; ?>
                        <div class="p-4">
                            <h5 class="offer-title"><?= htmlspecialchars($offer['title']) ?></h5>
                            <p class="offer-description"><?= htmlspecialchars($offer['description']) ?></p>
                            <p class="discount-tag"><?= $offer['discount_percent'] ?>% OFF</p>
                            <p class="validity mt-2">Valid: <?= $offer['valid_from'] ?> to <?= $offer['valid_until'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center fs-5 text-muted">No current offers available.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Go Back Button -->
    <div class="text-center go-back-btn">
        <button onclick="history.back()">⬅️ Go Back</button>
    </div>
</div>

</body>
</html>
