<?php
/* provide database connection for all functions of smeInventory system 
 * using PDO */

$user = "smesys";
$pass = "Inventory#sys";
try{
  $dbh = new PDO('pgsql:host=localhost;dbname=smeinventory', $user, $pass);
  //  $dbh = new PDO('pgsql:host=localhost;dbname=test', $user, $pass);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

/*
$conn_string = "host=localhost port=5432 dbname=smeInventory user=smesys password=Tzopemike";
$dbconn = pg_connect($conn_string) or die("Could not connect"); 
*/
?>
