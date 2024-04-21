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
  return htmlspecialchars($data, ENT_QUOTES, "UTF-8");
}

function setErrorMessage($message) {
  $_SESSION["error_message"] = $message;
}

function displayErrorMessage() {
  if (isset($_SESSION["error_message"])) {
    echo $_SESSION["error_message"];
    unset($_SESSION["error_message"]);
  }
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