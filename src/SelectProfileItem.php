<?php
  session_start();
  
  if (isset($_GET["targetUserId"])) {
    $displayUserId = $_GET["targetUserId"];
  } else if (isset($_POST["messageUserId"])) {
    $displayUserId = $_POST["messageUserId"];
  } else {
    $displayUserId = $_SESSION["userId"];
  }
  
  try {
    $profileSql = 
      "SELECT u.userId, u.username, u.gender, u.age, 
      u.bloodType, u.location, u.interests,
      u.description, u.height, u.weight, u.education, 
      u.occupation, u.smokingHabits, u.drinkingHabits,
      up.pictureContents, up.pictureType
      FROM Users u LEFT JOIN User_Pictures up 
      ON u.userId = up.userId WHERE u.userId = ?";
      
    $stmt = $conn->prepare($profileSql);
    
    $stmt->bindValue(1, $displayUserId);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
  } catch (PDOException $e) {
    var_dump($e->getMessage());
  }
  
  $username = checkValue($result['username']);
  $gender = $result['gender'];
  $age = $result['age'];
  $bloodType = checkValue($result['bloodType']);
  $location = checkValue($result['location']);
  $interests = checkValue($result['interests']);
  $description = checkValue($result['description']);
  $height = checkValue($result['height']);
  $weight = checkValue($result['weight']);
  $education = checkValue($result['education']);
  $occupation = checkValue($result['occupation']);
  $smokingHabits = checkValue($result['smokingHabits']);
  $drinkingHabits = checkValue($result['drinkingHabits']);
  
  $pictureContents = $result['pictureContents'];
  $pictureType = $result['pictureType'];
  
  $profileArray = [
    "性別" => $gender, 
    "年齢" => $age, 
    "血液型" => $bloodType, 
    "出身地" => $location, 
    "趣味" => $interests, 
    "自己紹介" => $description,
    "身長" => $height, 
    "体重" => $weight, 
    "学歴" => $education,
    "職業" => $occupation, 
    "喫煙" => $smokingHabits, 
    "飲酒" => $drinkingHabits
  ];
?>