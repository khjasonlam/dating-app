<?php
session_start();
include_once("Pdo.php");
include_once("CheckInput.php");

// $error = new errorMessage();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["sendMessage"])) {
    $inputValue = !empty($_POST["message"]);
    if ($inputValue) {
      try {
        $insertMessageSql = 
          "INSERT INTO Messages (senderId, receiverId, messageContent) 
          VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertMessageSql);
        
        $stmt->bindValue(1, $_POST["loginUserId"]);
        $stmt->bindValue(2, $_POST["messageUserId"]);
        $stmt->bindValue(3, $_POST["message"]);
        $stmt->execute();
        
      } catch (PDOException $e) {
        $errorMessage = "送信失敗: " . $e->getMessage();
        echo $e->getMessage();
        // $error->setErrorMessage($errorMessage);
      }
    } else {
      $errorMessage = "入力してください";
      // $error->setErrorMessage($errorMessage);
    }
    header("Location: Message.php?messageUserId=".$_POST["messageUserId"]);
  }
}