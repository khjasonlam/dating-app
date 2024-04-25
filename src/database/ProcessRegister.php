<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

if (isset($_POST["registerSubmit"])) {
  $inputValue = isset($_POST["loginId"]) && !empty($_POST["password"]) && 
    isset($_POST["username"]) && ($_POST["age"] !== "年齢を選択していください") && 
    isset($_POST["gender"]) && is_uploaded_file($_FILES["profilePicture"]["tmp_name"]);
  
  if ($inputValue) {
    $loginId = testInputValue($_POST["loginId"]);
    $password = testInputValue($_POST["password"]);
    $username = testInputValue($_POST["username"]);
    $age = testInputValue($_POST["age"]);
    $gender = testInputValue($_POST["gender"]);
    
    $pictureName = $_FILES["profilePicture"]["name"];
    $pictureType = $_FILES["profilePicture"]["type"];
    $pictureSize = $_FILES["profilePicture"]["size"];
    $pictureTmpName = $_FILES["profilePicture"]["tmp_name"];
    $pictureFile = file_get_contents($pictureTmpName);
    $pictureContents = base64_encode($pictureFile);
    
    if ($pictureSize <= MAX_SIZE) {
      try {
        $checkUserSql = "SELECT loginId FROM Users WHERE loginId = ?";
        $stmt = $conn->prepare($checkUserSql);
        $stmt->bindValue(1, $loginId);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (empty($result)) {
          $conn->beginTransaction();
          
          $registerSql = 
            "INSERT INTO Users (loginId, password, username, gender, age) 
            VALUES (?, ?, ?, ?, ?)";
          $stmt = $conn->prepare($registerSql);
          
          $stmt->bindValue(1, $loginId);
          $stmt->bindValue(2, $password);
          $stmt->bindValue(3, $username);
          $stmt->bindValue(4, $gender);
          $stmt->bindValue(5, $age);
          $stmt->execute();
          
          $lastInsertId = $conn->lastInsertId();
          
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
          header("Location: ../pages/Login.php");
          exit;
        } else {
          setErrorMessage("このログインIDが登録済みです");
        } 
      } catch (PDOException $e) {
        setErrorMessage("DB登録失敗" . $e->getMessage());
        $conn->rollback();
      }
    } else {
      setErrorMessage("画像サイズが1Mを超えました");
    }
  } else {
    setErrorMessage("すべてが必須項目");
  }
  header("Location: ../pages/Register.php");
  exit;
}