<?php
  $host = 'localhost'; // Hostname
  $dbname = 'datingAppDB'; // Database name
  $dbUsername = 'jasonlam'; // Username
  $dbPassword = '971216'; // Password
  
  try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbUsername, $dbPassword);
    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER['SCRIPT_NAME'] === "/Pdo.php") {
      echo "Connected successfully";
    }
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  } 
?>