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
<script type="text/javascript">

</script>
<body bgcolor="lightGrey">
<div class="ui-accordion" id="accordion">
  <h3 class="ui-accordion-header"><a href="#">User management</a></h3>
  <div class="ui-accordion-content">
      <a href="admin/adduser.php" target="right">Add User</a>
      <p></p>
      <a href="admin/userlist.php?pagenum=1" target="right">User list</a>
      <p></p>
      <a href="admin/changepass.php" target="right">Change password</a>
  </div>
  <h3 class="ui-accordion-header"><a href="#">Company management</a></h3>
  <div class="ui-accordion-content">
      <a href="admin/cominfo.php" target="right">Company information</a>
  </div>
</div>
</body>
</html>
