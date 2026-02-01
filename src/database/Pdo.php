<?php

/**
 * データベース接続ハンドラ
 * src/config.phpファイルから設定を読み込む
 */

// 設定ファイルの読み込み
require_once(__DIR__ . '/../config.php');

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    // PHP 8.5以降では新しい定数を使用、それ以前では古い定数を使用
    // PHP 8.5以降では \Pdo\Mysql::ATTR_INIT_COMMAND が利用可能
    if (PHP_VERSION_ID >= 80500) {
        // 新しい定数を使用（PHP 8.5以降で利用可能）
        $options[\Pdo\Mysql::ATTR_INIT_COMMAND] = "SET NAMES utf8mb4";
    } else {
        // 古い定数を使用（PHP 8.5未満）
        $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8mb4";
    }

    $conn = new PDO($dsn, DB_USER, DB_PASS, $options);

    // 直接アクセスされた場合のみ接続メッセージを表示（テスト用）
    if (basename($_SERVER['SCRIPT_NAME']) === "Pdo.php" && DEBUG_MODE) {
        echo "接続に成功しました";
    }
} catch (PDOException $e) {
    if (DEBUG_MODE) {
        echo "接続に失敗しました: " . $e->getMessage();
    } else {
        error_log("Database connection failed: " . $e->getMessage());
        echo "データベース接続エラー。管理者にお問い合わせください。";
    }
    exit;
}
