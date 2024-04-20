<?php 
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | MESSAGE</title>
    <link rel="icon" href="../icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../css/Style.css">
  </head>
  <body>
    <?php
      include_once("Pdo.php");
      include_once("CommonTools.php");
      include_once("LoginStatus.php");
      include_once("CheckInput.php");
      include_once("SelectProfileItem.php");
      
      $error = new ErrorMessage;
      
      $loginUserId = $_SESSION["userId"];      
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
      
      if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $messageUserId = $_GET["messageUserId"];
        if (isset($messageUserId)) {
          try {
            $SelectMessageSql = 
              "SELECT m.senderId, m.messageContent, 
              up.pictureContents, up.pictureType FROM Messages m 
              LEFT JOIN Users u ON m.senderId = u.userId 
              LEFT JOIN User_Pictures up ON m.senderId = up.userId 
              WHERE m.senderId = ? AND m.receiverId = ? 
              OR m.senderId = ? AND m.receiverId = ?
              ORDER BY m.timestamp ASC;";
            $stmt = $conn->prepare($SelectMessageSql);
            
            $stmt->bindValue(1, $loginUserId);
            $stmt->bindValue(2, $messageUserId);
            $stmt->bindValue(3, $messageUserId);
            $stmt->bindValue(4, $loginUserId);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $row = $stmt->rowCount();
            
          } catch (PDOException $e) {
            $error->setErrorMessage("メッセージ取得失敗：" . $e->getMessage());
          }
        }
      }
    ?>
    <div class="container p-4 bg-light">
    <div class="text-center text-danger"><?php $error->displayErrorMessage();?></div>
    <div class="card mb-3" style="height: 70vh;">
      <div class="card-header">
        <div class="row">
          <div class="col-2 h5 py-1 m-0">
            <a 
              class=
                "link-dark link-offset-2 link-offset-2-hover link-underline 
                link-underline-opacity-0 link-underline-opacity-75-hover" 
              href="/dating-app/src/MatchedList.php"
            >
              ＜戻る
            </a>
          </div>
          <div class="col-8 text-center h4 m-0">
            <a 
              class="link-dark link-underline link-underline-opacity-0"
              href="Profile.php?targetUserId=<?php echo $messageUserId; ?>"
            >
              <?php echo $username; ?>
            </a>    
          </div>
        </div>
      </div>
      <div class="card-body overflow-auto">
        <?php 
          foreach ($result as $users) { 
            if ($users["senderId"] === $loginUserId) {
              displayMessage("flex-row-reverse", $users);
            } else {
              displayMessage("flex-row", $users);
            }
          }
        ?>
      <form 
        class="container fixed-bottom bg-light p-4 rounded" 
        method="POST" action="ProcessMessage.php"
      >
        <input type="hidden" name="loginUserId" value="<?php echo $loginUserId; ?>">
        <input type="hidden" name="messageUserId" value="<?php echo $messageUserId; ?>">
        <div class="row mx-1">
          <input 
            type="text" class="form-control form-control-lg col" 
            name="message" placeholder="メッセージを入力してくだい"
          >
          <div class="col-auto">
            <button 
              type="submit" name="sendMessage" value="sent" 
              class="btn btn-primary btn-lg"
            >
              送る
            </button>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>