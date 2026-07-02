<?php
$host = '127.0.0.1';
$port = '3306';
$username = 'root';
$password = '';
$dbname = 'summerproject';

try {
    // Connect to MySQL server WITHOUT specifying the database name yet
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    echo "<h3>Database '$dbname' checked/created successfully.</h3>";
    
    // Select the newly created database for subsequent queries
    $pdo->exec("USE `$dbname`");

    // Create the users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )";
    $pdo->exec($sql);
    echo "<h3>Table 'users' checked/created successfully.</h3>";

    // Check if the admin user already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        // Insert a test user with a securely hashed password
        $pass = password_hash('password123', PASSWORD_DEFAULT);
        
        $insert = "INSERT INTO users (username, password) VALUES ('admin', :password)";
        $stmt = $pdo->prepare($insert);
        $stmt->execute(['password' => $pass]);
        
        echo "<p>Test user <strong>'admin'</strong> (password: <strong>password123</strong>) created successfully.</p>";
    } else {
        echo "<p>Test user <strong>'admin'</strong> already exists in the database.</p>";
    }
    
    echo "<br><p>Setup complete! You can now <a href='index.php'>go to the login page</a>.</p>";
    
} catch (PDOException $e) {
    die("Setup failed: " . $e->getMessage());
}
?>
