<?php
include "../shared/user_check_right.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript">
<!--
$(document).ready(function(){
    $('#color').keydown(function(event){
	if(event.which == 13){
	    var newCode = $('#iBarcode').val();
	    if($(this).val() != '')
	        newCode = newCode.substring(0,6) + $(this).val();
    	    else
	        newCode = newCode.substring(0,6) + '000';
	    $('#iBarcode').val(newCode);
	    event.preventDefault();
	}
    });

    $('#supplier').change(function(){
        var newCode;
        var suppl = {supplier : $('#supplier').val()};

	if($('#supplier').val().length < 2)
            newCode = '0'+ $('#supplier').val();
	else
	    newCode = $('#supplier').val();

	$.getJSON("../ajax/getMaxBarcode.php", suppl, function(result){
            if(result == "no result"){	//don't have any product for this supplier yet
		newCode = newCode + '0001';
            }else{
	        var nextProd = String(Number(result)+1);
		while(nextProd.length < 4)
		    nextProd = '0' + nextProd;
	        newCode = newCode + nextProd;
 	    }
            //alert(newCode);
	    $('#iBarcode').val(newCode);
	});

    });
    $('#supplier').trigger('change');


});

function checkform ( form )
{
  if (form.itemName.value == "") {
    alert( "Please enter the name of the item." );
    form.itemName.focus();
    return false ;
  }
  if (form.unitCost.value == "") {
    alert( "Please enter the cost of the item." );
    form.unitCost.focus();
    return false ;
  }  
  if (form.unitCost.value < 0) {
	alert( "The number you entered can not be negtive." );  
    form.unitCost.value = "";
    form.unitCost.focus();
    return false ;
  }
  if (form.unitPrice.value == "") {
    alert( "Please enter the price of the item." );
    form.unitPrice.focus();
    return false ;
  }    
  if (form.unitPrice.value < 0) {
    alert( "The number you entered can not be negtive." );
    form.unitPrice.value = "";
    form.unitPrice.focus();
    return false ;
  }
  if (form.supplier.value == "") {
    alert( "Please select the supplier of this item." );
    return false ;
  }
  if (form.iStock.value == "") {
    alert( "Please enter the initial number of the stock." );
    form.iStock.focus();
    return false ;
  }
   if (form.iStock.value < 0) {
    alert( "The number you entered can not be negtive." );
    form.iStock.value = "";
    form.iStock.focus();
    return false ;
  }
  return true ;
}
//-->
</script>
<style type="text/css">
   
</style>
</head>
<body>
<p class='blacktitle'>Add new item</p>

<form id='newitem' name="newitem" method="post" action="newItem_act.php" onsubmit='return checkform(this);'>
<table border="0">
    <tr>
      <td>Item name</td><td><input type="text" name="itemName" class="inputText" /></td>
      <td>Item Eng name</td><td><input type="text" name="itemNameEn" class="inputText" /></td>
      <!--<td>Brand</td><td><input type="text" name="brand" class="inputText" /></td>-->
    </tr>
    <tr>
      <td>Supplier</td>
      <td>
        <select id='supplier' name="supplier" class="inputText">
        <?php
        /* connect database */
        include "../shared/dbconnect.php";
        // get all supplier data and put them on the selection list 
        $sql = 'SELECT supplier_id,suppl_name FROM suppliers ORDER BY supplier_id';
        foreach ($dbh->query($sql) as $row) {
           print "<option value=\"".$row['supplier_id']."\">".$row['suppl_name']." (".$row['supplier_id'].")</option>";
        }
        $dbh = null;
        ?>
        </select>
      </td>
      <td>Color</td>
      <td><input type="text" name="color" id="color" class="inputText"/></td>
    </tr>
    <tr>
      <td>Unit cost</td><td><input type="text" name="unitCost" class="inputNum" /></td>
      <td>Unit price</td><td><input type="text" name="unitPrice" class="inputNum" /></td>
    </tr>
    <tr>
        <td>Initial stock</td><td><input type="text" name="iStock" class="inputNum" /></td>
        <td>Barcode</td><td><input type="text" id='iBarcode' name="iBarcode" class="inputText" /></td>
    </tr>
  
</table>
<input type="submit" value="Submit">
<input type="reset">
</form>

<p class='warning'>The suggested value of Barcode is for new product, you have to check it if the target item already existed and just a different color one. </p>
<p class='msg'>
The default status of new item will be active.<br/>
system will change brand name into UPPERCASE character automatically
</p>
</body>
</html>
