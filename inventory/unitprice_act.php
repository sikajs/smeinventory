<?php
include "../shared/user_check_right.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script type="text/javascript">
<!--
function checkform ( form )
{
  if (form.newPrice.value == "") {
    alert( "Please enter the new price." );
    form.newPrice.focus();
    return false ;
  }
  if (form.newPrice.value < 0) {
    alert( "The price cannot be negtive" );
    form.newPrice.value="";
    form.newPrice.focus();
    return false ;
  }
  return true ;
}
//-->
</script>
<title></title>

</head>
<body>
<center><h4>Unit price setting</h4></center>
<b>Step3 - Enter new price of the selected item and press Update :</b>
<form method="post" action="unitprice_final.php" onsubmit='return checkform(this);'>
<?php
/* connect database */
include "../shared/dbconnect.php";
// get target row        
$sql = "SELECT brand,item_name FROM items WHERE item_id='".htmlspecialchars($_GET['itemID'])."'";
$res = $dbh->query($sql);
$value = $res->fetchAll();
$sql = "SELECT * FROM items WHERE brand='".$value[0]['brand']."' AND item_name='".$value[0]['item_name']."' ORDER BY unit_cost";
//print $sql."<br/>";

print "<br/>The following item(s) will be affected by the new price -";
print "<table border='1'>";
print "<tr bgcolor='lightBlue'>".
      "<th>item id</th><th>brand</th><th>item name</th><th>item Eng name</th><th>unit cost</th><th>current price</th><th>stock</th><th>Supplier ID</th><th>Active</th>".
      "</tr>";
$result = $dbh->query($sql);
$row = $result->fetchAll();
$num = count($row);

//need to show the item(s) which will be affected and generate new sql for updating 
for($i=0; $i<$num; $i++){
	print "<tr align='right'>";
	print "<td>" . $row[$i]['item_id'] ."</td>";
	if($_SESSION['affectedItems'] == null){
		$_SESSION['affectedItems'] = $row[$i]['item_id'];
	} else {
		$_SESSION['affectedItems'] = $_SESSION['affectedItems'].",".$row[$i]['item_id'];
	}
   	print "<td>" . $row[$i]['brand'] ."</td>";
   	print "<td>" . $row[$i]['item_name'] ."</td>";
        print "<td>" . $row[$i]['item_name_en'] ."</td>";
   	print "<td>" . $row[$i]['unit_cost'] ."</td>";
   	print "<td>" . $row[$i]['unit_price'] ."</td>";
   	print "<td>" . $row[$i]['stock'] ."</td>";
   	print "<td>" . $row[$i]['supplier_id'] ."</td>";
        if($row[$i]['active'])
            echo "<td>Yes</td>";
        else
            echo "<td>No</td>";
	print "</tr>";
}
print "</table>";
print "<br/>New price : <input type='text' name='newPrice' size='10'/>  ";
   	print "<input type='submit' value='Update'/>";
echo "<br/>";

//close db connection
$dbh = null;
?>
</form>
need to provide "back to search result" function
</body>
</html>
