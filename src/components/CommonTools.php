<?php
$menubar = [
  "マッチング一覧" => "/dating-app/src/pages/MatchedList.php", 
  "いいね" => "/dating-app/src/pages/Interactions.php", 
  "プロフィール" => "/dating-app/src/pages/Profile.php"
];

function checkActive($directory) {
  if ($directory === $_SERVER["SCRIPT_NAME"]) {
    $mode = "btn btn-outline-dark active";
  } else {
    $mode = "btn btn-outline-dark";
  }
  return $mode;
}
?>
<div class="sticky-top container-fluid bg-warning py-3">
  <div class="row g-2">
    <div class="col-2">
      <img 
        src="../assets/icon/calendar-heart-fill.svg" 
        width="40" height="40" class="mx-5"
      >
    </div>
    <?php 
      if ($_SERVER["SCRIPT_NAME"] !== "/dating-app/src/pages/Login.php" && 
        $_SERVER["SCRIPT_NAME"] !== "/dating-app/src/pages/Register.php") { 
    ?>
      <div class="col-8">
        <div class="btn-group container">
          <?php 
            include_once("LoginStatus.php");
            foreach ($menubar as $title => $directory) {
              $btnClass = checkActive($directory);
              echo "<a href='$directory' class='$btnClass'>$title</a>";
            }
          ?>
        </div>
      </div>
      <div class="col-2">
        <div class="btn-group container justify-content-end">
          <form method="POST" action="<?php $_SERVER["SCRIPT_NAME"];?>">
            <button type="submit" name="logoutSubmit" class="btn btn-link p-0">
              <img 
                src="../assets/icon/box-arrow-in-right.svg" 
                width="40" height="40" class="mx-5"
              >
            </button>
          </form>
        </div>
      </div>
    <?php } ?>
  </div>
</div>