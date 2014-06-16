<?php
session_start();
if($_SESSION['user_info'] == NULL){
	//redirect 
	$URL = "left.php";
	header("Location: $URL");
}

?>
