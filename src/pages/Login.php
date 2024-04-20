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
    <link rel="stylesheet" href="../css/Style.css">
  </head>
  <body>
    <?php
      include_once("Pdo.php");
      include_once("CommonTools.php");
      include_once("CheckInput.php");
      
      $error = new errorMessage();
      
      if (isset($_POST["loginSubmit"])) {
        if(!empty($_POST['loginId']) && !empty($_POST['password'])) {
          $loginId = testInputValue($_POST["loginId"]);
          $password = testInputValue($_POST["password"]);
          
          try {
            $loginSql = "SELECT userId FROM Users WHERE loginId = ? AND password = ?";
            $stmt = $conn->prepare($loginSql);
            
            $stmt->bindValue(1, $loginId);
            $stmt->bindValue(2, $password);
            $stmt->execute();
            $loginUser = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!empty($loginUser)) {
              $_SESSION['userId'] = $loginUser['userId'];
              header("Location: Profile.php");
            } else {
              $error->setErrorMessage("ログインID又はパスワードが間違いました");
            }
          } catch (PDOException $e) {
            $error->setErrorMessage("DB Error: " . $e->getMessage());
          }
        } else {
          $error->setErrorMessage("必須項目です");
        }
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
        <div class="col-12 text-center text-danger"><?php $error->displayErrorMessage();?></div>
        <div class="col-md-6 d-grid">
          <button type="submit" class="btn btn-info btn-lg" name="loginSubmit">ログイン</button>
        </div>
        <div class="col-md-6 d-grid">
          <a type="button" href='Register.php' class="btn btn-dark btn-lg">新規登録</a>
        </div>
      </form>
    </div>
  </body>
</html>
