<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Eshara Resort | Activities</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
      body {
      background-color: #e6f4f9; /* light blue background */
      font-family: 'Segoe UI', sans-serif;
    }
    h2 {
      color: #0d6efd;
      margin-bottom: 30px;
    }
    .card {
      border: 2px solid #0d6efd;
      border-radius: 15px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s, box-shadow 0.3s;
      background: #ffffff;
    }
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
      border-color: #0d6efd;
    }
    .card-img-top {
      border-radius: 15px 15px 0 0;
      border-bottom: 2px solid #dee2e6;
    }
    .card-title {
      color: #0d6efd;
      font-weight: 600;
    }
    .card-text {
      color: #444;
    }
    footer {
      background: #d0e9f7;
      color: #444;
      font-weight: 500;
    }
    .go-back-btn {
      background: #fff;
      border: 2px solid #0d6efd;
      color: #0d6efd;
      transition: all 0.3s;
    }
    .go-back-btn:hover {
      background: #0d6efd;
      color: #fff;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
        <img src="images/logo.png" alt="Eshara Logo" width="40" height="40" class="me-2 rounded-circle">
        Eshara Resort
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="btn btn-primary ms-2" href="package_calculator.php">Book Now</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Activities Section -->
<section class="container py-5 mt-5">
  <h2 class="text-center fw-bold">Exciting Activities</h2>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card h-100">
        <img src="images/scuba.png" class="card-img-top" alt="Scuba Diving">
        <div class="card-body">
          <h5 class="card-title">Scuba Diving</h5>
          <p class="card-text">Discover the underwater world and experience the thrill of exploring marine life through scuba diving.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <img src="images/relax.png" class="card-img-top" alt="Spa & Wellness">
        <div class="card-body">
          <h5 class="card-title">Spa & Wellness</h5>
          <p class="card-text">Relax. Rejuvenate. Rediscover Yourself.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <img src="images/sunset.png" class="card-img-top" alt="Sunset Cruise">
        <div class="card-body">
          <h5 class="card-title">Sunset Cruise</h5>
          <p class="card-text">Enjoy breathtaking views on our private sunset cruise.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <img src="images/meditation.png" class="card-img-top" alt="Meditation & Exercise">
        <div class="card-body">
          <h5 class="card-title">Meditation & Exercise</h5>
          <p class="card-text">Calm your mind and embrace inner peace through guided meditation.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <img src="images/trekking.png" class="card-img-top" alt="Trekking">
        <div class="card-body">
          <h5 class="card-title">Trekking</h5>
          <p class="card-text">Conquer the trails, embrace the thrill – your next adventure starts here!</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <img src="images/cultural.png" class="card-img-top" alt="Cultural Tour">
        <div class="card-body">
          <h5 class="card-title">Cultural Tour</h5>
          <p class="card-text">Step into stories of the past – where every monument whispers history!</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <img src="images/play.png" class="card-img-top" alt="Cultural Tour">
        <div class="card-body">
          <h5 class="card-title">Kid's Play Zone</h5>
          <p class="card-text">Jump In. Slide Down. Laugh Loud!</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <img src="images/pottery.png" class="card-img-top" alt="Cultural Tour">
        <div class="card-body">
          <h5 class="card-title">Pottery Class</h5>
          <p class="card-text">Shape Your Imagination.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <img src="images/volleball.png" class="card-img-top" alt="Cultural Tour">
        <div class="card-body">
          <h5 class="card-title">Play Volleyball</h5>
          <p class="card-text">Where Passion Meets Precision.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <img src="images/animal.png" class="card-img-top" alt="Cultural Tour">
        <div class="card-body">
          <h5 class="card-title">Animal Petting Zoo</h5>
          <p class="card-text">Cuddle, Feed, and Fall in Love!</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <img src="images/campfire.png" class="card-img-top" alt="Cultural Tour">
        <div class="card-body">
          <h5 class="card-title">Bonfire & Campfire Nights </h5>
          <p class="card-text">Where Stories Glow and Memories Spark.</p>
        </div>
      </div>
    </div>
      <div class="col-md-4">
      <div class="card h-100">
        <img src="images/puppet.png" class="card-img-top" alt="Cultural Tour">
        <div class="card-body">
          <h5 class="card-title">Magic/Puppet Shows </h5>
          <p class="card-text">Let the Magic Begin!</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Go Back Button -->
<div class="text-center my-4">
  <button onclick="history.back()" class="btn go-back-btn px-4 py-2 rounded-pill">
    ⬅️ Go Back
  </button>
</div>

<!-- Footer -->
<footer class="text-center py-3 mt-5">
  <p>&copy; 2025 Luxury Resort. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
