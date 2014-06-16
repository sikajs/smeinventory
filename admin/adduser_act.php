<?php
include "../shared/user_check_right.php";

include "../shared/dbconnect.php";

$sql = "INSERT INTO sme_access (username,password,description,main_order,main_inventory,main_user) values ('".
		  $_POST['userName']."','".md5($_POST['pass'])."','".$_POST['desc']."',";
		  if($_POST['main_order'] != 1) {
		  		$sql = $sql."false";
		  } else {
		  		$sql = $sql."true";
		  }
		  $sql = $sql.",";
		  if($_POST['main_inventory'] != 1) {
		  		$sql = $sql."false";
		  } else {
		  		$sql = $sql."true";
		  }
		  $sql = $sql.",";
		  if($_POST['main_user'] != 1) {
		  		$sql = $sql."false";
		  } else {
		  		$sql = $sql."true";
		  }

$sql = $sql.");";
//print $sql;
$count = $dbh->exec($sql);
if($count != 1){
	print "Something is wrong, please contact your system administrator.";
} else {
	print "New account has been created.";
}

//cleanup
unset($count);
$dbh = NULL;
?>