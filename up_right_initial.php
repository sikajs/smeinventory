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
<link rel=stylesheet type="text/css" href="shared/smeInventory.css">
</head>
<body bgcolor="lightGrey">
    <center><h3> Welcome to smeInventory system <?php $sys_info->dispVersion();?></h3></center>
</body>
</html>
