<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | PROFILE</title>
    <link rel="icon" href="../assets/icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../assets/css/Style.css">
  </head>
  <body>
    <?php
    include_once("../components/CheckInput.php");
    include_once("../database/SelectProfileItem.php");
    include_once("../components/CommonTools.php");
    ?>
    <div class="container p-4 bg-light">
      <div class="text-center text-danger"><?php displayErrorMessage();?></div>
      <div class="row mx-3">
        <div class="col-md-6 text-center">
          <img 
            <?php echo "src='data: $pictureType; base64, $pictureContents'"; ?> 
            alt="profile_picture" class="object-fit-scale border rounded mx-4" 
            height="auto" width="auto" style="max-width: 300px;"
          >
          <div class="d-grid gap-2 col-9 mx-auto my-3">
          <?php if ($displayUserId === getUserIdSession()): ?>
          <a href="EditProfile.php" class="btn btn-outline-dark" type="button">
            プロフィール編集
          </a>
          <a href="../database/DeleteAccount.php" class="btn btn-outline-danger" type="button">
            アカウント削除
          </a>
          <?php elseif (empty($liked)): ?>
          <form class="d-grid gap-2" method="POST" action="../database/ProcessInteractions.php">
            <input type="hidden" name="targetUserId" value="<?php echo $displayUserId; ?>">
            <input type="hidden" name="likePage" value="profile">
            <button type="submit" class="btn btn-danger" name="likeSubmit" value="like"> 
              <img src="../assets/icon/balloon-heart-fill.svg" width="18" height="18">
            </button>
          </form>
          <?php elseif (!empty($matched)): ?>
          <a 
            href="Message.php?messageUserId=<?php echo $matched["userId"]; ?>" 
            class="btn btn-outline-success" type="button"
          >
            メッセージを送る
          </a>
          <?php endif; ?>
          </div>
        </div>
        <div class="col-md-6 p-4 card" style="height: 80vh; overflow-y: auto;">
          <div class="mb-3 h1 strong"><?php echo $username; ?></div>
          <?php
          foreach ($profileArray as $key => $value) {
            echo 
              "<div class='hstack py-2'>
                <div class='p-2 h5 strong' style='min-width: 25%;'>$key</div>
                <div class='vr'></div>
                <div class='p-2 mx-2'>$value</div>
              </div>";
          }
          ?>
        </div>
      </div>
    </div>
  </body>
</html>