<?php
include "../shared/user_check_right.php";

/* connect database */
include "../shared/dbconnect.php";

$sql = "UPDATE customers SET cust_name='".$_POST['custName'].
       "', cust_tel='".$_POST['tel']."', cust_fax='".$_POST['fax']."', cust_mobile='".$_POST['mobile'].
       "', cust_email='".$_POST['email']."', cust_address='".$_POST['address']."' ".
       "WHERE customer_id='".$_POST['CID']."'";
//print $sql;

$count = $dbh->exec($sql);
print "<center><h4>Update Customer detail</h4></center>";
if($count == 0){
	print "Update failed. Please contact system administrator.<br/>";
	print $dbh->errorInfo();
} else {
	print "Detail of $count customer updated.<br/>";
	print "You can <a href='customer.php'>Update next customer...</a>";
}
//close db connection
$dbh = null;
?>
