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
  <body class="bg-info-subtle">
    <?php
      include_once("Pdo.php");
      include_once("CommonTools.php");
      include_once("LoginStatus.php");
      include_once("CheckInput.php");
      include_once("SelectProfileItem.php");
      
      $loginUserId = $_SESSION["userId"];
      $messageUserId = $_POST["messageUserId"];
      
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["sendMessage"])) {
          $inputValue = !empty($_POST["message"]);
          
          if ($inputValue) {
            try {
              $insertMessageSql = 
                "INSERT INTO Messages (senderId, receiverId, messageContent) VALUES (?, ?, ?)";
              $stmt = $conn->prepare($insertMessageSql);
              
              $stmt->bindValue(1, $loginUserId);
              $stmt->bindValue(2, $messageUserId);
              $stmt->bindValue(3, $_POST["message"]);
              $stmt->execute();
              
            } catch (PDOException $e) {
              $errorMessage = "送信失敗";
              echo $e->getMessage();
            }
          } else {
            $errorMessage = "入力してください";
          }
        }
        if (isset($messageUserId)) {
          try {
            $SelectMessageSql = 
              "SELECT m.senderId, u.username as senderName, m.messageContent, 
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
            var_dump($e->getMessage());
          }
        }
      }
    ?>
    <div class="container p-4 bg-light">
    
    <div class="card mb-3" style="height: 70vh;">
      <div class="card-header">
        <div class="row">
          <div class="col-2 h5 py-1 m-0">
            <a class="link-dark link-offset-2 link-offset-2-hover link-underline 
              link-underline-opacity-0 link-underline-opacity-75-hover" 
              href="/dating-app/src/MatchedList.php">
              ＜戻る
            </a>
          </div>
          <div class="col-8 text-center h4 m-0">
            <a class="link-dark link-underline link-underline-opacity-0"
              href="Profile.php?targetUserId=<?php echo $messageUserId; ?>">
              <?php echo $username; ?>
            </a>    
          </div>
        </div>
      </div>
      <div class="card-body overflow-auto">
        <?php 
          foreach ($result as $users) { 
            $senderId = $users["senderId"];
            $senderName = $users["senderName"];
            $messageContent = $users["messageContent"];
            $description = $users["description"];
            $pictureContents = $users["pictureContents"];
            $pictureType = $users["pictureType"];
            
            if ($senderId === $loginUserId) {
              echo 
                "<div class='d-flex flex-row-reverse my-2'>
                  <img src='data: $pictureType; base64, $pictureContents' 
                    class='rounded-circle border my-1' height='60px' width='60px'>
                  <div class='rounded text-break text-bg-primary m-3 px-4 py-2 h5' 
                    style='max-width: 350px'>
                    $messageContent
                  </div>
                </div>";
            } else {
              echo 
                "<div class='d-flex flex-row my-2'>
                  <img src='data: $pictureType; base64, $pictureContents' 
                    class='rounded-circle border my-1' height='60px' width='60px'>
                  <div class='rounded text-break text-bg-primary m-3 px-4 py-2 h5' 
                    style='max-width: 350px'>
                    $messageContent
                  </div>
                </div>";
            }
          }
        ?>
      <form class="container fixed-bottom bg-light p-4 rounded" method="POST" action="Message.php">
        <input type="hidden" name="loginUserId" value="<?php echo $loginUserId; ?>">
        <input type="hidden" name="messageUserId" value="<?php echo $messageUserId; ?>">
        <div class="row mx-1">
          <input 
            type="text" class="form-control form-control-lg col" 
            name="message" placeholder="メッセージを入力してくだい"
          >
          <div class="col-auto">
            <button type="submit" name="sendMessage" value="sent" class="btn btn-primary btn-lg">送る</button>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>