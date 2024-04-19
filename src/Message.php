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
      
      <?php 
        foreach ($result as $users) { 
          $senderId = $users["senderId"];
          $senderName = $users["senderName"];
          $messageContent = $users["messageContent"];
          $description = $users["description"];
          $pictureContents = $users["pictureContents"];
          $pictureType = $users["pictureType"];
          
          if ($senderId === $loginUserId) {
            echo "<div class='d-flex flex-row-reverse mb-3'>";
            echo "<img src='data: $pictureType; base64, $pictureContents' class='rounded-circle border border-info' height='75px' width='75px'>";
            echo "<div class='rounded text-end text-break text-bg-success m-3 px-4 py-2 h5'>$messageContent</div>";
            echo "</div>";
          } else {
            echo "<div class='d-flex flex-row mb-3'>";
            echo "<img src='data: $pictureType; base64, $pictureContents' class='rounded-circle border border-info' height='75px' width='75px'>";
            echo "<div class='rounded text-start text-break text-bg-success m-3 px-4 py-2 h5'>$messageContent</div>";
            echo "</div>";
          }
        }
      ?>
      <form class="container fixed-bottom bg-light p-4" method="POST" action="Message.php">
        <input type="hidden" name="loginUserId" value="<?php echo $loginUserId; ?>">
        <input type="hidden" name="messageUserId" value="<?php echo $messageUserId; ?>">
        <div class="row mx-1">
          <input 
            type="text" class="form-control col" 
            name="message" placeholder="メッセージを入力してくだい"
          >
          <div class="col-auto">
            <button type="submit" name="sendMessage" value="sent" class="btn btn-primary">送る</button>
          </div>
        </div>
      </form>
    </div>
  </body>
</html>