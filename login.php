<?php
session_start();
$_SESSION['password'] = "empty";
if(isset($_POST['password'])) $_SESSION['password'] = $_POST['password'];
echo "<script>location.href=\"/\";</script>";
?>