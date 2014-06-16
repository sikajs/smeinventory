<?php
include "../shared/user_check_right.php";
/* connect to database */
include "../shared/dbconnect.php";

?>
<!doctype html>
<head>
<link rel=stylesheet type="text/css" href="../shared/smeInventory.css">
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.17.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="../js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript">
$(function() {
	$(".cell").droppable({
		activeClass: "ui-state-hover",
		hoverClass: "ui-state-active",
	});
	$(".yarn").draggable({ revert: "invalid" });
});
</script>
</head>
<style>
.sNum
{
	width: 30px;
}
.shelf
{
	 
	float: left;
	border-style: dashed;
	border-width:1px;
}
.cell
{
	
	border-style: dotted;
	border-width:1px;
	float: left;
}
.yarn
{
	
	border-style: solid;
	border-radius: 10px;
	float: left;
	background-color: lightblue;
	
}
</style>
<body>
<div id="factor">
Total num of shelf
<input id="" class="sNum" type="text" value="3">

<br/>
Column
<input id="" class="sNum" type="text">
* Level
<input id="" class="sNum" type="text">
= Total 
<input id="" class="sNum" type="text">
cells.
<br/>
Max for each cell
<input id="" class="sNum" type="text">
<input id="confirm" type="button" value="confirm">
</div>
<hr>
<?php
$shelfNum = 3;
$column = 2;
$level = 6;
$maxInCell = 6;
 
for($i = 0; $i < $shelfNum ;$i++){
	echo "<div id='shelf".$i."' class='shelf'>";
	for($j = 0; $j < $level ;$j++){
		for($k = 0; $k < $column ;$k++){
			echo "<div id='cell".$i.$j.$k."' class='cell'>";
			for($roll = 0 ; $roll < $maxInCell; $roll++){
				echo "<div class='yarn'>";
				echo "<br/>S&C<br/>".$i.$j.($roll+1);
				echo "</div>";
			}
			echo "</div>";
		}
		echo "<br/>";
	}
	echo "</div>";
}
?>
<div>

</div>

</body>
</html>

