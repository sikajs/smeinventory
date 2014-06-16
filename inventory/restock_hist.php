<?php
include "../shared/user_check_right.php";

//database connection
include "../shared/dbconnect.php"; 

//include "../shared/pagemanip.php";
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
        $('#rh_table').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers"
        });
} );
</script>
</head>
<body>
<p class='blacktitle'>Restock History</p>
<?php
//get restock history and echo it on screen
echo "<table border='1' class='table display' id='rh_table'>";
$sql = "SELECT rh.*,i.item_name,i.barcode FROM restock_history as rh, items as i ".
       "WHERE rh.item_id=i.item_id ";
       //"ORDER BY restock_time DESC";
//echo $sql;
if($stmt = $dbh->query($sql)){
	$result = $stmt->fetchAll();
	$count = count($result);
	if($count > 0){
		//pages manipulator calculation		
		//$numPerPage = $_SESSION['numPerPage'] = 20;
		//$numPerPage = $_SESSION['numPerPage'] = 15;
		//$currHead = pagemanip($result,$numPerPage);
		
		//show content in certain page
		echo "<thead>";
		echo "<tr bgcolor='#FFCC00'>".
                      "<th>Restock Time(Y/M/D, Time)</th><th>User ID</th><th>Item id</th><th>Barcode</th><th>Item name</th>".
                      "<th>Previous Stock</th><th>New Arrival</th><th>Previous Cost</th><th>New Cost</th><th>Supplier ID</th></tr>";
		echo "</thead>";
		echo "<tbody>";
		//for($j=$currHead; $j < ($currHead+$numPerPage); $j++){
                for($j=0; $j < count($result); $j++){
			if($result[$j] == NULL)
				break;
                        /*
			if($count % 2 != 0)
  	    	   	   echo "<tr align='center'>";
  	    		else
  	    		   echo "<tr align='center' bgcolor='lightblue'>";
                         * 
                         */
  			echo "<td>" . date('Y/m/d, g:i a',strtotime($result[$j]['restock_time'])) . "</td>";
                        echo "<td>" . $result[$j]['uid'] . "</td>";
  			echo "<td>" . $result[$j]['item_id'] . "</td>";
  			echo "<td>" . $result[$j]['barcode'] . "</td>";
  			echo "<td>" . $result[$j]['item_name'] . "</td>";
  			echo "<td>" . $result[$j]['previous_stock'] . "</td>";
  			echo "<td>" . $result[$j]['new_arrival'] . "</td>";
  			echo "<td>" . $result[$j]['previous_cost'] . "</td>";
  			echo "<td>" . $result[$j]['new_cost'] . "</td>";
  			echo "<td>" . $result[$j]['supplier_id'] . "</td>";
			echo "</tr>";
			$count--;
		}
		echo "</tbody>";
	} else {	//no user found
		echo "No restock history found.<br/>";
	}
	unset($stmt);
	unset($result);
}
echo "</table>";

//cleanup
unset($stmt);
unset($result);
unset($dbh);
?>
</body>
</html>
