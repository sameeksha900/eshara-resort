<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Eshara Resort | Reviews</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="styles.css" />
    <style>
        /* Background gradient for entire page */
        body {
            /* Subtle deep blue to lighter blue gradient */
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            color: #f1f1f1;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar overrides for better contrast */
        nav.navbar {
            background-color: rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        nav.navbar .navbar-brand,
        nav.navbar .nav-link {
            color: #1e3c72 !important;
            font-weight: 600;
        }
        nav.navbar .nav-link:hover {
            color: #764ba2 !important;
            text-decoration: underline;
        }

        /* Navbar brand logo styling */
        .navbar-brand img {
            height: 40px;
            width: auto;
            margin-right: 10px;
            vertical-align: middle;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }

        /* Container spacing */
        section.container {
            padding-top: 6rem;
            padding-bottom: 4rem;
            max-width: 960px;
        }

        /* Section heading */
        h2 {
            font-weight: 900;
            color: #ffd700; /* gold */
            text-shadow: 1px 1px 5px rgba(0,0,0,0.6);
            margin-bottom: 0.3rem;
            letter-spacing: 2px;
        }
        section > p {
            font-size: 1.1rem;
            color: #e0dede;
            margin-bottom: 3rem;
            font-style: italic;
        }

        /* Cards for reviews */
        .card {
            background: rgba(255 255 255 / 0.15);
            border: none;
            border-radius: 1rem;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(118, 75, 162, 0.6);
            background: rgba(255 255 255 / 0.25);
        }
        .card p {
            font-size: 1.05rem;
            font-weight: 500;
            line-height: 1.4;
            color: #fff;
            margin-bottom: 1.5rem;
        }
        .card h5 {
            font-style: italic;
            color: #ffd700;
            font-weight: 700;
            text-align: right;
            margin: 0;
        }

        /* Custom testimonial block styling */
        .testimonial {
            background: rgba(255 255 255 / 0.18);
            border-radius: 1rem;
            padding: 2rem;
            margin-top: 3rem;
            text-align: center;
            box-shadow: 0 6px 20px rgba(0,0,0,0.25);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: #fff;
            font-family: Georgia, serif;
        }
        .testimonial img {
            max-width: 140px;
            border-radius: 50%;
            border: 4px solid #ffd700;
            margin-bottom: 1rem;
            box-shadow: 0 0 15px #ffd700aa;
        }
        .testimonial blockquote {
            font-size: 1.3rem;
            font-weight: 600;
            font-style: italic;
            margin-bottom: 1rem;
            color: #ffe066;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
        }
        .testimonial p {
            font-weight: 700;
            font-size: 1.1rem;
            color: #ffd700cc;
            margin-bottom: 0;
        }

        /* Footer styling */
        footer {
            background: #1e3c72;
            color: #ffd700;
            font-weight: 600;
            box-shadow: 0 -4px 10px rgba(0,0,0,0.3);
            position: relative;
            z-index: 10;
        }

        /* Responsive tweaks */
        @media (max-width: 767px) {
            .card p {
                font-size: 1rem;
            }
            .testimonial {
                padding: 1.5rem;
            }
            .testimonial blockquote {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="images/logo.png" alt="Eshara Resort Logo" />
                Eshara Resort
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
                    <li class="nav-item"><a class="nav-link" href="activities.php">Activities</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Reviews Section -->
    <section class="container py-5 mt-5">
        <h2 class="text-center fw-bold">Guest Reviews</h2>
        <p class="text-center">See what our happy guests have to say!</p>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm p-4">
                    <p>"An amazing experience! The service was top-notch, and the views were breathtaking. Highly recommend!"</p>
                    <h5 class="text-end">- Sonam Kapoor</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-4">
                    <p>"Best vacation ever! The staff was friendly, and the activities were so much fun. Will definitely visit again."</p>
                    <h5 class="text-end">- Tarun Diwan</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-4">
                    <p>"A perfect place for a relaxing getaway. Loved every moment of my stay!"</p>
                    <h5 class="text-end">- Jansi Rathore</h5>
                </div>
            </div>
        </div>

        <!-- Testimonial block outside row for better centering -->
        <div class="testimonial mt-5">
            <img src="images/happy-guests.jpg" alt="Happy Guests" />
            <blockquote>"The best resort experience we've ever had!"</blockquote>
            <p>- Prajwal K</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-light text-center py-3">
        <p>&copy; 2025 Luxury Resort. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
