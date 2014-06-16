<?php
include_once "../shared/user_check_right.php";
unset($_SESSION['currpage']);   //for pagemanip
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<link type="text/css" href="../css/ui-lightness/jquery-ui-1.8.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.custom.min.js"></script>
</head>
<body>
<div id="itemSearch">
<b>Search the item(s) which you want to check :</b>
<form name="searchForm" method="post" action="profitCheck_act.php">
<table border="0">
    <tr>
      <td>Item name</td><td><input type="text" name="itemName" class="inputText" /></td>
      <!--<td>Brand</td><td><input type="text" name="brand" class="inputText" /></td>-->
    </tr>
    <tr>
      <td>Supplier</td>
      <td>
        <select name="supplier" class="inputText">
        <?php
        /* connect database */
        include "../shared/dbconnect.php";
        // get all supplier data and put them on the selection list 
        $sql = 'SELECT supplier_id,suppl_name FROM suppliers ORDER BY suppl_name';
        print "<option value=''>---</option>";
        foreach ($dbh->query($sql) as $row) {
           print "<option value=\"".$row['supplier_id']."\">".$row['suppl_name']."</option>";
        }
        ?>
        </select>
      </td>
      <td>Color</td>
      <td><input type="text" name="color" class="inputText" /></td>
    </tr>
    <tr>
        <td>Item ID</td><td><input type="text" name="id" class="inputText" /></td>
        <td>Barcode</td>
        <td><input type="text" id='iBarcode' name="iBarcode" class="inputText" /></td>
    </tr>
</table>
<input type="submit" value="Search">
<input type="reset">
</form>
</div>

</body>
</html>
