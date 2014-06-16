<?php
include "../shared/user_check_right.php";

//database connection
include "../shared/dbconnect.php"; 

include "../shared/pagemanip.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="../shared/smeInventory.css" />
</head>
<body>
<?php
//get list of users and print it on screen
echo "<table >";
$sql = "SELECT count(uid) FROM sme_access";
$res = $dbh->query($sql);
if($res !== FALSE){
	$count = $res->fetchColumn();
	if($count > 0){
		$sql = "SELECT * FROM sme_access ORDER BY uid";
		$stmt = $dbh->query($sql);
		$result = $stmt->fetchAll();
		//pages manipulator calculation		
		$numPerPage = $_SESSION['numPerPage'] = 20;
		$currHead = pagemanip($result,$numPerPage);
		
		//show content in certain page
		print "<tr bgcolor='lightblue'><th>user id</th><th>user name</th><th>Order dealing</th><th>Inventory</th>
		<th>User Management</th><th>function</th></tr>";
		for($j=$currHead; $j < ($currHead+$numPerPage); $j++){
			if($result[$j] == NULL)
				break;
			if($count % 2 == 0)
  	    		print "<tr align='center'>";
  	    	else
  	    		print "<tr align='center' bgcolor='orange'>";
  			print "<td>" . $result[$j]['uid'] . "</td>";
  			print "<td>" . $result[$j]['username'] . "</td>";
			if($result[$j]['main_order'] == true){
				print "<td>Yes</td>";
			} else {
				print "<td>No</td>";
			}
			if($result[$j]['main_inventory'] == true){
				print "<td>Yes</td>";
			} else {
				print "<td>No</td>";
			}
			if($result[$j]['main_user'] == true){
				print "<td>Yes</td>";
			} else {
				print "<td>No</td>";
			}
			print "<td><a href='deleteuser.php?target=".$result[$j]['uid']."'>Delete</a></td>";
			print "</tr>";
			$count--;
		}
	} else {	//no user found
		print "No user found.<br/>";
	}
	unset($stmt);
	unset($result);
}
print "</table>";

//cleanup
unset($res);
unset($dbh);
?>
</body>
</html>