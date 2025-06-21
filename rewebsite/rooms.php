<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'rooms.php';
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

// Function to fetch rooms based on search
function getRooms($conn, $search = '') {
    if (!empty($search)) {
        $searchParam = '%' . $search . '%';
        $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_type LIKE ? OR amenities LIKE ?");
        $stmt->bind_param("ss", $searchParam, $searchParam);
        $stmt->execute();
        return $stmt->get_result();
    } else {
        return $conn->query("SELECT * FROM rooms");
    }
}

// Handle AJAX requests first to avoid duplicate output
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $search_ajax = isset($_GET['search']) ? trim($_GET['search']) : '';
    $result_ajax = getRooms($conn, $search_ajax);

    if ($result_ajax->num_rows > 0) {
        $index = 0;
        while ($room = $result_ajax->fetch_assoc()) {
            $delay = $index * 0.1;
            echo '<a href="room_details.php?room_id=' . urlencode($room['room_id']) . '" class="room-card-style" style="animation-delay:' . $delay . 's;opacity:1;transform:none;">';
            echo '<img src="uploads/' . htmlspecialchars($room['image']) . '" alt="' . htmlspecialchars($room['room_type']) . '" loading="lazy" />';
            echo '<div class="room-card-content">';
            echo '<div style="display:flex; justify-content: space-between; align-items: center;">';
            echo '<h3>' . htmlspecialchars($room['room_type']) . '</h3>';
            echo '<p class="room-card-price">₹' . number_format($room['price'], 2) . '</p>';
            echo '</div>';
            echo '<p><strong>Description:</strong> ' . htmlspecialchars($room['description']) . '</p>';
            echo '<p><strong>Amenities:</strong></p><ul>';
            $amenities_ajax = explode(',', $room['amenities']);
            foreach ($amenities_ajax as $item) {
                echo '<li>' . htmlspecialchars(trim($item)) . '</li>';
            }
            echo '</ul></div></a>';
            $index++;
        }
    } else {
        echo '<p style="text-align:center; color:#fff; margin-top:40px;">No rooms found matching your search.</p>';
    }
    exit; // Important: stop here for AJAX
}

// Normal page load
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$rooms = getRooms($conn, $search);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Rooms | Eshara Resort</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />

<!-- Bootstrap & Fonts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />

<style>
   body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(270deg, #a1c4fd, #c2e9fb, #fcb69f, #ffecd2);
    background-size: 800% 800%;
    animation: slideBg 20s ease infinite;
    min-height: 100vh;
}
.search-container {
    max-width: 900px;
    margin: 30px auto;
    display: flex;
    gap: 10px;
    justify-content: center;
    background: linear-gradient(90deg, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.7));
    padding: 15px 20px;
    border-radius: 15px;
    flex-wrap: wrap;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-top: 40px;
    font-weight: 700;
    font-size: 2.8rem;
    color: #004d40;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
}

.btn-primary {
    background-color: #ff7043;
    border-color: #ff7043;
    color: white;
}

.btn-primary:hover {
    background-color: #e64a19;
    border-color: #e64a19;
}

.btn-secondary {
    background-color: #4db6ac;
    border-color: #4db6ac;
    color: white;
}

.btn-secondary:hover {
    background-color: #00796b;
    border-color: #00796b;
}
.go-back-wrapper {
  text-align: center;
  margin-top: 20px; /* Optional spacing from top */
}

.btn-go-back {
  background-color: #ff5e57; /* Example color */
  color: #fff;
  padding: 10px 20px;
  border-radius: 8px;
  transition: background-color 0.3s ease;
  font-weight: bold;
}

.btn-go-back:hover {
  background-color: #e04841; /* Darker shade on hover */
}

#rooms-container {
    max-width: 1000px;
    margin: 40px auto 80px;
    display: flex;
    flex-direction: column;
    gap: 25px;
    background-color: rgba(255, 255, 255, 0.97);
    border-radius: 20px;
    padding: 30px 40px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.room-card-style {
    display: flex;
    border-radius: 18px;
    background: #fff8f0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    padding: 25px;
    text-decoration: none;
    color: #1a1a1a;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    overflow: hidden;
    opacity: 0;
    transform: translateY(40px);
    animation-fill-mode: forwards;
    animation-name: fadeSlideUp;
    animation-duration: 0.7s;
    animation-timing-function: ease-out;
}

.room-card-style:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 50px rgba(0, 0, 0, 0.3);
    background: #fff3e0;
}

