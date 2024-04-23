<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | LOGIN PAGE</title>
    <link rel="icon" href="../assets/icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../assets/css/Style.css">
  </head>
  <body>
    <?php
      include_once("../components/CommonTools.php");
      include_once("../components/CheckInput.php");
    ?>
    <div class="container p-5 bg-light">
      <form 
        method="POST" class="row g-4 mx-3" 
        action="../database/ProcessLogin.php"
      >
        <div class="col-12">
          <label for="loginId" class="form-label">ログインID</label>
            <input 
              type="text" class="form-control form-control-lg" 
              name="loginId" placeholder="ログインIDを入力してください"
            >
        </div>
        <div class="col-12">
          <label for="password" class="form-label">パスワード</label>
          <input 
            type="password" class="form-control form-control-lg" 
            name="password" placeholder="パスワードを入力してください"
          >
        </div>
        <div class="col-md-6 d-grid">
          <button type="submit" class="btn btn-info btn-lg" name="loginSubmit">ログイン</button>
        </div>
        <div class="col-md-6 d-grid">
          <a type="button" href='Register.php' class="btn btn-dark btn-lg">新規登録</a>
        </div>
        <div class="col-12 text-center text-danger"><?php displayErrorMessage();?></div>
      </form>
    </div>
  </body>
</html>