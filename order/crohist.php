<?php
include "../shared/user_check_right.php";
//database connection
include "../shared/dbconnect.php";
$sql = "SELECT * FROM cro ";
       
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CRO history</title>
<style type="text/css" title="currentStyle">
        @import "../css/demo_table_jui.css";
        @import "../css/ui-lightness/jquery-ui-1.8.17.custom.css";
        @import "../shared/smeInventory.css";
</style>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" >
$(document).ready(function() {
        $('#croListTable').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers"
        });
} );
</script>
</head>
<body>
    <p class='blacktitle'>CRO History</p>
    <table id='croListTable' border='1' class='table display'>
        <thead>
        <tr><th>Return date</th><th>CRO ID</th><th>Total amount</th><th>Original discount</th></tr>
        </thead>
        <tbody>
        <?php
        $stmt = $dbh->query($sql);
        foreach($stmt as $row){
            echo "<tr align='center'>";
            echo "<td>".date('Y-m-d',  strtotime($row['return_time']))."</td>";
            echo "<td>";
            echo "<a href='cro_detail.php?croid=".$row['cro_id']."'>".$row['cro_id']."</a>";
            echo "</td>";
            echo "<td>".$row['return_total']."</td>";
            echo "<td>".$row['orig_discount']."</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</body>
</html>