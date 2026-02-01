<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

if (isset($_POST["sendMessage"])) {
  $message = testInputValue($_POST["message"] ?? '');
  $messageUserId = isset($_POST["messageUserId"]) ? (int)$_POST["messageUserId"] : 0;
  $loginUserId = requireLogin();
  
  // Validate message
  if (empty($message)) {
    setErrorMessage("メッセージを入力してください");
  } elseif (strlen($message) > 1000) {
    setErrorMessage("メッセージは1000文字以内で入力してください");
  } elseif ($messageUserId <= 0) {
    setErrorMessage("無効な受信者です");
  } else {
    try {
      // Verify that the receiver exists
      $checkUserSql = "SELECT userId FROM Users WHERE userId = ?";
      $checkStmt = $conn->prepare($checkUserSql);
      $checkStmt->bindValue(1, $messageUserId);
      $checkStmt->execute();
      
      if ($checkStmt->rowCount() === 0) {
        setErrorMessage("受信者が存在しません");
      } else {
        $insertMessageSql = 
          "INSERT INTO Messages (senderId, receiverId, messageContent, createdAt) 
          VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($insertMessageSql);
        
        $stmt->bindValue(1, $loginUserId);
        $stmt->bindValue(2, $messageUserId);
        $stmt->bindValue(3, $message);
        $stmt->execute();
        
        // Success - no error message needed
      }
    } catch (PDOException $e) {
      error_log("Message send error: " . $e->getMessage());
      setErrorMessage("メッセージの送信に失敗しました");
    }
  }
  
  header("Location: ../pages/Message.php?messageUserId=$messageUserId");
  exit;
}