<?php
session_start();
require_once 'db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);

$categories = $pdo->query("SELECT category_name FROM tbl_category WHERE status = 'active'")->fetchAll();

$selected_category = isset($_GET['category']) ? $_GET['category'] : 'All Products';
if($selected_category == 'All Products'){
    $stmt = $pdo->prepare("SELECT * FROM tbl_product WHERE status = 'active' ORDER BY product_id DESC");
    $stmt->execute();
    $products = $stmt->fetchAll();
}else{
    $stmt = $pdo->prepare("SELECT p.* FROM tbl_product p JOIN tbl_category c ON p.category_id = c.category_id WHERE c.category_name = ? AND p.status = 'active' ORDER BY p.product_id DESC");
    $stmt->execute([$selected_category]);
    $products = $stmt->fetchAll();
}
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

            /* curent showing catagory */
            .current-category {
                background: #f0f0f0;
                padding: 15px 30px;
                border-bottom: 1px solid #ddd;
                display: flex;
                flex-direction: column; 
                align-items: center;   
            }
            .current-category h2 {
                color: #333;
                font-size: 24px;
            }
            .showing-catagory {
                color: #e42b2b;
                margin: 5px 0 0 0; 
            }

            /* category filter bar */
            .category-nav {
                background: white;
                padding: 10px 0;
                display: flex;
                justify-content: center;
                gap: 25px;
                flex-wrap: wrap;
                border-bottom: 1px solid #eee;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            }
            .category-nav a {
                color: #333;
                text-decoration: none;
                font-size: 14px;
                padding: 8px 16px;
                border-radius: 20px;
                transition: 0.3s;
                font-weight: bold;
            }
            .category-nav a:hover {
                background: #e42b2b;
                color: white;
            }
            .category-nav a.active-category {
                background: #e42b2b;
                color: white;
            }

            /* product listing  */
            .product-container {
                max-width: 1200px;
                margin: 40px auto;
                padding: 0 20px;
            }
            .products-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 25px;
            }
            .product-card {
                background: white;
                border-radius: 10px;
                padding: 15px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                text-align: center;
                transition: 0.3s;
            }
            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            }
            .product-card img {
                width: 100%;
                height: 150px;
                object-fit: cover;
                border-radius: 8px;
            }
            .product-card h3 {
                margin: 12px 0;
                font-size: 16px;
                color: #333;
            }
            .price {
                color: #e42b2b;
                font-size: 18px;
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
            <a href="customer_home_page.php" class="<?php echo ($current_page == 'customer_home_page.php') ? 'active' : ''; ?>">Home</a>
            <a href="customer_shop_page.php" class="<?php echo ($current_page == 'customer_shop_page.php') ? 'active' : ''; ?>">Shopping</a>
            <a href="customer_book_service_page.php" class="<?php echo ($current_page == 'customer_book_service_page.php') ? 'active' : ''; ?>">Service</a>
            <a href="contact_us.php" class="<?php echo ($current_page == 'contact_us.php') ? 'active' : ''; ?>">Contact Us</a>
            <a href="store_location.php" class="<?php echo ($current_page == 'store_location.php') ? 'active' : ''; ?>">Store Location</a>
        </div>
        
        <!-- showing current category -->
        <div class="current-category">
            <h2>Category</h2>
            <h1 class="showing-catagory"><?php echo ($selected_category); ?></h1>
        </div>

        <!-- catagory filter -->
        <div class="category-nav">
            <?php foreach($categories as $category): ?>
            <a href="customer_shop_page.php?category=<?php echo urlencode($category['category_name']); ?>"class="<?php echo ($selected_category == $category['category_name']) ? 'active-category' : ''; ?>"><?php echo $category['category_name']; ?></a>
            <?php endforeach; ?>
        </div>
    
        <!-- product listing -->
        <div class="product-container">
            <?php if(count($products) > 0): ?>
            <div class="products-grid">
                <?php foreach($products as $product): ?>
                    <a href="product_detail.php?id=<?php echo $product['product_id']; ?>" style="text-decoration: none; color: inherit;">
                        <div class="product-card">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 5px;">
                            <h3><?php echo $product['product_name']; ?></h3>
                            <p class="price">RM <?php echo number_format($product['price'], 2); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <div class="no-product">
                    <p>No products found in this category.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p>&copy; 2026 Winsoft Solution Sdn Bhd. All rights reserved.</p>
            <p>📍 17, Jalan Cempaka 1, Taman Bunga Cempaka Biru, 86400 Parit Raja, Johor</p>
            <p>📞 012-3456789 | ✉️ tiam@winsoft.com.my</p>
        </div>
    </body>
</html>