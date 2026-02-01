<?php

/**
 * デートアプリの設定ファイル
 */

// プロジェクトルートの.envファイルから環境変数を読み込む
$envFile = __DIR__ . '/../.env';
if (!file_exists($envFile)) {
    die("エラー: .envファイルが見つかりません。プロジェクトルートに.envファイルを作成してください。\n" .
        "詳細はREADME.mdのセットアップ手順を参照してください。");
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) {
        continue; // コメントをスキップ
    }
    if (strpos($line, '=') === false) {
        continue; // 無効な行をスキップ
    }
    list($name, $value) = explode('=', $line, 2);
    $_ENV[trim($name)] = trim($value);
}

// Database configuration - 環境変数は必須
$requiredEnvVars = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];
foreach ($requiredEnvVars as $var) {
    if (!isset($_ENV[$var]) || empty($_ENV[$var])) {
        die("エラー: 環境変数 {$var} が設定されていません。.envファイルを確認してください。\n" .
            "必要な環境変数: DB_HOST, DB_NAME, DB_USER, DB_PASS\n");
    }
}

define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);

// アプリケーション設定
define('MAX_FILE_SIZE', 1048576); // 1MB（バイト単位）
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// セキュリティ設定
define('SESSION_LIFETIME', 3600); // 1時間
define('PASSWORD_MIN_LENGTH', 8);

// エラー報告（本番環境では0に設定）
define('DEBUG_MODE', true);

if (!DEBUG_MODE) {
    error_reporting(0);
    ini_set('display_errors', 0);
}
