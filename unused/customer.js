function getXmlHttpObject(){
	var xmlHttp=null;
	try	{
  		// Firefox, Opera 8.0+, Safari
  		xmlHttp=new XMLHttpRequest();
  	}
	catch (e) {
  		// Internet Explorer
  		try	{
    		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    	}
  		catch (e) {
    		try {
      			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      		}
    		catch (e) {
      			alert("Your browser does not support AJAX!");
      			return false;
      		}
    	}
  	}
	return xmlHttp;
}

function customerList(target){
	var xmlHttp=getXmlHttpObject();
	var custList=document.getElementById("customerList");
	var cust=document.getElementById("customer");
	var custDetail=document.getElementById("customerDetail");
	var url="custdetail.php"+"?CID="+target;
	xmlHttp.readystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
	
	custList.style.visibility = "hidden";
	cust.innerHTML = custDetail.innerHTML;
	custDetail.style.visibility = "visible";
}

function stateChanged() { 
	//if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") { 
 		document.getElementById("customerDetail").innerHTML=xmlHttp.responseText;
		alert("WOW");
	//} 
}
