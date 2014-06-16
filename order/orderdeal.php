    `<?php
include "../shared/user_check_right.php";

$_SESSION['removeitem'] = false;

//Initial the shopping cart
function checkShoppingCart(){
	if($_GET['resetCart'] == 1){
		unset($_SESSION['cart']);
		$_GET['resetCart'] = 0;
	}
	$cart = $_SESSION['cart'];
	$numInCart = count($cart);
	print "You have ";
	if($numInCart == 0){
		print "no item in your shopping cart.";
	} else {
		print $numInCart." item";
		if($numInCart >1)
			print "s";
		print " in your shopping cart now.";
	}
}
?>
<html>
<head>
<script type='text/javascript'>
function checkform ( form )
{
  if (form.amount.value == "") {
    alert( "Please enter the amount." );
    form.amount.focus();
    return false ;
  }
  if (form.amount.value <= 0) {
    alert( "The amount needs to be positive." );
    form.amount.focus();
    return false ;
  }
  return true ;
}
</script>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<p class='blacktitle'>New Order</p>
<div id="customerSearch">
<b>Step1 - Find the customer to start shopping or <a href='newcustomer.php'>Add a new customer</a> :</b>
<form name="customerSearchForm" method="post" action="orderdeal.php">
<table border="0">
    <tr>
      <td>Customer Name</td>
      <td><input type="text" name="customerName" size="30"/></td>
      <td>Customer Number</td>
      <td><input type="text" name="customerNum" size="30"/></td>
    </tr>
</table>
<input type="submit" value="Search">
<input type="reset">
</form>
<?php
/* connect database */
include "../shared/dbconnect.php";

//search customer and mark on the shopping cart
$sql = $sqlCheck = "";
if($_POST['customerNum'] != null){
	$sql = "SELECT * FROM customers ";
	$sql = $sql."WHERE customer_id='".$_POST['customerNum']."' ";
	$sqlCheck = "SELECT count(*) FROM customers ";
	$sqlCheck = $sqlCheck."WHERE customer_id='".$_POST['customerNum']."' ";
	if($_POST['customerName'] != null){
		$sql = $sql."AND cust_name like '%".$_POST['customerName']."%' ";
		$sql = $sql."ORDER BY customer_id ";
		$sqlCheck = $sqlCheck."AND cust_name like '%".$_POST['customerName']."%' ";
	}
} elseif($_POST['customerName'] != null){
	$sql = "SELECT * FROM customers ";
	$sql = $sql."WHERE cust_name like '%".$_POST['customerName']."%' ";
	$sql = $sql."ORDER BY customer_id ";
	$sqlCheck = "SELECT count(*) FROM customers ";
	$sqlCheck = $sqlCheck."WHERE cust_name like '%".$_POST['customerName']."%' ";
}
//print $sql."<br/>";
//print $sqlCheck;
if($_POST['customerName'] != null or $_POST['customerNum'] != null){
	if($res = $dbh->query($sqlCheck)){
		$count = $res->fetchColumn();
		if($count == 0){
			print "Customer not found. Please add a new customer.<br/>";
		} else {
			print "<table border='1'>";
			print "<tr><th>ID</th><th>Name</th><th>Tel</th><th>Mobile</th></tr>";
			foreach($dbh->query($sql) as $row){
				print "<tr>";
				print "<form name='custForm".$row['customer_id']."' method='post' action='selectcustomer.php'>";
				print "<td>".$row['customer_id']."<input type='hidden' name='custID' value='".$row['customer_id']."'/></td>";
				print "<td>".$row['cust_name']."<input type='hidden' name='custName' value='".$row['cust_name']."'/></td>";
				print "<td>".$row['cust_tel']."</td>";
				print "<td>".$row['cust_mobile']."</td>";
				print "<td><input type='submit' value='select' /></td>";
				print "</form>";
				print "</tr>";
			}
			print "</table>";
		}
	}
}
?>
</div>

<hr></hr>
<?php
if($_SESSION['customerID'] != null){
	print "<div id='afterCustomer'>";
} else {
	print "<div id='afterCustomer' style='visibility:hidden'>";
}
?>
<div id="itemSearch">
<b>Step2 - Search the item which you want to buy first :</b> 
<?php 
print "Customer - ".$_SESSION['customerName']; 
?>
<form name="searchForm" method="post" action="orderdeal.php">
<table border="0">
    <tr>
      <td>Brand</td><td><input type="text" name="brand" size="30" /></td>
      <td>Item name</td><td><input type="text" name="itemName" size="30" /></td>
    </tr>
</table>
<?php
//Base on the conditions user entered to generate sql statement
if($_POST['backToResult'] == 'true'){
	$sql = $_SESSION['lastSQL'];
} else {
	$sql = "";
	unset($_SESSION['lastSQL']);
	if($_POST['brand'] != null) { // user put brand as condition
		$sql = "SELECT item_id, item_name, brand, unit_price, stock FROM items ";
		$sql = $sql."WHERE brand='".strtoupper($_POST['brand'])."' ";
		if($_POST['itemName'] != null) // user put brand and item name as condition
			$sql = $sql."AND item_name='".$_POST['itemName']."' ";
	} elseif($_POST['itemName'] != null){ // user only put item name as condition
		//$sql = "SELECT * FROM items ";
		$sql = "SELECT item_id, item_name, brand, unit_price, stock FROM items ";
		$sql = $sql."WHERE item_name='".$_POST['itemName']."' ";
	}
	$sql = $sql." ORDER BY brand, item_name, item_id";
}
//print " mm ".$sql;
?>
<input type="submit" value="Search">
<input type="reset">
</form>
</div>

<hr/>

<div id="addToCart">

<b>Step3 - Add item to your shopping cart :</b>
<?php
checkShoppingCart();

/* After item(s) found, user can add the item to shopping cart here */
if($_POST['backToResult'] == 'true' or $_POST['brand'] != null or $_POST['itemName'] != null){
	if($res = $dbh->query($sql)){	//inefficient but work
		$check = $res->fetchColumn();
		//print $count;
		if($check != null){
			// record the sql and set the variable for back to the search result later
			$_SESSION['lastSQL'] = $sql;
			//print $sql;
			
			// list the search result for user to choose which item to add
			print "<table border='1'>";
			print "<tr><th>item id</th><th>brand</th><th>item name</th><th>unit price</th><th>stock</th><th>amount</th></tr>";
			$res = $dbh->query($sql);
			$rowNum = $res->rowCount(); //need to check if want to change to other database
			$columnNum = $res->columnCount();
			$result = $res->fetchAll();
			$totalStock = 0;
			for($i = 0; $i <$rowNum ;$i++){
				print "<tr>";
				print "<form name='addToCartForm".$i."' method='post' action='orderdeal_act.php' onsubmit='return checkform(this);'>";
				//Right now only list new item, although they have the same brand and name
				if($result[$i]['brand'] == $result[$i+1]['brand'] and $result[$i]['item_name'] == $result[$i+1]['item_name']){
					if($sameItem != null)
						array_push($sameItem,$result[$i]['item_id']);
					else
						$sameItem = array($result[$i]['item_id']);
					$totalStock = $totalStock + (int)($result[$i]['stock']);
				} else {
					print "<td>".$result[$i]['item_id']."<input type='hidden' name='itemID' value='".$result[$i]['item_id']."'/>";
					print "<input type='hidden' name='sameItem' value='";
					if($sameItem != null){
						foreach($sameItem as $value){
							print $value.",";
						}
						unset($sameItem);
						unset($value);
					}
					print "'/>";
					print "</td>";
					print "<td>".$result[$i]['brand']."<input type='hidden' name='itemBrand' value='".$result[$i]['brand']."'/></td>";
					print "<td>".$result[$i]['item_name']."<input type='hidden' name='itemName' value='".$result[$i]['item_name']."'/></td>";
					print "<td>".$result[$i]['unit_price']."<input type='hidden' name='unit_price' value='".$result[$i]['unit_price']."'/></td>";
					$totalStock = $totalStock + (int)($result[$i]['stock']);
					if($totalStock != 0){
						print "<td>".$totalStock."</td>";
						print "<td><input type='text' size='5' name='amount' value='0'/></td>";
						//print "<td><input type='submit' value='Add to cart' onsubmit='return validate_form(this)'/></td>";
						print "<td><input type='submit' value='Add to cart'/></td>";
					} else
						print "<td><font color='red'>Out of stock</font></td>";
					$totalStock = 0;
				}
				print "</form>";
				print "</tr>";
			}
			print "</table><br/>";
			
		} else {	//no item found
			print "<br/><br/>No item found, you can change the search condition and search again. <br/><br/>";
		}
	}
	unset($res);
	unset($result);
} else {
	print "<br/><i>Item searching result suppose to be here</i><br/>";
}

print "<hr>";
//provide the button to go to shopping cart
print "<form name='checkCart' method='post' action='orderdeal_act.php'>";
print "<input type='hidden' name='checkcart' value='true'/>";
if($_SESSION['cart']){
	print "<input type='submit' value='check shopping cart'/>";
} else {
	print "<input type='submit' value='check shopping cart' disabled='true'/>";
}
print "</form>";


//close db connection
$dbh = null;
?>
</div>
</div>
</body>
</html>
