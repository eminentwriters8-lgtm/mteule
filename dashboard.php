<?php
require_once 'config.php';
requireLogin();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Handle image upload
$uploadMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    
    // Validate file
    if ($file['error'] !== UPLOAD_OK) {
        $uploadMessage = 'File upload error: ' . $file['error'];
    } elseif ($file['size'] > MAX_FILE_SIZE) {
        $uploadMessage = 'File too large. Maximum size is 5MB.';
    } elseif (!in_array($file['type'], ALLOWED_TYPES)) {
        $uploadMessage = 'Invalid file type. Allowed: JPEG, PNG, GIF, WebP.';
    } else {
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $destination = UPLOAD_DIR . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $uploadMessage = 'Image uploaded successfully!';
            
            // Save to database if needed
            $conn = getDBConnection();
            $stmt = $conn->prepare("INSERT INTO uploaded_images (filename, original_name, uploaded_at) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $filename, $file['name']);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        } else {
            $uploadMessage = 'Failed to save file.';
        }
    }
}

// Get uploaded images
$images = [];
$conn = getDBConnection();
$result = $conn->query("SELECT * FROM uploaded_images ORDER BY uploaded_at DESC");
if ($result) {
    $images = $result->fetch_all(MYSQLI_ASSOC);
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MTEULE Ventures</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container {
            min-height: 100vh;
            background: #f5f7fa;
        }
        
        .admin-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px 0;
        }
        
        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .admin-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .admin-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .admin-main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            color: #333;
        }
        
        .card-header i {
            color: #2563eb;
            font-size: 20px;
        }
        
        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: border 0.3s;
        }
        
        .upload-area:hover {
            border-color: #2563eb;
        }
        
        .upload-area i {
            font-size: 48px;
            color: #2563eb;
            margin-bottom: 15px;
        }
        
        #imagePreview {
            max-width: 200px;
            max-height: 200px;
            margin: 20px auto;
            display: none;
        }
        
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .image-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .image-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }
        
        .image-info {
            padding: 10px;
            background: white;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
        }
        
        .btn-danger {
            background: #dc2626;
            color: white;
        }
        
        .btn-danger:hover {
            background: #b91c1c;
        }
        
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .alert-error {
            background: #fee;
            color: #c00;
            border: 1px solid #fcc;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <nav class="admin-nav">
                <a href="index.html" class="admin-logo">
                    <div class="logo-icon">M</div>
                    <span>MTEULE <strong>Ventures</strong></span>
                </a>
                
                <div class="admin-user">
                    <span>Welcome, <?php echo $_SESSION['admin_username']; ?></span>
                    <a href="?logout=true" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </nav>
        </header>
        
        <main class="admin-main">
            <?php if ($uploadMessage): ?>
                <div class="alert <?php echo strpos($uploadMessage, 'successfully') !== false ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo $uploadMessage; ?>
                </div>
            <?php endif; ?>
            
            <div class="dashboard-grid">
                <!-- Upload Card -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h3>Upload Images</h3>
                    </div>
                    
                    <form method="POST" enctype="multipart/form-data" id="uploadForm">
                        <div class="upload-area" onclick="document.getElementById('imageInput').click()">
                            <i class="fas fa-file-upload"></i>
                            <h4>Click to upload or drag & drop</h4>
                            <p>PNG, JPG, GIF, WebP up to 5MB</p>
                            <img id="imagePreview" src="" alt="Preview">
                        </div>
                        
                        <input type="file" id="imageInput" name="image" accept="image/*" style="display: none;" 
                               onchange="previewImage(this)">
                        
                        <div style="margin-top: 20px;">
                            <input type="text" name="image_name" placeholder="Image description (optional)" 
                                   style="width: 100%; padding: 10px; margin-bottom: 10px;">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                <i class="fas fa-upload"></i> Upload Image
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Manage Content Card -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-edit"></i>
                        <h3>Manage Website Content</h3>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="edit_home.php" class="btn" style="background: #f3f4f6; text-align: left;">
                            <i class="fas fa-home"></i> Edit Home Page
                        </a>
                        <a href="edit_services.php" class="btn" style="background: #f3f4f6; text-align: left;">
                            <i class="fas fa-concierge-bell"></i> Edit Services
                        </a>
                        <a href="edit_about.php" class="btn" style="background: #f3f4f6; text-align: left;">
                            <i class="fas fa-info-circle"></i> Edit About Page
                        </a>
                        <a href="edit_contact.php" class="btn" style="background: #f3f4f6; text-align: left;">
                            <i class="fas fa-envelope"></i> Edit Contact Info
                        </a>
                    </div>
                </div>
                
                <!-- Statistics Card -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <i class="fas fa-chart-bar"></i>
                        <h3>Website Statistics</h3>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div style="text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #2563eb;"><?php echo count($images); ?></div>
                            <div style="color: #666; font-size: 14px;">Uploaded Images</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: #10b981;">5</div>
                            <div style="color: #666; font-size: 14px;">Website Pages</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Uploaded Images -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-images"></i>
                    <h3>Uploaded Images</h3>
                </div>
                
                <?php if (empty($images)): ?>
                    <p style="text-align: center; color: #666;">No images uploaded yet.</p>
                <?php else: ?>
                    <div class="image-grid">
                        <?php foreach ($images as $image): ?>
                            <div class="image-item">
                                <img src="uploads/<?php echo htmlspecialchars($image['filename']); ?>" 
                                     alt="<?php echo htmlspecialchars($image['original_name']); ?>">
                                <div class="image-info">
                                    <p style="font-size: 12px; color: #666; margin: 0;">
                                        <?php echo date('M d, Y', strtotime($image['uploaded_at'])); ?>
                                    </p>
                                    <button onclick="copyImageUrl('<?php echo UPLOAD_DIR . $image['filename']; ?>')" 
                                            class="btn btn-primary" style="width: 100%; margin-top: 5px; padding: 5px; font-size: 12px;">
                                        <i class="fas fa-copy"></i> Copy URL
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function copyImageUrl(url) {
        const fullUrl = window.location.origin + '/' + url;
        navigator.clipboard.writeText(fullUrl).then(() => {
            alert('Image URL copied to clipboard!');
        });
    }
    
    // Drag and drop functionality
    const uploadArea = document.querySelector('.upload-area');
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#2563eb';
        uploadArea.style.backgroundColor = '#f0f7ff';
    });
    
    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#ddd';
        uploadArea.style.backgroundColor = 'transparent';
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#ddd';
        uploadArea.style.backgroundColor = 'transparent';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('imageInput').files = files;
            previewImage(document.getElementById('imageInput'));
        }
    });
    </script>
</body>
</html>
