<?php
include_once("Pdo.php");
include_once("../components/CheckInput.php");

if (isset($_POST["editProfileSubmit"])) {
  $inputValue = !empty($_POST["username"]) && isset($_POST["gender"]) && 
    ($_POST["age"] !== "年齢を選択していください");
  
  if ($inputValue) {
    try {
      $conn->beginTransaction();
      
      $updateProfileSql = 
        "UPDATE Users SET username = ?, age = ?, gender = ?,
        height = ?, weight = ?, bloodType = ?, location = ?, interests = ?,
        description = ?, education = ?, occupation = ?, 
        smokingHabits = ?, drinkingHabits = ? WHERE userId = ?";
      $stmt = $conn->prepare($updateProfileSql);
      
      $count = 1;
      foreach ($_POST as $postKey => $postValue) {
        if ($postKey !== "editProfileSubmit" && $postKey !== "profilePicture") {
          $postValue = testInputValue($postValue);
          $stmt->bindValue($count, $postValue);
          $count++;
        }
      }
      $stmt->execute();
      
      $uploadPicture = is_uploaded_file($_FILES["profilePicture"]["tmp_name"]);
      if ($uploadPicture) {
        $pictureName = $_FILES["profilePicture"]["name"];
        $pictureType = $_FILES["profilePicture"]["type"];
        $pictureSize = $_FILES["profilePicture"]["size"];
        $pictureTmpName = $_FILES["profilePicture"]["tmp_name"];
        $pictureFile = file_get_contents($pictureTmpName);
        $pictureContents = base64_encode($pictureFile);
        
        if ($pictureSize <= MAX_SIZE) {
          $updatePictureSql = 
            "UPDATE User_Pictures SET pictureName = ?, pictureType = ?, 
            pictureContents = ? WHERE userId = ?";
          $stmt = $conn->prepare($updatePictureSql);
          
          $stmt->bindValue(1, $pictureName);
          $stmt->bindValue(2, $pictureType);
          $stmt->bindValue(3, $pictureContents);
          $stmt->bindValue(4, $_POST["userId"]);
          $stmt->execute();
          
          $conn->commit();
          header("Location: ../pages/Profile.php");
        } else {
          setErrorMessage("画像サイズが1Mを超えました");
          $conn->rollback();
          header("Location: ../pages/EditProfile.php");
        }
      } else {
        $conn->commit();
        header("Location: ../pages/Profile.php");
      }
    } catch (PDOException $e) {
      setErrorMessage("DB登録失敗: " . $e->getMessage());
      $conn->rollback();
      header("Location: ../pages/EditProfile.php");
    }
  } else {
    setErrorMessage("名前、年齢、性別が必須項目です");
    header("Location: ../pages/EditProfile.php");
  }
}