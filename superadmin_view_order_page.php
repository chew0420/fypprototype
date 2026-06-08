<?php
// admin_home.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Winsoft Solution</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* Make sidebar fixed position */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            overflow-y: auto;
        }
        .sidebar img {
            height: 35px;
        }
        
        /* Main content area - push to the right of sidebar */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 10px;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        /* Responsive for mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar - fixed position -->
        <div class="sidebar">
            <div class="p-3">
                <h4 class="text-white text-center py-3 mb-4">
                    <img src="img/winsoftlogo.png" alt="Winsoft Logo"> Winsoft<br>
                    <small class="fs-6">Admin Panel</small>
                </h4>
                <nav class="nav flex-column">
                    <a href="superadmin_home_page.php" class="nav-link">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="superadmin_add_user_page.php" class="nav-link">
                        <i class="bi bi-people"></i> Users Management
                    </a>
                    <a href="superadmin_add_product_page.php" class="nav-link">
                        <i class="bi bi-box-seam"></i> Products
                    </a>
                    <a href="superadmin_view_order_page.php" class="nav-link active">
                        <i class="bi bi-cart"></i> Orders
                    </a>
                    <a href="superadmin_assign_service_request_page.php" class="nav-link">
                        <i class="bi bi-tools"></i> Service Requests
                    </a>
                    <a href="superadmin_web_page_list.php" class="nav-link">
                        <i class="bi bi-pencil-square"></i> Website Page
                    </a>
                    <hr class="bg-light">
                    <a href="logout.php" class="nav-link">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Main Content - scrollable -->
        <div class="main-content p-4" style="width: 100%;">
            
            
            
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>