<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

$loginUserId = getUserIdSession();
$targetUserId = $_POST["targetUserId"];
$likeSubmit = $_POST["likeSubmit"];
$dislikeSubmit = $_POST["dislikeSubmit"];

try {
  if (isset($likeSubmit)) {
    $likeSql = 
      "INSERT INTO User_Interactions (userId, targetUserId, interactionType)
      VALUES (?, ?, ?)";
    $stmt = $conn->prepare($likeSql);
    
    $stmt->bindValue(1, $loginUserId);
    $stmt->bindValue(2, $targetUserId);
    $stmt->bindValue(3, $likeSubmit);
    $result = $stmt->execute();
    
    if ($result) {
      $checklikeSql = 
        "SELECT userId FROM User_Interactions 
        WHERE userId = ? AND targetUserId = ? AND interactionType = ?";
      $stmt = $conn->prepare($checklikeSql);
      
      $stmt->bindValue(1, $targetUserId);
      $stmt->bindValue(2, $loginUserId);
      $stmt->bindValue(3, $likeSubmit);
      $stmt->execute();
      
      $matched = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($matched) {
        setMatchedUserSession($targetUserId);
      }
    } 
  } elseif ($dislikeSubmit) {
    $dislikeSql = 
      "INSERT INTO User_Interactions (userId, targetUserId, interactionType)
      VALUES (?, ?, ?)";
    $stmt = $conn->prepare($dislikeSql);
    
    $stmt->bindValue(1, $loginUserId);
    $stmt->bindValue(2, $targetUserId);
    $stmt->bindValue(3, $dislikeSubmit);
    $result = $stmt->execute();
  }
} catch (PDOException $e) {
  setErrorMessage("DB登録失敗しました: " . $e->getMessage());
}

if ($_POST["likePage"] === "profile") {
  header("Location: ../pages/Profile.php?targetUserId=".$targetUserId);
} elseif ($_POST["likePage"] === "interactions") {
  header("Location: ../pages/Interactions.php");
}
exit();