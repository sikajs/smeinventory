<?php
include "../shared/user_check_right.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8">
<!--
$(document).ready(function(){
    $('#newStock').focus();
});
function checkform ( form )
{
  if (form.newCost.value <= 0) {
  	 alert( "The number of the new cost needs to be positive." );
    form.newCost.value = "";
    form.newCost.focus();
    return false ;
  }  
  if (form.newStock.value == "") {
    alert( "Please enter the number of the new stock." );
    form.newStock.focus();
    return false ;
  }
  if (form.newStock.value <= 0) {
  	 alert( "The number of the new stock needs to be positive." );
    form.newStock.value = "";
    form.newStock.focus();
    return false ;
  }
  return true ;
}
//-->
</script>
<title></title>

</head>
<body>
<p class='blacktitle'>Restock</p>
<b>Step3 - Enter number of the stock for this item and press Update :</b>
<form name='restock' method='post' action='restock_final.php' onsubmit='return checkform(this);'>
<table border='1'>
    <tr><th>item id</th><th>brand</th><th>item name</th><th>curr unit cost</th><th>current stock</th>
    <th>new unit cost</th><th>new arrival</th><th>Supplier Name</th></tr>
<?php
/* connect database */
include "../shared/dbconnect.php";

// get target item        
$sql = "SELECT items.*,suppliers.suppl_name FROM items, suppliers ".
		 "WHERE item_id='".htmlspecialchars($_GET['itemID'])."' AND items.supplier_id=suppliers.supplier_id ";
$stmt = $dbh->query($sql);
$result = $stmt->fetchAll();
print "<tr align='center'>";
print "<td>" . $result[0]['item_id'] . "<input type='hidden' name='itemID' value='".$result[0]['item_id']."'/></td>";
print "<td>" . $result[0]['brand'] . "</td>";
print "<td>" . $result[0]['item_name'] . "</td>";
//allow user to record the latest price of the item
echo "<td align='right'>" . $result[0]['unit_cost'] . "</td>";
print "<input type='hidden' name='pre_cost' value='".$result[0]['unit_cost']."' />";
print "<td>" . $result[0]['stock'] . "<input type='hidden' name='currStock' value='".$result[0]['stock']."'/></td>";
print "<td><input type='text' size='10' name='newCost' value='".$result[0]['unit_cost']."' /></td>";
print "<td><input type='text' size='10' name='newStock' id='newStock' /></td>";
print "<input type='hidden' name='supplier_id' value='".$result[0]['supplier_id']."' />";
print "<td>" . $result[0]['suppl_name'] . "</td>";
print "<td><input type='submit' value='Update'/></td>";
print "</tr>";

//cleanup
unset($stmt);
unset($result);
$dbh = null;
?>
</table>
</form>
<!--* need to provide "back to search result" function later-->
</body>
</html>
