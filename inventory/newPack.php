<?php
include "../shared/user_check_right.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript">
<!--
$(document).ready(function(){
   $('#newPack').validate();
});

//-->
</script>
<style type="text/css">
   
</style>
</head>
<body>
<p class='blacktitle'>Add new pack</p>

<form id='newPack' name="newPack" method="post" action="newPack_act.php" onsubmit='return checkform(this);'>
<table border="0">
    <tr>
      <td>Pack name</td><td><input type="text" name="packName" class="inputText required" /></td>
      <td>Pack Eng name</td><td><input type="text" name="packNameEn" class="inputText" /></td>
    </tr>
    <tr>
      <td>Pack category</td>
      <td>
        <select id='pack_category' name="pack_category" class="inputText">
	<option value='carryBag'>Carry bag</option>
	<option value='pack'>Pack</option>
	<option value='paperTag'>Paper tag</option>
	<option value='barcodeTag'>Barcode tag</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Unit cost</td><td><input type="text" name="unitCost" class="inputNum number" /></td>
      <td>Initial stock</td><td><input type="text" name="pStock" class="inputNum number" /></td>
    </tr>
</table>
<input type="submit" value="Submit">
<input type="reset">
</form>
<p class='msg'>
* Although unit from supplier is weight, we will estimate the stock number we have.
</p>
</body>
</html>
