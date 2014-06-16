<?php
include "../shared/user_check_right.php";
include_once "../shared/dbconnect.php";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script type='text/javascript'>
</script>
</head>
<body>
<p class='blacktitle'>Adjust Inventory</p>
<div id="itemSearch">
<b>Step1: search the item(s) which you want to adjust :</b>
<form name="searchForm" method="post" action="adjust_inv.php">
<table border="0">
    <tr>
      <td>Item name</td><td><input type="text" name="itemName" class="inputText" /></td>
      <!--<td>Brand</td><td><input type="text" name="brand" class="inputText" /></td>-->
      <td>Product code</td><td><input type="text" name="prod_code" size="30" /></td>
    </tr>
    <tr>
      <td>Supplier</td>
      <td>
        <select name="supplier" class="inputText">
        <?php
        /* connect database */
        include "../shared/dbconnect.php";
        // get all supplier data and put them on the selection list 
        $sql = 'SELECT supplier_id,suppl_name FROM suppliers ORDER BY supplier_id';
        print "<option value=''>---</option>";
        foreach ($dbh->query($sql) as $row) {
           print "<option value=\"".$row['supplier_id']."\">".$row['suppl_name']." (".$row['supplier_id'].")</option>";
        }
        ?>
        </select>
      </td>
      <td>Color</td>
      <td><input type="text" name="color" class="inputText" /></td>
    </tr>
    <tr>
        <td>Item ID</td><td><input type="text" name="id" class="inputText" /></td>
        <td>Barcode</td>
        <td><input type="text" id='iBarcode' name="iBarcode" class="inputText" /></td>
    </tr>
</table>
<?php
//Base on the conditions user entered to generate sql statement
if($_POST['backToResult'] == 'true'){
	$sql = $_SESSION['lastsql'];
} else {
	unset($sql);
	unset($_SESSION['lastsql']);
	include "../shared/item_formsearch.php";
	// record the sql and set the variable for back to the search result later
	$_SESSION['lastsql'] = $sql;	
}
//echo $sql."<br/>";
//include "../unused/show_session.php";
?>
<input type="submit" value="Search">
<input type="reset">
</form>
</div>
<hr/>
<div>
<b>Step2: select the item for adjustment :</b>
<?php
if($_POST['backToResult']=='true' or $_POST['brand']!=null or $_POST['itemName']!=null or $_POST['supplier']!=null or $_POST['prod_code'] != null or $_POST['id']!=null or $_POST['color']!=null or $_POST['iBarcode']!=null){
	if($stmt = $dbh->query($sql)){
		$result = $stmt->fetchAll();
		unset($count);
		if(isset($result))
			$rowcount = count($result);
		else
			echo "<p class='warning'>Database didn't return the correct result.</p>";
		if($rowcount != NULL){
			// list the search result for user to choose which item to adjust
			echo "<table border='1'>";
			echo "<tr><th>Item id</th><th>Barcode</th><th>Brand</th><th>Item name</th><th>Product code</th><th>Unit price</th><th>Stock</th><th></th></tr>";
			$totalStock = 0;
			for($i = 0; $i <$rowcount ;$i++){
				//Plus all the stock of the items which have the same brand and item name together 			
				//Right now only show the new item, although they have the same brand and name
				if($result[$i]['brand'] == $result[$i+1]['brand'] and $result[$i]['item_name'] == $result[$i+1]['item_name']){
					if($sameItem != null)
						array_push($sameItem,$result[$i]['item_id']);
					else
						$sameItem = array($result[$i]['item_id']);
					$totalStock = $totalStock + (int)($result[$i]['stock']);
				} else {
					echo "<tr>";
					echo "<form name='adjustForm".$i."' method='post' action='adjust_act.php'>";
					echo "<td>".$result[$i]['item_id']."<input type='hidden' name='itemID' value='".$result[$i]['item_id']."'/>";
					echo "<input type='hidden' name='sameItem' value='";
					if($sameItem != null){
						foreach($sameItem as $value){
							echo $value.",";
						}
						unset($sameItem);
						unset($value);
					}
					echo "'/>";
					echo "</td>";
					echo "<td>".$result[$i]['barcode']."<input type='hidden' name='itemBarcode' value='".$result[$i]['barcode']."'/></td>";
					echo "<td>".$result[$i]['brand']."<input type='hidden' name='itemBrand' value='".$result[$i]['brand']."'/></td>";
					echo "<td>".$result[$i]['item_name']."<input type='hidden' name='itemName' value='".$result[$i]['item_name']."'/></td>";
					echo "<td>".$result[$i]['product_code']."</td>";					
					echo "<td>".$result[$i]['unit_price']."<input type='hidden' name='unit_price' value='".$result[$i]['unit_price']."'/></td>";
					$totalStock = $totalStock + (int)($result[$i]['stock']);
					if($totalStock != 0){
						echo "<td>".$totalStock."</td>";
						echo "<td><input type='submit' value='select'/></td>";
					} else {
						echo "<td><font color='red'>Out of stock</font></td>";
						echo "<td><input type='submit' value='select'/></td>";
					}
					$totalStock = 0;
					echo "</form>";
					echo "</tr>";
				}
			}
			
			echo "</table><br/>";
		} else {
			echo "<p>No item(s) found.</p>";
		}
	}
}

?>
</div>

</form>
</body>
</html>
