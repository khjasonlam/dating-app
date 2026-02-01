<?php
/**
 * Configuration file for Dating App
 * 
 * For production, create a .env file in the root directory with:
 * DB_HOST=localhost
 * DB_NAME=datingAppDB
 * DB_USER=your_username
 * DB_PASS=your_password
 */

// Load environment variables from .env file if it exists
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Database configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'datingAppDB');
define('DB_USER', $_ENV['DB_USER'] ?? 'username');
define('DB_PASS', $_ENV['DB_PASS'] ?? 'password');

// Application settings
define('MAX_FILE_SIZE', 1048576); // 1MB in bytes
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('UPLOAD_DIR', __DIR__ . '/uploads/');

// Security settings
define('SESSION_LIFETIME', 3600); // 1 hour
define('PASSWORD_MIN_LENGTH', 8);

// Error reporting (set to 0 in production)
define('DEBUG_MODE', true);

if (!DEBUG_MODE) {
    error_reporting(0);
    ini_set('display_errors', 0);
}
