<?php
include "../shared/user_check_right.php";

include "../shared/dbconnect.php";	//database connection 
include "../shared/pagemanip.php";
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
        $('#adjHistTable').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers"
        });
} );
</script>
</head>
<body>
<p class='blacktitle'>Adjust Inventory History</p>

<?php
//get restock history and echoit on screen
echo "<table border='1' id='adjHistTable' class='table display'>";
$sql = "SELECT adjust_history.*, i.brand, i.item_name, i.product_code, i.barcode, i.unit_cost ".
		 "FROM adjust_history, items as i ".
		 "WHERE adjust_history.item_id=i.item_id ";
if($_POST['iName'] != NULL){	//User entered searching condition
	unset($_SESSION['currpage']);
	$sql = $sql."AND i.item_name like '%".$_POST['iName']."%' ";
	if($_POST['prod_code'] != NULL){
		$sql = $sql."AND i.product_code='".$_POST['prod_code']."' ";
	}
	$sql = $sql."ORDER BY adjust_history.adjust_time DESC";
} elseif($_POST['prod_code'] != NULL){
	unset($_SESSION['currpage']);
	$sql = $sql."AND i.product_code='".$_POST['prod_code']."' ";
	$sql = $sql."ORDER BY adjust_history.adjust_time DESC";
} elseif($_SESSION['lastsql'] != NULL) {	//use the recorded sql to help pagemanip function work properly 
	$sql = $_SESSION['lastsql'];
} else {	
	$sql = $sql."ORDER BY adjust_history.adjust_time DESC";
}
$_SESSION['lastsql'] = $sql;
//echo $sql;
$stmt = $dbh->query($sql);
if($stmt !== FALSE){
	$result = $stmt->fetchAll();
	$count = count($result);
	if($count > 0){
		//pages manipulator calculation		
		//$numPerPage = $_SESSION['numPerPage'] = 15;
		//$currHead = pagemanip($result,$numPerPage);
		
		//show content in certain page
		echo "<thead><tr bgcolor='lightblue'>".
                     "<th>Adjust Time(Y/M/D)</th><th>User ID</th><th>Barcode</th>".
                     "<th>Brand / Item</th><th>Previous Stock</th><th>New Stock</th><th>Changed</th>".
                     "<th>Amount</th><th>Reason</th></tr>".
                     "</thead>";
                echo "<tbody>";
                //print_r($result);
                foreach($result as $row){
                    echo "<tr>";
                    echo "<td>" . date('Y/m/d, H:i ',strtotime($row['adjust_time'])) . "</td>";
                    echo "<td>" . $row['uid'] . "</td>";
                    //echo "<td>" . $row['item_id'] . "</td>";
                    echo "<td>" . $row['barcode'] . "</td>";
                    echo "<td>" . $row['brand'] . " / " . $row['item_name'] . "</td>";
                    //echo "<td>" . $row['product_code'] . "</td>";
                    echo "<td>" . $row['previous_stock'] . "</td>";
                    echo "<td>" . $row['new_stock'] . "</td>";
                    $changed = (int)($row['new_stock']-$row['previous_stock']);
                    echo "<td>" . $changed . "</td>";
                    if($changed < 0){
                        echo "<td>".($changed * $row['unit_cost'])."</td>";
                    }else{
                        echo "<td>-</td>";
                    }
                    echo "<td>" . $row['reason'] . "</td>";
                    echo "</tr>";
                }
                /*
		for($j=$currHead; $j < ($currHead+$numPerPage); $j++){
			if($result[$j] == NULL)
				break;
			if($count % 2 == 0)
                            echo"<tr align='center'>";
                        else
                            echo"<tr align='center' bgcolor='orange'>";
  			echo"<td>" . $result[$j]['uid'] . "</td>";
  			echo"<td>" . $result[$j]['item_id'] . "</td>";
                        echo"<td>" . $result[$j]['barcode'] . "</td>";
  			echo"<td>" . $result[$j]['brand'] . " / " . $result[$j]['item_name'] . "</td>";
  			//echo"<td>" . $result[$j]['product_code'] . "</td>";
  			echo"<td>" . $result[$j]['previous_stock'] . "</td>";
  			echo"<td>" . $result[$j]['new_stock'] . "</td>";
                        echo"<td>" . $result[$j]['reason'] . "</td>";
			echo"<td>" . date('d/m/Y, H:i ',strtotime($result[$j]['adjust_time'])) . "</td>";
                        
			echo"</tr>";
			$count--;
		}
                 * 
                 */
                echo "</tbody>";
	} else {	//no user found
		echo"No restock history found.<br/>";
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