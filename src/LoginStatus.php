<?php
  session_start();
  define("LOGIN_PAGE", "Login.php");
  
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['logoutSubmit'])) {
      session_unset();
      header("Location:". LOGIN_PAGE);
      exit;
    }
  }
  
  try {    
    $checkStatusSql = "SELECT userId FROM Users WHERE userId = ?";
    $stmt = $conn->prepare($checkStatusSql);
    
    $stmt->bindValue(1, $_SESSION["userId"]);
    $stmt->execute();
    
    $user = $stmt->rowCount();
    
    if ($user === 1) {
      $loginStatus = true;
    } else {
      $loginStatus = false;
      header("Location:" . LOGIN_PAGE);
    }
  } catch (PDOException $e) {
    var_dump($e);
  }
?>