<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

if (isset($_POST["editProfileSubmit"])) {
  $userId = requireLogin();
  
  // Validate required fields
  $username = testInputValue($_POST["username"] ?? '');
  $age = testInputValue($_POST["age"] ?? '');
  $gender = testInputValue($_POST["gender"] ?? '');
  
  $errors = [];
  
  if (empty($username)) {
    $errors[] = "名前は必須です";
  }
  
  if (empty($age) || $age === "年齢を選択してください" || !is_numeric($age) || $age < 18 || $age > 100) {
    $errors[] = "有効な年齢を選択してください";
  }
  
  if (empty($gender) || !in_array($gender, ['男', '女'])) {
    $errors[] = "性別を選択してください";
  }
  
  if (empty($errors)) {
    try {
      $conn->beginTransaction();
      
      // Prepare update statement with explicit field order
      $updateProfileSql = 
        "UPDATE Users SET username = ?, age = ?, gender = ?,
        height = ?, weight = ?, bloodType = ?, location = ?, interests = ?,
        description = ?, education = ?, occupation = ?, 
        smokingHabits = ?, drinkingHabits = ? WHERE userId = ?";
      $stmt = $conn->prepare($updateProfileSql);
      
      // Bind values in correct order
      $stmt->bindValue(1, $username);
      $stmt->bindValue(2, (int)$age);
      $stmt->bindValue(3, $gender);
      $stmt->bindValue(4, testInputValue($_POST["height"] ?? null));
      $stmt->bindValue(5, testInputValue($_POST["weight"] ?? null));
      $stmt->bindValue(6, testInputValue($_POST["bloodType"] ?? null));
      $stmt->bindValue(7, testInputValue($_POST["location"] ?? null));
      $stmt->bindValue(8, testInputValue($_POST["interests"] ?? null));
      $stmt->bindValue(9, testInputValue($_POST["description"] ?? null));
      $stmt->bindValue(10, testInputValue($_POST["education"] ?? null));
      $stmt->bindValue(11, testInputValue($_POST["occupation"] ?? null));
      $stmt->bindValue(12, testInputValue($_POST["smokingHabits"] ?? null));
      $stmt->bindValue(13, testInputValue($_POST["drinkingHabits"] ?? null));
      $stmt->bindValue(14, $userId);
      
      $stmt->execute();
      
      // Handle profile picture update if uploaded
      if (isset($_FILES["profilePicture"]) && $_FILES["profilePicture"]["error"] === UPLOAD_ERR_OK) {
        $pictureValidation = validateImageFile($_FILES["profilePicture"]);
        
        if ($pictureValidation['valid']) {
          $pictureName = testInputValue($_FILES["profilePicture"]["name"]);
          $pictureType = $_FILES["profilePicture"]["type"];
          $pictureTmpName = $_FILES["profilePicture"]["tmp_name"];
          $pictureFile = file_get_contents($pictureTmpName);
          $pictureContents = base64_encode($pictureFile);
          
          $updatePictureSql = 
            "UPDATE User_Pictures SET pictureName = ?, pictureType = ?, 
            pictureContents = ? WHERE userId = ?";
          $stmt = $conn->prepare($updatePictureSql);
          
          $stmt->bindValue(1, $pictureName);
          $stmt->bindValue(2, $pictureType);
          $stmt->bindValue(3, $pictureContents);
          $stmt->bindValue(4, $userId);
          $stmt->execute();
        } else {
          $errors[] = $pictureValidation['message'];
        }
      }
      
      if (empty($errors)) {
        $conn->commit();
        setErrorMessage("プロフィールを更新しました");
        header("Location: ../pages/Profile.php");
        exit;
      } else {
        $conn->rollback();
        setErrorMessage(implode("<br>", $errors));
      }
    } catch (PDOException $e) {
      error_log("Update profile error: " . $e->getMessage());
      if ($conn->inTransaction()) {
        $conn->rollback();
      }
      setErrorMessage("プロフィールの更新に失敗しました");
    }
  } else {
    setErrorMessage(implode("<br>", $errors));
  }
  
  header("Location: ../pages/EditProfile.php");
  exit;
}