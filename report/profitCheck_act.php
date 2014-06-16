<?php
include "../shared/user_check_right.php";
/* connect to database */
include "../shared/dbconnect.php";

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
        $('#result_table').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers"
        });
} );
</script>
</head>
<body class='example_alt_pagination'>
<p class='blacktitle'>Profit Check Result</p>
<?php
if($_POST['supplier'] != null){
	$sql = "SELECT suppliers.suppl_name, items.* ".
			 "FROM suppliers, items ";
	$sql = $sql."WHERE items.supplier_id='".$_POST['supplier']."' ";
	if($_POST['brand'] != null){
		$sql = $sql."AND items.brand='".strtoupper($_POST['brand']).")' ";
		if($_POST['itemName'] != null){
			$sql = $sql."AND items.item_name like '%".$_POST['itemName']."%' ";
		}
	} elseif($_POST['itemName'] != null){
		$sql = $sql."AND items.item_name like '%".$_POST['itemName']."%' ";
	}
} elseif($_POST['brand'] != null) {
	$sql = "SELECT suppliers.suppl_name, items.* ".
			 "FROM suppliers, items ";
	$sql = $sql."WHERE items.brand='".strtoupper($_POST['brand'])."' ";
	if($_POST['itemName'] != null)
		$sql = $sql."AND items.item_name like '%".$_POST['itemName']."%' ";
} elseif($_POST['itemName'] != null){
	$sql = "SELECT suppliers.suppl_name, items.* ".
			 "FROM suppliers, items ";
	$sql = $sql."WHERE items.item_name like '%".$_POST['itemName']."%' ";
}
//the following search condition will work on only one targeted condition
if($_POST['id'] != null){
    $sql = "SELECT suppliers.suppl_name, items.* ".
           "FROM suppliers, items ".
           "WHERE items.item_id='".$_POST['id']."' ";
}
if($_POST['iBarcode'] != null){
    $sql = "SELECT suppliers.suppl_name, items.* ".
           "FROM suppliers, items ".
           "WHERE items.barcode='".$_POST['iBarcode']."' ";
}
if($_POST['color'] != null){
    $sql = "SELECT suppliers.suppl_name, items.* ".
           "FROM suppliers, items ".
           "WHERE items.color='".$_POST['color']."' ";
}

if($_GET['pagenum'] != NULL)
	$sql = $_SESSION['lastsql'];	
else{
	$sql = $sql."AND suppliers.supplier_id=items.supplier_id ".
	"ORDER BY items.item_name, items.brand, items.item_id ";
}
$_SESSION['lastsql'] = $sql;
//print $sql;

/* select and print out items*/
echo "<center>";
print "<table border='1' class='report_table display' id='result_table'>";

if($stmt = $dbh->query($sql)){
	$result = $stmt->fetchAll();
	$count = count($result);
	if($count > 0){
		//show content in certain page
		echo "<thead>";
		print "<tr ><th>Item id</th><th>Item name</th><th>Item name En</th><th>Color</th><th>Stock</th>
		<th>Unit price</th><th>Unit cost</th><th>Gross profit rate</th><th>Active status</th><th>Supplier</th><th>Barcode</th></tr>";
		echo "</thead>";
		echo "<tbody>";
		for($j=0; $j < count($result); $j++){
			if($result[$j] == NULL)
				break;
			if($count % 2 == 0)
   	   	           //print "<tr bgcolor='lightblue'>";
   	   	           print "<tr>";
   	   		else
   	   	           print "<tr>";
			/*
  			print "<td align='center'><a href='editItem.php?id=".$result[$j]['item_id']."'>" 
			. $result[$j]['item_id'] . "</a></td>";
			*/
			echo "<td align='center'>". $result[$j]['item_id'] ."</td>";
  			//print "<td>" . $result[$j]['brand'] . "</td>";
  			print "<td>" . $result[$j]['item_name'] . "</td>";
  			print "<td>" . $result[$j]['item_name_en'] . "</td>";
                        print "<td>" . $result[$j]['color'] . "</td>";
			if($result[$j]['stock'] == 0)
  			   print "<td align ='center'><p class='warning'>Sold out</p></td>";
			else
  			   print "<td align ='center'>" . $result[$j]['stock'] . "</td>";
  			print "<td align='right'>" . $result[$j]['unit_price'] . "</td>";
  			print "<td align='right'>" . $result[$j]['unit_cost'] . "</td>";
			
                        $gpRate = ($result[$j]['unit_price']-$result[$j]['unit_cost'])/$result[$j]['unit_price']*100;
                        if($gpRate < 0.3)
                            $format = "<td align='center'><font color='red'>%.2f%%</font></td>";
                        else
                            $format = "<td align='center'>%.2f%%</td>";
			printf($format,$gpRate);
			if($result[$j]['active'])
			    echo "<td align='center'>Yes</td>";
			else
			    echo "<td align='center'>No</td>";
  			print "<td align='center'>" . $result[$j]['suppl_name'] . "</td>";
  			print "<td>" . $result[$j]['barcode'] . "</td>";
			print "</tr>";
			$count--;
		}
	} else {	//no item found
		print "No item found.<br/>";
	}
}
		echo "</tbody>";
print "</table>";
echo "</center>";

//cleanup and close db connection
$res = null;
$dbh = null;

?>
</body>
</html>
