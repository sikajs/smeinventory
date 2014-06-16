<?php
session_start();
//unset($_SESSION['user_info']);
session_unset();
$url="up_right_initial.php";
header("Location: $url");
?>
