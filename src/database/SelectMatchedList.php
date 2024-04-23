<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

try {
  $matchedSql = 
    "SELECT ui2.userId, u.username, u.age, u.description, 
      up.pictureContents, up.pictureType FROM User_Interactions ui1 
      INNER JOIN User_Interactions ui2 ON ui1.userId = ui2.TargetUserId 
      AND ui1.TargetUserId = ui2.userId
      INNER JOIN Users u ON ui2.userId = u.userId
      INNER JOIN User_Pictures up ON u.userId = up.userId
      WHERE ui1.interactionType = 'like' AND ui2.interactionType = 'like' 
      AND ui1.userId = ?";
    $stmt = $conn->prepare($matchedSql);
    
    $stmt->bindValue(1, getUserIdSession());
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $row = $stmt->rowCount();
} catch (PDOException $e) {
  setErrorMessage("DB Error:" . $e->getMessage());
}
if ($row === 0) {
  setErrorMessage("マッチングした相手がいません");
}