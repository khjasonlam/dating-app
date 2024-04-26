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

function setUserIdSession($userId) {
  $_SESSION["userId"] = $userId;
}

function getUserIdSession() {
  return $_SESSION["userId"];
}

function setMatchedUserSession($userId) {
  $_SESSION["matched"] = true; 
}

function getMatchedUserSession() {
  if (isset($_SESSION["matched"])) {
    $matched = true;
    unset($_SESSION["matched"]);
  }
  return $matched;
}