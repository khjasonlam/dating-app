<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | LIKE</title>
    <link rel="icon" href="../assets/icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../assets/css/Stylesheet.css">
  </head>
  <body class="bg-info-subtle">
    <?php
    include_once("../components/CheckInput.php");
    include_once("../database/SelectInteractions.php");
    include_once("../components/CommonTools.php");
    ?>
    <div class="container p-4 bg-info-subtle">
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
                  <input type="hidden" name="targetUserId" value="<?php echo $targetUserId; ?>">
                  <input type="hidden" name="likePage" value="interactions">
                  <button type="submit" class="btn btn-outline-success" name="likeSubmit" value="like"> 
                    <i class="bi-heart-fill" style="font-size: 25px;"></i>
                  </button>
                  <button type="submit" class="btn btn-outline-danger" name="dislikeSubmit" value="dislike"> 
                    <i class="bi-heartbreak-fill" style="font-size: 25px;"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div> 
  </body>
</html>