<?php
include "shared/user_check_left.php";
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
<body bgcolor="lightYellow">
<div class="ui-accordion" id="accordion">
  <h3 class="ui-accordion-header"><a href="#">Daily report</a></h3>
  <div class="ui-accordion-content">
      <a href="report/dailyOrders.php" target="right">Daily orders</a>
      <p></p>
      <a href="report/dailyNewItem.php" target="right">Daily new item</a>
      <p></p>
      <a href="report/dailyRestock.php" target="right">Daily restock</a>
  </div>
  <h3 class="ui-accordion-header"><a href="#">Whole report</a></h3>
  <div class="ui-accordion-content">
      <a href="report/newPeriod.php" target="right">Period orders</a>
      <p></p>
      <a href="report/showMth.php" target="right">Monthly report</a>
      <p></p>
      <a href="report/wholePic.php" target="right">Whole picture</a>
  </div>
  <h3 class="ui-accordion-header"><a href="#">Items report</a></h3>
  <div class="ui-accordion-content">
      <a href="report/itemActivity.php" target="right">Item activity</a>
      <p></p>
      <a href="report/items_Sold.php" target="right">Items sold</a>
      <p></p>
      <a href="report/top20.php" target="right">Top 20 popular products</a>
      <p></p>
      <a href="report/item_data.php" target="right">Item data</a>
      <p></p>
  </div>
</div>
</body>
</html>
