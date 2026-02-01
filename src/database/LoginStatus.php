<?php
/**
 * Login Status Check
 * Verifies that user is logged in and session is valid
 * Also verifies that the user exists in the database
 */
include_once("Pdo.php");
include_once("../components/CheckInput.php");

$userId = requireLogin();

try {
  $checkLoginUser = "SELECT userId FROM Users WHERE userId = ?";
  
  $stmt = $conn->prepare($checkLoginUser);
  $stmt->bindValue(1, $userId);
  $stmt->execute();
  
  $user = $stmt->rowCount();
  if ($user === 0) {
    unsetAllSession();
    setErrorMessage("ユーザーが見つかりません");
    header("Location: ../pages/Login.php");
    exit();
  }
} catch (PDOException $e) {
  error_log("Login status check error: " . $e->getMessage());
  setErrorMessage("認証エラーが発生しました");
  header("Location: ../pages/Login.php");
  exit();
}