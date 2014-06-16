<?php
include "../shared/user_check_right.php";
?>
<html>
<head><title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<link type="text/css" href="../css/ui-lightness/jquery-ui-1.8.17.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" >
$(document).ready(function(){
    $('#color').focus();
});
</script>
</head>
<body>
<p class='blacktitle'>Restock</p>
<div id="itemSearch">
<b>Step1 - Search the item which you want to restock first :</b>
<form name="searchForm" method="post" action="restock.php">
<table border="0">
    <tr>
      <td>Item name</td><td><input type="text" name="itemName" class="inputText" /></td>
      <td>Brand</td><td><input type="text" name="brand" class="inputText" /></td>
    </tr>
    <tr>
      <td>Supplier</td>
      <td>
        <select name="supplier" class="inputText">
        <?php
        /* connect database */
        include "../shared/dbconnect.php";
        // get all supplier data and put them on the selection list 
        $sql = 'SELECT supplier_id,suppl_name FROM suppliers ORDER BY suppl_name';
        print "<option value=''>---</option>";
        foreach ($dbh->query($sql) as $row) {
           print "<option value=\"".$row['supplier_id']."\">".$row['suppl_name']."</option>";
        }
        ?>
        </select>
      </td>
      <td>
        Color
      </td>
      <td><input type="text" name="color" id="color" class="inputText" /></td>
    </tr>
    <tr>
        <td>Item ID</td><td><input type="text" name="id" class="inputText" /></td>
        <td>Barcode</td>
        <td><input type="text" name="iBarcode" id="iBarcode" class="inputText" /></td>
    </tr>

</table>
<?php
//Base on the conditions user entered to generate sql statement
$sql = "";
if($_POST['supplier'] != null){
	$sql = "SELECT suppliers.suppl_name, items.* ".
			 "FROM suppliers, items ";
	$sql = $sql."WHERE items.supplier_id='".$_POST['supplier']."' ";
	if($_POST['brand'] != null){
		$sql = $sql."AND items.brand='".strtoupper($_POST['brand']).")' ";
		if($_POST['itemName'] != null){
			$sql = $sql."AND items.item_name like '%".$_POST['itemName']."%' ";
		}
	} elseif($_POST['itemName'] != null){
		$sql = $sql."AND items.item_name like '%".$_POST['itemName']."%' ";
	}
} elseif($_POST['brand'] != null) {
	$sql = "SELECT suppliers.suppl_name, items.* ".
			 "FROM suppliers, items ";
	$sql = $sql."WHERE items.brand='".strtoupper($_POST['brand'])."' ";
	if($_POST['itemName'] != null)
		$sql = $sql."AND items.item_name='".$_POST['itemName']."' ";
} elseif($_POST['itemName'] != null){
	$sql = "SELECT suppliers.suppl_name, items.* ".
			 "FROM suppliers, items ";
	$sql = $sql."WHERE items.item_name like '%".$_POST['itemName']."%' ";
}
//the following search condition will work on only one targeted condition
if($_POST['id'] != null){
    $sql = "SELECT suppliers.suppl_name, items.* ".
           "FROM suppliers, items ".
           "WHERE items.item_id='".$_POST['id']."' ";
}
if($_POST['iBarcode'] != null){
    $sql = "SELECT suppliers.suppl_name, items.* ".
           "FROM suppliers, items ".
           "WHERE items.barcode='".$_POST['iBarcode']."' ";
}
if($_POST['color'] != null){
    $sql = "SELECT suppliers.suppl_name, items.* ".
           "FROM suppliers, items ".
           "WHERE items.color='".$_POST['color']."' ";
}

if($_GET['pagenum'] != NULL)
	$sql = $_SESSION['lastsql'];	
else
	$sql = $sql."AND suppliers.supplier_id=items.supplier_id ".
					"ORDER BY items.item_name, items.brand, items.item_id ";
//print $sql;
?>
<input type="submit" value="Search">
<input type="reset">
</form>
</div>

<hr/>

<div id="restock">
<b>Step2 - Select the item which you want to restock and press Restock :</b>
<form name="restockForm" method="post" action="restock_act.php">
<?php
/* After found item, user can update the stock here 
 * based on restock information by sending another form */
if($_POST['supplier'] != null or $_POST['brand'] != null or $_POST['itemName'] != null or $_POST['color'] != null or $_POST['iBarcode'] != null or $_POST['id'] != null){
	if($stmt = $dbh->query($sql)){	
		$result = $stmt->fetchAll();
		$count = count($result);
		if($count > 0){
			print "<table border='1'>";
			print "<tr bgcolor='lightblue'><th>Item id</th><th>Brand</th><th>Item Name</th><th>Item name en</th><th>Color</th><th>Unit cost</th><th>Stock</th><th>Supplier Name</th></tr>";
			foreach($result as $row){
				if($count % 2 == 0)
   	    			print "<tr align='right'>";
   	    		else
   	    			print "<tr align='right' bgcolor='orange'>";
   				print "<td>" . $row['item_id'] . "</td>";
   				print "<td>" . $row['brand'] . "</td>";
   				print "<td>" . $row['item_name'] . "</td>";
   				print "<td>" . $row['item_name_en'] . "</td>";
   				print "<td>" . $row['color'] . "</td>";
   				print "<td>" . $row['unit_cost'] . "</td>";
   				//print "<td>" . $row['unit_price'] . "</td>";
   				print "<td>" . $row['stock'] . "</td>";
   				//print "<td>" . $row['supplier_id'] . "</td>";
   				print "<td>" . $row['suppl_name'] . "</td>";
			/* how to bring the content that user wants to update and search condition to next step (restock_act.php) ?
			 * - send 1 variables to bring "item id" to next page */
   				print "<td><a href='restock_act.php?itemID=".$row['item_id']."'>Restock</a></td>";
				print "</tr>";
				$count--;
			}
			print "</table><br/>";

		} else {	//no item found
			print "<br/>No item found, you can change the search condition and search again or 
			       <a href='newItem.php'>Add a new item</a></a><br/>";
		}
	}
	unset($row);
	unset($result);
} else {
	print "<br/><i>Search result suppose to be here</i><br/>";
}

/*
$sql = "UPDATE";
print $sql;
*/

//close db connection
$dbh = null;
?>
</form>
</div>
</body>
</html>
