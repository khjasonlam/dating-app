<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

$loginUserId = requireLogin();

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["messageUserId"])) {
  $messageUserId = (int)$_GET["messageUserId"];
  
  if ($messageUserId <= 0) {
    setErrorMessage("無効なユーザーIDです");
  } else {
    try {
      // SQLクエリの修正: OR条件に括弧を使用
      $SelectMessageSql = 
        "SELECT m.senderId, m.messageContent, m.createdAt,
        up.pictureContents, up.pictureType 
        FROM Messages m 
        LEFT JOIN Users u ON m.senderId = u.userId 
        LEFT JOIN User_Pictures up ON m.senderId = up.userId 
        WHERE (m.senderId = ? AND m.receiverId = ?) 
        OR (m.senderId = ? AND m.receiverId = ?)
        ORDER BY m.createdAt ASC";
      $stmt = $conn->prepare($SelectMessageSql);
      
      $stmt->bindValue(1, $loginUserId);
      $stmt->bindValue(2, $messageUserId);
      $stmt->bindValue(3, $messageUserId);
      $stmt->bindValue(4, $loginUserId);
      $stmt->execute();
      
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $row = count($result);
      
    } catch (PDOException $e) {
      error_log("Select message error: " . $e->getMessage());
      setErrorMessage("メッセージの取得に失敗しました");
      $result = [];
      $row = 0;
    }
  }
} else {
  $result = [];
  $row = 0;
}