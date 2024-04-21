<?php
session_start();

include_once("Pdo.php");
include_once("../components/CheckInput.php");

if (isset($_POST["sendMessage"])) {
  $message = testInputValue($_POST["message"]);
  $messageUserId = testInputValue($_POST["messageUserId"]);
  $loginUserId = testInputValue($_POST["loginUserId"]);
  if (!empty($message)) {
    try {
      $insertMessageSql = 
        "INSERT INTO Messages (senderId, receiverId, messageContent) 
        VALUES (?, ?, ?)";
      $stmt = $conn->prepare($insertMessageSql);
      
      $stmt->bindValue(1, $loginUserId);
      $stmt->bindValue(2, $messageUserId);
      $stmt->bindValue(3, $message);
      $stmt->execute();
      
    } catch (PDOException $e) {
      $errorMessage = "送信失敗: " . $e->getMessage();
      setErrorMessage($errorMessage);
    }
  } else {
    $errorMessage = "メッセージを入力してください";
    setErrorMessage($errorMessage);
  }
  header("Location: ../pages/Message.php?messageUserId=$messageUserId");
}