<?php
session_start();
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eshara Resort | Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1a1a;
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            background: linear-gradient(120deg, #C0C0C0, #FFD700);
            border-bottom: 3px solid #FFD700;
        }
        .navbar-nav .nav-link {
            color: #000; /* default text color */
            transition: color 0.3s ease, background-color 0.3s ease;
            white-space: nowrap;
        }
        .navbar-nav .nav-link:hover {
            color: #fff;
            background-color: #198754; /* Bootstrap's green (btn-success) */
            border-radius: 5px;
        }
        .navbar-brand, .nav-link {
            color: #1a1a1a !important;
        }
        .nav-link:hover {
            color: #FFD700 !important;
        }
        .navbar-brand img {
            height: 60px; /* Increased from 40px to 60px */
            width: auto;
            margin-right: 10px;
            vertical-align: middle;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
        .navbar .dropdown-menu a:hover {
            background-color: #f0f0f0;
        }
        .hero-section {
            background: linear-gradient(120deg, #C0C0C0, #FFD700);
            padding: 100px 20px 60px;
            text-align: center;
        }
        .btn-gold {
            background: #FFD700;
            color: #1a1a1a;
            font-weight: bold;
            border: none;
            padding: 12px 20px;
        }
        .btn-gold:hover {
            background: #C0C0C0;
        }
        h2 {
            color: #FFD700;
            font-weight: bold;
        }
        .footer {
            background: #C0C0C0;
            color: #1a1a1a;
            padding: 15px 0;
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3 mx-3" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3 mx-3" role="alert">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
        
    <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm">
        <div class="container-fluid px-3">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php" style="font-size: 1.75rem;">
                <img src="images/logo.png" alt="Eshara Logo" class="me-2" style="height: 60px;">
                <span style="font-size: 1.75rem;">Eshara Resort</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Right aligned nav -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
                    <li class="nav-item"><a class="nav-link" href="activities.php">Activities</a></li>
                    <li class="nav-item"><a class="btn btn-success" href="register.php">Register</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link">Welcome, <?= $_SESSION['$full_name'] ?? 'User'; ?></a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link btn btn-success text-white ms-2" href="login.php">Login</a></li>
                    <?php endif; ?>

                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="btn btn-gold" href="rooms.php">Book Now</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_login.php">ADMIN</a></li>
                    <li class="nav-item"><a class="nav-link" href="offers.php">Special Offers</a></li>
                    <li class="nav-item"><a class="btn btn-success" href="package_calculator.php">Select Package</a></li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-primary fw-bold" href="#" id="esharaDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Eshara
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="esharaDropdown">
                            <li><a class="dropdown-item" href="booking_history.php">My Bookings</a></li>
                            <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="review.php">Give Reviews</a></li>
                    <li class="nav-item"><a class="nav-link" href="gift_voucher.php">Gifts & Vouchers</a></li>

                    <!-- Your important one -->
                    <li class="nav-item"><a class="nav-link" href="location.php" style="white-space: nowrap;">Find Eshara Resort Location</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
    </div> <!-- Close container -->

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Hero Section -->
    <section class="hero-section mt-5 pt-5">
        <div class="container">
            <img src="images/eshara.png" alt="Eshara Resort" class="img-fluid shadow-sm mb-4" />

            <br><br><br>
    
            <h1>Welcome to Eshara Resort</h1>
            <p>Experience luxury like never before</p>
            <p class="lead text-white">Book your dream vacation with us</p>
            <a href="rooms.php" class="btn btn-primary">Book Now</a>
        </div>
    </section>
    
    <!-- About Section -->
    <section id="about" class="container py-5 text-white">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-warning fw-bold text-center mb-4">Welcome to Eshara Resort</h2>

                <p>
                    Eshara Resort is a type of accommodation that often focuses on providing a relaxed and luxurious getaway, 
                    typically with an emphasis on natural beauty, such as palm trees or a beach location. 
                    They offer a range of amenities and activities beyond just basic lodging, including spas, pools, 
                    entertainment—making them a destination in themselves.
                </p>

                <p>
                    Eshara Resort. Nestled between lush greenery and the gentle rhythm of a private coastline, 
                    Eshara Resort is a sanctuary where nature meets luxury. 
                    Designed for travelers who seek both adventure and peace, Eshara offers an immersive experience that leaves guests refreshed, 
                    inspired, and deeply connected to the environment around them.
                </p>

                <p>
                    From the moment one arrives, Eshara’s charm is undeniable. 
                    The resort’s architecture blends modern elegance with traditional influences, 
                    using natural materials like stone, wood, and bamboo to mirror the landscape. 
                    Winding pathways lead through tropical gardens, past tranquil koi ponds and open-air lounges, all the way to 
                    beautifully appointed villas and suites that offer sweeping views of the sea or the forest canopy.
                </p>

                <p>
                    Each accommodation at Eshara is a private haven. Spacious, sunlit rooms open to verandas with daybeds and plunge pools. 
                    The interiors are tastefully decorated in earthy tones, with handwoven fabrics, artisanal décor, and locally sourced furnishings. 
                    Every detail is thoughtfully curated to provide comfort while maintaining a connection to the natural world.
                </p>

                <p>
                    But Eshara is more than just a place to stay—it’s a destination in itself. 
                    Guests can begin their day with sunrise yoga on the beachfront platform, indulge in a traditional massage at the spa, 
                    or explore scenic trails through nearby hills and waterfalls. For the adventurous, the resort organizes guided snorkeling, 
                    cultural tours, and eco-treks, ensuring every traveler finds something meaningful.
                </p>

                <p>
                    Dining at Eshara is an experience to remember. The resort’s farm-to-table restaurant serves dishes made with organic produce from its own garden,
                    fresh seafood from local fishermen, and traditional recipes reimagined with modern flair. 
                    Meals are served in an open-air pavilion overlooking the ocean, where the sound of waves provides the perfect backdrop.
                </p>

                <p>
                    Eshara also takes pride in sustainability. It operates on solar energy, recycles water for landscaping, and employs locals to empower the surrounding community. 
                    Guests are invited to take part in green initiatives—from planting trees to learning about local wildlife conservation.
                </p>

                <p>
                    Whether you’re seeking a romantic retreat, a wellness escape, or a break from the noise of daily life, Eshara Resort is the perfect destination. 
                    Here, the days move slowly, the air is pure, and every moment feels like a gentle invitation to relax, explore, 
                    and reconnect—with nature, with others, and with yourself.
                </p>
            </div>
        </div>
    </section>

    <!-- Explore Destinations Section -->
    <section class="container py-5 text-center">
        <h2 class="text-warning fw-bold">Explore Our Stunning Destinations</h2>
        <p>Discover the beauty of India with our resorts in Goa, Jaipur, Kerala, and more.</p>
    </section>

    <div class="slider-container">
        <div class="slider" id="slider">
            <div class="slide"><img src="images/temple.webp" alt="Goa - Beach Paradise"></div>
            <div class="slide"><img src="images/taj.jpg" alt="Jaipur - Pink City"></div>
            <div class="slide"><img src="images/kerala.jpg" alt="Kerala - God's Own Country"></div>
            <div class="slide"><img src="images/darjeeling.jpg" alt="Manali-Snow Place"></div>
            <div class="slide"><img src="images/buddha.jpg" alt="Shimla"></div>
            <div class="slide"><img src="images/kochi.jpg" alt="Kochi"></div>
            <div class="slide"><img src="images/hampi.jpg" alt="Hampi"></div>
        </div>
        <!-- Navigation Buttons and Dots (if any) go here -->
        <button class="nav-button nav-left" onclick="moveSlide(-1)">&#10094;</button>
        <button class="nav-button nav-right" onclick="moveSlide(1)">&#10095;</button>
        <div class="dots" id="dots"></div>
    </div>

    <style>
    /* Container for the entire slider */
    .slider-container {
        position: relative;
        width: 80%; /* Smaller width */
        max-width: 600px; /* Limit maximum size */
        margin: auto; /* Center the slider */
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Wrapper for all slides */
    .slider {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }

    /* Each individual slide */
    .slide {
        min-width: 100%;
        transition: opacity 0.5s ease-in-out;
    }

    /* Slide image styling */
    .slide img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 10px;
    }

    /* Navigation buttons (left & right) */
    .nav-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.4);
        border: none;
        padding: 10px;
        cursor: pointer;
        color: white;
        font-size: 24px;
        border-radius: 50%;
        z-index: 10;
    }

    .nav-button:hover {
        background-color: rgba(0, 0, 0, 0.7);
    }

    .nav-left {
        left: 10px;
    }

    .nav-right {
        right: 10px;
    }

    /* Dot indicators below slider */
    .dots {
        text-align: center;
        margin-top: 10px;
    }

    .dot {
        height: 10px;
        width: 10px;
        margin: 0 4px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .dot.active {
        background-color: #717171;
    }
</style>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let currentSlide = 0;
            const slides = document.querySelectorAll('.slide');
            const slider = document.getElementById('slider');
            const dotsContainer = document.getElementById('dots');

            function updateSlider() {
                slider.style.transform = `translateX(-${currentSlide * 100}%)`;
                document.querySelectorAll('.dot').forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentSlide);
                });
            }

            function moveSlide(n) {
                currentSlide += n;
                if (currentSlide < 0) currentSlide = slides.length - 1;
                if (currentSlide >= slides.length) currentSlide = 0;
                updateSlider();
            }

            // Generate dots
            slides.forEach((_, index) => {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (index === 0) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    updateSlider();
                });
                dotsContainer.appendChild(dot);
            });

            // Initial update
            updateSlider();

            // Attach moveSlide to window for button onclick
            window.moveSlide = moveSlide;
        });
    </script>
    
    <!-- Rooms Section -->
    <section id="rooms" class="bg-light py-5 text-center">
        <div class="container">
            <h2 class="fw-bold">Our Luxury Rooms</h2>
            <p>Experience comfort and elegance with our premium accommodations.</p>
            <a href="rooms.php" class="btn btn-dark">Explore Rooms</a>
        </div>
    </section>
    
    <!-- Activities Section -->
    <section id="activities" class="container py-5">
        <h2 class="text-center fw-bold">Exciting Activities</h2>
        <p class="text-center">From relaxing spa treatments to thrilling water sports, we have something for everyone.</p>
        <a href="activities.php" class="btn btn-primary d-block mx-auto" style="width: 200px;">View Activities</a>
    </section>
    
    <section class="places-section">
    <div class="container">
        <h2 class="text-center mb-4">Explore Stunning Places</h2>
        <div class="row">
            <div class="col-md-4">
                <img src="images/beach.webp" alt="Beautiful Beach" class="img-fluid">
                <h4 class="text-center mt-2">Sunny Beach</h4>
            </div>
            <div class="col-md-4">
                <img src="images/garden.jpg" alt="Luxury Garden" class="img-fluid">
                <h4 class="text-center mt-2">Resort Garden</h4>
            </div>
            <div class="col-md-4">
                <img src="images/landmark.jpeg" alt="Nearby Landmark" class="img-fluid">
                <h4 class="text-center mt-2">Famous Landmark</h4>
            </div>
        </div>
    </div>
    </section>
    
    <!-- ...your previous content, like hero, about, rooms, etc... -->

    <!-- Place Testimonials Here -->
    <section style="padding: 40px 20px; text-align: center; background: #f9f9f9;">
        <h2 style="font-size: 2rem; font-weight: bold;">Our Testimonials</h2>
        <p style="color: #555; margin-bottom: 30px;">What people tell about our Eshara Hotel Booking</p>

        <div style="position: relative; max-width: 700px; margin: 0 auto;">
            <div id="testimonialBox" style="border: 4px solid royalblue; border-radius: 20px; padding: 30px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                <p id="testimonialText" style="font-size: 1.1rem; color: #444;">They are one of the best hotel booking service providers in Bangalore.</p>
                <strong id="testimonialAuthor" style="display: block; margin-top: 20px; font-size: 1.1rem;">Sahal SM</strong>
            </div>

            <div style="margin-top: 20px;">
                <button onclick="changeTestimonial(-1)" style="background: orange; border: none; padding: 10px 15px; margin: 0 5px; border-radius: 5px; cursor: pointer;">&#8249;</button>
                <button onclick="changeTestimonial(1)" style="background: orange; border: none; padding: 10px 15px; margin: 0 5px; border-radius: 5px; cursor: pointer;">&#8250;</button>
            </div>
        </div>
    </section>

    <script>
    const testimonials = [
        { text: "They are one of the best hotel booking service providers in Bangalore.", author: "Sahal SM" },
        { text: "Amazing experience! The staff were friendly and rooms were clean.", author: "Anita Paul" },
        { text: "Booking was easy and affordable. Highly recommend Funda Hotel Booking.", author: "Rahul Mehta" },
        { text: "Exceptional service and beautiful rooms! Loved my stay.", author: "Priya R" }
    ];

    let currentIndex = 0;
    let intervalId;

    function showTestimonial(index) {
        document.getElementById('testimonialText').innerText = testimonials[index].text;
        document.getElementById('testimonialAuthor').innerText = testimonials[index].author;
    }

    function changeTestimonial(direction) {
        currentIndex = (currentIndex + direction + testimonials.length) % testimonials.length;
        showTestimonial(currentIndex);
    }

    function autoPlayTestimonials() {
        intervalId = setInterval(() => {
           changeTestimonial(1);
        }, 4000);
    }

    document.getElementById('testimonialBox').addEventListener('mouseover', () => clearInterval(intervalId));
    document.getElementById('testimonialBox').addEventListener('mouseleave', autoPlayTestimonials);

    window.onload = () => {
        showTestimonial(currentIndex);
        autoPlayTestimonials();
    };
    </script>

    <!-- Special Offers Section -->
    <section id="offers" class="bg-warning text-dark py-5 text-center">
        <div class="container">
            <h2 class="fw-bold">Special Offers & Discounts</h2>
            <p>Exclusive deals for a limited time. Book now and save big!</p>
            <a href="offers.php" class="btn btn-dark">View Offers</a>
        </div>
    </section>
    
    
    
    <!-- Customer Reviews Section -->
    <section id="reviews" class="container py-5 text-center">
        <h2 class="fw-bold">What Our Guests Say</h2>
        <p>Read testimonials from our happy visitors.</p>
        <a href="reviews.php" class="btn btn-primary">Read Reviews</a>
    </section>
    
    <!-- Contact Section -->
    <section id="contact" class="bg-dark text-white py-5 text-center">
        <div class="container">
            <h2>Contact Us</h2>
            <p>Have questions? Get in touch with us today.</p>
            <a href="contact.php" class="btn btn-warning">Get in Touch</a>
        </div>
    </section>
    
    
    
    <!-- Footer -->
     <footer class="footer text-center">
        <p>&copy; 2025 Luxury Resort. All Rights Reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
