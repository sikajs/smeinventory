<?php
include "../shared/user_check_right.php";
?>
<html>
<head>
<script type="text/javascript">
<!--
function checkform ( form )
{
  if (form.custName.value == "") {
    alert( "Please enter customer's name." );
    form.custName.focus();
    return false ;
  }
  if (form.email.value == "") {
    alert( "Please enter customer's email." );
    form.email.focus();
    return false ;
  }
  return true ;
}
//-->
</script>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<p class='blacktitle'>New Customer Form</p>
<form name='addNewCustomer' method='post' action='newcustomer_act.php' onsubmit='return checkform(this);'>
<table>
	<tr>
		<td>Customer Name *</td><td><input type='text' size='30' name='custName'/></td>
		<td>Tel</td><td><input type='text' size='10' name='tel'/></td>
   	</tr>
	<tr>
   		<td>Email *</td><td><input type='text' size='40' name='email'/></td>
		<td>Mobile</td><td><input type='text' size='10' name='mobile'/></td>
	</tr>
	<tr>
   		<td>Address</td><td><input type='text' size='40' name='address'/></td>
		<td>Fax</td><td><input type='text' size='10' name='fax'/></td>
	</tr>
</table>
<input type='submit' value='Add'/>
</form>
note : * field is mandatary	

</body>
</html>
