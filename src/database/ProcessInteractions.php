<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

if (isset($_POST["likeSubmit"])) {
  try {
    $likeSql = 
      "INSERT INTO User_Interactions (userId, targetUserId, interactionType)
      VALUES (?, ?, ?)";
    $stmt = $conn->prepare($likeSql);
    
    $stmt->bindValue(1, $_POST["loginUserId"]);
    $stmt->bindValue(2, $_POST["targetUserId"]);
    $stmt->bindValue(3, $_POST["likeSubmit"]);
    $result = $stmt->execute();
    
    if ($result) {
      $checklikeSql = 
        "SELECT userId FROM User_Interactions 
        WHERE userId = ? AND targetUserId = ? AND interactionType = ?";
      $stmt = $conn->prepare($checklikeSql);
      
      $stmt->bindValue(1, $_POST["targetUserId"]);
      $stmt->bindValue(2, $_POST["loginUserId"]);
      $stmt->bindValue(3, $_POST["likeSubmit"]);
      $stmt->execute();
      
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } 
  } catch (PDOException $e) {
    setErrorMessage("DB error: " . $e->getMessage());
    header("Location: ../pages/Interactions.php");
  }
  if (empty($result)) {
    header("Location: ../pages/Interactions.php");
  } else {
    echo "success" . $_POST["targetUserId"];
    // header("Location: ../pages/MatchSuccess.php");
  }
}