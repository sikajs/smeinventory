<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include "../shared/user_check_right.php";
//database connection
include_once "../shared/dbconnect.php";
$sql = "SELECT ci.*, i.item_name,item_name_en FROM cro_items as ci,items as i ".
       "WHERE ci.cro_id=".$_GET['croid']." AND ci.item_id=i.item_id ";
//echo $sql."<br/>";
echo "CRO ID : ".$_GET['croid']."<br/>";
?>
<table border="1">
    <tr><th>Item ID</th><th>Item name</th><th>Item Eng name</th><th>Price</th><th>QTY</th></tr>
<?php
$stmt = $dbh->query($sql);
if($stmt !== FALSE){
    foreach ($stmt as $row) {
        echo "<tr align='center'>";
        echo "<td>".$row['item_id']."</td>";
        echo "<td>".$row['item_name']."</td>";
        echo "<td>".$row['item_name_en']."</td>";
        echo "<td>".$row['price']."</td>";
        echo "<td>".$row['qty']."</td>";
        echo "</tr>";
    }
}
?>
</table>
