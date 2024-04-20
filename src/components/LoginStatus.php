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

if (!isset($_SESSION["userId"])) {
  header("Location:" . LOGIN_PAGE);
}