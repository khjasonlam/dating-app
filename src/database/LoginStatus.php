<?php
/**
 * ログイン状態チェック
 * ユーザーがログインしているか、セッションが有効かを確認
 * データベースにユーザーが存在するかも確認
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