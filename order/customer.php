<?php
include "../shared/user_check_right.php";
?>
<html>
<head><title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="javascript" type="text/javascript" src="customer.js"></script>
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<body>
<p class='blacktitle'>Customer Management</p>
<div id="customerSearch">
<b>Try to find the customer first or directly <a href='newcustomer.php'>add a new customer</a> :</b>
<form name="customerSearchForm" method="post" action="customer.php">
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
$sqlCheck = "";
$sql = "";
if($_POST['customerNum'] != null){
	$sql = "SELECT * FROM customers ";
	$sql = $sql."WHERE customer_id='".$_POST['customerNum']."' ";
	$sqlCheck = "SELECT count(customer_id) FROM customers ";
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
	$sqlCheck = "SELECT count(customer_id) FROM customers ";
	$sqlCheck = $sqlCheck."WHERE cust_name like '%".$_POST['customerName']."%' ";
}
//print $sql."<br/>";
//print $sqlCheck;
?>
</div>
<hr>
<div id="customerList">
<center><h4>Customer detail</h4></center>
<?php
/* connect database */
include "../shared/dbconnect.php";

$count = 0;
if($_POST['customerName'] != null or $_POST['customerNum'] != null){
	if($res = $dbh->query($sqlCheck)){
		$count = $res->fetchColumn();
		//print $count;
		if($count > 0){ //customer found
			print $count." Customer found";
			if($res = $dbh->query($sql)){
				print "<table border='1'>";
				print "<tr><th>Customer Number</th><th>Customer Name</th><th>Email</th></tr>";
				foreach($res as $value){
					print "<form name='custUpdate".$value['customer_id']."' method='post' action='custdetail.php'>";
					print "<tr>";
					print "<td align='center'>".$value['customer_id']."<input type='hidden' name='CID' value='".$value['customer_id']."'/></td>";
					print "<td>".$value['cust_name']."</td>";
					print "<td>".$value['cust_email']."</td>";
					//print "<td><input type='button' value='Update detail' onclick='javascript:customerList(".$value['customer_id'].")'/></td>";
					print "<td><input type='submit' value='Update detail'/></td>";
					print "</tr>";
					print "</form>";
				}
				print "</table>";
			}
		} else { //customer not found, show the addnew form
			print "Customer not found. You can search again or <a href='newcustomer.php'>add a new customer</a>";
		}
	}	
} else {
	print "You need to enter the criteria for search.";
}
?>
</div>

<?php
//close db connection
$dbh = null;
?>
</body>
</html>
