<?php
include_once "../shared/user_check_right.php";
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="dailyNewItem.csv"');
readfile('/tmp/dailyNewItem.csv');
?>
