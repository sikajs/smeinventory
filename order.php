<?php
include "shared/user_check_left.php";
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
<body bgcolor="lightBlue">
<div class="ui-accordion" id="accordion">
  <h3 class="ui-accordion-header"><a href="#">Order</a></h3>
  <div class="ui-accordion-content">
    <!--
    <a href="order/orderdeal.php" target="right">New Order</a>
    -->
    <a href="order/orderBar.php" target="right">New Order</a>
    <p></p>
    <a href="order/quickStock.php" target="right">Quick Stock Check</a>
    <p></p>
    <a href="order/cro.php" target="right">Return Order</a>
    
  </div>
  <h3 class="ui-accordion-header"><a href="#">History</a></h3>
  <div class="ui-accordion-content">
    <p></p>
    <a href="order/orderhist.php?pagenum=1" target="right">Order History</a>
    <p></p>
    <a href="order/crohist.php" target="right">CRO History</a>
  </div>
  <!--
  <h3 class="ui-accordion-header"><a href="#">Customer</a></h3>
  <div class="ui-accordion-content">
    <a href="order/customer.php" target="right">Customer Management</a>
  </div>-->
</div>
</body>
</html>
