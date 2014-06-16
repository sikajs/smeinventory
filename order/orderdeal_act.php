<?php
include "../shared/user_check_right.php";
?>
<html>
<head><title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<body>
<center><h4>Checkout</h4></center>
<b>Step4 - Checkout confirmation</b><br/>
<?php
//update content of the shopping cart
$cart = $_SESSION['cart'];
$sameItem = explode(",",$_POST['sameItem']);
array_pop($sameItem);
if($_POST['checkcart'] != 'true' AND $_SESSION['removeitem'] != 'true'){
	$numInCart = count($cart);
	if($numInCart == 0){
		$cart = array($_POST['itemID'] => array('brand'=>$_POST['itemBrand'],'itemName'=>$_POST['itemName'],
		              'amount'=>$_POST['amount'],'price'=>$_POST['unit_price'],'sameItem'=>$sameItem));
	} else {
		if(!array_key_exists($_POST['itemID'],$cart)){ //make sure that target item is not in cart yet
			$cart = $cart + array($_POST['itemID'] => array('brand'=>$_POST['itemBrand'],'itemName'=>$_POST['itemName'],
			                      'amount'=>$_POST['amount'],'price'=>$_POST['unit_price'],'sameItem'=>$sameItem));
			ksort($cart);
		} else {
			print "<i>Selected item is already in your cart.</i><br/><br/>";
		}
	}
	$_SESSION['cart'] = $cart;
}


// Count number of item(s) in the shopping cart and show the message
$numInCart = count($cart);
print "You have ";
if(!$numInCart){
	print "no item in your shopping cart.";
} else {
	print $numInCart." item";
	if($numInCart >1)
		print "s";
	print " in your shopping cart now.";
}

//list item(s) in the shopping cart for confirmation
if($numInCart > 0){
	$_SESSION['removeitem'] = false;
	print "<table border='1'>";
	print "<tr><th>item id</th><th>brand</th><th>item name</th><th>unit price</th><th>amount</th></tr>";
	while($numInCart != 0){
		$key = key($cart);
		print "<tr>";
		print "<td>".$key."</td>";
		print "<td>".$cart[$key]['brand']."</td>";
		print "<td>".$cart[$key]['itemName']."</td>";
		print "<td>".$cart[$key]['price']."</td>";
		print "<td>".$cart[$key]['amount']."</td>";
		print "<td><a href='removeitem.php?target=".$key."'>remove</a></td>";
		print "</tr>";
		next($cart);
		$numInCart--;
	}
	print "</table>";
	reset($cart);
}
print "<hr>";
print "Add more item : ";
//show the options
print "<table>";
print "<tr>";
if($_SESSION['lastSQL'] != null){
	//show 'back to result' button
	print "<td><form name='backToResult' method='post' action='orderdeal.php'>";
	print "<input type='hidden' name='backToResult' value='true'/>";
	print "<input type='submit' value='Back to searched result' />";
	print "</form></td>";
}
//show 'Start another search' button
print "<td><form name='addMore' method='post' action='orderdeal.php'>";
print "<input type='hidden' name='backToResult' value='false'/>";
print "<input type='submit' value='Start another search' />";
print "</form></td>";
print "</tr>";
print "</table>";

print "Further processing:<br/>";
if(count($_SESSION['cart']) > 0){
	print " <a href='orderdeal.php?resetCart=1'>Reset the shopping cart</a> "; //temporary solution
}

//if want to reset customer, uncomment following
/*
unset($_SESSION['customerName']);
unset($_SESSION['customerID']);
*/
?>
<form action='orderdeal_confirmed.php'>
<input type='submit' value='Checkout' />
</form>
</body>
</html>
