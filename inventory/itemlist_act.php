<?php
include "../shared/user_check_right.php";
/* connect to database */
include "../shared/dbconnect.php";

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
        $('#itemListTable').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers"
        });
} );
</script>
</head>
<body>
<p class='blacktitle'>Item List Result</p>
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
print "<table id='itemListTable' border='1' class='table display'>";

if($stmt = $dbh->query($sql)){
	$result = $stmt->fetchAll();
	$count = count($result);
	if($count > 0){
		//pages manipulator calculation
		//$numPerPage = $_SESSION['numPerPage'] = 15;
		//$currHead = pagemanip($result,$numPerPage);
		
		//show content in certain page
		echo "<thead>";
		print "<tr bgcolor='lightblue'><th>Item id</th><th>Category</th><th>Brand</th><th>Item name</th><th>Item name En</th><th>Color</th>
		<th>Unit cost</th><th>Unit price</th><th>GP rate</th><th>Stock</th><th>Supplier Name</th><th>Barcode</th><th>Active</th></tr>";
		echo "</thead>";
		echo "<tbody>";
		foreach($result as $row){
   	   	    print "<tr>";
  		    print "<td align='center'><a href='editItem.php?id=".$row['item_id']."'>" 
		          . $row['item_id'] . "</a></td>";
		    echo "<td>" . $row['category'] . "</td>";
  		    print "<td>" . $row['brand'] . "</td>";
  		    print "<td>" . $row['item_name'] . "</td>";
  		    print "<td>" . $row['item_name_en'] . "</td>";
                    print "<td>" . $row['color'] . "</td>";
  		    print "<td align='right'>" . $row['unit_cost'] . "</td>";
  		    print "<td align='right'>" . $row['unit_price'] . "</td>";
                    $gpRate = ($row['unit_price']-$row['unit_cost'])/$row['unit_price']*100;
                    if($gpRate < 0.3)
                        $format = "<td align='center'><font color='red'>%.2f%%</font></td>";
                    else
                        $format = "<td align='center'>%.2f%%</td>";
                    printf($format,$gpRate);
		    if($row['stock'] == 0)
  		        print "<td align ='center'><p class='warning'>Sold out</p></td>";
		    else
  		        print "<td align ='center'>" . $row['stock'] . "</td>";
  		    print "<td>" . $row['suppl_name'] . "</td>";
  		    print "<td>" . $row['barcode'] . "</td>";
  		    print "<td>";  
		    if($row['active']) 
		        echo "Yes";
		    else
		        echo "No";
		    echo "</td>";
   	   	    print "</tr>";
		}
		/*
		for($j=$currHead; $j < ($currHead+$numPerPage); $j++){
			if($result[$j] == NULL)
				break;
			if($count % 2 == 0)
   	   	           print "<tr>";
   	   		else
   	   	           print "<tr bgcolor='orange'>";
  			print "<td align='center'><a href='editItem.php?id=".$result[$j]['item_id']."'>" 
			. $result[$j]['item_id'] . "</a></td>";
  			print "<td>" . $result[$j]['brand'] . "</td>";
  			print "<td>" . $result[$j]['item_name'] . "</td>";
  			print "<td>" . $result[$j]['item_name_en'] . "</td>";
                        print "<td>" . $result[$j]['color'] . "</td>";
  			print "<td align='right'>" . $result[$j]['unit_cost'] . "</td>";
  			print "<td align='right'>" . $result[$j]['unit_price'] . "</td>";
			if($result[$j]['stock'] == 0)
  			   //print "<td align ='center'><p class='warning'>" . $result[$j]['stock'] . "</p></td>";
  			   print "<td align ='center'><p class='warning'>Sold out</p></td>";
			else
  			   print "<td align ='center'>" . $result[$j]['stock'] . "</td>";
  			print "<td>" . $result[$j]['suppl_name'] . "</td>";
  			print "<td>" . $result[$j]['barcode'] . "</td>";
  			print "<td>";  
			if($result[$j]['active']) 
			   echo "Yes";
			else
			   echo "No";
			echo "</td>";
			print "</tr>";
			$count--;
		}
		*/
		echo "</tbody>";
	} else {	//no item found
		print "No item found.<br/>";
	}
}
print "</table>";

//cleanup and close db connection
$res = null;
$dbh = null;

?>
</body>
</html>
