<?php
$gifts = [
    '', // This represents the "Oops" box
    '10% Discount Voucher',
    'Free Breakfast',
    'Free Room Upgrade',
    'Late Checkout Pass',
    '20% Off Next Booking',
    'Spa Voucher'
];

shuffle($gifts); // Randomize gift order
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gift Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Warm sunny background gradient */
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 0;
            color: #5b2c06;
        }

        h2 {
            font-weight: 700;
            color: #5b2c06;
            letter-spacing: 1.2px;
            text-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }

        /* Container card */
        .container {
            background: #fff7f0;
            border-radius: 20px;
            padding: 40px 30px 50px 30px;
            max-width: 900px;
            box-shadow: 0 12px 30px rgba(215, 118, 48, 0.25);
        }

        /* Gift box styling */
        .gift-box {
            width: 160px;
            height: 160px;
            background-color: #fff2e8;
            border: 3px solid #eb6429;
            border-radius: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            font-weight: 700;
            color: #eb6429;
            cursor: pointer;
            transition: all 0.25s ease;
            user-select: none;
            box-shadow: 0 6px 12px rgba(235, 100, 41, 0.15);
            text-align: center;
            padding: 15px;
        }
        .gift-box:hover {
            background-color: #eb6429;
            color: #fff7f0;
            transform: scale(1.1);
            box-shadow: 0 12px 20px rgba(235, 100, 41, 0.35);
            border-color: #b94a20;
        }
        .gift-box.disabled {
            pointer-events: none;
            opacity: 0.5;
            background-color: #f0d6bf;
            color: #a1663b;
            border-color: #d9b388;
            box-shadow: none;
            transform: none !important;
        }

        /* Result box styling */
        #result {
            font-weight: 700;
            font-size: 1.4rem;
            min-height: 100px;
            margin-top: 2.5rem;
            color: #7a3f00;
            user-select: none;
        }
        #result h4 {
            margin-bottom: 0.6rem;
            letter-spacing: 0.03em;
        }
        #result p.fs-4 {
            color: #e67e22;
            font-weight: 800;
            font-size: 1.6rem;
            margin-top: 0;
        }
        #result .text-danger {
            color: #c0392b !important;
        }

        /* Buttons below result */
        #buttons {
            margin-top: 1.8rem;
        }
        #buttons .btn {
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 15px;
            padding: 10px 20px;
            box-shadow: 0 4px 10px rgba(235, 100, 41, 0.15);
            transition: background-color 0.3s ease;
        }
        #buttons .btn-secondary {
            background-color: #d35400;
            color: #fff;
            border: none;
        }
        #buttons .btn-secondary:hover {
            background-color: #a84300;
        }
        #buttons .btn-success {
            background-color: #e67e22;
            color: #fff;
            border: none;
        }
        #buttons .btn-success:hover {
            background-color: #ca6f17;
        }

        /* Back to Home button styling */
        .btn-primary {
            font-weight: 700;
            font-size: 1.15rem;
            border-radius: 20px;
            padding: 12px 30px;
            box-shadow: 0 8px 20px rgba(235, 100, 41, 0.3);
            background-color: #eb6429;
            border: none;
            color: #fff;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #b94a20;
            color: #fff;
        }

        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .gift-box {
                width: 130px;
                height: 130px;
                font-size: 18px;
            }
            #result p.fs-4 {
                font-size: 1.3rem;
            }
        }
        @media (max-width: 495px) {
            .gift-box {
                width: 110px;
                height: 110px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container text-center shadow-sm">
        <h2 class="mb-4">🎁 Pick a Gift Box</h2>
        <div class="row justify-content-center g-4">
            <?php foreach ($gifts as $index => $gift): ?>
                <div class="col-6 col-sm-4 col-md-2 d-flex justify-content-center">
                    <div class="gift-box" onclick="revealGift(this, '<?= htmlspecialchars($gift, ENT_QUOTES) ?>')">Pick Me</div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="result"></div>
        
        <div id="buttons" style="display: none;">
            <button class="btn btn-secondary me-3" onclick="window.print()">🖨️ Print</button>
            <button class="btn btn-success" onclick="downloadAsImage()">📥 Download as PNG</button>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        let picked = false;

        function revealGift(box, gift) {
            if (picked) return;

            picked = true;
            const allBoxes = document.querySelectorAll('.gift-box');
            allBoxes.forEach(b => b.classList.add('disabled'));

            if (gift === '') {
                box.innerHTML = "😢 Oops!";
                document.getElementById('result').innerHTML = `
                    <h4 class="text-danger mt-4">Oops! You missed a chance.</h4>
                    <a href="" class="btn btn-primary mt-3">Try Again</a>
                `;
            } else {
                box.innerHTML = "🎉 " + gift;
                document.getElementById('result').innerHTML = `
                    <h4 class="text-success mt-4">Congratulations! You got:</h4>
                    <p class="fs-4">${gift}</p>
                `;
                document.getElementById('buttons').style.display = 'block';
            }
        }

        function downloadAsImage() {
            html2canvas(document.querySelector("#result")).then(canvas => {
                const link = document.createElement('a');
                link.download = 'gift_voucher.png';
                link.href = canvas.toDataURL();
                link.click();
            });
        }
    </script>
</body>
</html>
