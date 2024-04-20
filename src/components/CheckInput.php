<?php 
session_start();

define("MAX_SIZE", 1048576);

function testInputValue($data) {
  return htmlspecialchars($data, ENT_QUOTES, "UTF-8");
}

function checkValue($data) {
  if (empty($data)) {
    $data = "入力なし";
  }
  return $data;
}

class ErrorMessage {
  private $message;
  
  public function setErrorMessage ($message) {
    $this->message = $message;
  }
  
  public function displayErrorMessage () {
    echo $this->message;
  }
}