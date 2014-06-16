<?php
/*
 * used to generate entire monthly report for some ensential numbers 
 * which we want to monitor
 */
include_once "../shared/user_check_right.php";
include_once('../shared/dbconnect.php');

//include_once ('../unused/debug_msg.php');

//$a = microtime();
//echo $a;

//cleanup report table, back to use commented sql if want to run for all of the result, current set got better performance
//$sqlClean = "truncate table mth_report ";
//$start = new DateTime('2010-09-01');
$today = new DateTime(date('Y-m-d'));
$start = new DateTime(date('Y-m-d',strtotime($today->format("Y-m")." -1 month")));

$sqlClean = "DELETE FROM mth_report WHERE year=".$start->format('Y')." AND month>=".$start->format("m")." OR year=".$today->format('Y')." AND month=".$today->format('m');
/*
echo $today->format('Y')."/".$today->format('m');
echo "<br/>";
echo $sqlClean;
 */

$stmt = $dbh->query($sqlClean);
//$stmt = true;

if($stmt !== FALSE){
    //setup sql statement for each number we want to generate
    $sqlRev = "select sum(o.cash_received - o.change) 
               from orders as o
               where o.update_time >= ? AND o.update_time < ? ";
    $sqlCost = "select sum(o.order_cost) 
               from orders as o
               where o.update_time >= ? AND o.update_time < ? ";
    $sqlNumOrder = "select count(*) from orders as o where o.update_time >= ?
                    AND o.update_time < ?";
    $sqlCRO = "select sum(return_total) from cro 
               where cro.return_time >= ? AND cro.return_time < ?";

    $sqlInsert = "insert into mth_report (year,month,num_of_order,revenue,gp,avg_revenue,total_cro) 
                  values(?,?,?,?,?,?,?)";

    //prepare all sql statement
    $rev = $dbh->prepare($sqlRev);
    $cost = $dbh->prepare($sqlCost);
    $numOrder = $dbh->prepare($sqlNumOrder);
    $cro = $dbh->prepare($sqlCRO);
    $ins = $dbh->prepare($sqlInsert);
    
    //setup date interval
    $interval = new DateInterval("P1M"); // 1 month
    //force DatePeriod to react on the first day of every month
    if($today->format("d") != "01"){
        $end = $today;
    } else {
        $end = new DateTime(date('Y-m-d',strtotime($today->format("Y-m-d")." +1 day")));
    }

    //echo $start->format("Y-m-d")." / ";
    //echo $end->format("Y-m-d")."<br/>";
    
    $period = new DatePeriod($start,$interval,$end);
    foreach($period as $dt){
        //echo $dt->format("Y-m-d")." ~ ";
        $next = date('Y-m-d',strtotime($dt->format("Y-m-d")." +1 month"));
        //echo $next."<br/> ";
    
        $rev->execute(array($dt->format("Y-m-d"),$next));
        $resultRev = $rev->fetchColumn();
        
        $cost->execute(array($dt->format("Y-m-d"),$next));
        $resultCost = $cost->fetchColumn();
        
        $numOrder->execute(array($dt->format("Y-m-d"),$next));
        $result_num = $numOrder->fetchColumn();
    
        $cro->execute(array($dt->format("Y-m-d"),$next));
        $result_cro = $cro->fetchColumn();
  
        $realRev = $resultRev-$result_cro;
        $gp = $resultRev-$resultCost-$result_cro;
        //echo $result_num." | Rev:".$realRev." | Cost:".$resultCost." | AVG:".($realRev/$result_num)."<br/>";

        //really insert into database
        $ins->execute(array($dt->format("Y"),$dt->format("m"),$result_num,$realRev,$gp,($realRev/$result_num),$result_cro));
        //echo "<br/>";
    }
    $where = "showMth.php";
    header("Location: ".$where);
}else
    echo "cleanup report table failed.";
        

 
//echo "<br/>".(microtime()-$a);

?>
