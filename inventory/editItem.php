<?php
include "../shared/user_check_right.php";
include "../shared/dbconnect.php";

$sql="SELECT * FROM items WHERE item_id=".$_GET['id'];
//echo $sql."<br/>";
$stmt = $dbh->query($sql);
if($stmt !== FALSE){
   $result = $stmt->fetchAll();
}
/*
echo "<pre>";
print_r($result);
echo "</pre>";
*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script type="text/javascript">
<!--
function checkform ( form )
{
  if (form.itemName.value == "") {
    alert( "Please enter the name of the item." );
    form.itemName.focus();
    return false ;
  }
  if (form.unitCost.value == "") {
    alert( "Please enter the cost of the item." );
    form.unitCost.focus();
    return false ;
  }  
  if (form.unitCost.value < 0) {
	alert( "The number you entered can not be negtive." );  
    form.unitCost.value = "";
    form.unitCost.focus();
    return false ;
  }
  if (form.unitPrice.value == "") {
    alert( "Please enter the price of the item." );
    form.unitPrice.focus();
    return false ;
  }    
  if (form.unitPrice.value < 0) {
    alert( "The number you entered can not be negtive." );
    form.unitPrice.value = "";
    form.unitPrice.focus();
    return false ;
  }
  if (form.supplier.value == "") {
    alert( "Please select the supplier of this item." );
    return false ;
  }
  if (form.iStock.value == "") {
    alert( "Please enter the initial number of the stock." );
    form.iStock.focus();
    return false ;
  }
   if (form.iStock.value < 0) {
    alert( "The number you entered can not be negtive." );
    form.iStock.value = "";
    form.iStock.focus();
    return false ;
  }
  return true ;
}
//-->
</script>
<style type="text/css">
   
</style>
</head>
<body>
<p class='blacktitle'>Edit item detail</p>
<p class='warning'>Waring: use this function with caution, changing some fields of item may have side effect.</p>
<form name="edititem" method="post" action="editItem_act.php" onsubmit='return checkform(this);'>
<table border="0">
    <input type='hidden' name='itemID' id='itemID' value='<?php echo $_GET['id']?>' />
    <tr>
      <td>Item name</td><td><input type="text" name="itemName" class="inputText" value="<?php echo $result[0]['item_name']?>"/></td>
      <td>Item Eng name</td><td><input type="text" name="itemNameEn" class="inputText" 
value="<?php echo $result[0]['item_name_en']?>"/></td>
    </tr>
<?php/*
    <tr>
        <td>Brand</td><td><input type="text" name="brand" class="inputText" value="<?php echo $result[0]['brand']?>"/></td>
        <td>Product code</td><td><input type="text" name="product_code" class="inputText"
value="<?php echo $result[0]['product_code']?>"/></td>
    </tr>
 *
 */
?>
    <tr>
      <td>Supplier</td>
      <td>
        <select name="supplier" class="inputText">
        <?php
        /* connect database */
        include "../shared/dbconnect.php";
        // get all supplier data and put them on the selection list 
        $sql = 'SELECT supplier_id,suppl_name FROM suppliers ORDER BY supplier_id';
        foreach ($dbh->query($sql) as $row) {
	   if($row['supplier_id'] == $result[0]['supplier_id'])
   	      print "<option value=\"".$row['supplier_id']."\" selected>".$row['suppl_name']." (".$row['supplier_id'].")</option>";
	   else
   	      print "<option value=\"".$row['supplier_id']."\">".$row['suppl_name']." (".$row['supplier_id'].")</option>";
        }
        ?>
        </select>
      </td>
      <td>Color</td>
      <td><input type="text" name="color" id="color" class="inputText" value="<?php echo $result[0]['color']?>"/></td>
    </tr>
    <tr>
      <td>Unit cost</td><td><input type="text" name="unitCost" class="inputNum" value="<?php echo $result[0]['unit_cost']?>"/></td>
      <td>Unit price</td><td><input type="text" name="unitPrice" class="inputNum" value="<?php echo $result[0]['unit_price']?>" readonly='readonly'/></td>
    </tr>
    <tr>
        <td>Initial stock</td><td><input type="text" name="iStock" class="inputNum" value="<?php echo $result[0]['stock']?>" readonly='readonly'/></td>
        <td>Barcode</td><td><input type="text" name="iBarcode" class="inputText" value="<?php echo $result[0]['barcode']?>"/></td>
    </tr>
    <tr>
        <td>Active</td>
        <td>
	    <input type='radio' id='active' name='active' value='true' <?php if($result[0]['active'] == true) echo 'checked';?> />
	    Yes
	    <input type='radio' id='active' name='active' value='false' <?php if($result[0]['active'] == false) echo 'checked';?> />
	    No
        </td>
    </tr>
</table>
<input type="submit" value="Update">
<!--<input type="reset">-->
</form>
<!--
<p class='msg'>system will change brand name into UPPERCASE character automatically</p>
-->
<hr>
<?php
//get the pack information for this item first
/*
?>

<h4>Packing for this item:</h4>
<table>
    <tr>
        <td>Barcode Tag</td>
        <td>
            <select name="barcodeTag" id="barcodeTag">
                <option value="">- - -</option>
                <?php
                    $sql_bctag = "SELECT pack_id,pack_name,pack_name_en ".
                                 "FROM packs WHERE pack_category='barcodeTag'";
                    $stmt_bctag = $dbh->query($sql_bctag);
                    foreach ($stmt_bctag as $row) {
                        echo "<option value='".$row['pack_id']."'>".$row['pack_name']."</option>";
                    }


                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>Pack</td>
        <td>
            <select name="pack" id="pack">
                <option value="">- - -</option>
                <?php
                    $sql_pack = "SELECT pack_id,pack_name,pack_name_en ".
                                 "FROM packs WHERE pack_category='pack'";
                    $stmt_pack = $dbh->query($sql_pack);
                    foreach ($stmt_pack as $row) {
                        echo "<option value='".$row['pack_id']."'>".$row['pack_name']."</option>";
                    }


                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>Paper Tag</td>
        <td>
            <select name="paperTag" id="paperTag">
                <option value="">- - -</option>
                <?php
                    $sql_pptag = "SELECT pack_id,pack_name,pack_name_en ".
                                 "FROM packs WHERE pack_category='paperTag'";
                    $stmt_pptag = $dbh->query($sql_pptag);
                    foreach ($stmt_pptag as $row) {
                        echo "<option value='".$row['pack_id']."'>".$row['pack_name']."</option>";
                    }


                ?>
            </select>
        </td>
    </tr>
</table>

<?php
 *
 */
?>
</body>
</html>
