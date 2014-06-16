<?php

//connect db
include "../shared/dbconnect.php";
// populate database for test
//if necessary then reset id by 'select setval([id name],1,false);' in database first

for ($i = 1; $i <= 20; $i++) {
	$sql = "insert into items (item_name,unit_cost,unit_price,stock,supplier_id,brand) values (?,?,?,5,1,'sika')";
	$sth = $dbh->prepare($sql);
	$sth->execute(array(test.$i,10*$i, 10*$i+5));

}

print "<table border='1'>";
foreach ($dbh->query('select * from items') as $row) {
   print "<tr>";
   print "<td>" . $row['item_id'] . "</td>";
   print "<td>" . $row['item_name'] . "</td>";
   print "<td>" . $row['unit_cost'] . "</td>";
   print "<td>" . $row['unit_price'] . "</td>";
   print "<td>" . $row['stock'] . "</td>";
   print "</tr>";
}
print "</table>";
//close db connection
$dbh = null;
?>
