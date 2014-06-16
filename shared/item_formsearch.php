<?php
/* files are using this 
	- itemlist.php
	- restock.php
	- adjust_inv.php
	- unitprice.php
*/
if($_POST['supplier'] != null){	//supplier is one of the condition(s)

	$sql = "SELECT suppliers.suppl_name, items.* ".
			 "FROM suppliers, items ";
	$sql = $sql."WHERE items.supplier_id='".$_POST['supplier']."' ";
	if($_POST['brand'] != null)
		$sql = $sql."AND items.brand='".strtoupper($_POST['brand'])."' ";
	if($_POST['itemName'] != null)
		//$sql = $sql."AND items.item_name='".$_POST['itemName']."' ";
		$sql = $sql."AND items.item_name like '%".$_POST['itemName']."%' ";
	if($_POST['prod_code'] != null)
		$sql = $sql."AND items.product_code='".$_POST['prod_code']."' ";
		
}elseif($_POST['brand'] != null){	//brand is one of the condition(s)

	$sql = "SELECT suppliers.suppl_name, items.* ".
			 "FROM suppliers, items ";
	$sql = $sql."WHERE items.brand='".strtoupper($_POST['brand'])."' ";
	if($_POST['itemName'] != null)
		$sql = $sql."AND items.item_name like '%".$_POST['itemName']."%' ";
	if($_POST['prod_code'] != null)
		$sql = $sql."AND items.product_code='".$_POST['prod_code']."' ";
		
}elseif($_POST['itemName'] != null){	//item name is one of the condition(s)

	$sql = "SELECT suppliers.suppl_name, items.* ".
			 "FROM suppliers, items ";
	$sql = $sql."WHERE items.item_name like '%".$_POST['itemName']."%' ";
	if($_POST['prod_code'] != null)
		$sql = $sql."AND items.product_code='".$_POST['prod_code']."' ";
		
}elseif($_POST['prod_code'] != null){	//product code is one of the condition(s)

	$sql = "SELECT suppliers.suppl_name, items.* ".
			 "FROM suppliers, items ";
	$sql = $sql."WHERE items.product_code='".$_POST['prod_code']."' ";
	
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


if($_GET['pagenum'] != NULL)	//used by pagemanip 
	$sql = $_SESSION['lastsql'];	
else	//finalize the sql statement generated 
	$sql = $sql."AND suppliers.supplier_id=items.supplier_id ".
					"ORDER BY items.item_name, items.brand, items.item_id ";

?>
