<?php
include_once "../shared/dbconnect.php";

$sql = "SELECT * FROM orders ".
       "WHERE update_time>='".$_POST['target_date'].
       "' AND update_time<'".$_POST['target_date']."'";
$stmt = $dbh->query($sql);
if($stmt !== FALSE){
   echo json_encode($stmt->fetchAll());
}else
   echo json_encode('failed');
