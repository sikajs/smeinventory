<?php
include "../shared/user_check_right.php";
include_once "../shared/dbconnect.php";

/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
*/

$sql = "UPDATE items SET item_name='".$_POST['itemName']."', item_name_en='".$_POST['itemNameEn'].
       "', brand='".$_POST['brand']."', product_code='".$_POST['product_code'].
       "', supplier_id='".$_POST['supplier']."', color='".$_POST['color'].
       "', unit_cost='".$_POST['unitCost']."', barcode='".$_POST['iBarcode'].
       "', active='".$_POST['active']."', last_detail_update=now() ".
       "WHERE item_id='".$_POST['itemID']."'"; 

//echo $sql."<br/>";
$affected = $dbh->exec($sql);
if($affected == 1)
   echo "Item detail updated.";
else{
   echo "Something wrong.";
   print_r($dbh->errorInfo());
}
?>
