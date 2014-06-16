<?php
include "../shared/user_check_right.php";
/* connect to database */
include_once "../shared/dbconnect.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css" title="currentStyle">
        @import "../css/demo_table_jui.css";
        @import "../css/ui-lightness/jquery-ui-1.8.17.custom.css";
        @import "../shared/smeInventory.css";
</style>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" >
$(document).ready(function() {
        $('#outStockTable').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers"
        });
} );
</script>

</head>
<body class='example_alt_pagination'>
<p class='blacktitle'>Outstock warning</p>
<table class='report_table display' border='1' id='outStockTable'>
<thead>
<tr ><th>Item name</th><th>Item name en</th><th>Barcode</th><th>Stock</th>
    <th>Supplier</th><th>Current unit cost</th><th>Unit price</th><th>Action</th></tr>
</thead>
<tbody>
<?php
$sql = "SELECT i.*,s.suppl_name FROM items as i, suppliers as s ".
       "WHERE i.stock<=2 AND active=true AND i.supplier_id=s.supplier_id ".
       "ORDER BY i.barcode ";
//echo $sql;
$stmt = $dbh->query($sql);
if($stmt !== FALSE){ //sql executed successfully
   $counter = 0;
   foreach($stmt as $row){
      echo "<tr>";
	/*
      if($counter%2 == 0)
         echo "<tr>";
      else
         echo "<tr bgcolor='lightblue'>";
      */
      //echo "<td align='center'>".($counter+1)."</td>";
      echo "<td>".$row['item_name']."</td>";
      echo "<td>".$row['item_name_en']."</td>";
      echo "<td>".$row['barcode']."</td>";
      echo "<td align='center'>".$row['stock']."</td>";
      echo "<td align='center'>".$row['suppl_name']."</td>";
      echo "<td align='right'>".$row['unit_cost']."</td>";
      echo "<td align='right'>".$row['unit_price']."</td>";
      echo "<td><a href='restock_act.php?itemID=".$row['item_id']."'>Restock now</a></td>";
      echo "</tr>";
      $counter++;
   }

}
?>
</tbody>
</table>
</body>
</html>
