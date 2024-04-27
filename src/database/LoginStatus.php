<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

try {
  $checkLoginUser = "SELECT userId FROM Users WHERE userId = ?";
  
  $stmt = $conn->prepare($checkLoginUser);
  $stmt->bindValue(1, getUserIdSession());
  $stmt->execute();
  
  $user = $stmt->rowCount();
  if ($user === 0) {
    header("Location: ../pages/Login.php");
  }
} catch (PDOException $e) {
  setErrorMessage("Error: " . $e->getMessage());
  header("Location:". $_SERVER["SCRIPT_NAME"]);
  exit();
}