<?php
include_once "../shared/user_check_right.php";
include_once "../shared/dbconnect.php";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Top20</title>
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script type="text/javascript" language="javascript" charset="UTF-8" >
</script>
</head>
<body>
<p class='blacktitle'>Top 20 popular products</p>
<div>
<center>
<table id='top20' class='report_table' border='1'>
<thead>
<tr class='titlee' bgcolor='#FFD700'><th>Item name</th><th>Top GP rate</th><th>Sold QTY</tr>
</thead>
<tbody>
<?php
$sql = "SELECT i.item_name, max(oi.margin/oi.price) as gp_rate, sum(oi.qty) as sold_qty ".
       //"SELECT i.item_name, ((i.unit_price-i.unit_cost)/i.price) as gp_rate, sum(oi.qty) as sold_qty ".
       "FROM order_items as oi left join items as i on oi.item_id=i.item_id ".
       "GROUP BY i.item_name ".
       "ORDER BY sold_qty DESC limit 20 ";
//echo $sql;
$stmt = $dbh->query($sql);
if($stmt !== FALSE){
   $counter = 0;
   foreach($stmt as $row){
      if($counter % 2 == 0)
         echo "<tr align='center'>";
      else
         echo "<tr bgcolor='#F0E68C' align='center'>";
      //echo "<td>".$row['barcode']."</td>";
      echo "<td>".$row['item_name']."</td>";
      //echo "<td>".$row['item_name_en']."</td>";
      if($row['gp_rate'] < 0.3)
         $format = "<td><font color='red'>%.2f%%</font></td>";
      elseif($row['gp_rate'] >= 0.5)
         $format = "<td><font color='green'>%.2f%%</font></td>";
      else
         $format = "<td>%.2f%%</td>";
      printf($format,($row['gp_rate']*100));
      //echo "<td>".$row['gp_rate']."</td>";
      echo "<td>".$row['sold_qty']."</td>";
      echo "</tr>";
      $counter++;
   }


}

?>
</tbody>
</table>
</center>
</div>
</body>
</html>
