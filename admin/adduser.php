<?php
include "../shared/user_check_right.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script type="text/javascript">
<!--
function checkform ( form )
{
  if (form.userName.value == "") {
    alert( "Please enter your username." );
    form.userName.focus();
    return false ;
  }
  if (form.pass.value == "") {
    alert( "Please enter your password." );
    form.pass.focus();
    return false ;
  }
  if (form.passconfirm.value == "") {
    alert( "Please enter your password again for confirmation." );
    form.passConfirm.focus();
    return false ;
  }
  if (form.pass.value != form.passconfirm.value) {
    alert( "Your password doesn't match with each other." );
    form.pass.value = "";
    form.passconfirm.value = "";
    form.pass.focus();
    return false ;
  } 
  return true ;
}
//-->
</script>
<title></title>
</head>
<p class='blacktitle'>New User Form</p>
<form name='addNewUser' method='post' action='adduser_act.php' onsubmit='return checkform(this);'>
<table border='0'>
	<tr>
		<td>User Name</td><td><input type='text' size='30' name='userName'/></td>
   </tr>
	<tr>
   	<td>Password</td><td><input type='password' size='30' name='pass'/></td>
	</tr>
	<tr>
   	<td>Password confirm</td><td><input type='password' size='30' name='passconfirm'/></td>
	</tr>
	<tr><td>Description</td><td><input type="text" size="50" name="desc" /></td></tr>
	<tr>
		<td>Access to Order dealing</td><td><input type="checkbox" name="main_order" value="1" /></td>
	</tr>
	<tr>
		<td>Access to Inventory</td><td><input type="checkbox" name="main_inventory" value="1" /></td>
	</tr>
	<tr>
		<td>Access to User Management</td><td><input type="checkbox" name="main_user" value="1" /></td>
	</tr>
	
</table>
<input type='submit' value='Add'/>
</form>
	

</body>
</html>
