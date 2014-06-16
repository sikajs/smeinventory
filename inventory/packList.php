<?php
include "../shared/user_check_right.php";
?>
<html>
<head>
<title></title>
</head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css" title="currentStyle">
        @import "../css/demo_table_jui.css";
        @import "../css/ui-lightness/jquery-ui-1.8.17.custom.css";
        @import "../shared/smeInventory.css";
</style>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" src="../js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" charset="UTF-8" >
$(document).ready(function() {
        $('#packListTable').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers"
        });
} );
</script>
<body>
<p class='blacktitle'>Pack List</p>
<table class='display' id='packListTable'>
<thead>
    <tr><th>Pack category</th><th>Pack name</th><th>Pack Eng name</th><th>Unit cost</th><th>Est. stock</th></tr>
</thead>
<tbody>
<?php
/* connect database */
include "../shared/dbconnect.php";

$sql = "SELECT * FROM packs ";
//echo $sql."<br/>";
try{
    $stmt = $dbh->query($sql);
    if($stmt === FALSE){
        throw new Exception('pack listing failed.');
    }else{

        foreach($stmt as $row){
            echo "<tr>";
            echo "<td>".$row['pack_category']."</td>";
            echo "<td>".$row['pack_name']."</td>";
            echo "<td>".$row['pack_name_en']."</td>";
            echo "<td>".$row['pack_unitcost']."</td>";
            echo "<td>".$row['est_stock']."</td>";
            echo "</tr>";
        }
        
    }
} catch (Exception $e){
    echo "<p class='warning'>".$e->getMessage()."</p>";
    print_r($dbh->errorInfo());
}
unset($stmt);
?>
</tbody></table>
</body>
</html>