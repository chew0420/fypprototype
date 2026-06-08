<?php
// admin_home.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

// get page from url
$page_id = isset($_GET['page_id']) ? $_GET['page_id'] : 1;

$stmt = $pdo->prepare("SELECT * FROM tbl_website_page WHERE page_id = ?");
$stmt->execute([$page_id]);
$page = $stmt->fetch();

$fileContent = file_get_contents($page['page_path']);

$pattern = '/<(h[1-5]|p|label|a|span|td|[a-zA-Z]+[^>]*\bword\b[^>]*)([^>]*)>/';

$editableContent = preg_replace($pattern, '<$1$2 contenteditable="true">', $fileContent);

$editableContent = preg_replace('/<a[^>]*><img([^>]*)><\/a>/', '<img$1>', $editableContent);

$editableContent = str_replace('fixed-top', '', $editableContent);

$search = '/(<table[^>]*id="acceptancLetterDetailsTable"[^>]*)([^>]*contenteditable="true"[^>]*)(>.*?<\/table>)/s';

$editableContent = preg_replace_callback($search, function($matches) {
    $tableHtml = $matches[1] . str_replace(' contenteditable="true"', '', $matches[2]) . $matches[3];
    $tableHtml = str_replace(' contenteditable="true"', '', $tableHtml);
    return $tableHtml;
}, $editableContent);

$search = '/(<div[^>]*class="acceptanceLetter"[^>]*)([^>]*)contenteditable="true"([^>]*>.*?<\/div>)/s';

$editableContent = preg_replace_callback($search, function($matches) {
    $divHtml = $matches[1] . $matches[2] . $matches[3];
    return $divHtml;
}, $editableContent);

$search = '/(<div[^>]*id="editContainer"[^>]*)([^>]*)contenteditable="true"([^>]*>.*?<\/div>)/s';

$editableContent = preg_replace_callback($search, function($matches) {
    $divHtml = $matches[1] . $matches[2] . $matches[3];
    return $divHtml;
}, $editableContent);

$search = '/(<div[^>]*)([^>]*)contenteditable="true"([^>]*>.*?<\/div>)/s';

$editableContent = preg_replace_callback($search, function($matches) {
    $divHtml = $matches[1] . $matches[2] . $matches[3];
    return $divHtml;
}, $editableContent);

$search = '/(<div[^>]*)([^>]*)style="[^"]*"[^>]*contenteditable="true"([^>]*>.*?<\/div>)/s';

$editableContent = preg_replace_callback($search, function($matches) {
    $divHtml = $matches[1] . $matches[2] . $matches[3];
    return $divHtml;
}, $editableContent);

$search = '/(<main[^>]*)([^>]*)contenteditable="true"([^>]*>.*?<\/main>)/s';

