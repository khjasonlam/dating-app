<?php
$menubar = [
  "マッチング一覧" => "/dating-app/src/pages/MatchedList.php", 
  "いいね" => "/dating-app/src/pages/Interactions.php", 
  "プロフィール" => "/dating-app/src/pages/Profile.php"
];

function checkActivePage($directory) {
  if ($directory === $_SERVER["SCRIPT_NAME"] && !isset($_GET["targetUserId"])) {
    $mode = "btn btn-outline-light active mt-2";
  } else {
    $mode = "btn btn-outline-light mt-2";
  }
  return $mode;
}

if (isset($_POST['logoutSubmit'])) {
  unsetAllSession();
}

?>
<script src="../assets/js/MatchedSuccess.js"></script>
<header class="sticky-top container-fluid bg-info p-2">
  <div class="row justify-content-around">
    <div class="col-auto m-0">
      <i class="bi-calendar-heart-fill text-light" style="font-size: 35px;"></i>
    </div>
    <?php 
    $showMenubar = 
      $_SERVER["SCRIPT_NAME"] !== "/dating-app/src/pages/Login.php" && 
      $_SERVER["SCRIPT_NAME"] !== "/dating-app/src/pages/Register.php";
    if ($showMenubar):
    ?>
      <nav class="col-9 m-0">
        <div class="btn-group container">
          <?php 
          include_once("../database/LoginStatus.php");
          foreach ($menubar as $title => $directory) {
            $btnClass = checkActivePage($directory);
            echo "<a href='$directory' class='$btnClass'>$title</a>";
          }
          ?>
        </div>
      </nav>
      <div class="col-auto m-0">
        <form method="POST" action="<?php $_SERVER["SCRIPT_NAME"];?>">
          <input type="hidden" name="logoutSubmit">
            <i 
              class="bi-box-arrow-right text-light" 
              onclick="this.parentNode.submit()" 
              style="cursor: pointer; font-size: 35px;"
            ></i>
        </form>
      </div>
    <?php endif; ?>
  </div>
</header>
<?php 
$isMatched = getMatchedUserSession();
if (isset($isMatched)):
?>
<div class="z-3 bg-danger-subtle position-absolute w-100 h-100" id="success">
  <div 
    class="position-absolute text-center text-danger 
    top-50 start-50 translate-middle fs-1"
  >
    <i class="bi-arrow-through-heart-fill" style="font-size: 200px;"></i>
    <br>
    マッチしました
  </div>
</div>
<?php endif; ?>