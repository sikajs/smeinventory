<?php
include "../shared/user_check_right.php";
?>
<html>
<head><title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<body>
<center><h4>Input new supplier</h4></center>
<?php
/* connect database */
include "../shared/dbconnect.php";

/* Can we use (supplier name, business num) to be Primary key instead? */
$sql = "INSERT INTO suppliers (suppl_name,business_num,suppl_tel,suppl_fax,suppl_mobile,suppl_email,suppl_address) 
        values ('".$_POST['supplName']."','".$_POST['busiNum']."','".$_POST['tel']."','".$_POST['fax']."','".
		       $_POST['mobile']."','".$_POST['email']."','".$_POST['address']."')";
//print $sql;
$count = $dbh->exec($sql);
if($count == 0){
	print "Supplier addition failed";
	die($dbh->errorInfo());	
} else {
	print "New supplier added.<br/>";
	print "<a href='newsupplier.php'>Add more supplier...</a>";
}
?>
</body>
</html>
