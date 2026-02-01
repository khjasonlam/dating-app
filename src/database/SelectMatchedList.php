<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

$loginUserId = requireLogin();

try {
  $matchedSql = 
    "SELECT ui2.userId, u.username, u.age, u.description, 
      up.pictureContents, up.pictureType 
    FROM User_Interactions ui1 
    INNER JOIN User_Interactions ui2 ON ui1.userId = ui2.targetUserId 
      AND ui1.targetUserId = ui2.userId
    INNER JOIN Users u ON ui2.userId = u.userId
    LEFT JOIN User_Pictures up ON u.userId = up.userId
    WHERE ui1.interactionType = 'like' AND ui2.interactionType = 'like' 
      AND ui1.userId = ?";
  $stmt = $conn->prepare($matchedSql);
  
  $stmt->bindValue(1, $loginUserId);
  $stmt->execute();
  
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $row = count($result);
  
  if ($row === 0) {
    // 空のリストの場合はエラーを表示せず、空の状態を表示
    $result = [];
  }
} catch (PDOException $e) {
  error_log("Select matched list error: " . $e->getMessage());
  setErrorMessage("マッチングリストの取得に失敗しました");
  $result = [];
  $row = 0;
}