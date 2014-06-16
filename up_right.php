<?php
session_start();
include_once "shared/smeInv_sysinfo.php";

$sys_info = new smeInv_sysinfo();
?>
<html>
<head>
<title></title>
<script language="javascript" src="interface.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="css/ui-lightness/jquery-ui-1.8.17.custom.css">
</head>
<body bgcolor='lightblue'>
    <center><h3>Welcome to smeInventory system <?php $sys_info->dispVersion();?></h3></center>
    <div class="ui-widget">
<table width="100%" border="0" cellspacing="3" class="ui-menu">
	<tr align="center">
	<?php
		if($_SESSION['user_info']['main_order'] == 1){
			print "<td width='15%' id='order_func'>";
			print "<a href='order.php' name='orderLink' target='left' onclick='orderInit()'>Order dealing</a>";
      	print "</td>";
      } else {
      	print "<td width='15%' id='order_func'>Order dealing</td>";
      }
		if($_SESSION['user_info']['main_inventory'] == 1){
			print "<td width='15%' id='inventory_func'>";
			print "<a href='inventory.php' name='inventoryLink' target='left' onclick='invInit()'>Inventory</a>";
      	print "</td>";
      } else {
      	print "<td width='15%' id='inventory_func'>Inventory</td>";
      }
      if($_SESSION['user_info']['main_user'] == 1){
			print "<td width='15%' id='report_func'>";
			print "<a href='report.php' target='left' onclick='reportInit()'>Report</a>";
      	print "</td>";
			print "<td width='15%' id='sys_func'>";
			print "<a href='syssetup.php' target='left' onclick='setupInit()'>System setup</a>";
      	print "</td>";
      }			
      
	?>
      <td width="15%"><a href="manual/help.html" target="_blank">Help</a></td>
      <td width="15%"><a href="manual/about.html" target="_blank">About</a></td>
	<?php
		if($_SESSION['user_info'] != NULL){
			print "<td><a href='logout.php' onclick='downFrameReset()'>Logout:".$_SESSION['user_info']['username']."</a> </td>";
		}
	?>
   </tr>
</table>
        </div>
</body>
</html>
