<?php
include "../shared/user_check_right.php";

if($_POST['custID']){
	$_SESSION['customerID'] = $_POST['custID'];
	$_SESSION['customerName'] = $_POST['custName'];
	unset($_SESSION['cart']);
} else {
	$_SESSION['customerID'] = null;
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script language="javascript" type="text/javascript">
window.location.href = "orderBarcode.php";
</script>
</head>
</html>
