<?php
session_start();

include_once("Pdo.php");
include_once("../components/CheckInput.php");

$loginUserId = $_SESSION["userId"];

try {
  $likeListSql = 
    "SELECT u.userId, u.username, u.age, u.gender, up.pictureContents, up.pictureType 
    FROM Users u LEFT JOIN User_Pictures up ON u.userId = up.userId 
    LEFT JOIN User_Interactions ui ON u.userId = ui.targetUserId AND ui.userId = ? 
    WHERE ui.userId IS NULL AND NOT u.userId = ?";
  
  $stmt = $conn->prepare($likeListSql);
  
  $stmt->bindValue(1, $loginUserId);
  $stmt->bindValue(2, $loginUserId);
  $stmt->execute();
  
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $row = $stmt->rowCount();
} catch (PDOException $e) {
  setErrorMessage("DB error: " . $e->getMessage());
}
if ($row === 0) {
  setErrorMessage("いいねする相手いません");
}