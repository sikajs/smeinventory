<?php
include "../shared/user_check_right.php";

//database connection
include "../shared/dbconnect.php";
?>
<html>
<head><script type='text/javascript' src='../shared/utility.js'></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<body>
<center><table border='0'>
<?php
// =============== show the final of the certain order ===============
//company information
$sql="SELECT * FROM com_info";
$stmt = $dbh->query($sql);
$result = $stmt->fetchAll();
$vat = $result[0]['com_vat'];
?>
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
//print detail of the targeted order
if($_GET['target'] != NULL){
	$sql="SELECT orders.*, items.item_name, items.brand, oi.price, oi.qty, items.barcode ".
		  "FROM orders, order_items as oi, items ".
		  "WHERE orders.order_id=".$_GET['target']." AND orders.order_id = oi.order_id ".
		  "AND oi.item_id=items.item_id";
		
	$stmt = $dbh->query($sql);
	$result = $stmt->fetchAll();
	$totalRow = count($result);
	
	//order information
	print "<tr><td>"; 
	print "<table border='0'>";
	print "<tr><td>Order number: ".$result[0]['order_id']."</td></tr>";
	print "<tr><td>Customer number: ".$result[0]['customer_id']."</td></tr>";
	$orderdate = date('d/m/Y, g:i a',strtotime($result[0]['update_time']));
	print "<tr><td>Date: ".$orderdate."</td>";
	print "</tr>";
	print "</table><br/>";
	print "</td></tr>";
	
	//item(s) in the order	
	print "<tr><td>Detail";
	print "<table border='1' width='100%'>";
	print "<tr><th>No.</th><th>Barcode</th><th>Brand/Item</th><th>Unit price</th><th>Amount</th><th>Total price</th><th>Comment</th></tr>";
	(double)$totalmoney = 0;
	for($i=0; $i<$totalRow; $i++){
		print "<tr>";
		print "<td align='center'>".($i+1)."</td>";
                echo "<td>".$result[$i]['barcode']."</td>";
		print "<td>".$result[$i]['brand']."/".$result[$i]['item_name']."</td>";
		print "<td align='center'>".$result[$i]['price']."</td>";
		$history_price = (double)($result[$i]['price']);
		print "<td align='center'>".$result[$i]['qty']."</td>";
		$amount = (int)$result[$i]['qty'];
		$subprice = $history_price * $amount;
		printf("<td align='right'> %.2f </td>",$subprice);
		print "<td></td>";
		print "</tr>";
		$totalmoney += $subprice;
	}
	
	print "<tr align='right' ><td colspan='4'></td><td bgcolor='lightgray'>TOTAL</td><td>";
	printf("%.2f </td></tr>",$totalmoney);
	$totalvat = $totalmoney * $vat;
	print "<tr align='right' id='invoice_vat'><td colspan='4'></td><td bgcolor='lightgray'>VAT(".($vat*100)."%)</td><td>";
	printf("%.2f </td></tr>",$totalvat);
	print "<tr align='right' ><td colspan='4'></td><td bgcolor='lightgray'>DISCOUNT</td><td>";
	printf("%.2f </td></tr>",$result[0]['discount']);

	//net total with VAT	
	$nettotal = $totalmoney + $totalvat;
	print "<tr align='right' bgcolor='lightgray' id='invoice'><td colspan='4'></td><td>NET TOTAL</td><td>";
	$nettotal = $nettotal - $result[0]['discount'];
	printf("%.2f </td></tr>",$nettotal);
	
	//net total without VAT	
	$nettotal = $totalmoney;
	//print "<tr align='right' bgcolor='lightgray' id='quote' style='visibility:hidden'><td colspan='4'></td><td>NET TOTAL</td><td>";
	print "<tr align='right' bgcolor='lightgray' id='quote' style='display:none'><td colspan='4'></td><td>NET TOTAL</td><td>";
	$nettotal = $nettotal - $result[0]['discount'];
	printf("%.2f </td></tr>",$nettotal);
	echo "<tr>";
        echo "<td bgcolor='lightgray'>ORDER COMMENT</td>";
        echo "<td colspan='6'>".$result[0]['comment']."</td>";
        echo "</tr>";
?>
</table>

<?php
	print "</table>";
	print "</td></tr>";
	
}
?>
</table></center>
<br/><hr/>
</body>
<form>
<br/><input type='button' name='printbutton1' value='Print Invoice' onclick='printout();'/>
<input type='button' name='printbutton2' value='Print Quote' onclick='printquote();'/>
</form>
</html>
