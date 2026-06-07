<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

$stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$showPopup = false;
$requestId = '';

if(isset($_POST['book'])) {
    $service_type = $_POST['service_type'];
    $service_option = $_POST['service_option'];
    $device_brand = $_POST['device_brand'];
    $problem_description = $_POST['problem_description'];
    $preferred_date = $_POST['preferred_date'];
    $preferred_time = $_POST['preferred_time'];
    $address = $_POST['address'];
    
    $stmt = $pdo->prepare("INSERT INTO tbl_service_requests (customer_id, service_type, service_option, description, device_brand, preferred_date, preferred_time, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $service_type, $service_option, $problem_description, $device_brand, $preferred_date, $preferred_time, $address]);
    
    $requestId = $pdo->lastInsertId();
    $showPopup = true;
}

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
            background: #ff4800;
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

        /* book service container */
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input:disabled {
            background: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }
        button {
            background: #28a745;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            width: 100%;
            cursor: pointer;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        /* success popup dialog */
        .success-dialog {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            justify-content: center;
            align-items: center;
        }
        .success-container {
            background: white;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            text-align: center;
            animation: popupFadeIn 0.3s ease;
        }
        .success-container-header {
            background: #28a745;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            color: white;
        }
        .success-container-header h3 {
            margin: 0;
            font-size: 24px;
        }
        .success-container-body {
            padding: 30px;
        }
        .success-container-body p {
            margin: 10px 0;
            font-size: 16px;
            color: #333;
        }
        .success-container-body .request-id {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin: 15px 0;
        }
        .success-container-footer {
            padding: 20px;
            border-top: 1px solid #eee;
        }
        .success-container-footer button {
            background: #007bff;
            width: auto;
            padding: 10px 30px;
            margin: 0;
        }
        @keyframes popupFadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
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
        <a href="customer_book_service_page.php" class="<?php echo ($current_page == 'customer_book_service_page.php') ? 'active' : ''; ?>">Service</a>
        <a href="contact_us.php" class="<?php echo ($current_page == 'contact_us.php') ? 'active' : ''; ?>">Contact Us</a>
        <a href="store_location.php" class="<?php echo ($current_page == 'store_location.php') ? 'active' : ''; ?>">Store Location</a>
    </div>
    
    <!-- bookng form -->
    <div class="container">
        <h2>🔧 Book Repair Service</h2>
        
        <form method="post">
            <label>Name:</label>
            <input type="text" value="<?php echo htmlspecialchars($user['name']); ?>" disabled>

            <label>Email:</label>
            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>

            <label>Phone:</label>
            <input type="text" value="<?php echo htmlspecialchars($user['phone_number']); ?>" disabled>

            <label>Service Type:</label>
            <select name="service_type" required>
                <option value="" disabled selected>Please Select Your Service Type</option>
                <option value="Computer Repair">Computer Repair</option>
                <option value="Laptop Repair">Laptop Repair</option>
                <option value="Server Repair">Server Repair</option>
                <option value="Network Installation">Network Installation</option>
            </select>
            
            <label>Service Option:</label>
            <select name="service_option" required id="service_option">
                <option value="" disabled selected>Please Select Your Service Option</option>
                <option value="walk-in">Walk-in (Visit Our Store)</option>
                <option value="door-to-door">Door-to-Door (Technician Visit You)</option>
            </select>
            
            <label id="address_label" style="display:none">Address:</label>
            <textarea name="address" id="address" style="display:none" rows="3" placeholder="Enter your full address"></textarea>
            
            <label>Your Device Brand:</label>
            <select name="device_brand" required>
                <option value="" disabled selected>Please Select Your Device Brand</option>
                <option value="Apple">Apple</option>
                <option value="Dell">Dell</option>
                <option value="HP">HP</option>
                <option value="Lenovo">Lenovo</option>
                <option value="Samsung">Samsung</option>
                <option value="Others">Others</option>
            </select>

            <label>Problem Description:</label>
            <textarea name="problem_description" rows="4" required placeholder="Describe your problem..."></textarea>
            
            <label>Preferred Date:</label>
            <input type="date" name="preferred_date" required>

            <label>Preferred Time:</label>
            <input type="time" name="preferred_time" required>
            
            <button type="submit" name="book">Submit Request</button>
        </form>
    </div>

    <!-- success popup dialog -->
    <div id="success-dialog" class="success-dialog">
        <div class="success-container">
            <div class="success-container-header">
                <h3>Service Request Submitted!</h3>
            </div>
            <div class="success-container-body">
                <div class="request-id">
                    Request ID: <?php echo $requestId; ?>
                </div>
                <p>Our technician will contact you shortly to confirm the details.</p>
            </div>
            <div class="success-container-footer">
                <button onclick="closePopupAndRedirect()">OK</button>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 Winsoft Solution Sdn Bhd. All rights reserved.</p>
        <p>📍 17, Jalan Cempaka 1, Taman Bunga Cempaka Biru, 86400 Parit Raja, Johor</p>
        <p>📞 012-3456789 | ✉️ tiam@winsoft.com.my</p>
    </div>
    
    <script>
        const optionSelect = document.getElementById('service_option');
        const addressLabel = document.getElementById('address_label');
        const addressField = document.getElementById('address');
        
        // set address field visibility
        optionSelect.addEventListener('change', function() {
            if(this.value === 'door-to-door') {
                addressLabel.style.display = 'block';
                addressField.style.display = 'block';
                addressField.required = true;
            } else {
                addressLabel.style.display = 'none';
                addressField.style.display = 'none';
                addressField.required = false;
            }
        });

        // get today date and set as min for date input
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[type="date"]').setAttribute('min', today);
        
        // control popup dialog visibility
        <?php if($showPopup): ?>
            document.getElementById('success-dialog').style.display = 'flex';
        <?php endif; ?>
        
        // control popup dialog visibility and redirect to home page
        function closePopupAndRedirect() {
            document.getElementById('success-dialog').style.display = 'none';
            window.location.href = 'customer_home_page.php';
        }
        
        // control form resubmission when page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>
</html>