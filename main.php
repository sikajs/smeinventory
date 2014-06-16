<?php
session_start();
?>
<html>
<head>
<title>upper_frame</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="shared/smeInventory.css">
</head>
<frameset cols=160,* frameborder="0" framespacing="0" border="0">
<frame name="up_left" src="up_left.php">
<?php
if($_SESSION['user_info'] != NULL){
	print "<frame name='up_right' src='up_right.php'>";
} else {
	print "<frame name='up_right' src='up_right_initial.php'>";
}
?>
</frameset>
</html>
