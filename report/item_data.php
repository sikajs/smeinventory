<?php
include_once "../shared/user_check_right.php";
include_once("../shared/dbconnect.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sale data by item name</title>
<link href="../shared/smeInventory.css" rel="stylesheet" type="text/css" />
</head>
    <body>
<?php
$sql = "SELECT distinct(item_name) FROM items order by item_name ";
$stmt = $dbh->query($sql);
//echo "<pre>";
echo "<table>";
if($stmt !== FALSE){
    $itemArray = $stmt->fetchAll();
    //print_r($itemArray);
    //exit;
    
    echo "The number of target(s):".count($itemArray)."<br/>";
    $sql_id = "SELECT item_id FROM items WHERE item_name = ?";
    //echo $sql_id."<br/>";
    $stmt_id = $dbh->prepare($sql_id);
    $total = 0;
    foreach ($itemArray as $row) {
        if($stmt_id->execute(array($row["item_name"]))){
            $sameItemList = $stmt_id->fetchAll();
            $num = count($sameItemList);
            $sql_sale = "SELECT sum(price*qty) FROM order_items WHERE item_id in (";
            $stmt_sale = $dbh->prepare($sql_sale);
            if($num > 1){
                $list ="";
                foreach($sameItemList as $item){
                    $list .= $item["item_id"];
                    $list .= ",";
                }
                $list = rtrim($list,",");
                //echo $row["item_name"]." -- ".$list."<br/>";
                $sql_sale .= $list;
                //$stmt_sale->execute(array($list));
            }else{
                //echo $row["item_name"]." -- ".$sameItemList[0]["item_id"]."<br/>";
                $sql_sale .= $sameItemList[0]["item_id"];
                //$stmt_sale->execute(array($sameItemList[0]["item_id"]));
            }
            $sql_sale .= ")";
            //echo $sql_sale."<br/>";
            $stmt_sale = $dbh->query($sql_sale);
            if($stmt_sale !== FALSE){
                $amount = (float)$stmt_sale->fetchColumn();                
                echo "<tr><td>".$row["item_name"]."</td><td>".$amount."</td></tr>";
            }else
                print_r($stmt_sale->errorInfo());
            
            $total += $amount;
            //exit;
        }else
            print_r($stmt_id->errorInfo());
        
    }
    echo "<tr><td>Total (not minus CRO yet)</td><td>".$total."</td></tr>";
}
//echo "</pre>";
echo "</table>";

?>
    </body>
</html>