CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (full_name, email, password, created_at) 
VALUES ('Admin User', 'admin@example.com', 'admin123', '2025-05-16 14:14:45');

CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    room_id INT,
    check_in DATE,
    check_out DATE,
    guests INT,
    total_price DECIMAL(10,2),
    payment_method VARCHAR(20),
    status VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (room_id) REFERENCES rooms(room_id)
);

CREATE TABLE booking_packages (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    room_id INT,
    room_type VARCHAR(100),
    nights INT,
    checkin DATE,
    checkout DATE,
    room_total DECIMAL(10,2),
    travel_expenses DECIMAL(10,2),
    activity_total DECIMAL(10,2),
    food_total DECIMAL(10,2),
    total_cost DECIMAL(10,2),
    activities TEXT,
    food TEXT,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_method VARCHAR(50),
    card_name VARCHAR(100),
    card_number VARCHAR(20),
    cvv VARCHAR(10),
    valid_from DATE,
    valid_till DATE,
    
    -- Optional foreign keys (uncomment if needed and ensure the tables exist)
    -- FOREIGN KEY (user_id) REFERENCES users(user_id),
    -- FOREIGN KEY (room_id) REFERENCES rooms(room_id)
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE offers (
    offer_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150),
    description TEXT,
    discount_percent DECIMAL(5,2),
    image_url VARCHAR(255),
    valid_from DATE,
    valid_until DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(room_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_type VARCHAR(50),
    price DECIMAL(10,2),
    description TEXT,
    amenities TEXT,
    image VARCHAR(100),
    max_people INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    no_of_beds INT,
    availability INT
);

INSERT INTO rooms (room_type, price, description, amenities, image, max_people, created_at, no_of_beds, availability)
VALUES (
    'Bungalow',
    8000.00,
    'Private bungalow with lush garden surroundings.',
    'WiFi, TV, AC, Kitchen, Private Lawn',
    'bungalow.jpg',
    6,
    '2025-05-16 14:19:55',
    3,
    10
);

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    phone VARCHAR(15),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    gender ENUM('Male', 'Female', 'Other'),
    status ENUM('active', 'banned') DEFAULT 'active'
);
