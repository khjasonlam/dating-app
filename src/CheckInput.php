<?php 
  session_start();
  
  function testInputValue($data) {
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  
  function checkValue($data) {
    if (empty($data)) {
      $data = "入力なし";
    }
    return $data;
  }
?>