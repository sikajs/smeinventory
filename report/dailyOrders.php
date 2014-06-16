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
<style type="text/css" title="currentStyle">
        @import "../css/ui-lightness/jquery-ui-1.8.17.custom.css";
        @import "../shared/smeInventory.css";
</style>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" >
$(document).ready(function() {
    $('#targetDate').datepicker({ dateFormat: 'yy-mm-dd' , firstDay: 1});
} );
</script>
</head>
<body >
<p class='blacktitle'>Daily orders</p>
<form id='dailyOrders' name='dailyOrders' method='post' action='dailyOrders.php'>
Target Date : <input type='text' name='targetDate' id='targetDate' value='<?php echo $today?>'/>

<input type='submit' value='search' />
</form>
<?php
$nextDate = date('Y-m-d',strtotime($_POST['targetDate']." +1 day"));
$sql = "SELECT * ".
       "FROM orders ".
       "WHERE update_time>='".$_POST['targetDate']."' AND update_time<'".$nextDate."' ".
       "ORDER BY order_id ";
//echo $sql."<br/>";
$stmt=$dbh->query($sql);
$totalRevenue = 0;
$totalCost = 0;
$counter = 1;
if($_POST['targetDate'] != null){
   echo "Current target date: <b>".$_POST['targetDate']."</b>";
}
echo "<table class='report_table' id='dailyOrderTable'>";
echo "<thead>";
echo "<tr bgcolor='orange' class='titlee'><th>Order id</th><th>Cash received</th><th>Change</th><th>Discount</th><th>Actual retail</th></tr>";
echo "</thead>";
echo "<tbody>";
foreach($stmt as $row){
   if($counter % 2 == 0)
      echo "<tr bgcolor='lightblue' align='center'>";
   else
      echo "<tr align='center'>";
   echo "<td><a href='../order/orderdetail.php?target=".$row['order_id']."'>".$row['order_id']."</a></td>";
   echo "<td>".$row['cash_received']."</td>";
   echo "<td>".$row['change']."</td>";
   echo "<td>".$row['discount']."</td>";
   $retail = $row['cash_received']-$row['change'];
   echo "<td>".$retail."</td>";
   echo "</tr>";
   $totalRevenue += $retail;
   $counter++;
}
$sql_return = "SELECT * FROM cro ".
	      "WHERE return_time>='".$_POST['targetDate']."' AND return_time<'".$nextDate."' ";
$stmt_return=$dbh->query($sql_return);
foreach($stmt_return as $row){
   if($counter % 2 == 0)
      echo "<tr bgcolor='lightblue' align='center'>";
   else
      echo "<tr align='center'>";
   echo "<td>CRO ".$row['cro_id']."</td>";
   echo "<td></td><td></td><td></td>";
   echo "<td>-".$row['return_total']."</td>";
   echo "</tr>";
   $totalRevenue -= $row['return_total'];
   $counter++;
}
echo "<tr bgcolor='lightgray' class='blacktitle'><td colspan='3'></td><td>Total Revenue</td><td>".$totalRevenue."</td></tr>";
echo "</tbody>";
echo "</table>";

?>
</body>
</html>
