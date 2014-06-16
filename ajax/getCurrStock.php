<?php
/*
 * 
 * 
 */
include_once '../shared/dbconnect.php';
//$_GET['code']="140001535";
$sql = "SELECT stock FROM items WHERE barcode='".$_GET['code']."' ";
//echo $sql."<br/>";

$stmt = $dbh->query($sql);
if($stmt !== FALSE){
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($result != null)
        echo json_encode($result[0]);
    else
        echo json_encode("no result");
}
?>
