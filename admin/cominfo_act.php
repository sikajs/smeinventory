<?php
include "../shared/user_check_right.php";

include "../shared/dbconnect.php";

if($_SESSION['cominfosetted'] == 0){   //first time setting
	$sql = "INSERT into com_info (com_name, com_businessno, com_address, com_tel, com_fax, com_zipcode, com_vat) 
	       values('".$_POST['com_name']."','".$_POST['com_busino']."','".$_POST['com_address'].
			 "','".$_POST['com_tel']."','".$_POST['com_fax']."','".$_POST['com_zipcode']."','".$_POST['com_vat']."')";
} else {   //update information
	$sql = "UPDATE com_info SET com_name='".$_POST['com_name']."', com_businessno='".$_POST['com_busino'].
			 "', com_address='".$_POST['com_address']."', com_tel='".$_POST['com_tel']."', com_fax='".$_POST['com_fax'].
			 "', com_zipcode='".$_POST['com_zipcode']."', com_vat='".($_POST['com_vat']/100.0)."'";
}	
//print $sql."<br/>";

$afterexec = $dbh->exec($sql);
if($afterexec != 1)
	print "Something wrong during set up company information, please contact system administrator.";
else
	print "Company information setted.";
?>