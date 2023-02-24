<?php
  session_start();
  $bdd = new PDO('mysql:host=localhost;dbname=horloges;charset=utf8', 'root', '');
  if (isset($_SESSION["userId"]) && isset($_SESSION["userName"]) && isset($_SESSION["adminAccess"])){
    $sessionUserId = $_SESSION["userId"];
    $sessionUserName = $_SESSION["userName"];
    $sessionUserRole = $_SESSION["adminAccess"];
    $sessionUserFirstName = $_SESSION["userFirstName"];
    $sessionUserPseudo = $_SESSION["userPseudo"];
  }
