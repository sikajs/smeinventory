<!DOCTYPE HTML>
<?php
include_once "../shared/user_check_right.php";
include_once "../shared/dbconnect.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daily order</title>
<link href="../shared/smeInventory.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
#bybrand_info {
    float : left;
}
#bycategory_info {
    float : left;
}
.alnR {
    text-align:right;
}
#bybrand, #bycategory
{
font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
width:100%;
border-collapse:collapse;
}
#bybrand td, #bybrand th, #bycategory td, #bycategory th  
{
font-size:1em;
//border:1px solid #98bf21;
border:1px solid #FABD1A;
padding:3px 7px 2px 7px;
}
#bybrand th , #bycategory th
{
font-size:1.1em;
text-align:left;
padding-top:5px;
padding-bottom:4px;
//background-color:#A7C942;
background-color:orange;
color:#ffffff;
}
-->
</style>
<body>
<p class='blacktitle'>Whole picture</p>
<table class="border_table">
<?php
$today = date('Y-m-d');
echo "Today is ".$today."<br/><br/>";
//Get whole revenue
$sql_wholeRevenue = "select sum(cash_received-change) as revenue from orders";
//echo $sql_wholeRevenue."<br/>";
$revenueSt = $dbh->query($sql_wholeRevenue);
if($revenueSt !== FALSE){
   $wholeRevenue = $revenueSt->fetchColumn();
   //Get whole CRO
   $sql_CRO = "SELECT sum(return_total) FROM cro ";
   $croST = $dbh->query($sql_CRO);
   $resultCRO = $croST->fetchColumn();
   //echo $resultCRO;
   $wholeRevenue = $wholeRevenue - $resultCRO;
   echo "<tr><td>Whole revenue (Since 2010-09-03): </td><td>".$wholeRevenue."</td>";
}else
   echo "Query database failed";

//Get whole cost and calculate profit
$sql_wholeCost = "select sum(curr_cost*qty) as cost from order_items";
//echo $sql_whole."<br/>";
$stmt = $dbh->query($sql_wholeCost);
if($stmt !== FALSE){
   $whole = $stmt->fetchAll();
   echo "<tr><td>Whole cost (Since 2010-09-03): </td><td>".$whole[0]['cost']."</td>";
   $wholeProfit = $wholeRevenue-$whole[0]['cost'];
   echo "<tr><td>Whole profit (Since 2010-09-03): </td><td>".$wholeProfit."</td>";
}else
   echo "Query database failed";

$format = "<tr><td>Profit rate (profit/revenue)</td><td>%.2f%%</td></tr>";
printf($format,$wholeProfit/$wholeRevenue*100);
?>
</table>
<br/>
<div id="inventory_info">
    
<div id="bybrand_info">
<table id="bybrand" class="border_table">
<thead>
	<tr><th colspan="2">Current inventory by brand</th></tr>
	<tr><th>Brand</th><th>Amount</th></tr>
</thead>
<tbody>
<?php
$total_inv = 0;
//by brand
$sqlByBrand = "SELECT brand,sum(unit_cost*stock) as tocbybrand ".
			  "FROM items ".
			  "GROUP BY brand ".
			  "ORDER BY tocbybrand DESC";
//echo $sqlByBrand;
$stmt_bybrand = $dbh->query($sqlByBrand);
if($stmt_bybrand !== FALSE){
	foreach($stmt_bybrand as $row){
		echo "<tr>";
		echo "<td >".$row['brand']."</td>";
		echo "<td class='alnR'>".$row['tocbybrand']."</td>";
		echo "</tr>";
		$total_inv += (float)$row['tocbybrand'];
	}
}
echo "<tr><td>Current total inventory</td>";
echo "<td>".$total_inv."</td>";
?>
</tbody>
</table>
</div>

<div id="bycategory_info">
<table id="bycategory" class="border_table">
<thead>
	<tr><th colspan="3">Current inventory by category</th></tr>
	<tr><th>Category</th><th>Amount</th><th>%</th></tr>
</thead>
<tbody>
<?php
//by category
$sqlByCategory = "SELECT category,sum(unit_cost*stock) as tocbycategory ".
			  "FROM items ".
			  "GROUP BY category ".
			  "ORDER BY tocbycategory DESC";
//echo $sqlByCategory;
$format = "<tr><td>%s</td><td class='alnR'>%.2f</td><td class='alnR'>%.2f%%</td></tr>";
$stmt_bycategory = $dbh->query($sqlByCategory);
if($stmt_bycategory !== FALSE){
	foreach($stmt_bycategory as $row){
        printf($format,$row['category'],$row['tocbycategory'],(((float)$row['tocbycategory'])/$total_inv*100));
        /*
		echo "<tr>";
		echo "<td align='center'>".$row['category']."</td>";
		echo "<td align='right'>".$row['tocbycategory']."</td>";
                echo "<td class='alnR'>".(((float)$row['tocbycategory'])/$total_inv)."</td>";
		echo "</tr>";
	*/	
	}
}
?>
</tbody>
</table>
</div>

</div>
</body>
</html>
