<?php
//the first page for every time using the smeInventory system
session_start();

?>
<HTML>
<head>
<link rel=stylesheet type="text/css" href="shared/smeInventory.css">
<script type="text/javascript" language="javascript" charset="UTF-8" src="./js/jquery-1.7.1.min.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
$(document).ready(function(){
    $('#user').focus();
});
function checkform ( form )
{
  if (form.user.value == "") {
    alert( "Please enter your username." );
    form.user.focus();
    return false ;
  }
  if (form.smepass.value == "") {
    alert( "Please enter your password." );
    form.smepass.focus();
    return false ;
  }
  return true ;
}
//-->
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<center>
<h3>Login</h3>
<form name='loginform' method='post' action='login_process.php' onsubmit='return checkform(this);'>
<table>
	<tr><td>User : </td><td><input type='text' id='user' name='user'/></td></tr>
	<tr><td>Password : </td><td><input type='password' name='smepass'/></td></tr>
	<tr><td></td><td><input type='reset'/> <input type='submit' value='Login'/> </td></tr>
</table>
</form>

</center>
</body>
</HTML>
