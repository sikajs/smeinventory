<?php
/* need to be used with session setted
	$result: selected result from database
	$numPerPage: the number of the stuff showed per page
*/

function pagemanip($result, $numPerPage ){
$totalRow = count($result);
if(($totalRow % $numPerPage) == 0)
	$totalPage = $totalRow / $numPerPage;
else
	$totalPage = ((int)($totalRow / $numPerPage)+1);

//set current page
if($_GET['pagenum'] != NULL)
	$_SESSION['currpage'] = $currPage = $_GET['pagenum'];

if($_SESSION['currpage'] == NULL)
	$_SESSION['currpage'] = $currPage = 1;
else
	$currPage = $_SESSION['currpage'];

//set the range of pages, gonna be 1,11,21...
if($_SESSION['startpage'] == NULL)
	$_SESSION['startpage'] = $startPage = 1; 
else {
	$startPage = $_SESSION['startpage'];
	if($currPage > ($startPage+9)){
		$startPage += 10;
		$_SESSION['startpage'] = $startPage;
	}
	if($currPage < $startPage){
		$startPage -= 10;
		$_SESSION['startpage'] = $startPage;
	}
}
if( ($startPage+9) > $totalPage)
	$maxPage = $totalPage;
else
	$maxPage = ($startPage+9);

//calculate the starting row of the result
$currHead = ($currPage-1) * $numPerPage;
	
//print out the page selector
print "<center>";
if($totalPage > 10){
	if($startPage == 1)
		print "<< ";
	else
		print "<a href='?pagenum=".($startPage-10)."'><<</a> ";
}
if($currPage <= 1)
	print "Prev ";
else
	print "<a href='?pagenum=".($currPage-1)."'>Prev</a> ";
for($i=$startPage;$i <= $maxPage;$i++){
	if($i == $currPage)
		print "<font color='red'>".$i."</font> ";
	else
		print "<a href='?pagenum=".$i."'>".$i."</a> ";
}
if($currPage >= $totalPage)
	print "Next";
else
	print "<a href='?pagenum=".($currPage+1)."'>Next</a> ";
if($totalPage > 10){
	if($maxPage < $totalPage)
		print " <a href='?pagenum=".($startPage+10)."'>>></a>";
	else
		print " >> ";
}
print "</center><br/>";

//return value
return $currHead;
}

?>