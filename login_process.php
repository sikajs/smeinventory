<?php
session_start();
include "shared/dbconnect.php";
$sql = "SELECT * FROM sme_access WHERE username='".$_POST['user']."'";
if($_POST['user'] != NULL){
	if($res = $dbh->query($sql)){
		$rowNum = $res->rowCount();
		if($rowNum != 0){
			$result = $res->fetchAll();
			if(md5($_POST['smepass']) == $result[0]['password']){
				$_SESSION['user_info'] = array("uid"=>$result[0]['uid'],"username"=>$result[0]['username'],
				"main_order"=>$result[0]['main_order'],"main_inventory"=>$result[0]['main_inventory'],
				"main_user"=>$result[0]['main_user']);
				//check if user login for the first time, if yes then lead to change password
				if($result[0]['firstlogin'] == true){
					$_SESSION['firstlogin'] = true;
					header("Location: admin/changepass.php");
				} else {
					print "Welcome! ".$result[0]['username'];
				}
			} else {
				print "Wrong password. Please <a href='login.php'>try again</a>.";
			}
		} else {
			print "User not found!<br/> Please <a href='login.php'>try again</a> or contact system administrator.";
			unset($_POST['user']);
			unset($_POST['smepass']);
		}
	}
}

?>
<HTML>
<head>
<script type="text/javascript">
window.parent.parent.upFrame.up_right.location.href = "up_right.php";
</script>
</head>
</HTML>
