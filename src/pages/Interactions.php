<?php
  session_start();
?>
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
      include_once("../database/Pdo.php");
      include_once("../components/CommonTools.php");
      include_once("../components/CheckInput.php");
      
      $loginUserId = $_SESSION["userId"];
      
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["likeSubmit"])) {
          try {
            $likeSql = 
              "INSERT INTO User_Interactions (userId, targetUserId, interactionType)
              VALUES (?, ?, ?)";
            $stmt = $conn->prepare($likeSql);
            
            $stmt->bindValue(1, $_POST["loginUserId"]);
            $stmt->bindValue(2, $_POST["targetUserId"]);
            $stmt->bindValue(3, $_POST["likeSubmit"]);
            $result = $stmt->execute();
            
            if ($result) {
              $checklikeSql = 
                "SELECT userId FROM User_Interactions 
                WHERE userId = ? AND targetUserId = ? AND interactionType = ?";
              $stmt = $conn->prepare($checklikeSql);
              
              $stmt->bindValue(1, $_POST["targetUserId"]);
              $stmt->bindValue(2, $_POST["loginUserId"]);
              $stmt->bindValue(3, $_POST["likeSubmit"]);
              $stmt->execute();
              
              $result = $stmt->fetch(PDO::FETCH_ASSOC);
              if (!empty($result)) {
                echo "Yeah, you are matched with" . $result["userId"];
              }
            } 
          } catch (PDOException $e) {
            setErrorMessage("DB error" . $e->getMessage());
          }
        }
      }
      
      try {
        $likeListSql = 
          "SELECT u.userId, u.username, u.age, u.gender, up.pictureContents, up.pictureType 
          FROM Users u LEFT JOIN User_Pictures up ON u.userId = up.userId 
          LEFT JOIN User_Interactions ui ON u.userId = ui.targetUserId AND ui.userId = ? 
          WHERE ui.userId IS NULL AND NOT u.userId = ?";
        
        $stmt = $conn->prepare($likeListSql);
        
        $stmt->bindValue(1, $loginUserId);
        $stmt->bindValue(2, $loginUserId);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $row = $stmt->rowCount();
      } catch (PDOException $e) {
        setErrorMessage("DB error: " . $e->getMessage());
      }
    ?>
    <div class="container p-4 bg-light">
      <div class="col-12 text-danger">
        <?php 
          displayErrorMessage();
          if ($row === 0) {
            echo "<h1 class='text-dark text-center'>いいねする相手いません<h1>";
          }
        ?>
      </div>
      <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php 
          foreach ($result as $key => $users) {
            $targetUserId = $users['userId'];
            $username = $users['username'];
            $age = $users['age'];
            $gender = $users['gender'];
            $pictureType = $users['pictureType'];
            $pictureContents = $users['pictureContents'];
        ?>
          <div class="col">
            <div class="card h-100 mx-3 text-center">
              <a href="Profile.php?targetUserId=<?php echo $targetUserId; ?>">
                <img 
                  <?php echo "src='data: $pictureType; base64, $pictureContents'"; ?>
                  class="card-img-top object-fit-scale border rounded" 
                  alt="profilePicture" height="300px" width="200px"
                >
              </a>
              <div class="card-body">
                <h5 class="card-title">
                  <?php echo "$username ($age)"; ?>
                </h5>
                <p class="card-text">
                  <?php echo $gender; ?>
                </p>
                <form class="d-grid gap-2" method="POST" action="Interactions.php">
                  <input type="hidden" name="loginUserId" value="<?php echo $loginUserId; ?>">
                  <input type="hidden" name="targetUserId" value="<?php echo $targetUserId; ?>">
                  <button type="submit" class="btn btn-danger" name="likeSubmit" value="like"> 
                    <img src="../assets/icon/balloon-heart-fill.svg" width="32" height="32" class="m-1">
                  </button>
                </form>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div> 
  </body>
</html>