<?php 
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | REGISTER PAGE</title>
    <link rel="icon" href="../icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../css/Style.css">
  </head>
  <body class="bg-info-subtle">
    <?php
      include_once("Pdo.php");
      include_once("CommonTools.php");
      include_once("CheckInput.php");
      
      define("MAX_SIZE", 1048576);
      
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["registerSubmit"])) {
          $inputValue = isset($_POST["loginId"]) && !empty($_POST["password"]) && 
            isset($_POST["username"]) && isset($_POST["age"]) && isset($_POST["gender"]) &&
            is_uploaded_file($_FILES["profilePicture"]["tmp_name"]);
          
          if ($inputValue) {
            $loginId = testInputValue($_POST["loginId"]);
            $password = testInputValue($_POST["password"]);
            $username = testInputValue($_POST["username"]);
            $age = $_POST["age"];
            $gender = $_POST["gender"];
            
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
                  
                  // echo $lastInsertId . "<br>";
                  
                  $uploadPictureSql = 
                    "INSERT INTO User_Pictures (userId, pictureName, pictureType, pictureContents) 
                    VALUES (?, ?, ?, ?)";
                  $stmt = $conn->prepare($uploadPictureSql);
                  
                  // echo $uploadPictureSql . "<br>";
                  
                  $stmt->bindValue(1, $lastInsertId);
                  $stmt->bindValue(2, $pictureName);
                  $stmt->bindValue(3, $pictureType);
                  $stmt->bindValue(4, $pictureContents);
                  $stmt->execute();
                  
                  $conn->commit();
                  header("Location: Login.php");
                } else {
                  $errorMessage = "このログインIDが登録済みです";
                } 
              } catch (Exception $exception) {
                $errorMessage = "登録失敗";
                echo $e->getMessage();
                $conn->rollback();
              }
            } else {
              $errorMessage = "画像サイズが1Mを超えました";
            }
          } else {
            $errorMessage = "すべてが必須項目です";
          }
        }
      }
    ?>
    <div class="container p-4 bg-light">
      <form class="row g-4" method="POST" action="Register.php">
        <!-- username -->
        <div class="col-md-6">
          <label for="loginId" class="form-label">ログインID</label>
          <input 
            type="text" class="form-control form-control-lg" 
            name="loginId" placeholder="ログインIDを入力してくだい"
          >
        </div>
        <!-- password -->
        <div class="col-md-6">
          <label for="password" class="form-label">バスワード</label>
          <input 
            type="password" class="form-control form-control-lg" 
            name="password" placeholder="バスワードを入力してくだい"
          >
        </div>
        <!-- username -->
        <div class="col-md-12">
          <label for="username" class="form-label">名前</label>
          <input 
            type="text" class="form-control form-control-lg" 
            name="username" placeholder="名前を入力してくだい"
          >
        </div>
        <!-- profile picture -->
        <div class="col-md-5">
          <label for="profilePicture" class="form-label">プロフィール写真</label>
          <input 
            type="file" class="form-control form-control-lg" 
            name="profilePicture" id="profilePicture"
          >
        </div>
        <!-- age -->
        <div class="col-md-3">
          <label for="age" class="form-label">年齢</label>
          <select class="form-select form-select-lg" name="age">
            <option selected>年齢を選択してください</option>
            <?php
              // Generate options for ages 18 to 100
              for ($ageRange = 18; $ageRange <= 100; $ageRange++) {
                echo "<option value='$ageRange'>$ageRange</option>";
              }
            ?>
          </select>
        </div>
        <!-- gender -->
        <div class="col-md-4">
          <label for="gender" class="form-label">性別</label>
          <div class="form-check px-0">
            <div class="row g-2">
              <div class="col-md-6 col-6 d-grid">
                <input type="radio" class="btn-check" name="gender" id="male" value="男">
                <label class="btn btn-outline-dark btn-lg px-5" for="male">男</label>
              </div>
              <div class="col-md-6 col-6 d-grid">
                <input type="radio" class="btn-check" name="gender" id="female" value="女">
                <label class="btn btn-outline-dark btn-lg px-5" for="female">女</label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 text-center text-danger"><?php echo $errorMessage;?></div>
        <!-- submit -->
        <div class="col-md-6 d-grid">
          <input 
            type="submit" class="btn btn-outline-info btn-lg my-2" 
            value="登録する" name="registerSubmit"
            formenctype="multipart/form-data"
          >
        </div>
        <!-- back to login page -->
        <div class="col-md-6 d-grid">
          <a type="button" href='Login.php' class="btn btn-outline-dark btn-lg my-2">
            ログイン画面に戻る
          </a>
        </div>
      </form>
    </div>
  </body>
</html>