<?php
include_once "../shared/user_check_right.php";
//echo "<pre>";
//print_r($_POST);
//echo "<hr>";
$orderItems = array();
//rearrange item array
for($i=0; $_POST['ProdBC'][$i]!=NULL; $i++){
    if(array_key_exists($_POST['ProdBC'][$i],$orderItems)){	//already existed in array
        $orderItems[$_POST['ProdBC'][$i]]['qty'] += $_POST['orderQTY'][$i];
    }else{	//not in array yet
        if(($_POST['orderQTY'][$i] == 0) || ($_POST['currCost'][$i] == 0)) //check if it is empty item (qty equal to 0) or cost of the item equal to 0
            continue;
        else{
            $orderItems = $orderItems + array($_POST['ProdBC'][$i]=>array('id'=>$_POST['ProdID'][$i],
								      'qty'=>$_POST['orderQTY'][$i],
								      'price'=>$_POST['ProdPrice'][$i],
								      'cost'=>$_POST['currCost'][$i],
								      'margin'=>($_POST['ProdPrice'][$i]-$_POST['currCost'][$i]) 
								     ));
        }
    }
}
$_SESSION['cart'] = $orderItems;
//$_SESSION['previous_order'] = $orderItems;
//print_r($orderItems);
//print_r($_SESSION);
//echo "</pre>";
//exit();

//calculate the cost or entire order
$order_cost = 0;
for($i=0;$i<count($orderItems);$i++){
    $temp = each($orderItems);
    $order_cost += ($temp[value][cost] * $temp[value][qty]);
}
//echo $order_cost;
reset($orderItems);

/* connect database */
include_once "../shared/dbconnect.php";

//start an order record transaction to insert the detail of the order into database

$dbh->beginTransaction();

//insert data into order and get the order_id
$sql = "INSERT INTO orders (customer_id,discount,cash_received,change,order_cost,comment) values(".$_POST['Customer_Number'].
       ",".$_POST['discount'].",".$_POST['cash'].",".$_POST['change'].",".$order_cost.",'".$_POST['comment']."')";
//echo $sql;
$count = $dbh->exec($sql);
if($count == 1){
    $sql = "SELECT order_id FROM orders where customer_id='".$_POST['Customer_Number']."' AND update_time=transaction_timestamp()";
    $res = $dbh->query($sql);
    if($res != FALSE){
        $OID = $res->fetchColumn();
    }
}else{
    $dbh->rollback();
    echo "Failed to get order id";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    exit(1);
}
//insert the detail of the order (which item in the order) into order_items
$sql = "INSERT INTO order_items (order_id,item_id,qty,price,curr_cost,margin,barcode) values(".$OID.",?,?,?,?,?,?)";
//echo $sql;
$itemInsert = $dbh->prepare($sql);

//$numInCart = count($_POST['ProdBC'])-1;
$totalAmount = 0;
$numInCart = count($orderItems);
for($i=0; $i < $numInCart; $i++){
    $temp = each($orderItems);
    //$margin = $_POST['ProdPrice'][$i]-$_POST['currCost'][$i];
    //if(!$itemInsert->execute(array($_POST['ProdID'][$i],
    if(!$itemInsert->execute(array($temp[value][id],
				   $temp[value][qty],
				   $temp[value][price],
				   $temp[value][cost],
				   $temp[value][margin],
				   $temp[key]))){
        $dbh->rollback();
        echo "item ".$i." of the order insertion failed.<br/>";
        echo "<pre>";
        print_r($itemInsert->errorInfo());
        print_r($_SESSION);
        echo "</pre>";
        exit(1);
        //break;
    }else{
        $totalAmount += ($temp[value][price] * $temp[value][qty]);
    }
}
reset($orderItems);

//warn if the total amount didn't match with the cash received
$totalAmount = $totalAmount - (float)$_POST['discount'];
if($totalAmount != $_POST['unTaxTotal']){
    echo "<p>Warning : Total amount didn't match with cash received!</p>";
    echo $totalAmount." != ".$_POST['unTaxTotal'];
    exit(); //need to find better solution to deal with error
}
//updating stock of the items in the order
$sql_stock = "UPDATE items SET stock=? WHERE item_id=?";
$stock_update = $dbh->prepare($sql_stock);
for($i=0; $i < $numInCart; $i++){
    //$sql = "SELECT stock FROM items WHERE item_id=".$_POST['ProdID'][$i];
    $temp = each($orderItems);
    $sql = "SELECT stock FROM items WHERE item_id=".$temp[value][id];
    //echo $sql.",";
    $stmtCurr = $dbh->query($sql);
    $currStock = $stmtCurr->fetchColumn();
    //$newStock = $currStock - $_POST['orderQTY'][$i];
    $newStock = $currStock - $temp[value][qty];
    //echo $newStock."<br/>";
    //if(!$stock_update->execute(array($newStock,$_POST['ProdID'][$i]))){
    if(!$stock_update->execute(array($newStock,$temp[value][id]))){
        $dbh->rollback();
        echo "stock of item ".$i." in the order update failed.<br/>";
        echo "<pre>";
        print_r($temp[value]);
        print_r($itemInsert->errorInfo());
        print_r($_SESSION);
        echo "</pre>";
        exit(1);
        //break;
    }
}

//commit and finished transaction
$dbh->commit();
unset($orderItems);
$where = "Location: orderBar.php";
header($where);
?>
