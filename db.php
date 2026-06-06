<?php
// db.php - Database connection
$host = 'localhost';
$dbname = 'winsoft';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS tbl_user (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100) UNIQUE,
        password VARCHAR(255),
        phone_number VARCHAR(20),
        address TEXT,
        role VARCHAR(20) DEFAULT 'customer',
        status VARCHAR(20) DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS tbl_category (
        category_id INT AUTO_INCREMENT PRIMARY KEY,
        category_name VARCHAR(100),
        status VARCHAR(20) DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS tbl_product (
        product_id INT AUTO_INCREMENT PRIMARY KEY,
        product_name VARCHAR(200),
        price DECIMAL(10,2),
        description TEXT,
        image VARCHAR(255),
        stock_quantity INT DEFAULT 0,
        min_stock_level INT DEFAULT 5,
        status VARCHAR(20) DEFAULT 'active',
        category_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES tbl_category(category_id) ON DELETE SET NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS tbl_order (
        order_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        total_amount DECIMAL(10,2),
        payment_status VARCHAR(20) DEFAULT 'unpaid',
        shipping_address TEXT,
        status VARCHAR(20) DEFAULT 'pending', 
        tracking_number VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES tbl_user(user_id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS tbl_payment (
        payment_id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        total_amount DECIMAL(10,2),
        transaction_id VARCHAR(100),    
        status VARCHAR(20) DEFAULT 'pending', 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES tbl_order(order_id) ON DELETE CASCADE
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS tbl_service_requests (
        request_id INT AUTO_INCREMENT PRIMARY KEY,
        customer_id INT,
        technician_id INT,
        service_type VARCHAR(50),
        service_option VARCHAR(50),
        description TEXT,
        device_type VARCHAR(50),
        device_brand VARCHAR(50),
        preferred_date DATE,
        preferred_time TIME,
        address TEXT,
        status VARCHAR(50) DEFAULT 'pending',
        quotation DECIMAL(10,2),
        final_price DECIMAL(10,2),
        technician_notes TEXT,
        request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        completed_date DATETIME,
        FOREIGN KEY (customer_id) REFERENCES tbl_user(user_id) ON DELETE CASCADE,
        FOREIGN KEY (technician_id) REFERENCES tbl_user(user_id) ON DELETE SET NULL
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS tbl_website_page (
        page_id INT AUTO_INCREMENT PRIMARY KEY,
        page_name VARCHAR(100),
        page_path VARCHAR(255),
        status VARCHAR(20) DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    $hashsa = hash('sha256', 'superadmin');
    $hashcust = hash('sha256', 'customer');
    // Insert sample data
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_user WHERE role = 'admin'");
    $stmt->execute();
    if($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO tbl_user (name, email, password, role) VALUES 
            ('Super Admin', 'superadmin@gmail.com', '$hashsa', 'admin'),  
            ('Customer User', 'customer@gmail.com', '$hashcust', 'customer')");
    }
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_product");
    $stmt->execute();
    if($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO tbl_product (product_name, price, description, image, stock_quantity, min_stock_level) VALUES 
            ('Gaming Mouse', 89.00, 'High performance gaming mouse with RGB lighting', '/img/mouse.jpg', 20, 5),
            ('Mechanical Keyboard', 150.00, 'RGB mechanical keyboard with blue switches', '/img/keyboard.png', 25, 5),
            ('24 Inch Monitor', 450.00, 'Full HD IPS monitor', '/img/monitor.png', 10, 5),
            ('Laptop Stand', 45.00, 'Aluminum adjustable laptop stand', '/img/laptop.png', 5, 5)");
    }
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>