.room-card-style img {
    width: 320px;
    height: 220px;
    object-fit: cover;
    border-radius: 14px;
    margin-right: 25px;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.room-card-style:hover img {
    transform: scale(1.05);
}

.search-container input,
.search-container button {
    flex-shrink: 0;
    white-space: nowrap;
}

.room-card-content h3 {
    font-size: 26px;
    font-weight: 700;
    color: #00796b;
    margin-bottom: 10px;
}

.room-card-price {
    font-weight: 700;
    font-size: 20px;
    color: #d84315;
}

.room-card-content ul {
    list-style: circle;
    padding-left: 20px;
    font-size: 15px;
    color: #5f6368;
}
.resort-header {
    display: flex;
    justify-content: center;
    padding: 15px 0;
    margin-bottom: 30px;
}
.resort-banner {
    display: flex;
    align-items: center;
    background: white;
    padding: 10px 25px;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
}
.resort-banner img.logo-img {
    height: 50px;
    margin-right: 15px;
}

.resort-title {
    font-size: 24px;
    color: #2e7d32;
    font-weight: 600;
    margin: 0;
}

footer {
    background-color: #004d40;
    text-align: center;
    padding: 15px 0;
    color: #e0f2f1;
    font-size: 0.9rem;
    position: fixed;
    width: 100%;
    bottom: 0;
    left: 0;
    z-index: 1000;
}



    /* Responsive */
    @media (max-width: 900px) {
        #rooms-container {
            max-width: 90%;
            padding: 20px 10px;
        }

        .room-card-style {
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .room-card-style img {
            margin-right: 0;
            margin-bottom: 15px;
            width: 100%;
            height: 200px;
        }

        .room-card-content h3 {
            font-size: 22px;
        }
    }
</style>

</head>
<body>
    
<div class="resort-header">
    <div class="resort-banner">
        <img src="images/logo.png" alt="Eshara Resort Logo" class="logo-img">
        <h1 class="resort-title">Eshara Resort</h1>
    </div>
</div>

<h2>Explore Our Rooms</h2>

<div class="search-container">
    <input
        type="text"
        id="searchInput"
        class="form-control"
        placeholder="Search by room type or amenities..."
        aria-label="Search rooms"
        value="<?php echo htmlspecialchars($search); ?>"
        style="max-width: 300px;"
    />
    <button class="btn btn-primary" id="searchBtn">Search</button>
    <button class="btn btn-secondary" id="resetBtn">Reset</button>
</div>
<div class="go-back-wrapper">
  <a href="index.php" class="btn btn-go-back">Go Back</a>
</div>


<div id="rooms-container">
    <?php if ($rooms->num_rows > 0): ?>
        <?php $index = 0; ?>
        <?php while ($room = $rooms->fetch_assoc()): ?>
            <?php $delay = $index * 0.1; ?>
            <a href="room_details.php?room_id=<?php echo urlencode($room['room_id']); ?>" class="room-card-style" style="animation-delay: <?php echo $delay; ?>s; opacity: 1; transform: none;">
                <img src="uploads/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['room_type']); ?>" loading="lazy" />
                <div class="room-card-content">
                    <div style="display:flex; justify-content: space-between; align-items: center;">
                        <h3><?php echo htmlspecialchars($room['room_type']); ?></h3>
                        <p class="room-card-price">₹<?php echo number_format($room['price'], 2); ?></p>
                    </div>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($room['description']); ?></p>
                    <p><strong>Amenities:</strong></p>
                    <ul>
                        <?php
                            $amenities = explode(',', $room['amenities']);
                            foreach ($amenities as $item) {
                                echo '<li>' . htmlspecialchars(trim($item)) . '</li>';
                            }
                        ?>
                    </ul>
                </div>
            </a>
            <?php $index++; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; color:#fff; margin-top:40px;">No rooms found.</p>
    <?php endif; ?>
</div>

<footer>
    &copy; <?php echo date("Y"); ?> Eshara Resort. All rights reserved.
</footer>

<script>
    const roomsContainer = document.getElementById('rooms-container');
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const resetBtn = document.getElementById('resetBtn');

    function loadRooms(search = '') {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'rooms.php?ajax=1&search=' + encodeURIComponent(search), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                roomsContainer.innerHTML = xhr.responseText;
            } else {
                roomsContainer.innerHTML = '<p style="text-align:center; color:#fff; margin-top:40px;">Error loading rooms.</p>';
            }
        };
        xhr.send();
    }

    searchBtn.addEventListener('click', () => {
        loadRooms(searchInput.value.trim());
    });

    resetBtn.addEventListener('click', () => {
        searchInput.value = '';
        loadRooms('');
    });

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim();
        loadRooms(query);
    });
</script>

</body>
</html>
