<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | MATCHED LIST</title>
    <link rel="icon" href="../assets/icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../assets/css/Stylesheet.css">
  </head>
  <body class="bg-info-subtle">
    <?php
    include_once("../components/CheckInput.php");
    include_once("../database/SelectMatchedList.php");
    include_once("../components/CommonTools.php");
    ?>
    <div class="container p-4 bg-info-subtle">
      <div class="text-center text-danger"><?php displayErrorMessage();?></div>
      <?php 
      foreach ($result as $users):
        $targetUserId = testInputValue($users["userId"]);
        $username = testInputValue($users["username"]);
        $age = testInputValue($users["age"]);
        $description = testInputValue($users["description"]);
        $pictureContents = testInputValue($users["pictureContents"]);
        $pictureType = testInputValue($users["pictureType"]);
      ?>
        <form method="GET" action="Message.php">
          <input type="hidden" name="messageUserId" value="<?php echo $targetUserId;?>">
          <div 
            class="card mb-3 mx-3 w-auto" 
            onclick="this.parentNode.submit()" 
            style="cursor: pointer;"
          >
            <div class="row g-0">
              <div class="col-auto">
                <a href="Profile.php?targetUserId=<?php echo $targetUserId; ?>">
                  <img 
                    <?php echo "src='data: $pictureType; base64, $pictureContents'"; ?> 
                    class="me-3 object-fit-scale border rounded"
                    height="150px" width="150px"
                  >
                </a>
              </div>
              <div class="col-8">
                <div class="card-body">
                  <h3 class="card-title"><?php echo "$username ($age) " ?></h3>
                  <p class="card-text text-truncate"><?php echo $description ?></p>
                </div>
              </div>
            </div>
          </div>
        </form>
      <?php endforeach; ?>
    </div>
  </body>
</html>