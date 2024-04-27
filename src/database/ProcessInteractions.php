<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

if (isset($_POST["likeSubmit"])) {
  $loginUserId = getUserIdSession();
  $targetUserId = $_POST["targetUserId"];
  $likeSubmit = $_POST["likeSubmit"];
  try {
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
  } catch (PDOException $e) {
    setErrorMessage("DB error: " . $e->getMessage());
  }
  if ($_POST["likePage"] === "profile") {
    header("Location: ../pages/Profile.php?targetUserId=".$targetUserId);
  } else {
    header("Location: ../pages/Interactions.php");
  }
  exit();
}