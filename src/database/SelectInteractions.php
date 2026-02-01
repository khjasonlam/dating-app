<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

$loginUserId = requireLogin();

try {
  $likeListSql = 
    "SELECT u.userId, u.username, u.age, u.gender, up.pictureContents, up.pictureType 
    FROM Users u 
    LEFT JOIN User_Pictures up ON u.userId = up.userId 
    LEFT JOIN User_Interactions ui ON u.userId = ui.targetUserId AND ui.userId = ? 
    WHERE ui.userId IS NULL AND u.userId != ?";
  
  $stmt = $conn->prepare($likeListSql);
  
  $stmt->bindValue(1, $loginUserId);
  $stmt->bindValue(2, $loginUserId);
  $stmt->execute();
  
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $row = count($result);
  
  if ($row === 0) {
    // Don't show error for empty list, just show empty state
    $result = [];
  }
} catch (PDOException $e) {
  error_log("Select interactions error: " . $e->getMessage());
  setErrorMessage("ユーザーリストの取得に失敗しました");
  $result = [];
  $row = 0;
}