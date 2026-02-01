<?php
/**
 * データベース接続ハンドラ
 * ルートディレクトリのconfig.phpファイルから設定を読み込む
 */

// 設定ファイルの読み込み
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
  
  // 直接アクセスされた場合のみ接続メッセージを表示（テスト用）
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
