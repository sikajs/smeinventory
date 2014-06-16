<?php	//this file plans to let user search the old orders and look the detail of the order
include "../shared/user_check_right.php";

//database connection
include "../shared/dbconnect.php";

include "../shared/pagemanip.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
</head>
<body>
    <p class='blacktitle'>Order History</p>
    <form id="searchOrderForm" method="post" action="orderhist.php">
        Search by order comment:
        <input type="text" name="comment"></input>
        <input type="submit" value="search"></input>
    </form>
<?php
//get the list of orders and print it on screen
echo "<table border='1' class='table'>";
//pagemanip will have problem bcoz of the following part
if($_POST['comment'] != ''){
    $sql = "SELECT * FROM orders WHERE comment like '%".$_POST['comment']."%' ";
    unset($_SESSION['currpage']);	//for pagemanip
}else
    $sql = "SELECT * FROM orders ";
$sql .="ORDER BY order_id DESC";
//echo $sql."<br/>";
$_SESSION['lastsql']=$sql;
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
 * 
 */
$stmt = $dbh->query($sql);
if($stmt !== FALSE){	
	$result = $stmt->fetchAll();
	$count = count($result);
	if($count > 0){
		//pages manipulator calculation		
		$numPerPage = $_SESSION['numPerPage'] = 20;
		$currHead = pagemanip($result,$numPerPage);
				
		//show content in certain page
		print "<tr bgcolor='lightblue'><th>Order#</th><th>Date</th><th></th></tr>";
		for($j=$currHead; $j < ($currHead+$numPerPage); $j++){
			if($result[$j] == NULL)
				break;
			if($count % 2 == 0)
   	   	print "<tr align='right'>";
   	   else
   	   	print "<tr align='right' bgcolor='orange'>";
  			print "<td align='center'>".$result[$j]['order_id']."</td>";
  			print "<td align='center'>".substr($result[$j]['update_time'],0,11)."</td>";
  			print "<td><a href='orderdetail.php?target=".$result[$j]['order_id']."'>Detail</a></td>";
			print "</tr>";
			$count--;
		}
		
	} else {	//no order found
		print "No order found.<br/>";
	}
	unset($stmt);
	unset($result);
}	
print "</table>";		
//cleanup
unset($dbh);
?>
</body>
</html>
