<?php
// config.php - Database connection

// Database settings
$host = 'localhost';
$dbname = 'healthcare_db';
$username = 'root';
$password = ''; // Leave empty if no password set in XAMPP

try {
    // Create connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional: Set character set to UTF-8
    $pdo->exec("SET NAMES utf8");
    
    // Uncomment below line to test if connection works
    // echo "Connected successfully!";
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>