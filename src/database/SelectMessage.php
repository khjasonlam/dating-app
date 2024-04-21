<?php
session_start();
include_once("Pdo.php");
include_once("../components/CheckInput.php");

$loginUserId = $_SESSION["userId"];

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $messageUserId = $_GET["messageUserId"];
  if (isset($messageUserId)) {
    try {
      $SelectMessageSql = 
        "SELECT m.senderId, m.messageContent, 
        up.pictureContents, up.pictureType FROM Messages m 
        LEFT JOIN Users u ON m.senderId = u.userId 
        LEFT JOIN User_Pictures up ON m.senderId = up.userId 
        WHERE m.senderId = ? AND m.receiverId = ? 
        OR m.senderId = ? AND m.receiverId = ?
        ORDER BY m.timestamp ASC;";
      $stmt = $conn->prepare($SelectMessageSql);
      
      $stmt->bindValue(1, $loginUserId);
      $stmt->bindValue(2, $messageUserId);
      $stmt->bindValue(3, $messageUserId);
      $stmt->bindValue(4, $loginUserId);
      $stmt->execute();
      
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $row = $stmt->rowCount();
      
    } catch (PDOException $e) {
      setErrorMessage("メッセージ取得失敗：" . $e->getMessage());
    }
  }
}