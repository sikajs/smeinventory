<?php
include "../shared/user_check_right.php";

//check old password if correct then change to new one
include "../shared/dbconnect.php";
$sql = "SELECT password FROM sme_access WHERE uid='".$_SESSION['user_info']['uid']."'";
if($res = $dbh->query($sql)){
	if(md5($_POST['oldpass']) == $res->fetchColumn()){
		$sql = "UPDATE sme_access SET password='".md5($_POST['newpass'])."', lastupdate='".date('r').
		       "', firstlogin='false' ".
			   "WHERE uid='".$_SESSION['user_info']['uid']."'";
		$count = $dbh->exec($sql);
		if($count != 1){
			print "Something is wrong, please contact your system administrator.";
		} else {
			print "Your password has been updated.";
		}
	} else {
		print "Your password cannot be changed since wrong password entered.";
	}
}

?>

<html>
<head>
<script language="javascript" src="interface.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<body>
<a href='../right.php' onclick='urFrameReset()'>continue</a>...
</body>
</html>
