<?php
// login.php
session_start();
require_once 'db.php';

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']);
    
    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();
    
    if($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        
        if($user['role'] == 'superadmin') {
            header("Location: superadmin_home.php");
        } elseif($user['role'] == 'staff') {
            header("Location: staff_home.php");
        } elseif($user['role'] == 'technician') {
            header("Location: technician_home.php");
        } else {
            header("Location: customer_home_page.php");
        }
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Winsoft Solution</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #e42b2b;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        /* Main container - split into two columns */
        .main-container {
            display: flex;
            width: 1000px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        /* Left side - Branding section */
        .brand-section {
            flex: 1;
            background: white;
            padding: 50px 30px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .brand-section img {
            max-width: 180px;
            margin-bottom: 20px;
        }
        
        .brand-section h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }
        
        .brand-section p {
            font-size: 16px;
            color: #666;
        }
        
        /* Right side - Login form section */
        .form-section {
            flex: 1;
            background: #f8f9fa;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-section h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        
        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
            font-weight: 500;
        }
        
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }
        
        input:focus {
            outline: none;
            border-color: #007bff;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            margin-top: 20px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        
        button:hover {
            background: #0056b3;
        }
        
        .error {
            color: red;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .register-link a {
            color: #007bff;
            text-decoration: none;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        /* Top navbar */
        .navbar {
            background: white;
            padding: 12px 30px;
            display: flex;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            gap: 20px;
        }
        
        .navbar img {
            height: 35px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php"><img src="/img/winsoftlogo.png" alt="Winsoft Logo"></a>
        <p style="font-size: 25px; font-weight: bold;">Login</p>
    </div>

    <div class="main-container">
        <div class="brand-section">
            <img src="/img/winsoftlogo.png" alt="Winsoft Logo">
            <h2>Winsoft Solution</h2>
            <h1>The Leading Online IT Store</h1>
        </div>
        
        <div class="form-section">
            <h2>Login</h2>
            <form method="post">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="Enter your email">
                
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter your password">
                
                <button type="submit" name="login">Login</button>
            </form>
            <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
            <div class="register-link">
                <a href="register.php">Don't have an account? Register</a>
            </div>
        </div>
    </div>
</body>
</html>