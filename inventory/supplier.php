<?php
include "../shared/user_check_right.php";
?>
<html>
<head><title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<body>
<p class='blacktitle'>Supplier Management</p>
<!-- Provide the form for user to search supplier-->
<div id="supplierSearch">
<b>Step1 - Search the supplier which you want to change or <a href='newsupplier.php'>Add a new supplier</a> :</b>
<form name="supplierSearchForm" method="post" action="supplier.php">
<table border="0">
    <tr>
      <td>Supplier Name</td>
      <td><input type="text" name="supplName" class="inputText"/></td>
      <td>Business Number</td>
      <td><input type="text" name="busiNum" class="inputText"/></td>
    </tr>
</table>
<input type="submit" value="Search">
<input type="reset">
</form>
<?php
$sqlCheck = "";
$sql = "";
if($_POST['supplName'] != null){
	$sql = "SELECT * FROM suppliers ";
	$sqlCheck = "SELECT count(*) FROM suppliers ";
	$sql = $sql."WHERE suppl_name like '%".$_POST['supplName']."%' ";
	$sqlCheck = $sqlCheck."WHERE suppl_name='".$_POST['supplName']."' ";
	if($_POST['busiNum'] != null){
		$sql = $sql."AND business_num='".$_POST['busiNum']."' ";
		$sqlCheck = $sqlCheck."AND business_num='".$_POST['busiNum']."' ";
	}
} elseif($_POST['busiNum'] != null){
	$sql = "SELECT * FROM suppliers ";
	$sqlCheck = "SELECT count(*) FROM suppliers ";
	$sql = $sql."WHERE business_num='".$_POST['busiNum']."' ";
	$sqlCheck = $sqlCheck."WHERE business_num='".$_POST['busiNum']."' ";
}
$sql = $sql."ORDER BY supplier_id ";
//print $sql;
//print $sqlCheck;
?>
</div>

<hr></hr>

<div name="supplierDetail">
<center><h4>Supplier detail</h4></center>

<?php
/* connect database */
include "../shared/dbconnect.php";
// get supplier detail by searching its name or business number
if($_POST['supplName'] != null or $_POST['busiNum'] != null){
    foreach ($dbh->query($sql) as $row) {
    	print "<form method='post' name='supplUpdate".$row['supplier_id']."' action='supplier_act.php'>";
    	print "<table border='1'>";
		print "<tr><th>Supplier ID</th><th>Supplier Name</th><th>Business Number</th><th>Tel</th>
	           <th>Fax</th><th>Mobile</th><th>Email</th><th>Address</th></tr>";
    	print "<tr><td align='center'>".$row['supplier_id']."<input type='hidden' name='supplID' value='".$row['supplier_id']."'/></td>";
    	print "<td><input type='text' name='supplName' size='20' value='".$row['suppl_name']."'/></td>";
    	print "<td><input type='text' name='busiNum' size='10' value='".$row['business_num']."'/></td>";
    	print "<td><input type='text' name='tel' size='10' value='".$row['suppl_tel']."'/></td>";
    	print "<td><input type='text' name='fax' size='10' value='".$row['suppl_fax']."'/></td>";
    	print "<td><input type='text' name='mobile' size='10' value='".$row['suppl_mobile']."'/></td>";
    	print "<td><input type='text' name='email' size='20' value='".$row['suppl_email']."'/></td>";
    	print "<td><input type='text' name='address' size='60' value='".$row['suppl_address']."'/></td>";
    	print "</tr>";
    	print "</table>";
    	print "<br/><input type='submit' value='Update detail' size='10' />";
    	print "</form>";
    }
    unset($row);
} else {
	print "You need to enter the criteria for search.";
}

//close db connection
$dbh = null;
?>
</div>

</body>
</html>
