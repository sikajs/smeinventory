<?php
include_once "../shared/user_check_right.php";
$_SESSION['lastPage'] = $_SERVER['REQUEST_URI'];
include_once('../shared/dbconnect.php');
$today = date('Y-m-d');
//include_once('../unused/debug_msg.php')
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Item sold by period</title>
<style type="text/css" title="currentStyle">
        @import "../css/demo_table_jui.css";
        @import "../css/ui-lightness/jquery-ui-1.8.17.custom.css";
        @import "../shared/smeInventory.css";
</style>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" >
$(document).ready(function() {
    $('#startDate').datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1 });
    $('#endDate').datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1 });
    $('#itemSoldTable').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });
    $('#thisMthButton').click(function(){
 	   var now = new Date();
 	   var start = new String(now.getFullYear());
 	   start = start + "-" + (now.getMonth()+1);
 	   end = start;
 	   start = start + "-01";
 	   end = end + "-" + (now.getDate()+1);
 	   $('#startDate').val(start);
 	   $('#endDate').val(end);
 	   $('#items_Sold').submit();
 	});
} );
</script>
</head>
<body >
<p class='blacktitle'>Items sold by period</p>
<form id='items_Sold' name='items_Sold' method='post' action='items_Sold.php'>
Start Date : <input type='text' name='startDate' id='startDate' />
End Date(not included) : <input type='text' name='endDate' id='endDate' />
<input type='button' id='thisMthButton' class='lazyButton' value='This Month' title='Check result until today' />
<input type='submit' value='search' />
</form>
<?php
if($_POST['endDate'] == null){
   $_POST['endDate'] = $today;
}
//get the data of orders
$sql = "SELECT i.item_name, sum(oi.qty) as qty_sold ".
       "FROM order_items as oi left join items as i on oi.item_id=i.item_id ".
       "WHERE order_id in (SELECT order_id FROM orders as o WHERE o.update_time>='".
       $_POST['startDate']."' AND o.update_time<'".$_POST['endDate']."') ".
       "GROUP BY i.item_name ";
       //"ORDER BY i.item_name";
//echo $sql."<br/>";
$finalRows = 0;
if(isset($_POST['startDate'])){
    $stmt=$dbh->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $totalRows = count($result); 
    echo "Current target period: <b>".$_POST['startDate']." ~ ".$_POST['endDate']."</b><br/>";
    $finalRows = $totalRows;
}
/*
echo "<pre>";
print_r($result);
echo "</pre>";

//get the data of customer return order
$sql_return = "SELECT * FROM cro ".
	      "WHERE return_time>='".$_POST['startDate']."' AND return_time<'".$_POST['endDate']."' ";
$stmt_return=$dbh->query($sql_return);
$result_return = $stmt_return->fetchAll(PDO::FETCH_ASSOC);

$currDate = $_POST['startDate'];
$nextDate = date('Y-m-d',strtotime($_POST['startDate']." +1 day"));
*/

//get all of the curr stock
$sql_curr = "SELECT item_name,sum(stock) FROM items GROUP BY item_name ";
$stmt_curr = $dbh->query($sql_curr);
if($stmt_curr !== FALSE){   //query succeeded
    $result_curr = $stmt_curr->fetchAll(PDO::FETCH_ASSOC);
    $totalCurr = count($result_curr);
    
}



//print out the final result
echo "<table border='1' id='itemSoldTable' class='report_table display'>";
echo "<thead><tr bgcolor='orange' class='titlee'><th>Item name</th><th>QTY sold</th><th>Curr stock</th></tr></thead>";
echo "<tbody>";
for($i=0 ; $i<$finalRows; $i++){
   if($i%2 == 0)
      echo "<tr align='center'>";
   else
      echo "<tr bgcolor='lightblue' align='center'>";
   
   //echo "<td>".$result[$i]['item_id']."</td";
   echo "<td align='left'>".$result[$i]['item_name']."</td>";
   //echo "<td align='left'>".$result[$i]['item_name_en']."</td";
   echo "<td>".$result[$i]['qty_sold']."</td>";
   //echo "<td>".$result[$i]['curr_stock']."</td>";
   for($j=0;$j<$totalCurr;$j++){
        if($result_curr[$j]['item_name'] == $result[$i]['item_name']){
            if((int)$result_curr[$j]['sum'] == 0)
                echo "<td class='warning'> Sold out </td>";
            else
                echo "<td>".$result_curr[$j]['sum']."</td>";
        }
   }
   echo "</tr>";
}
echo "</tbody>";
echo "</table>";

?>
</body>
</html>
