<?php
include "../shared/user_check_right.php";
?>
<html>
<head><title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<body>
<p class='blacktitle'>Unit price setting</p>
<div id="itemSearch">
<b>Step1 - Search the item which you want to set first :</b>
<form name="searchForm" method="post" action="unitprice.php">
<table border="0">
    <tr>
      <td>Item name</td><td><input type="text" name="itemName" class="inputText" /></td>
    </tr>
    <tr>
      <td>Brand</td><td><input type="text" name="brand" class="inputText" /></td>
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




<div id="">
<b>Step2 - Select the item which you want to set and press select:</b>
<form name="Form" method="post" action="unitprice_act.php">
<?php
/* After found item, user can update the stock here 
 * based on restock information by sending another form */
if($_POST['backToResult']=='true' or $_POST['brand']!=null or $_POST['itemName']!=null or $_POST['supplier']!=null or $_POST['prod_code'] != null or $_POST['id']!=null or $_POST['color']!=null or $_POST['iBarcode']!=null){
	$res = $dbh->query($sql);
        if($res !== FALSE){	//query succeeded
                $result = $res->fetchAll(PDO::FETCH_ASSOC);
		$count = count($result);
		if($count > 0){
			print "<table border='1'>";
			//print "<tr><th>item id</th><th>brand</th><th>item name</th><th>unit cost</th><th>unit price</th><th>stock</th><th>Supplier ID</th></tr>";
			print "<tr><th>item id</th><th>brand</th><th>item name</th><th>item Eng name</th><th>unit cost</th><th>stock</th><th>Supplier name</th></tr>";
			foreach($result as $row){
				if($count % 2 == 0)
   	    			print "<tr align='right'>";
   	    		else
   	    			print "<tr align='right' bgcolor='orange'>";
   				print "<td>" . $row['item_id'] . "</td>";
   				print "<td>" . $row['brand'] . "</td>";
   				print "<td>" . $row['item_name'] . "</td>";
                                print "<td>" . $row['item_name_en'] . "</td>";
   				print "<td>" . $row['unit_cost'] . "</td>";
   				//print "<td>" . $row['unit_price'] . "</td>";
   				print "<td>" . $row['stock'] . "</td>";
   				print "<td>" . $row['suppl_name'] . "</td>";
			/* how to bring the content that user wants to update and search condition to next step (restock_act.php) ?
			 * - send 1 variables to bring "item id" to next page */
   				print "<td><a href='unitprice_act.php?itemID=".$row['item_id']."'>select</a></td>";
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

//close db connection
$dbh = null;
?>
</form>
</div>
</body>
</html>
