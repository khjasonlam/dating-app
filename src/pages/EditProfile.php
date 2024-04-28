<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | EDIT PROFILE</title>
    <link rel="icon" href="../assets/icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../assets/css/Stylesheet.css">
  </head>
  <body>
    <?php
    include_once("../components/CheckInput.php");
    include_once("../database/SelectProfileItem.php");
    include_once("../components/CommonTools.php");
    
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
    ?>
    <div class="container p-4 bg-light">
      <form class="row g-4" method="POST" action="../database/UpdateEditProfile.php">
        <!-- username -->
        <div class="col-md-5">
          <?php profileTextField($result['username'], "名前", "username"); ?>
        </div>
        <!-- age -->
        <div class="col-md-3">
          <label for="age" class="form-label">年齢</label>
            <select class="form-select form-select-lg" name="age">
              <option>年齢を選択していください</option>
              <?php
              // Generate options for ages 18 to selected age
              $age = $result['age'];
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
                  <?php if ($result['gender'] === "男") echo "checked";?>
                >
                <label class="btn btn-outline-dark btn-lg px-5" for="male">男</label>
              </div>
              <div class="col-6 col-md-6 d-grid">
                <input 
                  type="radio" class="btn-check" name="gender" id="female" value="女"
                  <?php if ($result['gender'] === "女") echo "checked";?>
                >
                <label class="btn btn-outline-dark btn-lg px-5" for="female">女</label>
              </div>
            </div>
          </div>
        </div>
        <!-- height -->
        <div class="col-md-3">
          <?php profileTextField($result['height'], "身長", "height"); ?>
        </div>
        <!-- weight -->
        <div class="col-md-3">
          <?php profileTextField($result['weight'], "体重", "weight"); ?>
        </div>
        <!-- blood Type -->
        <div class="col-md-3">
          <?php profileTextField($result['bloodType'], "血液型", "bloodType"); ?>
        </div>
        <!-- location -->
        <div class="col-md-3">
          <?php profileTextField($result['location'], "出身地", "location"); ?>
        </div>
        <!-- interests -->
        <div class="col-md-12">
          <?php profileTextArea($result['interests'], "趣味", "interests"); ?>
        </div>
        <!-- description -->
        <div class="col-md-12">
          <?php profileTextArea($result['description'], "自己紹介", "description"); ?>
        </div>
        <!-- education -->
        <div class="col-md-3">
          <?php profileTextField($result['education'], "学歴", "education"); ?>
        </div>
        <!-- occupation -->
        <div class="col-md-3">
          <?php profileTextField($result['occupation'], "職業", "occupation"); ?>
        </div>
        <!-- smokingHabits -->
        <div class="col-md-3">
          <?php profileTextField($result['smokingHabits'], "喫煙", "smokingHabits"); ?>
        </div>
        <!-- drinkingHabits -->
        <div class="col-md-3">
          <?php profileTextField($result['drinkingHabits'], "飲酒", "drinkingHabits"); ?>
        </div>
        <!-- profile picture -->
        <div class="col-md-12">
          <label for="profilePicture" class="form-label">プロフィール写真</label>
          <input 
            type="file" class="form-control form-control-lg" 
            name="profilePicture" id="profilePicture"
          >
        </div>
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
        <input type="hidden" name="userId" value="<?php echo $result['userId'];?>">
        <div class="text-center text-danger"><?php displayErrorMessage();?></div>
      </form>
    </div>
  </body>
</html>