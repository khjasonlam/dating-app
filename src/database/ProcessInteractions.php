<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

$loginUserId = requireLogin();

$targetUserId = isset($_POST["targetUserId"]) ? (int)$_POST["targetUserId"] : 0;
$likeSubmit = $_POST["likeSubmit"] ?? null;
$dislikeSubmit = $_POST["dislikeSubmit"] ?? null;
$likePage = $_POST["likePage"] ?? 'interactions';

// 対象ユーザーIDの検証
if ($targetUserId <= 0 || $targetUserId === $loginUserId) {
  setErrorMessage("無効なユーザーです");
  header("Location: ../pages/Interactions.php");
  exit;
}

try {
  if (isset($likeSubmit) && $likeSubmit === 'like') {
    // 既にいいね済みか確認
    $checkExistingSql = 
      "SELECT interactionId FROM User_Interactions 
      WHERE userId = ? AND targetUserId = ? AND interactionType = 'like'";
    $checkStmt = $conn->prepare($checkExistingSql);
    $checkStmt->bindValue(1, $loginUserId);
    $checkStmt->bindValue(2, $targetUserId);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() === 0) {
      $likeSql = 
        "INSERT INTO User_Interactions (userId, targetUserId, interactionType)
        VALUES (?, ?, 'like')";
      $stmt = $conn->prepare($likeSql);
      
      $stmt->bindValue(1, $loginUserId);
      $stmt->bindValue(2, $targetUserId);
      $result = $stmt->execute();
      
      if ($result) {
        // 相手もいいねを返しているか確認（マッチング）
        $checkMatchSql = 
          "SELECT userId FROM User_Interactions 
          WHERE userId = ? AND targetUserId = ? AND interactionType = 'like'";
        $matchStmt = $conn->prepare($checkMatchSql);
        
        $matchStmt->bindValue(1, $targetUserId);
        $matchStmt->bindValue(2, $loginUserId);
        $matchStmt->execute();
        
        $matched = $matchStmt->fetch(PDO::FETCH_ASSOC);
        if ($matched) {
          setMatchedUserSession();
        }
      }
    }
  } elseif (isset($dislikeSubmit) && $dislikeSubmit === 'dislike') {
    // 既にいいえ済みか確認
    $checkExistingSql = 
      "SELECT interactionId FROM User_Interactions 
      WHERE userId = ? AND targetUserId = ? AND interactionType = 'dislike'";
    $checkStmt = $conn->prepare($checkExistingSql);
    $checkStmt->bindValue(1, $loginUserId);
    $checkStmt->bindValue(2, $targetUserId);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() === 0) {
      $dislikeSql = 
        "INSERT INTO User_Interactions (userId, targetUserId, interactionType)
        VALUES (?, ?, 'dislike')";
      $stmt = $conn->prepare($dislikeSql);
      
      $stmt->bindValue(1, $loginUserId);
      $stmt->bindValue(2, $targetUserId);
      $stmt->execute();
    }
  }
} catch (PDOException $e) {
  error_log("Process interactions error: " . $e->getMessage());
  setErrorMessage("処理に失敗しました");
}

// ページに応じてリダイレクト
if ($likePage === "profile") {
  header("Location: ../pages/Profile.php?targetUserId=" . $targetUserId);
} else {
  header("Location: ../pages/Interactions.php");
}
exit();