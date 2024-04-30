<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | REGISTER PAGE</title>
    <link rel="icon" href="../assets/icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../assets/css/Stylesheet.css">
  </head>
  <body class="bg-info-subtle">
    <?php
    include_once("../components/CheckInput.php");
    include_once("../components/CommonTools.php");
    ?>
    <div class="container p-4 bg-info-subtle">
      <form class="row g-4" method="POST" action="../database/ProcessRegister.php">
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
        <!-- age -->
        <div class="col-md-6">
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
        <div class="col-md-6">
          <label for="gender" class="form-label">性別</label>
          <div class="form-check px-0">
            <div class="btn-group btn-group-lg container px-0">
              <input type="radio" class="btn-check" name="gender" id="male" value="男">
              <label class="btn btn-outline-dark" for="male">男</label>
              <input type="radio" class="btn-check" name="gender" id="female" value="女">
              <label class="btn btn-outline-dark" for="female">女</label>
            </div>
          </div>
        </div>
        <!-- profile picture -->
        <div class="col-md-12 mb-3">
          <label for="profilePicture" class="form-label">プロフィール写真</label>
          <input 
            type="file" class="form-control form-control-lg" 
            name="profilePicture" id="profilePicture"
          >
        </div>
        <!-- submit -->
        <div class="col-md-6 d-grid">
          <input 
            type="submit" class="btn btn-info btn-lg my-2" 
            value="登録する" name="registerSubmit"
            formenctype="multipart/form-data"
          >
        </div>
        <!-- back to login page -->
        <div class="col-md-6 d-grid">
          <a type="button" href='Login.php' class="btn btn-dark btn-lg my-2">
            ログイン画面に戻る
          </a>
        </div>
        <div class="text-center text-danger"><?php displayErrorMessage();?></div>
      </form>
    </div>
  </body>
</html>