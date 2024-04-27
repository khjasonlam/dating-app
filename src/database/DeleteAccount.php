<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

$loginUserId = getUserIdSession();

try {
  $conn->beginTransaction();
  
  $deleteMessage = "DELETE FROM Messages WHERE senderId = ?";  
  $stmt = $conn->prepare($deleteMessage);
  $stmt->bindValue(1, $loginUserId);
  $stmt->execute();
  
  $deleteInteractions = "DELETE FROM User_Interactions WHERE userId = ? OR targetUserId = ?";
  $stmt = $conn->prepare($deleteInteractions);
  $stmt->bindValue(1, $loginUserId);
  $stmt->bindValue(2, $loginUserId);
  $stmt->execute();
  
  $deletePictures = "DELETE FROM User_Pictures WHERE userId = ?";
  $stmt = $conn->prepare($deletePictures);
  $stmt->bindValue(1, $loginUserId);
  $stmt->execute();
  
  $deleteUser = "DELETE FROM Users WHERE userId = ?";
  $stmt = $conn->prepare($deleteUser);
  $stmt->bindValue(1, $loginUserId);
  $stmt->execute();
  
  $conn->commit();
  unsetAllSession();
  
} catch (PDOException $e) {
  $conn->rollBack();
  setErrorMessage("Error: ". $e->getMessage());
  header("Location: ../pages/Profile.php");
}