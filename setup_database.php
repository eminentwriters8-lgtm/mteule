<?php
// Database setup script - Run this once to create the database and tables

$host = 'localhost';
$user = 'root';
$pass = '';

// Create connection
$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS mteule_website CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

// Select database
mysqli_select_db($conn, "mteule_website");

// Create images table
$sql = "CREATE TABLE IF NOT EXISTS uploaded_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    description TEXT,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Images table created successfully<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

// Create content management table
$sql = "CREATE TABLE IF NOT EXISTS website_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page VARCHAR(50) NOT NULL,
    section VARCHAR(50) NOT NULL,
    content LONGTEXT,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY page_section (page, section)
)";

if (mysqli_query($conn, $sql)) {
    echo "Content table created successfully<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

// Create admin users table (for multiple admins)
$sql = "CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME
)";

if (mysqli_query($conn, $sql)) {
    echo "Admin users table created successfully<br>";
    
    // Insert default admin (password: admin123)
    $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
    $sql = "INSERT IGNORE INTO admin_users (username, password_hash, email, full_name) 
            VALUES ('admin', '$hashed_password', 'admin@mteuleventures.co.ke', 'System Administrator')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Default admin user created (username: admin, password: admin123)<br>";
    }
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);

echo "<br><strong>Setup completed!</strong> You can now use the admin system.";
echo "<br><a href='login.php'>Go to Admin Login</a>";
?>
