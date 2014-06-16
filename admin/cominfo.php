<?php
include "../shared/user_check_right.php";

//database connection
include "../shared/dbconnect.php";

$sql="SELECT * FROM com_info";
$stmt = $dbh->query($sql);
$result = $stmt->fetchAll();
$com_name = $result[0]['com_name'];
$com_busino = $result[0]['com_businessno'];
if($result[0]['com_businessno'] != '')
	$_SESSION['cominfosetted']=1;
else
	$_SESSION['cominfosetted']=0;
$com_address = $result[0]['com_address'];
$com_tel = $result[0]['com_tel'];
$com_fax = $result[0]['com_fax'];
$com_zipcode = $result[0]['com_zipcode'];
$com_vat = ($result[0]['com_vat']*100.0);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script type="text/javascript">
<!--
function checkform ( form )
{
  if (form.com_name.value == "") {
    alert( "Please enter your company name." );
    form.com_name.focus();
    return false ;
  }
  if (form.com_busino.value == "") {
    alert( "Please enter your business number." );
    form.com_busino.focus();
    return false ;
  }
  return true ;
}
//-->
</script>
</head>
<body>
<p class='blacktitle'>Setup company infomation</p>
<hr/>
<form action="cominfo_act.php" method="post" onsubmit='return checkform(this);'>
<table>
<tr>
	<td>Company Name :</td><td><input type="text" name="com_name" value="<?php if (isset($com_name)) { echo $com_name; } ?>"></td>
	<td>Company Business Number :</td><td><input type="text" name="com_busino" value="<?php if (isset($com_busino)) { echo $com_busino; } ?>"></td>
</tr>
<tr><td>Company Address :</td><td colspan='3'><input type="text" name="com_address" size='80' value="<?php if (isset($com_address)) { echo $com_address; } ?>"></td></tr>
<tr>
	<td>Company Zipcode :</td><td><input type="text" name="com_zipcode" value="<?php if (isset($com_zipcode)) { echo $com_zipcode; } ?>"></td>
	<td>Company VAT Percentage (%) :</td><td><input type="text" name="com_vat" value="<?php if (isset($com_vat)) { echo $com_vat; } ?>"></td>
</tr>
<tr>
	<td>Company Tel :</td><td><input type="text" name="com_tel" value="<?php if (isset($com_tel)) { echo $com_tel; } ?>"></td>
	<td>Company Fax :</td><td><input type="text" name="com_fax" value="<?php if (isset($com_fax)) { echo $com_fax; } ?>"></td>
</tr>

</table>
<input type='submit' value='Set'/>
<input type='reset' />
</form>
</body>
</html>