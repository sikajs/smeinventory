<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
include "../shared/user_check_right.php";

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
    <script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function() {
            $('#iBarcode').focus();
        });
        
    </script>
</head>
<body>
<p class='blacktitle'>Item activity (Stock card)</p>
<div id="itemSearch">
<form name="searchForm" method="post" action="itemActivity.php">
<table border="0">
    <!--<tr>
      <td>Item name</td><td><input type="text" name="itemName" class="inputText" /></td>
    </tr>-->
    <tr>
        <td>Item ID</td><td><input type="text" name="id" class="inputText" /></td>
        <td>Barcode</td>
        <td><input type="text" id='iBarcode' name="iBarcode" class="inputText" /></td>
    </tr>
</table>
<?php
//Base on the conditions user entered to generate sql statement
if($_POST['backToResult'] == 'true'){
	$sql = $_SESSION['lastsql'];
} else {
	unset($sql);
	unset($_SESSION['lastsql']);
	if($_POST['id'] != NULL){
            $sql = "SELECT initial_time,item_name,barcode,initial_stock,stock FROM items "."WHERE item_id=".$_POST['id'];
            $sql_sold = "SELECT o.update_time,oi.qty FROM orders as o, order_items as oi ".
                        "WHERE oi.item_id=".$_POST['id']." AND o.order_id=oi.order_id";
            $sql_rh = "SELECT restock_time,new_arrival FROM restock_history WHERE item_id=".$_POST['id'];
            $sql_adj = "SELECT * FROM adjust_history WHERE item_id=".$_POST['id'];
            $sql_cro = "SELECT c.return_time,ci.qty FROM cro_items as ci, cro as c WHERE ci.item_id=".$_POST['id']." AND c.cro_id=ci.cro_id";
        }
        if($_POST['iBarcode'] != NULL){
            $sql = "SELECT initial_time,item_name,barcode,initial_stock,stock FROM items "."WHERE barcode='".$_POST['iBarcode']."'";
            $sql_sold = "SELECT o.update_time,oi.qty FROM orders as o, order_items as oi ".
                        "WHERE oi.barcode='".$_POST['iBarcode']."' AND o.order_id=oi.order_id";
            $sql_rh = "SELECT rh.restock_time,rh.new_arrival FROM restock_history as rh, items as i ".
                      "WHERE i.barcode='".$_POST['iBarcode']."' AND i.item_id=rh.item_id";
            $sql_adj = "SELECT ah.* FROM adjust_history as ah,items as i ".
                       "WHERE i.barcode='".$_POST['iBarcode']."' AND i.item_id=ah.item_id";
            $sql_cro = "SELECT c.return_time,ci.qty FROM cro_items as ci, cro as c ".
                       "WHERE ci.barcode='".$_POST['iBarcode']."' AND c.cro_id=ci.cro_id";
        }
	// record the sql and set the variable for back to the search result later
	$_SESSION['lastsql'] = $sql;
}
//echo $sql."<br/>";
//echo $sql_sold."<br/>";
//echo $sql_rh."<br/>";
//echo $sql_adj."<br/>";
//include "../unused/show_session.php";
?>
<input type="submit" value="Search">
<input type="reset">
</form>
</div>
<hr/>
<div id="result">
<?php
include_once "../shared/dbconnect.php";

$stmt = $dbh->query($sql);
if($stmt !== FALSE){
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $resultArray = array();
    array_push($resultArray,array('date'=>$result['initial_time'],'activity'=>'Initial','qty'=>$result['initial_stock']));
    $stmt_rh = $dbh->query($sql_rh);
    if($stmt_rh !== FALSE){
        foreach($stmt_rh as $row){
            //$resultArray += array($row['restock_time']=>array('Restock',$row['new_arrival']));
            array_push($resultArray,array('date'=>$row['restock_time'],'activity'=>'Restock','qty'=>$row['new_arrival']));
        }
    }
    $stmt_sold = $dbh->query($sql_sold);
    if($stmt_sold !== FALSE){
        foreach($stmt_sold as $row){
            array_push($resultArray,array('date'=>$row['update_time'],'activity'=>'Sell','qty'=>$row['qty']));
        }
    }
    $stmt_adj = $dbh->query($sql_adj);
    if($stmt_adj !== FALSE){
        foreach($stmt_adj as $row){
            array_push($resultArray,array('date'=>$row['adjust_time'],'activity'=>'Adjust','qty'=>($row['new_stock']-$row['previous_stock'])));
        }
    }
    $stmt_cro = $dbh->query($sql_cro);
    if($stmt_cro !== FALSE){
        foreach($stmt_cro as $row){
            array_push($resultArray,array('date'=>$row['return_time'],'activity'=>'Return','qty'=>$row['qty']));
        }
    }
}
sort($resultArray);
echo "Target item : <em>".$result['item_name']." (".$result['barcode'].")</em>";
?>

<table id="itemActTable" border="1">
<thead>
<tr bgcolor="orange"><th>Date & time</th><th>Activity</th><th>QTY</th></tr>
</thead>
<tbody>
<?php
//print out the final result
/*
echo "<pre>";
print_r($resultArray);
echo "</pre>";
*/
for($i=0;$i<count($resultArray);$i++){
    switch($resultArray[$i]['activity']){
        case "Restock":
            echo "<tr bgcolor='lightblue' align='center'>";
            break;
        case "Sell":
            echo "<tr bgcolor='lightgreen' align='center'>";
            break;
        case "Adjust":
            echo "<tr bgcolor='pink' align='center'>";
            break;
        case "Return":
            echo "<tr bgcolor='yellow' align='center'>";
            break;
        default:
            echo "<tr align='center'>";
    }
    try {
        $date = new DateTime($resultArray[$i]['date']);
    } catch (Exception $e) {
        echo $e->getMessage();
        exit(1);
    }
    echo "<td>".$date->format('Y-m-d Ah:i')."</td>";
    echo "<td>".$resultArray[$i]['activity']."</td>";
    echo "<td>".$resultArray[$i]['qty']."</td>";
    echo "</tr>";
}
echo "<tr bgcolor='orange' align='center'>";
echo "<td>".date('Y-m-d Ah:i')."</td>";
echo "<td>Current stock</td>";
if($result['stock'] == 0){
    echo "<td> 0 </td>";
}else{
    echo "<td>".$result['stock']."</td>";
}

echo "</tr>";
?>
</tbody>
</table>
</div>
</body>
</html>
