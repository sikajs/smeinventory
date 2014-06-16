<?php
/* 
 * tried to find the unmatched item in the order
 * meaning : the total amount of money we received is not matched with the sum of item price * qty
 */

include_once '../shared/dbconnect.php';

$sql = "(select order_id,(cash_received-change+discount) as total
from orders
union
(select oi.order_id,sum(oi.price*oi.qty) as total
from order_items as oi
group by order_id))
order by order_id";

$unmatch_array = array();

$stmt = $dbh->query($sql);
if($stmt !== FALSE){
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    for($i=0; $i<count($result);$i++){
        //echo $i.",";
        if($result[$i]['order_id'] == $result[$i+1]['order_id']){
            array_push($unmatch_array, $result[$i]);
        }
    }
    
    echo "<pre>";
    print_r($unmatch_array);
    echo "</pre>";
     
}

?>
