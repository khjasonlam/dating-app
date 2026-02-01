<?php
$menubar = [
  "マッチング一覧" => "../pages/MatchedList.php", 
  "いいね" => "../pages/Interactions.php", 
  "プロフィール" => "../pages/Profile.php"
];

function checkActivePage($directory) {
  $currentPage = basename($_SERVER["SCRIPT_NAME"]);
  $directoryPage = basename($directory);
  if ($directoryPage === $currentPage && !isset($_GET["targetUserId"])) {
    $mode = "btn btn-outline-light active mt-2";
  } else {
    $mode = "btn btn-outline-light mt-2";
  }
  return $mode;
}

if (isset($_POST['logoutSubmit'])) {
  unsetAllSession();
}

$currentPage = basename($_SERVER["SCRIPT_NAME"]);
$showMenubar = 
  $currentPage !== "Login.php" && 
  $currentPage !== "Register.php";

// マッチング成功時のみJavaScriptを読み込む
$isMatched = false;
if ($showMenubar) {
  $isMatched = getMatchedUserSession();
}

?>
<?php if ($showMenubar && $isMatched): ?>
<script src="../js/MatchedSuccess.js"></script>
<?php endif; ?>
<header class="sticky-top container-fluid bg-info p-2">
  <div class="row justify-content-around">
    <div class="col-auto m-0">
      <i class="bi-calendar-heart-fill text-light" style="font-size: 35px;"></i>
    </div>
    <?php if ($showMenubar): ?>
      <nav class="col-9 m-0 text-center">
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
// マッチング成功メッセージはログインページと登録ページでは表示しない
if ($showMenubar && $isMatched):
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
<?php 
endif;
?>