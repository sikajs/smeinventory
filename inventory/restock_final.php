<?php
include "../shared/user_check_right.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<p class='blacktitle'>Restock</p>
<?php
/* connect database */
include "../shared/dbconnect.php";

//todo
//check current stock again first before really record into db, concurrent issue!!

//record restock operation into history
/*
 * The newCost here is the latest price we bought from suppliers
 */
$sql = "INSERT INTO restock_history (uid,item_id,previous_stock,new_arrival,previous_cost,new_cost,supplier_id) ".
		 "VALUES(".$_SESSION['user_info']['uid'].",".$_POST['itemID'].",".$_POST['currStock'].",".
		 $_POST['newStock'].",".$_POST['pre_cost'].",".$_POST['newCost'].",".$_POST['supplier_id'].") ";
//print $sql."<br/>";
$count = $dbh->exec($sql);
if($count == 0)
   die($dbh->errorInfo());
else
	print "Restock operation recorded.<br/>";
/*
echo "<pre>";
print_r($_POST);
echo "</pre>";
 * 
 */
//caculate the final cost and force to round up to 2 digits after the point
$finalCost = ($_POST['pre_cost'] * $_POST['currStock']+$_POST['newCost'] * $_POST['newStock'])/($_POST['currStock']+$_POST['newStock']);
$finalCostRounded = round($finalCost,2);
//echo $finalCost." O<br/>";
//echo $finalCostRounded." R<br/>";
if($finalCostRounded < $finalCost){
    $finalCostRounded += 0.01;
}
//echo $finalCostRounded." F<br/>";

//update item record
$sql_update = "UPDATE items SET stock=".($_POST['currStock']+$_POST['newStock']).",unit_cost=".$finalCostRounded.
		 ",last_restock_date=now() ".
		 "WHERE item_id=".$_POST['itemID'];
//echo $sql_update;
$count = $dbh->exec($sql_update);
if($count == 0){
   die($dbh->errorInfo());
} else {
	print "Stock updated. <br/>You can <a href='restock.php'>Restock next item</a> or choose other function.";
}

//close db connection
$dbh = null;
?>
</html>
