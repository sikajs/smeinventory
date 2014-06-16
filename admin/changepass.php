<?php
include "../shared/user_check_right.php";
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script language="JavaScript" type="text/javascript">
<!--
function checkform ( form )
{
  if (form.oldpass.value == "") {
    alert( "Please enter your old password." );
    form.oldpass.focus();
    return false ;
  }
  if (form.newpass.value == "") {
    alert( "Please enter your new password." );
    form.newpass.focus();
    return false ;
  }
  if (form.passconfirm.value == "") {
    alert( "Please enter your new password again." );
    form.passconfirm.focus();
    return false ;
  }
  if (form.oldpass.value == form.newpass.value) {
    alert( "Your new password is the same with the old one." );
    form.newpass.value = "";
    form.passconfirm.value = "";
    form.newpass.focus();
    return false ;
  } 
  if (form.newpass.value != form.passconfirm.value) {
    alert( "Your new password doesn't match with each other." );
    form.newpass.value = "";
    form.passconfirm.value = "";
    form.newpass.focus();
    return false ;
  } 
  return true ;
}
//-->
</script>
</head>
<body>
<p class='blacktitle'>Change password</p>
<?php
if($_SESSION['firstlogin'] == true){
	print "Hi! This is your first time to login, please <font color='red'>change your password immediately</font>.<br/>";
	print "Remember to use your new password to login in the future.<br/><br/>";
}
echo "You login as : <b>".$_SESSION['user_info']['username']."</b>";
?>
<form name='changepass' method='post' action='changepass_process.php' onsubmit='return checkform(this);'>
<table>
	<tr><td>Enter your old password : </td><td><input type='password' name='oldpass' /></td></tr>
	<tr><td>Type new password here : </td><td><input type='password' name='newpass' /></td></tr>
	<tr><td>Type new password again : </td><td><input type='password' name='passconfirm' /></td></tr>
	<tr><td></td><td><input type='submit' value='Submit'/> <input type='reset'/> </td></tr>
</table>
 
</form>
</body>
</html>
