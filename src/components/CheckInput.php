<?php 
/**
 * Input validation and session management functions
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once(__DIR__ . '/../../config.php');

/**
 * Sanitize input value
 * @param string $data Input data
 * @return string Sanitized data
 */
function testInputValue($data) {
  if (!isset($data)) {
    return '';
  }
  $data = trim($data);
  $data = stripslashes($data);
  return htmlspecialchars($data, ENT_QUOTES, "UTF-8");
}

/**
 * Check and sanitize value, return default if empty
 * @param string $data Input data
 * @return string Sanitized data or default message
 */
function checkValue($data) {
  if (empty($data)) {
    $data = "入力なし";
  }
  return htmlspecialchars($data, ENT_QUOTES, "UTF-8");
}

/**
 * Validate password strength
 * @param string $password Password to validate
 * @return array ['valid' => bool, 'message' => string]
 */
function validatePassword($password) {
  if (strlen($password) < PASSWORD_MIN_LENGTH) {
    return [
      'valid' => false,
      'message' => 'パスワードは' . PASSWORD_MIN_LENGTH . '文字以上である必要があります'
    ];
  }
  
  if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
    return [
      'valid' => false,
      'message' => 'パスワードは英字と数字を含む必要があります'
    ];
  }
  
  return ['valid' => true, 'message' => ''];
}

/**
 * Validate uploaded image file
 * @param array $file $_FILES array element
 * @return array ['valid' => bool, 'message' => string]
 */
function validateImageFile($file) {
  if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
    return [
      'valid' => false,
      'message' => 'ファイルのアップロードに失敗しました'
    ];
  }
  
  if ($file['size'] > MAX_FILE_SIZE) {
    return [
      'valid' => false,
      'message' => '画像サイズが' . (MAX_FILE_SIZE / 1024 / 1024) . 'MBを超えました'
    ];
  }
  
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mimeType = finfo_file($finfo, $file['tmp_name']);
  finfo_close($finfo);
  
  if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
    return [
      'valid' => false,
      'message' => '許可されていないファイル形式です。JPEG、PNG、GIF、WebPのみ許可されています'
    ];
  }
  
  return ['valid' => true, 'message' => ''];
}

/**
 * Set error message in session
 * @param string $message Error message
 */
function setErrorMessage($message) {
  $_SESSION["error_message"] = $message;
}

/**
 * Display and clear error message from session
 */
function displayErrorMessage() {
  if (isset($_SESSION["error_message"])) {
    echo htmlspecialchars($_SESSION["error_message"], ENT_QUOTES, "UTF-8");
    unset($_SESSION["error_message"]);
  }
}

/**
 * Set user ID in session
 * @param int $userId User ID
 */
function setUserIdSession($userId) {
  $_SESSION["userId"] = (int)$userId;
  $_SESSION["last_activity"] = time();
}

/**
 * Get user ID from session
 * @return int|null User ID or null if not set
 */
function getUserIdSession() {
  // Check session timeout
  if (isset($_SESSION["last_activity"]) && 
      (time() - $_SESSION["last_activity"] > SESSION_LIFETIME)) {
    unsetAllSession();
    return null;
  }
  
  if (isset($_SESSION["last_activity"])) {
    $_SESSION["last_activity"] = time();
  }
  
  return isset($_SESSION["userId"]) ? (int)$_SESSION["userId"] : null;
}

/**
 * Set matched user session flag
 */
function setMatchedUserSession() {
  $_SESSION["matched"] = true; 
}

/**
 * Get and clear matched user session flag
 * @return bool True if matched flag was set
 */
function getMatchedUserSession() {
  if (isset($_SESSION["matched"])) {
    $matched = true;
    unset($_SESSION["matched"]);
    return $matched;
  }
  return false;
}

/**
 * Require user to be logged in
 * Redirects to login page if not logged in
 * @return int User ID if logged in (never returns if not logged in)
 */
function requireLogin() {
  $userId = getUserIdSession();
  
  if (!$userId) {
    setErrorMessage("ログインが必要です");
    header("Location: ../pages/Login.php");
    exit;
  }
  
  return $userId;
}

/**
 * Clear all session data and redirect to login
 */
function unsetAllSession() {
  session_unset();
  session_destroy();
  header("Location: ../pages/Login.php");
  exit();
}