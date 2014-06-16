<?php
include "../shared/user_check_right.php";
echo "<pre>";
//print_r($_SESSION);
//echo "<hr>";
print_r($_POST);
echo "</pre>";

/* connect database */
include_once "../shared/dbconnect.php";

//start an order record transaction to insert the detail of the order into database
$dbh->beginTransaction();

//insert data into cro and get the cro_id
$sql = "INSERT INTO cro (customer_id,return_total,orig_discount) ".
       "values(".$_POST['Customer_Number'].",".$_POST['unTaxTotal'].",".$_POST['orig_discount'].")";
//echo $sql."<br/>";
$count = $dbh->exec($sql);
if($count == 1){
    $sql = "SELECT cro_id FROM cro where customer_id='".$_POST['Customer_Number']."' AND return_time=transaction_timestamp()";
    echo $sql."<br/>";
    $res = $dbh->query($sql);
    if($res != FALSE){
        $CROID = $res->fetchColumn();
    }
}else{
    $dbh->rollback();
    echo "Failed to get cro id";
    exit(1);
}
//insert the detail of the cro (which item in the cro) into cro_items
$sql = "INSERT INTO cro_items (cro_id,item_id,qty,price,barcode) values(".$CROID.",?,?,?,?)";
//echo $sql;
$itemInsert = $dbh->prepare($sql);
$numInCart = count($_POST['ProdBC'])-1;
for($i=0; $i < $numInCart; $i++){
    if(!$itemInsert->execute(array($_POST['ProdID'][$i],$_POST['orderQTY'][$i],$_POST['ProdPrice'][$i],$_POST['ProdBC'][$i]))){
        $dbh->rollback();
        echo "item ".$i." of the order insertion failed.<br/>";
        print_r($itemInsert->errorInfo());
        exit(1);
        //break;
    }
}
//echo "order inserted.<br/>";

//updating stock of the items in the cro
$sql_stock = "UPDATE items SET stock=? WHERE item_id=?";
$stock_update = $dbh->prepare($sql_stock);
for($i=0; $i < $numInCart; $i++){
    $sql = "SELECT stock FROM items WHERE item_id=".$_POST['ProdID'][$i];
    //echo $sql.",";
    $stmtCurr = $dbh->query($sql);
    $currStock = $stmtCurr->fetchColumn();
    $newStock = $currStock + $_POST['orderQTY'][$i];
    //echo $newStock."<br/>";
    if(!$stock_update->execute(array($newStock,$_POST['ProdID'][$i]))){
        $dbh->rollback();
        echo "stock of item ".$i." in the cro update failed.<br/>";
        print_r($itemInsert->errorInfo());
        exit(1);
        //break;
    }
}

//commit and finished transaction
$dbh->commit();
$where = "Location: cro.php";
header($where);
?>
