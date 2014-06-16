<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "../shared/user_check_right.php";
$_SESSION['lastPage'] = $_SERVER['REQUEST_URI'];
include_once('../shared/dbconnect.php');

//include_once ('../unused/debug_msg.php');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daily order</title>
<link href="../shared/smeInventory.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#report_div
{
	margin-left: auto;
    margin-right: auto;
    width:	60%;
}
.mth_table th
{
	border: solid orange 1px;
	background-color: orange;
}
.mth_table td
{
	border: solid orange 1px;
}
</style>
<body>
<p class='blacktitle'>Monthly report</p>
<div style="float: right">
<form id="updateMthForm" action="gen_monthly.php" method="post">
    <input type="submit" id="updateButton" value="Update"/>
</form>
</div>
<div id='report_div'>
<table class='mth_table'>
    <tr><th>Year-Month</th><th>Number of Order</th><th>Revenue</th>
        <th>Gross profit</th><th>GP rate</th><th>Total cro</th><th>Avg. Rev. per order</th>
    </tr>

<?php
$yearlyRev = 0;
$yearlyGP = 0;
$yearMth = getdate();
$thisMth = date('m',strtotime('this month'));

$sql = "SELECT * FROM mth_report order by year,month ";
$stmt = $dbh->query($sql);
if($stmt !== FALSE){
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //print_r($result);
    foreach($result as $row){
        echo "<tr align='right'>";
        echo "<td align='center'>".$row['year']."-".$row['month']."</td>";
        echo "<td align='center'>".$row['num_of_order']."</td>";
        echo "<td>".$row['revenue']."</td>";
        $yearlyRev += (float)$row['revenue'];
        $format = "<td> %.3f </td>";
        printf($format,(float)$row['gp']);
        $yearlyGP += (float)$row['gp'];
        $format = "<td> %.2f%% </td>";
        printf($format,(float)($row['gp']/$row['revenue'])*100);
        echo "<td>".$row['total_cro']."</td>";
        $format = "<td> %.2f </td>";
        printf($format,(float)$row['avg_revenue']);
        echo "</tr>";
        if($row['month'] == 8)
        {
        	$format = "<tr align='right' class='emBG'><td align='center'>Yearly Total</td><td>&nbsp</td><td>%.d</td><td>%.3f</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td></tr>";
        	printf($format, $yearlyRev,$yearlyGP);
        	$yearlyRev = 0;
        	$yearlyGP = 0;
        	echo "</table><br/>";
        	echo "<table class='mth_table'>";
        	echo "<tr><th>Year-Month</th><th>Number of Order</th><th>Revenue</th>
        		 <th>Gross profit</th><th>GP rate</th><th>Total cro</th><th>Avg. Rev. per order</th>
    			 </tr>";
        }elseif (($row['year'] == $yearMth['year'] && $row['month'] == $thisMth))
        {
        	$format = "<tr align='right' class='emBG'><td align='center'>Yearly Total</td><td>&nbsp</td><td>%.d</td><td>%.3f</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td></tr>";
        	printf($format, $yearlyRev,$yearlyGP);
        	echo "</table><br/>";
        }
    }
}else
    print_r($stmt->errorInfo());
?>
</table>
</div>
</body>
</html>