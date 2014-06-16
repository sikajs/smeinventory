<?php
include "../shared/user_check_right.php";

/* connect database */
include "../shared/dbconnect.php";

/* search database and compare with the input
 * if not found then insert into database else reject and report error to user */
$sql = "select count(*) from items where supplier_id='".$_POST['supplier'].
       "' AND brand='".strtoupper($_POST['brand'])."' AND item_name='".$_POST['itemName']."'";
       //print $sql;
if($res = $dbh->query($sql)){
	$count = $res->fetchColumn();
	if($count > 0){    //item existed, ask user to update stock only
		print "Item from the same supplier already existed in database, please use the 'Restock' function to update stock<br/>";
	} else {	
		//check if same item exist		unfinished
		/*
		$sql_check = "select item_id FROM items WHERE brand='".strtoupper($_POST['brand'])."' AND item_name='".$_POST['itemName']."'";
		$res_chk = $dbh->query($sql_check);
		if($res_chk->fetchAll() > 0){	//same item existed
		    $sql_update = "UPDATE items set 
		}
		*/

		//item did not exist, run insertion procedure
		$sql = "insert into items (brand,item_name_en,item_name,unit_cost,unit_price,stock,supplier_id,barcode,color,initial_stock) values ('','".
		       $_POST['itemNameEn']."','".$_POST['itemName']."','".$_POST['unitCost']."','".$_POST['unitPrice'].
		       "','".$_POST['iStock']."','".$_POST['supplier']."','".$_POST['iBarcode']."','".$_POST['color'].
                       "','".$_POST['iStock']."')";
		/*
		$sql = "insert into items (brand,item_name,unit_cost,unit_price,stock,supplier_id,barcode,color) values ('".
		       strtoupper($_POST['brand'])."','".$_POST['itemName']."','".$_POST['unitCost']."','".$_POST['unitPrice'].
		       "','".$_POST['iStock']."','".$_POST['supplier']."','".$_POST['iBarcode']."','".$_POST['color']."')";
		*/
//echo $sql;
		$sth = $dbh->prepare($sql);
		if($sth->execute())
			print "Item inserted! You can <A HREF='newItem.php' target='right'>add next item</A><br/> or choose other function.";
		else{
                    print "Something is wrong! The item cannot be added.<br/>";
                    print_r($sth->errorInfo());
                }
	}
}

//cleanup and close db connection
$res = null;
$dbh = null;
?>
