<?php
// admin_home.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

$stmt = $pdo->prepare("SELECT name FROM tbl_user WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Get dashboard statistics
$totalUsers = $pdo->query("SELECT COUNT(*) FROM tbl_user")->fetchColumn();
$totalProducts = $pdo->query("SELECT COUNT(*) FROM tbl_product")->fetchColumn();
$totalServices = $pdo->query("SELECT COUNT(*) FROM tbl_service_requests")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM tbl_order")->fetchColumn();

// Get recent service requests
$recentServices = $pdo->query("SELECT * FROM tbl_service_requests ORDER BY request_date DESC LIMIT 5")->fetchAll();

// Get recent users
$recentUsers = $pdo->query("SELECT * FROM tbl_user ORDER BY created_at DESC LIMIT 5")->fetchAll();
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
        
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 48px;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            top: 20px;
        }
        
        .welcome-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            color: white;
        }
        
        .table-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
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
                    <i class="bi bi-shop"></i> Winsoft<br>
                    <small class="fs-6">Admin Panel</small>
                </h4>
                <nav class="nav flex-column">
                    <a href="admin_home.php" class="nav-link active">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="superadmin_add_user_page.php" class="nav-link">
                        <i class="bi bi-people"></i> Users Management
                    </a>
                    <a href="superadmin_add_product_page.php" class="nav-link">
                        <i class="bi bi-box-seam"></i> Products
                    </a>
                    <a href="superadmin_view_order_page.php" class="nav-link">
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
            <!-- Welcome Banner -->
            <div class="welcome-banner p-4 mb-4">
                <h2><i class="bi bi-person-circle"></i> Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>            </div>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card stat-card bg-primary text-white">
                        <div class="card-body position-relative">
                            <h5 class="card-title">Total Users</h5>
                            <h2 class="mb-0"><?php echo $totalUsers; ?></h2>
                            <i class="bi bi-people stat-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card stat-card bg-success text-white">
                        <div class="card-body position-relative">
                            <h5 class="card-title">Total Products</h5>
                            <h2 class="mb-0"><?php echo $totalProducts; ?></h2>
                            <i class="bi bi-box-seam stat-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card stat-card bg-warning text-dark">
                        <div class="card-body position-relative">
                            <h5 class="card-title">Service Requests</h5>
                            <h2 class="mb-0"><?php echo $totalServices; ?></h2>
                            <i class="bi bi-tools stat-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card stat-card bg-info text-white">
                        <div class="card-body position-relative">
                            <h5 class="card-title">Total Orders</h5>
                            <h2 class="mb-0"><?php echo $totalOrders; ?></h2>
                            <i class="bi bi-cart stat-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card table-card">
                        <div class="card-header bg-white fw-bold">
                            <i class="bi bi-lightning-charge"></i> Quick Actions
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <a href="superadmin_web_page_list.php" class="btn btn-outline-primary w-100 py-3">
                                        <i class="bi bi-pencil-square fs-4 d-block"></i>
                                        Edit Website
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="superadmin_add_product_page.php" class="btn btn-outline-success w-100 py-3">
                                        <i class="bi bi-plus-circle fs-4 d-block"></i>
                                        Add Product
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="superadmin_add_user_page.php" class="btn btn-outline-warning w-100 py-3">
                                        <i class="bi bi-person-plus fs-4 d-block"></i>
                                        Add Staff
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="superadmin_assign_service_request_page.php" class="btn btn-outline-info w-100 py-3">
                                        <i class="bi bi-file-text fs-4 d-block"></i>
                                        Assign Technician
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Recent Service Requests -->
                <div class="col-md-6 mb-4">
                    <div class="card table-card h-100">
                        <div class="card-header bg-white fw-bold">
                            <i class="bi bi-tools"></i> Recent Service Requests
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($recentServices) > 0): ?>
                                            <?php foreach($recentServices as $service): ?>
                                            <tr>
                                                <td>#<?php echo $service['request_id']; ?></td>
                                                <td><?php echo $service['service_type']; ?></td>
                                                <td>
                                                    <?php 
                                                    $statusClass = '';
                                                    if($service['status'] == 'pending') $statusClass = 'bg-warning';
                                                    elseif($service['status'] == 'in-progress') $statusClass = 'bg-info';
                                                    elseif($service['status'] == 'completed') $statusClass = 'bg-success';
                                                    else $statusClass = 'bg-secondary';
                                                    ?>
                                                    <span class="badge <?php echo $statusClass; ?>"><?php echo $service['status']; ?></span>
                                                </td>
                                                <td><?php echo date('d/m/Y', strtotime($service['request_date'])); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No service requests</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="#" class="text-decoration-none">View all requests →</a>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Users -->
                <div class="col-md-6 mb-4">
                    <div class="card table-card h-100">
                        <div class="card-header bg-white fw-bold">
                            <i class="bi bi-people"></i> Recent Users
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($recentUsers) > 0): ?>
                                            <?php foreach($recentUsers as $user): ?>
                                            <tr>
                                                <td><?php echo $user['name']; ?></td>
                                                <td><?php echo $user['email']; ?></td>
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo $user['role']; ?></span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center">No users found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="#" class="text-decoration-none">View all users →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>