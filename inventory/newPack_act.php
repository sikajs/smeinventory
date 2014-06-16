<?php
include "../shared/user_check_right.php";

/* connect database */
include "../shared/dbconnect.php";

$sql = "INSERT INTO packs (pack_name,pack_name_en,pack_category,pack_unitcost,est_stock) ".
       "VALUES ('".$_POST['packName']."','".$_POST['packNameEn']."','".$_POST['pack_category']."','".
       $_POST['unitCost']."','".$_POST['pStock']."')";
//echo $sql."<br/>";
try{
    if($dbh->exec($sql) === FALSE){
        throw new Exception('pack insertion failed.');
    }else{
        echo "New pack added. <a href='newPack.php'>Add another pack</a> or choose other function.";
    }
} catch (Exception $e){
    echo "<p class='warning'>".$e->getMessage()."</p>";
    print_r($dbh->errorInfo());
}



?>
