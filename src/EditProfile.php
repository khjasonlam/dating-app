<?php 
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | EDIT PROFILE</title>
    <link rel="icon" href="../icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../style/css/bootstrap.css">
  </head>
  <body class="bg-info-subtle">
    <?php
      include_once("Pdo.php");
      include_once("CommonTools.php");
      include_once("LoginStatus.php");
      include_once("CheckInput.php");
      include_once("SelectProfileItem.php");
      
      function profileTextField($itemValue, $itemTitle, $itemKey) {
        echo "<label for='$itemKey' class='form-label'>$itemTitle</label>";
        echo "<input type='text' class='form-control form-control-lg' 
          name='$itemKey' placeholder='$itemTitle"."を入力して下さい' value='$itemValue'>";
      }
      function profileTextArea($itemValue, $itemTitle, $itemKey) {
        echo "<label for='$itemKey' class='form-label'>$itemTitle</label>";
        echo "<textarea class='form-control form-control-lg' name='$itemKey' 
          placeholder='$itemTitle"."を入力して下さい'>$itemValue</textarea>";
      }
      
      try {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
          if (isset($_POST["editProfileSubmit"])) {
            if (isset($_POST["username"]) && isset($_POST["gender"]) && 
            ($_POST["age"] !== "年齢を選択していください")) {
              $updateProfileSql = 
                "UPDATE Users SET username = ?, age = ?, gender = ?,
                height = ?, weight = ?, bloodType = ?, location = ?, interests = ?,
                description = ?, education = ?, occupation = ?, 
                smokingHabits = ?, drinkingHabits = ? WHERE userId = ?";
              $stmt = $conn->prepare($updateProfileSql);
              
              $count = 1;
              foreach ($_POST as $postKey => $postValue) {
                if ($postKey !== "editProfileSubmit") {
                  $postValue = testInputValue($postValue);
                  $stmt->bindValue($count, $postValue);
                  $count++;
                }
              }
              $stmt->execute();
              header("Location: Profile.php");
            } else {
              $errorMessage = "名前、年齢、性別が必須項目です";
            }
          }
        }
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    ?>
    <div class="container p-4 bg-light">
      <form class="row g-4" method="POST" action="editProfile.php">
        <!-- username -->
        <div class="col-md-5">
          <?php profileTextField($result["username"], "名前", "username"); ?>
        </div>
        <!-- age -->
        <div class="col-md-3">
          <label for="age" class="form-label">年齢</label>
            <select class="form-select form-select-lg" name="age">
              <option>年齢を選択していください</option>
              <?php
                // Generate options for ages 18 to selected age
                for ($ageRange = 18; $ageRange < 100; $ageRange++) {
                  if ($ageRange === $age) {
                    echo "<option selected value='$age'>$age</option>";
                  } else {
                    echo "<option value='$ageRange'>$ageRange</option>";
                  }
                }
              ?>
            </select>
        </div>
        <!-- gender -->
        <div class="col-md-4">
          <label for="gender" class="form-label">性別</label>
          <div class="form-check px-0">
            <div class="row">
              <div class="col-6 col-md-6 d-grid">
                <input 
                  type="radio" class="btn-check" name="gender" id="male" value="男"
                  <?php if ($gender === "男") echo "checked";?>
                >
                <label class="btn btn-outline-dark btn-lg px-5" for="male">男</label>
              </div>
              <div class="col-6 col-md-6 d-grid">
                <input 
                  type="radio" class="btn-check" name="gender" id="female" value="女"
                  <?php if ($gender === "女") echo "checked";?>
                >
                <label class="btn btn-outline-dark btn-lg px-5" for="female">女</label>
              </div>
            </div>
          </div>
        </div>
        <!-- height -->
        <div class="col-md-3">
          <?php profileTextField($result["height"], "身長", "height"); ?>
        </div>
        <!-- weight -->
        <div class="col-md-3">
          <?php profileTextField($result["weight"], "体重", "weight"); ?>
        </div>
        <!-- blood Type -->
        <div class="col-md-3">
          <?php profileTextField($result["bloodType"], "血液型", "bloodType"); ?>
        </div>
        <!-- location -->
        <div class="col-md-3">
          <?php profileTextField($result["location"], "出身地", "location"); ?>
        </div>
        <!-- interests -->
        <div class="col-md-12">
          <?php profileTextArea($result["interests"], "趣味", "interests"); ?>
        </div>
        <!-- description -->
        <div class="col-md-12">
          <?php profileTextArea($result["description"], "自己紹介", "description"); ?>
        </div>
        <!-- education -->
        <div class="col-md-3">
          <?php profileTextField($result["education"], "学歴", "education"); ?>
        </div>
        <!-- occupation -->
        <div class="col-md-3">
          <?php profileTextField($result["occupation"], "職業", "occupation"); ?>
        </div>
        <!-- smokingHabits -->
        <div class="col-md-3">
          <?php profileTextField($result["smokingHabits"], "喫煙", "smokingHabits"); ?>
        </div>
        <!-- drinkingHabits -->
        <div class="col-md-3">
          <?php profileTextField($result["drinkingHabits"], "飲酒", "drinkingHabits"); ?>
        </div>
        <!-- profile picture -->
        <!-- <div class="col-md-12">
          <label for="profilePicture" class="form-label">Profile Picture</label>
          <input 
            type="file" class="form-control form-control-lg" 
            id="profilePicture" name="profilePicture"
          >
        </div> -->
        <div class="col-12 text-center text-danger"><?php echo $errorMessage;?></div>
        <!-- submit -->
        <div class="col-md-6 d-grid">
          <input 
            type="submit" class="btn btn-outline-primary btn-lg my-2" 
            value="プロフィールを更新する" name="editProfileSubmit"
            formenctype="multipart/form-data"
          >
        </div>
        <!-- back to login page -->
        <div class="col-md-6 d-grid">
          <a type="button" href='Profile.php' class="btn btn-outline-dark btn-lg my-2">
            プロフィール画面に戻る
          </a>
        </div>
        <input type="hidden" name="userId" value="<?php echo $_SESSION["userId"];?>">
      </form>
    </div>
  </body>
</html>