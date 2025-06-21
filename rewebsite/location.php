<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Locations</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Background gradient */
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #222;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        /* Container styling */
        .container {
            background: #fff;
            border-radius: 15px;
            padding: 30px 40px;
            max-width: 700px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        h3 {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            letter-spacing: 1.2px;
            text-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }

        p {
            font-size: 1.1rem;
            line-height: 1.5;
            color: #34495e;
        }

        .text-center p {
            margin-bottom: 0.4rem;
        }

        iframe {
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
        }

        iframe:hover {
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .directions {
            margin-top: 30px;
            background: #f0f8ff;
            border-radius: 12px;
            padding: 20px 25px;
            box-shadow: inset 0 0 10px rgba(116, 235, 213, 0.3);
        }

        .directions h4 {
            color: #2980b9;
            margin-bottom: 1rem;
            font-weight: 600;
            letter-spacing: 0.8px;
        }

        .directions ol {
            padding-left: 1.2rem;
            color: #2c3e50;
            font-size: 1.05rem;
        }

        .directions ol li {
            margin-bottom: 10px;
        }

        @media (max-width: 576px) {
            .container {
                padding: 25px 20px;
            }
            p {
                font-size: 1rem;
            }
            .directions ol {
                font-size: 1rem;
            }
        }

        /* Enhanced back button */
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #2980b9;
            color: #fff !important;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(41, 128, 185, 0.3);
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background-color: #1c6ca3;
            box-shadow: 0 8px 20px rgba(41, 128, 185, 0.4);
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h3 class="text-center mb-4">📍 Our Resort Location</h3>

        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <p><strong>Eshara Resort</strong></p>
                <p>Near Baga Beach, North Goa, India</p>
                <p>Email: contact@eshararesort.com | Phone: +91-9876543210</p>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3782.345588351218!2d73.76516681435108!3d15.299326288298073!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bbfdd2fc2bfc2f1%3A0x6df8d92469d0b2de!2sBaga%20Beach%2C%20Goa!5e0!3m2!1sen!2sin!4v1681012345678"
                    width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <div class="directions">
            <h4>Directions from Goa International Airport</h4>
            <ol>
                <li>Exit the airport and head north on Airport Road.</li>
                <li>Take NH66 highway towards Panaji.</li>
                <li>Take the exit toward Calangute/Baga Beach.</li>
                <li>Follow signs for Baga Beach and continue on the main road.</li>
                <li>Turn right near Baga Market towards Eshara Resort.</li>
                <li>Arrive at Eshara Resort, located near Baga Beach.</li>
            </ol>
        </div>

        <div class="text-center">
            <a href="index.php" class="back-btn">← Go Back to Home</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
