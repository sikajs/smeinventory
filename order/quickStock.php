<?php
include "../shared/user_check_right.php";
?>
<html>
<head><title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css" title="currentStyle">
        @import "../css/demo_table_jui.css";
        @import "../css/ui-lightness/jquery-ui-1.8.17.custom.css";
        @import "../shared/smeInventory.css";
</style>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" >
    $(document).ready(function(){
        $('#iBarcode').focus();
        $('#quickStockTable').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers"
        });
    });
</script>
</head>
<body>
<p class='blacktitle'>Quick Stock Checking</p>
<div id="itemSearch">
<b>Step1 - Search the item which you want to set first :</b>
<form name="searchForm" method="post" action="quickStock.php">
<table border="0">
    <tr>
      <td>Item name</td><td><input type="text" name="itemName" class="inputText" /></td>
    </tr>
    <!--
    <tr>
      <td>Brand</td><td><input type="text" name="brand" class="inputText" /></td>
      <td>Product code</td><td><input type="text" name="prod_code" size="30" /></td>
    </tr>
    -->
    <tr>
      <td>Supplier</td>
      <td>
        <select name="supplier" class="inputText">
        <?php
        /* connect database */
        include "../shared/dbconnect.php";
        // get all supplier data and put them on the selection list
        $sql = 'SELECT supplier_id,suppl_name FROM suppliers ORDER BY supplier_id';
        print "<option value=''>---</option>";
        foreach ($dbh->query($sql) as $row) {
           print "<option value=\"".$row['supplier_id']."\">".$row['suppl_name']." (".$row['supplier_id'].")</option>";
        }
        ?>
        </select>
      </td>
      <td>Item ID</td><td><input type="text" name="id" class="inputText" /></td>
    </tr>
    <tr>
        <td>Color</td>
        <td><input type="text" name="color" class="inputText" /></td>
        <td>Barcode</td>
        <td><input type="text" id='iBarcode' name="iBarcode" class="inputText" /></td>
    </tr>
</table>
<?php
//Base on the conditions user entered to generate sql statement
if($_POST['backToResult'] == 'true'){
	$sql = $_SESSION['lastsql'];
} else {
    unset($sql);
    unset($_SESSION['lastsql']);
    //include "../shared/item_formsearch.php";
    
    if($_POST['supplier'] != null){
        $sql = "SELECT items.* ".
               "FROM items ";
        $sql .= "WHERE items.supplier_id='".$_POST['supplier']."' ";
        if($_POST['brand'] != null){
            $sql .= "AND items.brand='".strtoupper($_POST['brand']).")' ";
            if($_POST['itemName'] != null){
		$sql .= "AND items.item_name like '%".$_POST['itemName']."%' ";
            }
        } elseif($_POST['itemName'] != null){
            $sql .= "AND items.item_name like '%".$_POST['itemName']."%' ";
        }
    } elseif($_POST['brand'] != null) {
        $sql = "SELECT items.* ".
               "FROM items ";
        $sql .= "WHERE items.brand='".strtoupper($_POST['brand'])."' ";
        if($_POST['itemName'] != null)
            $sql .= "AND items.item_name like '%".$_POST['itemName']."%' ";
    } elseif($_POST['itemName'] != null){
        $sql = "SELECT items.* ".
               "FROM items ";
        $sql .= "WHERE items.item_name like '%".$_POST['itemName']."%' ";
    }
    //the following search condition will work on only one targeted condition
    if($_POST['id'] != null){
        $sql = "SELECT items.* ".
               "FROM items ".
               "WHERE items.item_id='".$_POST['id']."' ";
    }
    if($_POST['iBarcode'] != null){
        $sql = "SELECT items.* ".
               "FROM items ".
               "WHERE items.barcode='".$_POST['iBarcode']."' ";
    }
    if($_POST['color'] != null){
        $sql = "SELECT items.* ".
               "FROM items ".
               "WHERE items.color='".$_POST['color']."' ";
    }
    // record the sql and set the variable for back to the search result later
    $_SESSION['lastsql'] = $sql;
}
//echo $sql."<br/>";
//include "../unused/show_session.php";
/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
 * 
 */
?>

<input type="submit" value="Search">
<input type="reset">
</form>
</div>

<div id="searchResult">
<hr/>
<table id='quickStockTable' border='1' class='table display'>
<?php
//echo $sql."<br/>";
$stmt = $dbh->query($sql);
echo "<thead>";
echo "<tr><th>Barcode</th><th>Item Name</th><th>Item Eng Name</th><th>Curr Stock</th></tr>";
echo "</thead>";
echo "<tbody>";
if($stmt !== FALSE){
    foreach($stmt as $row){
        echo "<tr>";
        echo "<td>".$row['barcode']."</td>";
        echo "<td>".$row['item_name']."</td>";
        echo "<td>".$row['item_name_en']."</td>";
        if((int)$row['stock'] == 0)
            echo "<td class='warning'> Sold out </td>";
        else
            echo "<td>".$row['stock']."</td>";
        echo "</tr>";
    }
}
echo "</tbody>";
//close db connection
$dbh = null;
?>
</table>
</div>
</body>
</html>
