<?php
/**
 * Database Connection Handler
 * Uses configuration from root config.php file
 */

// Load configuration
require_once(__DIR__ . '/../../config.php');

try {
  $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
  ];
  
  $conn = new PDO($dsn, DB_USER, DB_PASS, $options);
  
  // Only display connection message if accessed directly (for testing)
  if (basename($_SERVER['SCRIPT_NAME']) === "Pdo.php" && DEBUG_MODE) {
    echo "Connected successfully";
  }
} catch(PDOException $e) {
  if (DEBUG_MODE) {
    echo "Connection failed: " . $e->getMessage();
  } else {
    error_log("Database connection failed: " . $e->getMessage());
    echo "Database connection error. Please contact administrator.";
  }
  exit;
} 
