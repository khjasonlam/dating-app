<?php 
  session_start(); 
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DATING APP | MATCHED LIST</title>
    <link rel="icon" href="../icon/calendar-heart-fill.svg">
    <link rel="stylesheet" href="../css/Style.css">
  </head>
  <body class="bg-info-subtle">
    <?php
      include_once("Pdo.php");
      include_once("CommonTools.php");
      include_once("LoginStatus.php");
      include_once("CheckInput.php");
      
      try {
        $matchedSql = 
          "SELECT ui2.userId, u.username, u.age, u.description, 
            up.pictureContents, up.pictureType FROM User_Interactions ui1 
            INNER JOIN User_Interactions ui2 ON ui1.userId = ui2.TargetUserId 
            AND ui1.TargetUserId = ui2.userId
            INNER JOIN Users u ON ui2.userId = u.userId
            INNER JOIN User_Pictures up ON u.userId = up.userId
            WHERE ui1.interactionType = 'like' AND ui2.interactionType = 'like' 
            AND ui1.userId = ?";
          $stmt = $conn->prepare($matchedSql);
          
          $stmt->bindValue(1, $_SESSION["userId"]);
          $stmt->execute();
          
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $row = $stmt->rowCount();
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    ?>
    <div class="container p-4 bg-light">
      <?php 
        if (empty($result)) {
          echo "マッチングした相手がいません";
        } else {
          foreach ($result as $users) { 
            $targetUserId = $users["userId"];
            $username = $users["username"];
            $age = $users["age"];
            $description = $users["description"];
            $pictureContents = $users["pictureContents"];
            $pictureType = $users["pictureType"];
      ?>
        <form method="POST" action="Message.php">
          <input type="hidden" name="messageUserId" value="<?php echo $targetUserId;?>">
          <div class="card mb-3 mx-3 w-auto" onclick="this.parentNode.submit()" style="cursor: pointer;">
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
      <?php 
          }
        } 
      ?>
    </div>
  </body>
</html>