<?php
include "../shared/user_check_right.php";
$_SESSION['lastPage'] = $_SERVER['REQUEST_URI'];
include_once('../shared/dbconnect.php');
$today = date('Y-m-d');

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Return_order</title>
<link href="../shared/smeInventory.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    input.eachOrderQTY {width: 50px;}
    input.eachProdDis {width: 50px;}
    input.eachProdPrice {width: 50px;}
    input.eachSubCal {width: 60px;}
</style>

<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/cro.js"></script>
</head>
<body >
<?php
/*
echo "<pre>";
print_r($_POST);
//echo "<br/><br/>";
//print_r($_SESSION);
//echo "<br/><br/>";
//  print_r($_SERVER);
//  print_r($result);
echo "</pre>";
 *
 */
?>
<form name="returnOrder" id="returnOrder" method="post" action="">

<table width="746" border="0" align="left" cellspacing="1" class="table" id="new">
  <tr>
    <th colspan="5" align="left" bgcolor="#FFCC00" class="titlee" scope="col">Customer Return Order</th>
  </tr>
  <tr>
    <td width="113" bgcolor="#CCCCCC">Order Date</td>
    <td width="204"><?=$today;?></td>
    <td width="28">&nbsp;</td>
    <td width="130" align="left" bgcolor="#CCCCCC">Customer Name</td>
    <td width="180" id="custName">
        <input type="text" name="Customer_Name" id="Customer_Name" value="General Customer" readonly="readonly" />
    </td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC">Order No</td>
    <td><input type="text" name="order_Num" id="order_Num" readonly="readonly"/></td>
    <td>&nbsp;</td>
    <td align="left" bgcolor="#CCCCCC">Customer No</td>
    <td><input type="text" name="Customer_Number" id="Customer_Number" value="1" readonly="readonly" /></td>
  </tr>
    
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<br/>
<hr width="100%" />
<table width="1000" border="1" align="left" cellspacing="0" class="table" id="orderTable">
  <tr>
    <th colspan="15" align="left" bgcolor="#FFCC00" scope="col"><span class="titlee">Item in return</span></th>
  </tr>
  <tr>
    <th width="15" bgcolor="#CCCCCC" scope="row">&nbsp;</th>
    <td width="168" align="center" bgcolor="#CCCCCC">Barcode</td>
    <!--<td align="center" bgcolor="#CCCCCC"> Item ID</td>-->
    <td width="168" align="center" bgcolor="#CCCCCC">Item Name</td>
    <td width="120" align="center" bgcolor="#CCCCCC">Color</td>
    <td width="60" align="center" bgcolor="#CCCCCC">Unit Price</td>
    <td width="60" align="center" bgcolor="#CCCCCC">QTY</td>
    <!--
    <td width="60" align="center" bgcolor="#CCCCCC">Discount(%)</td>
    <td width="60" align="center" bgcolor="#CCCCCC">Discounted Price</td>
    -->
    <td width="60" align="center" bgcolor="#CCCCCC">Sub Total</td>
    <!--<td width="90" align="center" bgcolor="#CCCCCC">Stock</td>
    <td width="168" align="center" bgcolor="#CCCCCC">Remark</td>-->
    <td width="100" bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
  <tr class="eachProduct" id="product">
    <th align="center" scope="row" id="lineNum">1</th>
    <td width="168"><input type="text" class='eachProdBC' name="ProdBC[]" id="barcode"/></td>
    <td width="168">
        <input type="hidden" name="ProdID[]" id="ProdID" readonly="readonly"/>
        <input type="text" name="ProdName[]" id="ProdName" readonly="readonly"/>
    </td>
    <td width="120" id="prodColor">    </td>
    <td id="prodSalePrice"><input type="text" class="eachProdPrice" name="ProdPrice[]" id="ProdPrice" readonly="readonly"/>
    <input type='hidden' name='currCost[]' id='currCost' value='0'/>
    </td>
    <td width="60"><input type="text" class='eachOrderQTY' name="orderQTY[]" id="orderQTY" value="0"/></td>
    <td width="60"><input type="text" class='eachSubCal' name="subCal[]" id="subCal" value="0" readonly="readonly"/></td>
    <td align="center">
    <span class="button">
      <input type="submit" name="delete" id="delete" value="Del"/>
    </span></td>
  </tr>
  <tr>
    <td colspan="6" align="right" scope="row">Original discount (Baht)</td>
    <td colspan="5"><input type="text" name="orig_discount" id="orig_discount" value="0"/></td>
  </tr>
  <tr>
    <!--<th colspan="2" align="center" scope="row"><span class="button">
      <input type="submit" name="addProduct" id="addProduct" value="Add more" />
    </span></th>-->
    <td colspan="6" align="right" scope="row">Total</td>
    <td colspan="5"><input type="text" name="unTaxTotal" id="unTaxTotal" value="0" readonly="readonly"/></td>
  </tr>

  <!--
  <tr>
    <td colspan="6" align="right" scope="row">Tax(%)</td>
    <td colspan="5"><input type="text" name="Tax_Rate" id="Tax_Rate" value="0"/></td>
  </tr>
  <tr>
    <td colspan="6" align="right" scope="row">Total</td>
    <td colspan="5"><input type="text" name="taxedTotal" id="taxedTotal" value="0" readonly="readonly"/></td>
  </tr>-->

</table>


<input type="hidden" name="sub" id="sub" value=""/>
</form>
<table width="746" border="0" align="left" cellspacing="1" class="table">
  <tr>
    <td colspan="2" bgcolor="#FFFFFF"><span class="button">
      <input type="submit" name="confirm_next" id="confirm_next" value="Confirm & next order" onclick="checkForm(this)"/>
      <input type="reset" name="reset" id="reset" value="Reset" onclick="document.forms['returnOrder'].reset()"/>
    </span></td>
  </tr>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
