<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | MESSAGE</title>
    <link rel="icon" href="../assets/icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../assets/css/Stylesheet.css">
  </head>
  <body class="bg-info-subtle">
    <?php
    include_once("../components/CheckInput.php");
    include_once("../database/SelectProfileItem.php");
    include_once("../database/SelectMessage.php");
    include_once("../components/CommonTools.php");
    
    function displayMessage ($class, $users) {
      $messageContent = testInputValue($users["messageContent"]);
      $pictureContents = testInputValue($users["pictureContents"]);
      $pictureType = testInputValue($users["pictureType"]);
      echo 
        "<div class='d-flex $class my-2'>
          <img 
            src='data: $pictureType; base64, $pictureContents' 
            class='rounded-circle border my-1' height='60px' width='60px'
          >
          <div 
            class='rounded text-break text-bg-primary m-3 px-4 py-2 h5' 
            style='max-width: 350px'
          >
            $messageContent
          </div>
        </div>";
    }
    ?>
    <div class="container p-4 bg-info-subtle">
    <div class="card" style="height: 74vh;">
      <div class="card-header">
        <div class="row">
          <div class="col-2 h5 py-1 m-0">
            <a 
              href="MatchedList.php"
              class="link-dark link-underline link-underline-opacity-0" 
            >
              ＜戻る
            </a>
          </div>
          <div class="col-8 text-center h4 m-0">
            <a 
              href="Profile.php?targetUserId=<?php echo $messageUserId; ?>"
              class="link-dark link-underline link-underline-opacity-0"
            >
              <?php echo $username; ?>
            </a>    
          </div>
        </div>
      </div>
      <div class="card-body overflow-auto">
      <div class="text-center text-danger"><?php displayErrorMessage();?></div>
        <?php 
        foreach ($result as $users) { 
          if ($users["senderId"] === getUserIdSession()) {
            displayMessage("flex-row-reverse", $users);
          } else if ($users["senderId"] == $messageUserId) {
            displayMessage("flex-row", $users);
          }
        }
        ?>
      <form 
        class="container fixed-bottom bg-info-subtle rounded p-4" 
        method="POST" action="../database/ProcessMessage.php"
      >
        <input 
          type="hidden" name="loginUserId" 
          value="<?php echo getUserIdSession(); ?>"
        >
        <input 
          type="hidden" name="messageUserId" 
          value="<?php echo $messageUserId; ?>"
        >
        <div class="input-group">
          <input 
            type="text" class="form-control" name="message" 
            placeholder="メッセージを入力してくだい"
          >
          <button 
            type="submit" name="sendMessage" 
            value="sent" class="btn btn-primary"
          >
            <i class="bi bi-send-fill" style="font-size: 25px;"></i>
          </button>
        </div>
      </form>
    </div>
  </body>
</html>