
<?php
include "../shared/user_check_right.php";

/* connect database */
include "../shared/dbconnect.php";

?>
<html>
<head><script type='text/javascript' src='../shared/utility.js'></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<body>
<?php
//start an order record transaction to insert the detail of the order into database
$dbh->beginTransaction();

//insert data into order and get the order_id
$sql = "INSERT INTO orders (customer_id) values(".$_SESSION['customerID'].")";
$count = $dbh->exec($sql);
$sql = "SELECT order_id FROM orders where customer_id='".$_SESSION['customerID']."' AND update_time=transaction_timestamp()";
if($res = $dbh->query($sql)){
	$OID = $res->fetchColumn();
}

//insert the detail of the order (which item in the order) into order_items
$sql = "INSERT INTO order_items (order_id,item_id,amount,sameitem,price) values(".$OID.",?,?,?,?)";
$cart = $_SESSION['cart']; //get the content of customer's shopping cart
$numInCart = count($cart);
$walker = $numInCart;
$itemInsert = $dbh->prepare($sql);
while($walker > 0){
	$key = key($cart);
	$sameItem = implode(",",$cart[$key]['sameItem']);
	$itemInsert->execute(array($key,$cart[$key]['amount'],$sameItem,$cart[$key]['price']));
	next($cart);
	$walker--;
}
reset($cart);

//updating stock of the items in the order
$walker = $numInCart;
$sqlSimple = "UPDATE items SET stock=? WHERE item_id=?";
$stockSimple = $dbh->prepare($sqlSimple);
while($walker > 0){
	$key = key($cart);
	$totalNeedAmount = (int)$cart[$key]['amount'];
	$numItems = 1 + count($cart[$key]['sameItem']);
	if($numItems > 1){	//has item with the same brand and name but from different supplier
		$itemList = $cart[$key]['sameItem'];
		//reducing the stock will start from the item which has the oldest "last_restock_date"  ,?Is this correct logic?
		$sql = "SELECT item_id,stock,last_restock_date FROM items WHERE item_id in (".$key;
		foreach($itemList as $value){
			$sql = $sql.",".$value;
		}
		unset($value);
		unset($itemList);
		$sql = $sql.") ORDER BY last_restock_date";
		if($res = $dbh->query($sql)){
			$result = $res->fetchAll();
			$i = 0;
			while($totalNeedAmount > 0){
				if($result[$i]['stock'] > $totalNeedAmount){
					$stockComplicated = "UPDATE items SET stock=".((int)$result[$i]['stock']-$totalNeedAmount).
					                    " WHERE item_id=".$result[$i]['item_id'];
					$dbh->exec($stockComplicated);
					$totalNeedAmount = 0;
				} else {
					if((int)$result[$i]['stock'] != 0){
						$stockComplicated = "UPDATE items SET stock=0 WHERE item_id=".$result[$i]['item_id'];
						$dbh->exec($stockComplicated);
						$totalNeedAmount = $totalNeedAmount - (int)$result[$i]['stock'];
					}
				}
				$i++;
			}
		}
	} else {
		$sql = "SELECT stock FROM items WHERE item_id=".$key;
		if($res = $dbh->query($sql)){
			$currStock = $res->fetchColumn();
		}
		$stockSimple->execute(array(((int)$currStock - (int)$totalNeedAmount),$key));
	}
	next($cart);
	$walker--;
}

//commit changes and finish the transaction
$dbh->commit();

// =============== show the final of the certain order ===============
//company information
$sql="SELECT * FROM com_info";
$stmt = $dbh->query($sql);
$result = $stmt->fetchAll();
$vat = $result[0]['com_vat'];
?>
<center><table border='0'>
<tr><td>
<div id='hideShow'>
<table>
<?php
print "<tr><td><b>".$result[0]['com_name']."</b></td></tr>";
print "<tr><td>".$result[0]['com_address']." </td><td> ".$result[0]['com_zipcode']."</td></tr>";
print "<tr><td>Tel.".$result[0]['com_tel']."</td><td> Fax.".$result[0]['com_fax']."</td></tr>";
print "<tr><td>Business number: ".$result[0]['com_businessno']."</td></tr>";
?>
</table><br/>
</div>
</td></tr>
<?php
//get date and time of the order
$sql = "SELECT update_time FROM orders WHERE order_id=".$OID;
$stmt = $dbh->query($sql);
$orderdate = date('d/m/Y, g:i a',strtotime($stmt->fetchColumn()));

//order information
print "<tr><td>"; 
print "<table border='0'>";
print "<tr><td>Order number: ".$OID."</td></tr>";
print "<tr><td>Customer number: ".$_SESSION['customerID']."</td></tr>";
print "<tr><td>Date: ".$orderdate."</td>";
print "</tr>";
print "</table><br/>";
print "</td></tr>";
	
//item(s) in the order	
print "<tr><td>Detail";
print "<table border='1' width='100%'>";
print "<tr><th>No.</th><th>Brand/Item</th><th>Unit price</th><th>Amount</th><th>Total price</th></tr>";
$totalmoney = 0;
$cart = $_SESSION['cart'];
for($i=0; $i<$numInCart; $i++){
	$key = key($cart);
	print "<tr>";
	print "<td align='center'>".($i+1)."</td>";
	print "<td>".$cart[$key]['brand']."/".$cart[$key]['itemName']."</td>";
	print "<td>".$cart[$key]['price']."</td>";
	$history_price = (double)($cart[$key]['price']);
	print "<td align='center'>".$cart[$key]['amount']."</td>";
	$amount = (int)$cart[$key]['amount'];
	$subprice = $history_price * $amount;
	printf("<td align='right'> %.2f </td>",$subprice);
	print "</tr>";
	$totalmoney += $subprice;
	next($cart);
}
print "<tr align='right' ><td colspan='4'></td><td bgcolor='lightgray'>TOTAL</td><td>";
printf("%.2f </td></tr>",$totalmoney);
$totalvat = $totalmoney * $vat;
print "<tr align='right' id='invoice_vat'><td colspan='4'></td><td bgcolor='lightgray'>VAT(".($vat*100)."%)</td><td>";
printf("%.2f </td></tr>",$totalvat);
//net total with VAT	
$nettotal = $totalmoney + $totalvat;
print "<tr align='right' bgcolor='lightgray' id='invoice'><td colspan='4'></td><td>NET TOTAL</td><td>";
printf("%.2f </td></tr>",$nettotal);

//net total without VAT	
$nettotal = $totalmoney;
//print "<tr align='right' bgcolor='lightgray' id='quote' style='visibility:hidden'><td colspan='4'></td><td>NET TOTAL</td><td>";
print "<tr align='right' bgcolor='lightgray' id='quote' style='display:none'><td colspan='4'></td><td>NET TOTAL</td><td>";
printf("%.2f </td></tr>",$nettotal);

?>
</table>

<?php
print "</table>";
print "</td></tr>";
print "</table></center>";

//clean up data of the inserted order
unset($cart);
unset($_SESSION['cart']);
unset($_SESSION['customerID']);
unset($_SESSION['customerName']);

//close db connection
unset($itemInsert);
$dbh = null;
?>

<form>
<br/><input type='button' name='printbutton1' value='Print Invoice' onclick='printout();'/>
<input type='button' name='printbutton2' value='Print Quote' onclick='printquote();'/>
</form>
<hr>
Order accomplished. <a href="orderdeal.php">Serve next customer</a>
</body>
</html>