$editableContent = preg_replace_callback($search, function($matches) {
    $divHtml = $matches[1] . $matches[2] . $matches[3];
    return $divHtml;
}, $editableContent);
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

        .welcome-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            color: white;
        }

        /* load page */
        .editor-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .editor-toolbar {
            background: #f8f9fa;
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .editor-content {
            padding: 30px;
            min-height: 500px;
        }
        
        [contenteditable="true"] {
            padding: 2px 5px;
            border-radius: 4px;
            cursor: text;
            transition: background 0.2s;
        }
        
        [contenteditable="true"]:hover {
            background: #ffe69e;
        }
        
        [contenteditable="true"]:focus {
            background: #ffdf7e;
            outline: none;
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
                    <a href="superadmin_view_order_page.php" class="nav-link">
                        <i class="bi bi-cart"></i> Orders
                    </a>
                    <a href="superadmin_assign_service_request_page.php" class="nav-link">
                        <i class="bi bi-tools"></i> Service Requests
                    </a>
                    <a href="superadmin_web_page_list.php" class="nav-link active">
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
            <div class="mb-3">
                <a href="superadmin_web_page_list.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Page List
                </a>
            </div>

            <div class="welcome-banner p-4 mb-4">
                <h2><i class="bi bi-pencil-square"></i> Editing: <?php echo htmlspecialchars($page['page_name']); ?></h2>
                <p class="mb-0">Last updated: <?php echo $page['updated_at']; ?></p>
            </div>

            <?php if(isset($_GET['saved'])): ?>
                <div class="success-message">✅ Page updated successfully!</div>
            <?php endif; ?>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                <div id="editContainer" contenteditable="true">
                    <?php echo $editableContent; ?>
                </div>
                <p style="margin: auto; margin-top: 10px;">Click on any text to edit. Click on any image to change it.</p>
                <button id="saveChanges" type="submit" onclick="return confirm('Are you sure you want to save changes?')">💾 Save Changes</button>
            </form>
            
        </div>
    </div>
    <script>
        var form = document.getElementById('editForm');
        // var editContainer = document.getElementById('editContainer');
        var divContainer = document.querySelector('div');
        var image = document.querySelector('img');
        var currentImageElement = null;
        var fileInput = null;
        var originalImage = image;

        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            var image = document.querySelector('img');
            if(image){
            image.parentNode.replaceChild(originalImage, image);
            }
            // Update the value of the editableContent input field with the HTML content
            var updatedContent = divContainer.innerHTML;
            var updatedContentInput = document.createElement('input');

            updatedContentInput.setAttribute('type', 'hidden');
            updatedContentInput.setAttribute('name', 'updatedContent');
            updatedContentInput.value = updatedContent;
            form.appendChild(updatedContentInput);
            form.submit();
            // Submit the form asynchronously using AJAX
            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.onload = function() {
            if (xhr.status === 200) {
                // Success, do something if needed
            } else {
                // Error, handle accordingly
            }
            };
            xhr.onerror = function() {
            // Error, handle accordingly
            };
            xhr.send(formData);
        });

        image.addEventListener('click', function() {
            // Remove the existing file input if it exists
            if (fileInput) {
            fileInput.remove();
            }

            // Show file upload dialog
            fileInput = document.createElement('input');
            fileInput.setAttribute('type', 'file');
            fileInput.setAttribute('name', 'images');
            fileInput.style.display = 'none';
            form.appendChild(fileInput);

            fileInput.click();

            fileInput.addEventListener('change', function(event) {
            var file = event.target.files[0];

            // Remove the error message if it exists
            var errorSpan = form.querySelector('p[style="color: red;"]');
            if (errorSpan) {
                errorSpan.remove();
            }

            // Create a new FileReader instance
            var reader = new FileReader();

            // When the file has been read successfully
            reader.onload = function(e) {
                // Create a new image element
                var newImage = document.createElement('img');
                newImage.classList.add('img-fluid', 'rounded-3', 'my-5');

                // Set the source of the new image to the file URL
                newImage.src = e.target.result;

                // Replace the clicked image with the new image
                image.parentNode.replaceChild(newImage, image);
                console.log(newImage);
                // Update the file input with the uploaded file
                fileInput = file;
            };

            // Read the file as a data URL
            reader.readAsDataURL(file);
            });
        });
        
        // Handle image click - exactly like your Laravel version
        // editContainer.addEventListener('click', function(event) {
        //     if(event.target.tagName === 'img') {
        //         event.preventDefault();
        //         currentImageElement = event.target;
                
        //         // Remove existing file input if any
        //         if(fileInput) {
        //             fileInput.remove();
        //         }
                
        //         // Create file input
        //         fileInput = document.createElement('input');
        //         fileInput.setAttribute('type', 'file');
        //         fileInput.setAttribute('accept', 'image/*');
        //         fileInput.style.display = 'none';
        //         form.appendChild(fileInput);
                
        //         fileInput.click();
                
        //         fileInput.addEventListener('change', function(e) {
        //             var file = e.target.files[0];
                    
        //             if(file) {
        //                 // Check file size (max 2MB)
        //                 if(file.size > 2 * 1024 * 1024) {
        //                     alert('File size exceeds 2MB. Please select a smaller image.');
        //                     return;
        //                 }
                        
        //                 var reader = new FileReader();
                        
        //                 reader.onload = function(e) {
        //                     // Create new image element
        //                     var newImage = document.createElement('img');
        //                     newImage.src = e.target.result;
        //                     newImage.style.maxWidth = '100%';
        //                     newImage.style.height = 'auto';
                            
        //                     // Replace old image with new image
        //                     if(currentImageElement && currentImageElement.parentNode) {
        //                         currentImageElement.parentNode.replaceChild(newImage, currentImageElement);
        //                         currentImageElement = null;
        //                     }
        //                 };
                        
        //                 reader.readAsDataURL(file);
        //             }
        //         });
        //     }
        // });
        
        // Handle form submission
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            var updatedContent = editContainer.innerHTML;
            
            // Create form data
            var formData = new FormData();
            formData.append('page_id', '<?php echo $page['page_id']; ?>');
            formData.append('file_path', '<?php echo $page['page_path']; ?>');
            formData.append('updatedContent', updatedContent);
            
            // Send AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'save_editor_ajax.php', true);
            xhr.onload = function() {
                if(xhr.status === 200) {
                    window.location.href = 'web_editor.php?page_id=<?php echo $page['page_id']; ?>&saved=1';
                } else {
                    alert('Error saving changes. Please try again.');
                }
            };
            xhr.onerror = function() {
                alert('Error saving changes. Please try again.');
            };
            xhr.send(formData);
        });
    </script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>