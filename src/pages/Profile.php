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
      include_once("../components/CommonTools.php");
      include_once("../components/CheckInput.php");
      include_once("../database/SelectProfileItem.php");
    ?>
    <div class="container p-4 bg-light">
      <div class="row mx-3">
        <div class="col-md-5 text-center">
          <img 
            <?php echo "src='data: $pictureType; base64, $pictureContents'"; ?> 
            alt="profile_picture" class="object-fit-scale border rounded" 
            height="auto" width="auto" style="max-width: 300px;"
          >
        </div>
        <div class="col-md-7 px-4" style="height: 80vh; overflow-y: auto;">
          <div class="row">
            <div class="col-10 h1 strong">
              <?php echo $username; ?>
            </div>
            <div class="col-2">
              <?php if ($displayUserId === getUserIdSession()): ?>
                <a class="float-end" href="EditProfile.php">
                  <img src="../assets/icon/pencil-square.svg" width="32" height="32" class="m-2">
                </a>
              <?php endif; ?>
            </div>
          </div>
          <?php
            foreach ($profileArray as $key => $value) {
              echo "<div class='row my-4'>
                <div class='col-md-4 h4'>$key</div>
                <div class='col-md-8 h6'>$value</div></div>";
            }
          ?>
        </div>
      </div>
    </div>
  </body>
</html>