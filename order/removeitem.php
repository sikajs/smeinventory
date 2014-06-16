<?php
include "../shared/user_check_right.php";

$cart = $_SESSION['cart'];
//remove item from the cart
if(array_key_exists($_GET['target'],$cart)){
	unset($cart[$_GET['target']]);
	$num = count($cart);
	if($num == 0){
		unset($_SESSION['cart']);
	} else {
		$_SESSION['cart'] = $cart;
	}
}
unset($cart);
$_SESSION['removeitem']=true;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script language='javascript' type='text/javascript'>
window.location.href = 'orderdeal_act.php';
</script>
</head>
</html>

