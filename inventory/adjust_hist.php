<?php
//If user didn't enter any condition, function will show all of the history
 
include "../shared/user_check_right.php";

include "../shared/dbconnect.php";	//database connection
unset($_SESSION['lastsql']); 
unset($_SESSION['currpage']);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<body>
<p class='blacktitle'>Adjust Inventory History</p>
<div id='adjust_hist_search'>
<b>Search adjust inventory history by item name: </b>
<form method='post' action='adjust_hist_act.php'>
<table>
<tr>
<td>Item Name</td><td><input type='text' name='iName' size='40' /></td>
<td>Product Code</td><td><input type='text' name='prod_code' size='20' /></td>
<td><input type='submit' name="submit" value="Search" type="submit" /></td>
</tr>
</table>
</form>
<p class='msg'>Hint: Leave field empty if you want to see all history</p>
</div>

</body>
</html>