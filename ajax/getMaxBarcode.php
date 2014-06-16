<?php
include_once '../shared/dbconnect.php';

//this won't include books
$sql = "SELECT MAX(substr(barcode,3,4)) FROM items ".
       "WHERE barcode not like '97%' AND supplier_id='".$_GET['supplier']."' ";
//echo $sql."<br/>";

$stmt = $dbh->query($sql);
if($stmt !== FALSE){
    $result = $stmt->fetchColumn();
    if($result != null)
        echo json_encode($result);
    else
        echo json_encode("no result");
}

?>
