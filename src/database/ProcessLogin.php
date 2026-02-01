<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

if (isset($_POST["loginSubmit"])) {
  $loginId = testInputValue($_POST["loginId"] ?? '');
  $password = $_POST["password"] ?? '';
  
  if (!empty($loginId) && !empty($password)) {
    try {
      // パスワードハッシュを含むユーザー情報を取得
      $loginSql = "SELECT userId, password FROM Users WHERE loginId = ?";
      $stmt = $conn->prepare($loginSql);
      $stmt->bindValue(1, $loginId);
      $stmt->execute();
      $loginUser = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!empty($loginUser)) {
        // パスワードの検証（移行のためハッシュ化とプレーンテキストの両方をサポート）
        $passwordValid = false;
        
        // パスワードがハッシュ化されているか確認（bcryptの場合は$2y$で始まる）
        if (password_verify($password, $loginUser["password"])) {
          $passwordValid = true;
        } 
        // レガシーサポート: プレーンテキストの確認（既存ユーザー用）
        elseif ($loginUser["password"] === $password) {
          $passwordValid = true;
          // ハッシュ化パスワードにアップグレード
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
        // セキュリティのベストプラクティス: ユーザーが存在するかどうかを明かさない
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