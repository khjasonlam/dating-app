<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

if (isset($_POST["registerSubmit"])) {
  // Validate all required fields
  $loginId = testInputValue($_POST["loginId"] ?? '');
  $password = $_POST["password"] ?? '';
  $username = testInputValue($_POST["username"] ?? '');
  $age = testInputValue($_POST["age"] ?? '');
  $gender = testInputValue($_POST["gender"] ?? '');
  
  $errors = [];
  
  // Validate login ID
  if (empty($loginId)) {
    $errors[] = "ログインIDは必須です";
  } elseif (strlen($loginId) < 3) {
    $errors[] = "ログインIDは3文字以上である必要があります";
  } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $loginId)) {
    $errors[] = "ログインIDは英数字とアンダースコアのみ使用できます";
  }
  
  // Validate password
  if (empty($password)) {
    $errors[] = "パスワードは必須です";
  } else {
    $passwordValidation = validatePassword($password);
    if (!$passwordValidation['valid']) {
      $errors[] = $passwordValidation['message'];
    }
  }
  
  // Validate username
  if (empty($username)) {
    $errors[] = "名前は必須です";
  }
  
  // Validate age
  if (empty($age) || $age === "年齢を選択してください") {
    $errors[] = "年齢を選択してください";
  } elseif (!is_numeric($age) || $age < 18 || $age > 100) {
    $errors[] = "有効な年齢を選択してください";
  }
  
  // Validate gender
  if (empty($gender) || !in_array($gender, ['男', '女'])) {
    $errors[] = "性別を選択してください";
  }
  
  // Validate profile picture
  $pictureValid = false;
  if (isset($_FILES["profilePicture"]) && $_FILES["profilePicture"]["error"] === UPLOAD_ERR_OK) {
    $pictureValidation = validateImageFile($_FILES["profilePicture"]);
    if ($pictureValidation['valid']) {
      $pictureValid = true;
    } else {
      $errors[] = $pictureValidation['message'];
    }
  } else {
    $errors[] = "プロフィール写真をアップロードしてください";
  }
  
  // If all validations pass, proceed with registration
  if (empty($errors) && $pictureValid) {
    try {
      // Check if login ID already exists
      $checkUserSql = "SELECT loginId FROM Users WHERE loginId = ?";
      $stmt = $conn->prepare($checkUserSql);
      $stmt->bindValue(1, $loginId);
      $stmt->execute();
      
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (empty($result)) {
        $conn->beginTransaction();
        
        // Hash password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user
        $registerSql = 
          "INSERT INTO Users (loginId, password, username, gender, age) 
          VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($registerSql);
        
        $stmt->bindValue(1, $loginId);
        $stmt->bindValue(2, $hashedPassword);
        $stmt->bindValue(3, $username);
        $stmt->bindValue(4, $gender);
        $stmt->bindValue(5, (int)$age);
        $stmt->execute();
        
        $lastInsertId = $conn->lastInsertId();
        
        // Process and store profile picture
        $pictureName = testInputValue($_FILES["profilePicture"]["name"]);
        $pictureType = $_FILES["profilePicture"]["type"];
        $pictureTmpName = $_FILES["profilePicture"]["tmp_name"];
        $pictureFile = file_get_contents($pictureTmpName);
        $pictureContents = base64_encode($pictureFile);
        
        $uploadPictureSql = 
          "INSERT INTO User_Pictures (userId, pictureName, pictureType, pictureContents) 
          VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($uploadPictureSql);
        
        $stmt->bindValue(1, $lastInsertId);
        $stmt->bindValue(2, $pictureName);
        $stmt->bindValue(3, $pictureType);
        $stmt->bindValue(4, $pictureContents);
        $stmt->execute();
        
        $conn->commit();
        setErrorMessage("登録が完了しました。ログインしてください。");
        header("Location: ../pages/Login.php");
        exit;
      } else {
        setErrorMessage("このログインIDは既に登録されています");
      } 
    } catch (PDOException $e) {
      error_log("Registration error: " . $e->getMessage());
      if ($conn->inTransaction()) {
        $conn->rollback();
      }
      setErrorMessage("登録処理中にエラーが発生しました");
    }
  } else {
    // Combine all errors
    setErrorMessage(implode("<br>", $errors));
  }
  
  header("Location: ../pages/Register.php");
  exit;
}