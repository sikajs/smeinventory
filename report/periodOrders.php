<?php
include_once "../shared/user_check_right.php";
$_SESSION['lastPage'] = $_SERVER['REQUEST_URI'];
include_once('../shared/dbconnect.php');
$today = date('Y-m-d');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daily order</title>
<link href="../shared/smeInventory.css" rel="stylesheet" type="text/css" />
<link href="../css/ui-lightness/jquery-ui-1.8.17.custom.css" rel="stylesheet" type="text/css" />
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
<form id='periodOrders' name='periodOrders' method='post' action='periodOrders.php'>
Start Date : <input type='text' name='startDate' id='startDate' />
End Date(not included) : <input type='text' name='endDate' id='endDate' />
<input type='button' id='thisMthButton' class='lazyButton' value='This Month' title='Check orders until today' />
<input type='submit' value='search' />
</form>
<?php
if($_POST['endDate'] == null){
   $_POST['endDate'] = $today;
}
//get the data of orders, all of the orders from startDate to the day before endDate
$sql = "SELECT * ".
       "FROM orders ".
       "WHERE update_time>='".$_POST['startDate']."' AND update_time<'".$_POST['endDate']."' ".
       " ORDER BY update_time ";
echo $sql."<br/>";
$stmt=$dbh->query($sql);
if($stmt !== FALSE)
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//get the data of customer return order, all of the return orders from startDate to the day before endDate
$sql_return = "SELECT * FROM cro ".
	      "WHERE return_time>='".$_POST['startDate']."' AND return_time<'".$_POST['endDate']."' ";
$stmt_return=$dbh->query($sql_return);
if($stmt_return !== FALSE)
    $result_return = $stmt_return->fetchAll(PDO::FETCH_ASSOC);

$currDate = $_POST['startDate'];
$nextDate = date('Y-m-d',strtotime($currDate." +1 day"));
$totalRows = count($result); 

echo "{{".$totalRows."}}<br/>";

$oneDayRetail = 0;
$oneDayCost = 0;
$oneDayDiscount = 0;
$oneDayOrder = 0;

if($_POST['startDate'] != null){
    echo "Current target period: <b>".$_POST['startDate']." ~ ".date('Y-m-d',strtotime($_POST['endDate']." -1 day"))."</b><br/>";
    
    $final = array();
    $totalRetail = 0;   //for calculate the total retail amount
    $totalProfit = 0;
    $totalDiscount = 0; //for calculate the total discount amount

    // calculate retail, cost, discount day by day
    for($i=0; $i<$totalRows; $i++){
        echo "{".$i."}<br/>";
        if($currDate == substr($result[$i]['update_time'],0,10)){
            echo substr($result[$i]['update_time'],0,10);
            $oneDayRetail += ($result[$i]['cash_received']-$result[$i]['change']);
            $oneDayCost += ($result[$i]['order_cost']);
            $oneDayDiscount += $result[$i]['discount'];
            $oneDayOrder++;
            $totalRetail += ($result[$i]['cash_received']-$result[$i]['change']);
            $totalProfit += ($result[$i]['cash_received']-$result[$i]['change']-$result[$i]['order_cost']);
            $totalDiscount += $result[$i]['discount'];
        }else{
            array_push($final, array('date'=>$currDate,'retail'=>$oneDayRetail,'cost'=>$oneDayCost,'discount'=>$oneDayDiscount,'orderNum'=>$oneDayOrder));
            $currDate = $nextDate;
            $nextDate = date('Y-m-d',strtotime($currDate." +1 day"));
            $i--;	//in order to re-scan the first order of the next day once
            $oneDayRetail = 0;
            $oneDayCost = 0;
            $oneDayDiscount = 0;
            $oneDayOrder = 0;
        }
    }
    array_push($final, array('date'=>$currDate,'retail'=>$oneDayRetail,'cost'=>$oneDayCost,'discount'=>$oneDayDiscount,'orderNum'=>$oneDayOrder));

    echo "<pre>";
print_r($final);
echo "</pre>";
   
exit();
    //deduct the return order from the retail
    $finalRows = count($final);
    for($i=0;$i<count($result_return);$i++){
        for($j=0;$j<$finalRows;$j++){
            if($final[$j]['date'] == substr($result_return[$i]['return_time'],0,10)){
                //echo "found in ".$j."<br/>";
                $final[$j]['retail'] -= $result_return[$i]['return_total'];
                $totalRetail -= $result_return[$i]['return_total'];
                $totalProfit -= $result_return[$i]['return_total'];
                break;
            }
        }
    }

    echo $finalRows;
    

    //print out the final result
    $weeks = ($finalRows / 7 + 1);
    $workday = 7;

    echo "<table border='1' class='report_table'>";
    //echo "<tr bgcolor='orange' class='titlee'><th>Date</th><th>Daily retail</th><th>Daily discount</th></tr>";
    echo "<tr bgcolor='orange' class='titlee'>";
    echo "<th>Mon</th>";
    echo "<th>Tue</th>";
    echo "<th>Wed</th>";
    echo "<th>Thu</th>";
    echo "<th>Fri</th>";
    echo "<th>Sat</th>";
    echo "<th>Sun</th>";
    echo "<th>Average</th>";
    echo "</tr>";

    $walker = 1;
    echo "<tr align='center'>";
    //decide the start point on calendar table
    try {
        $date = new DateTime($final[0]['date']);
    } catch (Exception $e) {
        echo $e->getMessage();
        exit(1);
    }
    // setup the starting point in one week
    switch($date->format('D')){
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
    for($k=1;$k<$walker;$k++){
        echo "<td></td>";
    }
    $weekTotal = 0.0;
    $numOfDays = 0;
    for($i=0 ; $i<$finalRows; $i++){
        try {
            $date = new DateTime($final[$i]['date']);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit(1);
        }
        echo "<td><b>".$date->format('m/d')."</b><br/>";
        echo "R:".$final[$i]['retail']."<br/>";
        echo "GP:".($final[$i]['retail']-$final[$i]['cost'])."<br/>";
        echo " D:".$final[$i]['discount']."<br/>";
        echo " O:".$final[$i]['orderNum']."</td>";
        $weekTotal += $final[$i]['retail'];
        $numOfDays++;
        if( $walker%$workday == 0){
            $format = "<td>%.2f</td></tr>";
            if($numOfDays != 0)
                printf ($format,$weekTotal/$numOfDays) ;
            else
                echo "Error occured";
            echo "<tr align='center'>";
            $weekTotal = 0;
            $numOfDays = 0;
        }
        $walker++;
    }
    echo "</tr>";
    echo "<tr align='center' bgcolor='lightgray'>";
    $gpRate = $totalProfit/$totalRetail;
    echo "<td colspan=4 >Total</td><td>R: ".$totalRetail."</td><td>GP: ".$totalProfit.
         "</td><td>GP rate:";

    printf("%.2f%%",$gpRate*100);
    echo "</td><td>D: ".$totalDiscount."</td>";
    //echo "<td colspan=5 >Total</td><td>R: ".$totalRetail."</td><td>GP: ".$totalProfit."</td><td>D: ".$totalDiscount."</td>";
    //echo "<td colspan=6 >Total</td><td>R: ".$totalRetail."</td> <td>D: ".$totalDiscount."</td>";
    echo "</table>";
}


?>

</body>
</html>
