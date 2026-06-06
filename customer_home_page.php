<?php
// index.php - Default homepage
session_start();
require_once 'db.php';

// Check if user is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

// Get products for public view
$products = $pdo->query("SELECT * FROM tbl_product LIMIT 4")->fetchAll();

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Winsoft Solution</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f8f9fa;
        }

        /* top nav bar */
        .navbar {
            background: white;
            padding: 12px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar img {
            height: 35px;
        }
        .navbar a {
            text-decoration: none;
            margin-left: 20px;
        }

        /* page nav bar */
        .second-nav {
            background: #e42b2b;
            padding: 12px 0;
            display: flex;
            justify-content: center;
            gap: 5%;
        }
        .second-nav a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            padding: 5px 15px;
        }
        .second-nav a:hover {
            background: white;
            color: #e42b2b;
            border-radius: 5px;
        }
        .second-nav a.active {
            background: white;
            color: #e42b2b;
            border-radius: 5px;
            padding: 5px 15px;
            font-weight: bold;
            transform: scale(1.3);
        }

        /* banner */
        .banner {
            width: 100%;
            overflow: hidden;
        }
        .banner img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* category and top product section */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        .products {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        .product-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .product-card h3 {
            margin: 0 0 10px;
        }
        .price {
            color: #28a745;
            font-size: 20px;
            font-weight: bold;
        }

        /* footer */
        .footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <!-- top nav bar -->
    <div class="navbar">
        <a href="index.php"><img src="img/winsoftlogo.png" alt="Winsoft Logo""></a>
        <div>
            <a href="cart.php"><i class="fas fa-shopping-cart" style="font-size: 24px; color: #333;"></i></a>
            <a href="profile.php"><i class="fas fa-user" style="font-size: 24px; color: #333;"></i></a>
            <a href="logout.php"><i class="fas fa-sign-out-alt" style="font-size: 24px; color: #333;"></i></a>
        </div>
    </div>

    <!-- page nav bar -->
    <div class="second-nav">
        <a href="index.php" class="<?php echo ($current_page == 'customer_home_page.php') ? 'active' : ''; ?>">Home</a>
        <a href="customer_shop_page.php" class="<?php echo ($current_page == 'customer_shop_page.php') ? 'active' : ''; ?>">Shopping</a>
        <a href="service.php" class="<?php echo ($current_page == 'service.php') ? 'active' : ''; ?>">Service</a>
        <a href="contact_us.php" class="<?php echo ($current_page == 'contact_us.php') ? 'active' : ''; ?>">Contact Us</a>
        <a href="store_location.php" class="<?php echo ($current_page == 'store_location.php') ? 'active' : ''; ?>">Store Location</a>
    </div>
    
    <!-- banner -->
    <div class="banner">
        <img src="img/banner.jpg" alt="Winsoft Banner">
    </div>
    
    <div class="container">
        
        <h2>🔥 Top Products</h2>
        <div class="products">
            <?php foreach($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>" style="width:100%; height:150px; object-fit:cover; border-radius:5px;">
                    <h3><?php echo $product['product_name']; ?></h3>
                    <p class="price">RM <?php echo number_format($product['price'], 2); ?></p>
                    <p><?php echo substr($product['description'], 0, 60); ?>...</p>
                    <a href="login.php" class="btn">Login to Buy</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; 2026 Winsoft Solution Sdn Bhd. All rights reserved.</p>
        <p>📍 17, Jalan Cempaka 1, Taman Bunga Cempaka Biru, 86400 Parit Raja, Johor</p>
        <p>📞 012-3456789 | ✉️ info@winsoft.com</p>
    </div>
</body>
</html>