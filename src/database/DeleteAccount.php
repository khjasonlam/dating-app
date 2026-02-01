<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

$loginUserId = requireLogin();

try {
  $conn->beginTransaction();
  
  // Delete all messages sent or received by this user
  $deleteMessage = "DELETE FROM Messages WHERE senderId = ? OR receiverId = ?";  
  $stmt = $conn->prepare($deleteMessage);
  $stmt->bindValue(1, $loginUserId);
  $stmt->bindValue(2, $loginUserId);
  $stmt->execute();
  
  // Delete all interactions (likes/dislikes) involving this user
  $deleteInteractions = "DELETE FROM User_Interactions WHERE userId = ? OR targetUserId = ?";
  $stmt = $conn->prepare($deleteInteractions);
  $stmt->bindValue(1, $loginUserId);
  $stmt->bindValue(2, $loginUserId);
  $stmt->execute();
  
  // Delete profile pictures
  $deletePictures = "DELETE FROM User_Pictures WHERE userId = ?";
  $stmt = $conn->prepare($deletePictures);
  $stmt->bindValue(1, $loginUserId);
  $stmt->execute();
  
  // Finally, delete the user account
  $deleteUser = "DELETE FROM Users WHERE userId = ?";
  $stmt = $conn->prepare($deleteUser);
  $stmt->bindValue(1, $loginUserId);
  $stmt->execute();
  
  $conn->commit();
  unsetAllSession();
  setErrorMessage("アカウントを削除しました");
  header("Location: ../pages/Login.php");
  exit;
  
} catch (PDOException $e) {
  error_log("Delete account error: " . $e->getMessage());
  if ($conn->inTransaction()) {
    $conn->rollBack();
  }
  setErrorMessage("アカウントの削除に失敗しました");
  header("Location: ../pages/Profile.php");
  exit;
}