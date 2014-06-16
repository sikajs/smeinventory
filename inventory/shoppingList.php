<?php
include "../shared/user_check_right.php";
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
        $('#shopping').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers"
        });
} );
</script>
</head>
<body>
<p class='blacktitle'>Shopping list (Outstock)</p>
<center>
<table id='shopping' class='display report_table' border='1'>
<thead>
<tr bgcolor='orange'><th>No.</th><th>Barcode</th><th>Item name</th><th>Item name en</th></tr>
</thead>
<tbody>
<?php
$sql = "SELECT * FROM items ".
       "WHERE stock<=2 AND active=true ".
       "ORDER BY barcode ";
$stmt = $dbh->query($sql);
if($stmt !== FALSE){ //sql executed successfully
   $counter = 0;
   foreach($stmt as $row){
      if($counter%2 == 0)
         echo "<tr>";
      else
         echo "<tr bgcolor='lightblue'>";
      echo "<td align='center'>".($counter+1)."</td>";
      echo "<td>".$row['barcode']."</td>";
      echo "<td>".$row['item_name']."</td>";
      echo "<td>".$row['item_name_en']."</td>";
      //echo "<td><a href='restock_act.php?itemID=".$row['item_id']."'>Restock now</a></td>";
      echo "</tr>";
      $counter++;
   }

}
?>
</tbody>
</table>
</center>
<table class='report_table' border='1'>
</body>
</html>
