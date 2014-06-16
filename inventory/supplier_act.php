<?php
include "../shared/user_check_right.php";

/* connect database */
include "../shared/dbconnect.php";

//Update supplier details
$sql = "UPDATE suppliers SET suppl_name='".$_POST['supplName']."', business_num='".$_POST['busiNum'].
       "', suppl_tel='".$_POST['tel']."', suppl_fax='".$_POST['fax']."', suppl_mobile='".$_POST['mobile'].
       "', suppl_email='".$_POST['email']."', suppl_address='".$_POST['address']."' ".
       "WHERE supplier_id='".$_POST['supplID']."'";
//print $sql;

$count = $dbh->exec($sql);
if($count == 0){
	print "Update failed. Please contact system administrator.<br/>";
	print $dbh->errorInfo();
} else {
	print "Detail of $count supplier updated.<br/>";
	print "You can <a href='supplier.php'>Update next supplier...</a>";
}
//cleanup and close db connection
//$res = null;
$dbh = null;
?>
