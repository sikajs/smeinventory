<?php
session_start();
if($_SESSION['user_info'] == NULL){
	//redirect to login.php
	$URL = "/smeInventory/login.php";
	//$URL = "/testInv/login.php";
	header("Location: $URL");
}

?>
