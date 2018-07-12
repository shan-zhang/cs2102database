<?php
session_start();
if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
  header("location: login.php");
  exit;
}
else{
  header("location: home.php");
  exit;
}
?>