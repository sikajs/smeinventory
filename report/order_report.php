<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../shared/dbconnect.php';

$sql = "select order_id from orders order by order_id";
$stmt = $dbh->query($sql);
if($stmt !== FALSE){
    //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql_update = "update orders set order_cost=
                   (select sum(oi.curr_cost*oi.qty)
                   from order_items as oi
                   where order_id=?
                   group by oi.order_id)
                   where order_id=?";
    $sth = $dbh->prepare($sql_update);
    foreach($stmt as $row){
       echo  $row['order_id'].",";
        $sth->execute(array($row['order_id'],$row['order_id']));
    }
    /*
    echo "<pre>";
    print_r($result);
    echo "</pre>";
     *
     */
}
?>
