<?php
include "../shared/dbconnect.php";

$sql = "select count(*) from items where item_name = (select item_name from items where item_id=?)";
$same = $dbh->prepare($sql);

$target = $dbh->query("select item_id from items order by item_id");
foreach($target as $row){
	$same->execute(array($row['item_id']));
	$result = $same->fetchColumn();
	if($result > 1){
	    echo $row['item_id'].",";
	    print_r($result);
	    echo "<br/>";


	}
}


?>
