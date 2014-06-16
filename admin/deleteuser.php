<?php
include "../shared/user_check_right.php";

if($_SESSION['user_info']['main_user'] != 1){  //user doesn't have right to delete
	exit();
} else {
	include "../shared/dbconnect.php"; //database connection
	$sql = "DELETE FROM sme_access WHERE uid='".$_GET['target']."' ";
	$count = $dbh->exec($sql);
	if($count != 1)
		print "Something is wrong, please contact system administrator.";
	else
		print $count." user deleted";
}

//cleanup
unset($dbh);
?>