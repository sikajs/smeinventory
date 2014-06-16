<?php
include "../shared/user_check_right.php";

// function for converting the encoding to uf8
function convEncoding($in_str)
{
  $cur_encoding = mb_detect_encoding($in_str) ;
  if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
    return $in_str;
  else
    return utf8_encode($in_str);
}
 
/* connect database */
include "../shared/dbconnect.php";

//Insert user's input into database, the encoding of data in database will be utf-8
$sql = "INSERT INTO customers (cust_name,cust_tel,cust_fax,cust_mobile,cust_email,cust_address) values ('".
       convEncoding($_POST['custName'])."','".$_POST['tel']."','".$_POST['fax']."','".$_POST['mobile']."','".
	   $_POST['email']."','".convEncoding($_POST['address'])."')";
//print $sql."<br/>";
	
if($dbh->exec($sql)){
	$sql = "SELECT customer_id FROM customers WHERE cust_name='".$_POST['custName']."'";
	$idResult = $dbh->query($sql);
	$custID = $idResult->fetchColumn();
	print "Customer added. Your customer number is ".$custID."<br/>";
} else {
	print "Something is wrong! The customer cannot be added. <br/>";
	//print_r($dbh->errorInfo());
}
//close db connection
$dbh = null;
?>
