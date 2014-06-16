<?php
include_once "../shared/user_check_right.php";
$_SESSION['lastPage'] = $_SERVER['REQUEST_URI'];
include_once('../shared/dbconnect.php');
$today = date('Y-m-d');
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>new_order</title>
<link href="../shared/smeInventory.css" rel="stylesheet" type="text/css" />
<link href="../css/ui-lightness/jquery-ui-1.8.17.custom.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" >
$(document).ready(function(){
   $('#targetDate').datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1 });

});
</script>
</head>
<body >
<p class='blacktitle'>The Items restocked</p>
<form id='dailyRestock' name='dailyRestock' method='post' action='dailyRestock.php'>
Date : <input type='text' name='targetDate' id='targetDate' value="<?php echo $today;?>"/>
<input type='submit' value='submit' />
</form>
<?php
$sql = "SELECT i.barcode,i.item_name,i.item_name_en,rh.new_arrival,i.unit_price ".
       "FROM restock_history as rh left join items as i on rh.item_id=i.item_id ".
       "WHERE rh.restock_time>='".$_POST['targetDate']."' AND rh.restock_time<'".date('Y-m-d',strtotime($_POST['targetDate']." +1 day"))."' ".
       "ORDER BY i.barcode ";
//echo $sql;
$stmt=$dbh->query($sql);
echo "Target date : ".$_POST['targetDate']."<br/>";
if($stmt !== FALSE){
    //prepare the file
    $fp = fopen('/tmp/dailyRestock.csv', 'w');
    /*
    if($fp !== FALSE)
        echo "file opened";
    else
        echo "open file failed";
     */
    $counter = 0;
    echo "<table border='1' class='report_table'>";
    echo "<tr class='titlee' bgcolor='orange'><th>Barcode</th><th>Item name</th><th>Item name en</th><th>New arrival</th><th>Price</th></tr>";

    fputcsv($fp, array("Barcode","Item name","Item Eng name","New arrival","Unit price"));
    foreach($stmt as $row){
        if($counter%2 == 0)
            echo "<tr>";
        else
            echo "<tr bgcolor='lightblue'>";
        echo "<td>".$row['barcode']."</td>";
        echo "<td>".$row['item_name']."</td>";
        echo "<td>".$row['item_name_en']."</td>";
        echo "<td align='center'>".$row['new_arrival']."</td>";
        echo "<td>".$row['unit_price']."</td>";
        echo "</tr>";
        $counter++;
        $unit_price = "ราคา ".$row['unit_price']." บาท";
        $temp = array($row['barcode'],$row['item_name'],$row['item_name_en'],$row['new_arrival'],$unit_price);
        fputcsv($fp, $temp);
    }
    fclose($fp);
    echo "</table><br/>";
    echo "<form id='exportForm' method='post' action='exportDailyRestock.php'> ";
    echo "<input type='submit' id='export' value='Export to csv'/>";
    echo "</form>";
}
?>


</body>
</html>
