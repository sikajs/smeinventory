<?php
include "../shared/user_check_right.php";

/* connect database */
include "../shared/dbconnect.php";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
//prepare the customer's detail for updating
print "<center><h4>Update Customer detail</h4></center>";
$sql = "SELECT * FROM customers WHERE customer_id=".$_POST['CID'];
if($res = $dbh->query($sql)){
	$result = $res->fetchAll();
	print "<form method='post' action='custdetail_act.php'>";
	print "<table>";
	print "<tr><input type='hidden' name='CID' value='".$_POST['CID']."'";
	print "<td>Customer Name</td><td><input type='text' size='30' name='custName' value='".$result[0]['cust_name']."'/></td>";
	print "<td>Tel</td><td><input type='text' size='10' name='tel' value='".$result[0]['cust_tel']."'/></td>";
   	print "</tr>";
	print "<tr>";
   	print "<td>Email</td><td><input type='text' size='40' name='email' value='".$result[0]['cust_email']."'/></td>";
	print "<td>Mobile</td><td><input type='text' size='10' name='mobile' value='".$result[0]['cust_mobile']."'/></td>";
	print "</tr>";
	print "<tr>";
   	print "<td>Address</td><td><input type='text' size='40' name='address' value='".$result[0]['cust_address']."'/></td>";
	print "<td>Fax</td><td><input type='text' size='10' name='fax' value='".$result[0]['cust_fax']."'/></td>";
	print "</tr>";
	print "</table>";
	print "<input type='submit' value='Submit'/>";
	print "</form>";
}

//close db connection
$dbh = null;
?>
</html>