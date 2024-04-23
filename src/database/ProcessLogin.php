<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

if (isset($_POST["loginSubmit"])) {
  $loginId = testInputValue($_POST["loginId"]);
  $password = testInputValue($_POST["password"]);
  if(!empty($loginId) && !empty($password)) {
    try {
      $loginSql = "SELECT userId FROM Users WHERE loginId = ? AND password = ?";
      $stmt = $conn->prepare($loginSql);
      
      $stmt->bindValue(1, $loginId);
      $stmt->bindValue(2, $password);
      $stmt->execute();
      $loginUser = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!empty($loginUser)) {
        setUserIdSession($loginUser["userId"]);
        header("Location: ../pages/Profile.php");
      } else {
        setErrorMessage("ログインID又はパスワードが間違いました");
        header("Location: ../pages/Login.php");
      }
    } catch (PDOException $e) {
      setErrorMessage("DB Error: " . $e->getMessage());
      header("Location: ../pages/Login.php");
    }
  } else {
    setErrorMessage("必須項目です");
    header("Location: ../pages/Login.php");
  }
}