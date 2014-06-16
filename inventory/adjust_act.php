<?php
include "../shared/user_check_right.php";
include_once "../shared/dbconnect.php";

$items = $_POST['itemID'].",".$_POST['sameItem'];
$items = substr($items,0,strlen($items)-1);
$sql = "SELECT items.*, suppliers.suppl_name ".
		 "FROM items, suppliers ".
		 "WHERE items.supplier_id=suppliers.supplier_id AND items.item_id in (".$items.") ".
		 "ORDER BY item_id ";
//echo $sql;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script type='text/javascript'>
<!--
function checkform ( form )
{
  if (form.qty.value == "") {
    alert( "Please enter the quantity." );
    form.qty.focus();
    return false ;
  }
  return true ;
}
-->
</script>
</head>
<body>
<p class='blacktitle'>Adjust Inventory</p>
<?php
//list all the item(s) which related to the adjustment for user to choose 
if($stmt = $dbh->query($sql)){
	$result = $stmt->fetchAll();
	$count = count($result);
	if($count != NULL){
		echo "<table border='1'>";
		echo "<tr><th>Item id</th><th>Brand</th><th>Item name</th><th>Product code</th><th>Supplier</th>".
		     "<th>Unit cost</th><th>Unit price</th><th>Stock</th><th>Adjust quantity</th><th>Adjust reason</th>".
		     "<th></th></tr>";
		$totalstock = 0;		
		foreach($result as $row){
			echo "<form name='adjustForm".$row['item_id']."' method='post' action='adjust_final.php' onsubmit='return checkform(this);'>";
			echo "<tr>";
			echo "<td>".$row['item_id']."<input type='hidden' name='id' value='".$row['item_id']." '/></td>";
			echo "<td>".$row['brand']."</td>";
			echo "<td>".$row['item_name']."</td>";
			echo "<td>".$row['product_code']."</td>";
			echo "<td>".$row['suppl_name']."</td>";			
			echo "<td>".$row['unit_cost']."</td>";			
			echo "<td>".$row['unit_price']."</td>";
			echo "<td>".$row['stock']."<input type='hidden' name='pre_stock' value='".$row['stock']." '/></td>";
			echo "<td><input type='text' name='qty' value='0' /></td>";
			echo "<td><input type='text' name='reason' /></td>";
			echo "<td><input type='submit' value='adjust' /></td>";
			echo"</tr>";
			echo "</form>";
			$totalstock += $row['stock'];
		}
		echo "<tr bgcolor='lightgrey'><td colspan='6'></td><td>Total Stock</td><td>".$totalstock."</td></tr>";
		echo "</table>";
	}
} else {
	echo "<p class='warning'>Database query failed, please contact administrator.</p>";
}
echo "<br/>";
echo "<table>";
if($_SESSION['lastsql'] != null){
	//show 'back to result' button
	echo "<tr><td><form name='backToResult' method='post' action='adjust_inv.php'>";
	echo "<input type='hidden' name='backToResult' value='true'/>";
	echo "<input type='submit' value='Back to searched result' />";
	echo "</form></td></tr>";
}
echo "</table>";
?>
</body>
</html>
