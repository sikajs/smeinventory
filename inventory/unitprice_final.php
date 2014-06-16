<?php
include "../shared/user_check_right.php";

/* connect database */
include "../shared/dbconnect.php";

$sql = "UPDATE items SET unit_price='".$_POST['newPrice'].
	   "' WHERE item_id=?";
$targets = explode(",",$_SESSION['affectedItems']);

$updatePrice = $dbh->prepare($sql);
$num = count($targets);
for($i = 0; $i<$num; $i++){
	$endstate = $updatePrice->execute(array($targets[$i]));
	if(!$endstate)
		print "<br/>Something goes wrong when updating the price, please contact system administrator.<br/>";
}
print "update processed";

unset($_SESSION['affectedItems']);
//close db connection
$dbh = null;
?>
