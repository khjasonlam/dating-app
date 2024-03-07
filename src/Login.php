<?php 
  session_start();
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | LOGIN PAGE</title>
    <link rel="icon" href="../icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../style/css/bootstrap.css">
  </head>
  <body class="bg-info-subtle">
    <?php
      include_once("pdo.php");
      include_once("CommonTools.php");
      include_once("CheckInput.php");
      
      try {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
          if (isset($_POST["loginSubmit"])) {
            if(!empty($_POST['loginId']) && !empty($_POST['password'])) {
              $loginId = testInputValue($_POST["loginId"]);
              $password = testInputValue($_POST["password"]);
              
              $loginSql = "SELECT userId FROM Users WHERE loginId = ? AND password = ?";
              $stmt = $conn->prepare($loginSql);
              
              $stmt->bindValue(1, $loginId);
              $stmt->bindValue(2, $password);
              $stmt->execute();
              $loginUser = $stmt->fetch(PDO::FETCH_ASSOC);
              
              if (!empty($loginUser)) {
                $_SESSION['userId'] = $loginUser['userId'];
                header("Location: profile.php");
              } else {
                $errorMessage = "ログインID又はパスワードが間違いました";
              }
            } else {
              $errorMessage = "必須項目です";
            }
          }
        }
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    ?>
    <div class="container p-5 bg-light">
      <form 
        method="POST" class="row g-4 mx-3" 
        action="<?php echo htmlspecialchars($_SERVER["SCRIPT_NAME"]);?>"
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
        <div class="col-12 text-center text-danger"><?php echo $errorMessage;?></div>
        <div class="col-md-6 d-grid">
          <button type="submit" class="btn btn-info btn-lg" name="loginSubmit">ログイン</button>
        </div>
        <div class="col-md-6 d-grid">
          <a type="button" href='register.php' class="btn btn-dark btn-lg">新規登録</a>
        </div>
      </form>
    </div>
  </body>
</html>
