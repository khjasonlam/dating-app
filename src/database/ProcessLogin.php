<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

if (isset($_POST["loginSubmit"])) {
  $loginId = testInputValue($_POST["loginId"] ?? '');
  $password = $_POST["password"] ?? '';
  
  if (!empty($loginId) && !empty($password)) {
    try {
      // Get user with password hash
      $loginSql = "SELECT userId, password FROM Users WHERE loginId = ?";
      $stmt = $conn->prepare($loginSql);
      $stmt->bindValue(1, $loginId);
      $stmt->execute();
      $loginUser = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!empty($loginUser)) {
        // Verify password (supports both hashed and plain text for migration)
        $passwordValid = false;
        
        // Check if password is hashed (starts with $2y$ for bcrypt)
        if (password_verify($password, $loginUser["password"])) {
          $passwordValid = true;
        } 
        // Legacy support: check plain text (for existing users)
        elseif ($loginUser["password"] === $password) {
          $passwordValid = true;
          // Upgrade to hashed password
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
          $updateSql = "UPDATE Users SET password = ? WHERE userId = ?";
          $updateStmt = $conn->prepare($updateSql);
          $updateStmt->bindValue(1, $hashedPassword);
          $updateStmt->bindValue(2, $loginUser["userId"]);
          $updateStmt->execute();
        }
        
        if ($passwordValid) {
          setUserIdSession($loginUser["userId"]);
          header("Location: ../pages/Profile.php");
          exit;
        } else {
          setErrorMessage("ログインID又はパスワードが間違いました");
        }
      } else {
        // Don't reveal if user exists or not (security best practice)
        setErrorMessage("ログインID又はパスワードが間違いました");
      }
    } catch (PDOException $e) {
      error_log("Login error: " . $e->getMessage());
      setErrorMessage("ログイン処理中にエラーが発生しました");
    }
  } else {
    setErrorMessage("ログインIDとパスワードは必須項目です");
  }
  header("Location: ../pages/Login.php");
  exit;
}