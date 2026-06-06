<?php
// register.php
session_start();
require_once 'db.php';

if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = hash('sha256',$_POST['password']);
    $phone = $_POST['phone'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO tbl_user (name, email, password, phone_number, role) VALUES (?, ?, ?, ?, 'customer')");
        $stmt->execute([$name, $email, $password, $phone]);
        header("Location: login.php");
        exit();
    } catch(PDOException $e) {
        $error = "Email already exists!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Winsoft Solution</title>
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

        .main-container {
            display: flex;
            width: 1000px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-top: 80px;
            margin-bottom: 80px;
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
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php"><img src="/img/winsoftlogo.png" alt="Winsoft Logo"></a>
        <p style="font-size: 25px; font-weight: bold;">Sign Up</p>
    </div>

    <div class="main-container">
        <div class="brand-section">
            <img src="/img/winsoftlogo.png" alt="Winsoft Logo">
            <h2>Winsoft Solution</h2>
            <h1>The Leading Online IT Store</h1>
        </div>

        <div class="form-section">
            <h2>Register</h2>
            <form method="post">
                <label>Full Name:</label>
                <input type="text" name="name" required placeholder="Enter your full name">
                <label>Email:</label>
                <input type="email" name="email" required placeholder="Enter your email">
                <label>Password:</label>
                <input type="password" name="password" required placeholder="Enter your password">
                <label>Phone Number:</label>
                <input type="text" name="phone" placeholder="Enter your phone number">
                <button type="submit" name="register">Register</button>
            </form>
            <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
            <div class="login-link">
                <a href="login.php">Already have an account? Login</a>
            </div>
        </div>
    </div>
</body>
</html>