<?php
  session_start();
  
  $menubar = [
    "/MatchedList.php" => "マッチング一覧", 
    "/Interactions.php" => "いいね", 
    "/Profile.php" => "プロフィール"
  ];
  
  function checkActive($url) {
    if ($url === $_SERVER["SCRIPT_NAME"]) {
      $mode = "btn btn-outline-dark active";
    } else {
      $mode = "btn btn-outline-dark";
    }
    return $mode;
  }
?>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> -->
<div class="sticky-top container-fluid bg-info py-3">
  <div class="row g-2">
    <div class="col-2">
      <img 
        src="../icon/calendar-heart-fill.svg" 
        width="40" height="40" class="mx-5"
      >
    </div>
    <?php 
      if ($_SERVER["SCRIPT_NAME"] !== "/Login.php" && 
        $_SERVER["SCRIPT_NAME"] !== "/Register.php") { 
    ?>
      <div class="col-8">
        <div class="btn-group container">
          <?php 
            foreach ($menubar as $fileName => $value) {
              $btnClass = checkActive($fileName);
              echo "<a href='$fileName' class='$btnClass'>$value</a>";
            }
          ?>
        </div>
      </div>
      
      <div class="col-2">
        <div class="btn-group container justify-content-end">
          <form method="POST" action="<?php echo htmlspecialchars($_SERVER["SCRIPT_NAME"]);?>">
            <button type="submit" name="logoutSubmit" class="btn btn-link p-0">
              <img src="../icon/box-arrow-in-right.svg" width="40" height="40" class="mx-5">
            </button>
          </form>
        </div>
      </div>
    <?php } ?>
  </div>
</div>