function noNegtive (target){
	if(target < 0) { 
		alert( "The number you entered can not be negtive." );
		return false;
	} else {
		return true;
	}
}
function hide(target) {
if (document.getElementById) { // DOM3 = IE5, NS6
	x = document.getElementById(target);
	//alert(x.innerHTML);  //debug statement
	x.style.visibility = 'hidden';
} else {
	if (document.layers) { // Netscape 4
		document.hideShow.visibility = 'hidden';
	}
	else { // IE 4
		document.all.hideShow.style.visibility = 'hidden';
	}
}
}
function show(target) {
if (document.getElementById) { // DOM3 = IE5, NS6
	x = document.getElementById(target);
	//alert(x.innerHTML);  //debug statement
	x.style.visibility = 'visible';
} else {
	if (document.layers) { // Netscape 4
		document.hideShow.visibility = 'visible';
	} else { // IE 4
		document.all.hideShow.style.visibility = 'visible';
	}
}
}
function nodisplayItem(target) {
if (document.getElementById) { // DOM3 = IE5, NS6
	y = document.getElementById(target);
	y.style.display = 'none'; //IE doesn't support 
} else {
	alert("Please upgrade your browser.");
	return false;
}
}
function displayItem(target) {
if (document.getElementById) { // DOM3 = IE5, NS6
	y = document.getElementById(target);
	y.style.display = 'table-row'; //IE doesn't support 
} else {
	alert("Please upgrade your browser.");
	return false;
}
}
function printout (){ //print out the page which called this function
	show('hideShow');
	show('invoice_vat');
	//show('invoice');
	displayItem('invoice');
	//hide('quote');
	nodisplayItem('quote');
	window.print();
}
function printquote (){ //print out the page which called this function
   hide('hideShow'); //hide company information
   hide('invoice_vat');
   //hide('invoice');
   nodisplayItem('invoice');
   //show('quote');
   displayItem('quote');
	window.print();
}