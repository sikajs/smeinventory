<?php
include "shared/user_check_left.php";
unset($_SESSION['currpage']);	//for pagemanip
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel=stylesheet type="text/css" href="shared/smeInventory.css">
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.17.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    var icons = {
        header: "ui-icon-circle-arrow-e",
        headerSelected: "ui-icon-circle-arrow-s"
    };
    $( "#accordion" ).accordion({
        icons: icons
    });
    //$( "button, input:submit, a", ".demo" ).button();
});
</script>
</head>

<body>
<div class="ui-accordion" id="accordion">
  <h3 class="ui-accordion-header"><a href="#">Item management</a></h3>
  <div class="ui-accordion-content">
      <a href="inventory/itemlist.php" target="right">Item list</a>
      <p></p>
      <a href="inventory/newItem.php" target="right">New item</a>
      <p></p>
      <!--
      <a href="inventory/newPack.php" target="right">New pack</a>
      <p></p>
      <a href="inventory/packList.php" target="right">Pack list</a>
      <p></p>-->
      <a href="inventory/unitprice.php" target="right">Set price</a>
  </div>
  <h3 class="ui-accordion-header"><a href="#">Stock management</a></h3>
  <div class="ui-accordion-content">
      <a href="inventory/outStock.php" target="right">Outstock warning</a>
      <p></p>
      <a href="inventory/shoppingList.php" target="right">Shopping list</a>
      <p></p>
      <a href="inventory/restock.php" target="right">Restock item</a>
      <p></p>
      <a href="inventory/restock_hist.php" target="right">Restock history</a>
      <p></p>
      <a href="inventory/adjust_inv.php" target="right">Adjust inventory</a>
      <p></p>
      <a href="inventory/adjust_hist.php" target="right">Adjust history</a>
  </div>
  <h3 class="ui-accordion-header"><a href="#">Supplier management</a></h3>
  <div class="ui-accordion-content">
      <a href="inventory/supplier.php" target="right">Supplier detail</a>
  </div>
</div>
</body>
</html>
