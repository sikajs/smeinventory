<?php
include "../shared/user_check_right.php";
include_once "../shared/dbconnect.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script type='text/javascript'>
</script>
</head>
<body>
<p class='blacktitle'>Adjust Inventory</p>
<?php
//start transaction
$dbh->beginTransaction();

//update stock number in table 'items'
$new_stock = ($_POST['pre_stock']+$_POST['qty']);
$sql = "UPDATE items SET stock=".$new_stock." ".
		 "WHERE item_id=".$_POST['id'];
//echo $sql."<br/>";
$count = $dbh->exec($sql);
if($count == 0){
   $dbh->rollback();
   print_r($dbh->errorInfo());
}else
   print "<p>Adjusting inventory operation done.</p>";

//record history
unset($sql);
$sql = "INSERT into adjust_history(uid,item_id,previous_stock,new_stock,reason) ".
		 "VALUES(".$_SESSION['user_info']['uid'].",".$_POST['id'].",".$_POST['pre_stock'].
		 ",".$new_stock.",'".$_POST['reason']."') ";
//echo $sql."<br/>";
unset($count);
$count = $dbh->exec($sql);
if($count == 0){
   $dbh->rollback();
   print_r($dbh->errorInfo());
}else
   print "Adjusting inventory operation recorded.<br/>";

//commit
$dbh->commit();
?>
</body>
</html>
