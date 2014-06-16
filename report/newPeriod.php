<?php
include_once "../shared/user_check_right.php";
$_SESSION['lastPage'] = $_SERVER['REQUEST_URI'];
//include_once('../shared/dbconnect.php');
//$today = date('Y-m-d');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daily order</title>
<link href="../shared/smeInventory.css" rel="stylesheet" type="text/css" />
<link href="../css/ui-lightness/jquery-ui-1.8.17.custom.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.avg {
	background-color: gold;
}
td {
	width: 85px;
	text-align: justify;
}
em {
	font-style: normal;
	font-weight: bold;
}
</style>
<script type="text/javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="../js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" charset="UTF-8" >
$(document).ready(function(){
   $('#startDate').datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1 });
   $('#endDate').datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1 });
   $('#thisMthButton').click(function(){
	   var now = new Date();
	   var start = new String(now.getFullYear());
	   start = start + "-" + (now.getMonth()+1);
	   start = start + "-01";
          
           var lastDay = new Date(now.getFullYear(),now.getMonth()+1,0);
           var end = lastDay.getFullYear() + "-" + (lastDay.getMonth()+1) + "-" + lastDay.getDate();
           if(now.toDateString() == lastDay.toDateString()){
               if(now.getMonth() == 11){
                   end = lastDay.getFullYear()+1 + "-01-01";
               }else{
                   end = lastDay.getFullYear() + "-" + (lastDay.getMonth()+2) + "-01";
               }
           }
               
	   $('#startDate').val(start);
	   $('#endDate').val(end);
	   $('#periodOrders').submit();
	});
});
</script>
</head>
<body >
<p class='blacktitle'>Orders by period</p>
<form id='periodOrders' name='periodOrders' method='post' action='newPeriod.php'>
Start Date : <input type='text' name='startDate' id='startDate' />
End Date : <input type='text' name='endDate' id='endDate' />
<input type='button' id='thisMthButton' class='lazyButton' value='This Month' title='Check orders until today' />
<input type='submit' value='search' />
</form>    
<?php
include_once '../model/orders.php';
include_once '../model/cro.php';

$order = new orders();
$cro = new cro();



try {
    $start = new DateTime($_POST['startDate']);
} catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
}
try {
    $end = new DateTime($_POST['endDate']);
} catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
}
    echo $start->format('Y-m-d')." ~ ";
    echo $end->format('Y-m-d')."<br/>";
?>
<table border='1' class='report_table'>
<tr bgcolor='orange' class='titlee'>
<th>Mon</th>
<th>Tue</th>
<th>Wed</th>
<th>Thu</th>
<th>Fri</th>
<th>Sat</th>
<th>Sun</th>
<th>Average</th>
</tr>
<?php
    $walker = 1;
    echo "<tr>";
    //decide the start point on calendar table
    $interval = new DateInterval("P1D");
    $day = new DateTime($_POST['startDate']);
    
    // setup the starting point in one week
    switch($start->format('D')){
        case 'Tue':
            $walker = 2;
            break;
        case 'Wed':
            $walker = 3;
            break;
        case 'Thu':
            $walker = 4;
            break;
        case 'Fri':
            $walker = 5;
            break;
        case 'Sat':
            $walker = 6;
            break;
        case 'Sun':
            $walker = 7;
            break;
    }
    //move to starting point
    for($k=1;$k<$walker;$k++){
        echo "<td></td>";
    }
    
    $revWeek = 0.0;
    $croWeek = 0.0;
    $gpWeek = 0.0;
    $discountWeek = 0.0;
    $orderWeek = 0;
    $totalRetail = 0.0;
    $totalProfit = 0.0;
    $totalDiscount = 0.0;
    //query db and show result
    while($day <= $end){
        if($walker == 8){   //show weekly average
            echo "<td class='avg'>";
            printf("AVG: %.2f<br/>",$revWeek/($walker-1));
            echo "R: ".$revWeek."<br/>";
            echo "GP: ".$gpWeek."<br/>";
            echo "CRO: ".$croWeek."<br/>";
            echo "D: ".$discountWeek."<br/>";
            echo "O: ".$orderWeek."<br/>";
            echo "</td>";
            echo "</tr>";
            echo "<tr>";
            
            //reset weekly number
            $revWeek = 0.0;
            $croWeek = 0.0;
            $gpWeek = 0.0;
            $discountWeek = 0.0;
            $orderWeek = 0;
            $walker = 1;
            continue;
        }
        echo "<td><em>".$day->format('m-d')."</em><br/>";
        //process daily cro
        $croArray = $cro->getOneDayCro($day->format('Y-m-d'));
        $croDay = 0.0;
        foreach($croArray as $row){
            $croDay += $row['return_total'];
        }
        //process daily orders
        $orderArray = $order->getOneDayOrder($day->format('Y-m-d'));
        $numOrder = count($orderArray);
        $revDay = 0.0;
        $discountDay = 0.0;
        $costDay = 0.0;
        foreach($orderArray as $row){
            $revDay += ($row['cash_received']-$row['change']);
            $discountDay += $row['discount'];
            $costDay += $row['order_cost'];
        }
        //update weekly number
        $revWeek += ($revDay-$croDay);
        $croWeek += $croDay;
        $gpWeek += ($revDay-$croDay-$costDay);
        $discountWeek += $discountDay;
        $orderWeek += $numOrder;
        //update total number
        $totalRetail += ($revDay-$croDay);
        $totalProfit += ($revDay-$croDay-$costDay);
        $totalDiscount += $discountDay;
        //print daily result
        echo "R: ".($revDay-$croDay)."<br/>";
        	//echo "C: ".$costDay."<br/>";
        echo "GP: ".($revDay-$croDay-$costDay)."<br/>";
        echo "CRO: ".$croDay."<br/>";
        echo "D: ".$discountDay."<br/>";
        echo "O: ".$numOrder."<br/>";
        echo "</td>";
        $day->add($interval);
        $walker++;
    }
    echo "</tr>";
    echo "<tr bgcolor='lightgray'>";
    $gpRate = $totalProfit/$totalRetail;
    echo "<td colspan=4 >Total</td><td>R: ".$totalRetail."</td><td>GP: ".$totalProfit.
             "</td><td>GPR:";
    
    printf("%.2f%%",$gpRate*100);
    echo "</td><td>D: ".$totalDiscount."</td>";
    echo "</tr>";
?>
</table>
</body>
</html>