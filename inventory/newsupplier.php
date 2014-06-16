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
  if (form.supplName.value == "") {
    alert( "Please enter the name of the supplier." );
    form.supplName.focus();
    return false ;
  }
  if (form.busiNum.value == "") {
    alert( "Please enter the business number of the supplier." );
    form.busiNum.focus();
    return false ;
  }
  return true ;
}
//-->
</script>

</head>
<body>
<p class='blacktitle'>Input new supplier</p>

<form method="post" action="newsupplier_act.php" onsubmit="return checkform(this);">
<table border="0">
    <tr>
      <td>Supplier name</td><td><input type="text" name="supplName" size="30" /></td>
      <td>Business number</td><td><input type="text" name="busiNum" size="30" /></td>
    </tr>
    <tr>
      <td>Telephone</td><td><input type="text" name="tel" size="10" /></td>
      <td>Fax</td><td><input type="text" name="fax" size="10" /></td>
    </tr>
    <tr>
      <td>Mobile</td><td><input type="text" name="mobile" size="10" /></td>
      <td>Email</td><td><input type="text" name="email" size="30" /></td>
    </tr>
	<tr><td>Address</td><td><input type="text" name="address" size="60" /></td></tr>
</table>
<input type="submit" value="Submit">
<input type="reset">
</form>
</body>
</html>
