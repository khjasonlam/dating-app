<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | LIKE</title>
    <link rel="icon" href="../assets/icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../assets/css/Style.css">
  </head>
  <body>
    <?php
      include_once("../components/CommonTools.php");
      include_once("../components/CheckInput.php");
      include_once("../database/SelectInteractions.php");
    ?>
    <?php 
      $matched = getMatchedUserSession();
      if (isset($matched)):
    ?>
      <div class='z-3 bg-danger position-absolute w-100 h-100' id='success'>
        <div class="position-absolute text-center top-50 start-50 translate-middle fs-1">
          <img 
            src="../assets/icon/arrow-through-heart-fill.svg" 
            width="200" height="200"
          >
          <br>
          マッチしました
        </div>
      </div>
    <?php endif; ?>
    <div class="container p-4 bg-light">
      <div class="text-center text-danger"><?php displayErrorMessage();?></div>
      <div class="row row-cols-1 row-cols-md-3 g-3">
        <?php 
          foreach ($result as $key => $users):
            $targetUserId = $users['userId'];
            $username = $users['username'];
            $age = $users['age'];
            $gender = $users['gender'];
            $pictureType = $users['pictureType'];
            $pictureContents = $users['pictureContents'];
        ?>
          <div class="col">
            <div class="card h-100 mx-1 text-center">
              <a href="Profile.php?targetUserId=<?php echo $targetUserId; ?>">
                <img 
                  <?php echo "src='data: $pictureType; base64, $pictureContents'"; ?>
                  class="card-img-top object-fit-scale border rounded" 
                  alt="profilePicture" height="300px" width="200px"
                >
              </a>
              <div class="card-body">
                <h5 class="card-title">
                  <?php echo $username; ?>
                </h5>
                <p class="card-text">
                  <?php echo "$gender ($age)"; ?>
                </p>
                <form class="d-grid gap-2" method="POST" action="../database/ProcessInteractions.php">
                  <input type="hidden" name="loginUserId" value="<?php echo getUserIdSession(); ?>">
                  <input type="hidden" name="targetUserId" value="<?php echo $targetUserId; ?>">
                  <button type="submit" class="btn btn-danger" name="likeSubmit" value="like"> 
                    <img src="../assets/icon/balloon-heart-fill.svg" width="32" height="32" class="m-1">
                  </button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div> 
    <script src="../assets/js/MatchedSuccess.js"></script>
  </body>
</html>