function urFrameReset(){
	window.parent.parent.upFrame.right.location.href = "up_right.php";
}
function drFrameReset(){
	window.parent.parent.downFrame.right.location.href = "right.php";
}
function downFrameReset(){
	window.parent.parent.downFrame.right.location.href = "right.php";
	window.parent.parent.downFrame.left.location.href = "left.php";
}
function orderInit(){
    window.parent.parent.downFrame.right.location.href = "order/orderBar.php";
}
function invInit(){
    window.parent.parent.downFrame.right.location.href = "inventory/itemlist.php";
}
function reportInit(){
    window.parent.parent.downFrame.right.location.href = "report/dailyOrders.php";
}
function setupInit(){
    window.parent.parent.downFrame.right.location.href = "admin/userlist.php";
}