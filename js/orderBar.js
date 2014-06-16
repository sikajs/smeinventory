
$(document).ready(function(){
    $('#newOrder').validate();

    //default focus on barcode field
    $('#product').find('#barcode').focus();

    //use ajax to get item detail for each product
    $(document).on('keydown','.eachProdBC', function(event){    //updated for jquery1.7.1
    //$('.eachProdBC').live('keydown',function(event){
       if(event.which == 13){
          //get item detail and fill the fields
          var currProdBC = $(this);
          var prod = {code: $(this).val()};
	  
   	  var existFlag = 0;
	  var currBCVal = $(this).val();
	  
	  //check whether the item is in the cart or not, if yes, add 1 to qty
	  $('.eachProduct').each(function(){
		if($(this).find('#barcode').attr('value') == currBCVal && $(this).attr('id')!='product'){
		    existFlag = 1;
		    var oldQTY = Number($(this).find('#orderQTY').attr('value'));
		    //alert($(this).find('#barcode').attr('value'));
                    var newQTY = oldQTY+1;
                    //check current stock in the system with the required qty
                    $.getJSON("../ajax/getCurrStock.php", prod, function(result){
                        if(result == "no result"){
                            alert('Cannot find the product');
                        }else{
                            if(result['stock'] < newQTY){   //warn user if the current stock in system is lesser than the real situation
                                $('#confirm_next').attr('disabled','true');
                                $('#restock_now').removeAttr('disabled');
                                alert("Warning: current stock in system is lesser than required QTY, please check first.");
                            }
                        }
                    });
		    $(this).find('#orderQTY').attr('value',newQTY);
		    var subCal = 0;
		    subCal = Number($(this).find('#ProdPrice').val())*(newQTY);
		    $(this).find('#subCal').val(subCal);
		    $('#unTaxTotal').trigger('click', "");
		}
	    });
	  
	  if(!existFlag){  //new item, so get the detail of the item  
	    $.getJSON("../ajax/getItemDetail.php", prod, function(result){
                    if(result == "no result"){
                        alert('Cannot find the product');
                    }else{	//found item detail
			if(result['stock'] == 0){	//warn user if don't have stock in system currently
			    $('#confirm_next').attr('disabled','true');
                            $('#restock_now').removeAttr('disabled');
                            alert("Warning: no stock in system, please restock first.");
                        }
                        var target = currProdBC.parents('.eachProduct');
                        //alert(target.attr('id'));
                        target.find('#ProdID').attr('value', result['item_id']);
                        target.find('#ProdName').attr('value', result['item_name']);
                        target.find('#prodColor').replaceWith("<td width='120' id='prodColor'>"+result["color"]+"</td>");
                        target.find('#ProdPrice').attr('value', result['unit_price']);
                        target.find('#currCost').attr('value', result['unit_cost']);
                        target.find('#orderQTY').attr('value', '1');
                        var subCal = 0;
                        if(Number(target.find('#orderQTY').attr('value')) !=0)
                            subCal = Number(result["unit_price"]) * Number(target.find('#orderQTY').attr('value'));
                        target.find('#subCal').val(subCal);
                        addNewProd();
                        $('#unTaxTotal').trigger('click', "");
                    }
	    });
	  }else{  //item existed in the cart, so cleanup the content, wait for next input
	    $(this).val('');
	  }
          event.preventDefault();
       }
    });

    //deal with modified qty
    $('.eachProduct').find('#orderQTY').live('keydown',function(event){
       if(event.which == 13){
           //check current stock in the system with the required qty
           var prod = {code: $(this).parents('.eachProduct').find('#barcode').val()};
           //alert($(this).parents('.eachProduct').find('#barcode').val());
           var orderQTY = $(this).val();
           $.getJSON("../ajax/getCurrStock.php", prod, function(result){
               if(result == "no result"){
                   alert('Cannot find the product');
               }else{
                   if(result['stock'] < orderQTY){   //warn user if the current stock in system is lesser than the real situation
                       $('#confirm_next').attr('disabled','true');
                       $('#restock_now').removeAttr('disabled');
                       alert("Warning: current stock in system is lesser than required QTY, please check first.");
                   }
                }
                
           });
           var subCal = 0;
           var price = Number($(this).parents('.eachProduct').find('#ProdPrice').val());
           subCal = price * Number($(this).val());
           $(this).parents('.eachProduct').find('#subCal').val(subCal);
           $('#unTaxTotal').trigger('click', "");
           $('#product').find('#barcode').focus();
           event.preventDefault();
       }
    });

    //calculate untaxedTotal (sum of all subCal)
    $('#unTaxTotal').click(function(){
        var untaxTotal = 0;

        $('.eachProduct').find('#subCal').each(function(){
            untaxTotal = untaxTotal + Number($(this).val());
        });
        $('#unTaxTotal').val(untaxTotal);
    });

    //deal with the delete function
    $('.eachProduct').find('#delete').live('click',function(event){
        //alert($(this).parents('.eachProduct').attr('id'));
        if($(this).parents('.eachProduct').attr('id') != "product"){
           $(this).parents('.eachProduct').remove();
           $('#unTaxTotal').trigger('click', "");
        }
    });

    //calculate the discount
    $('#discount').keydown(function(event){
       if(event.which == 13){
           var discounted = 0;
           discounted = Number($('#unTaxTotal').val()-$(this).val());
           $('#unTaxTotal').val(discounted);
           event.preventDefault();
       }
    });

    //calculate the change
    $('#cash').keydown(function(event){
       if(event.which == 13){
           var change = 0;
           change = Number($(this).val()-$('#unTaxTotal').val());
           $('#change').val(change).hide(); //hide this field after changed value
           $('td em').html(change);
           $('td em').addClass('bigfont');
           event.preventDefault();
       }
    });

    //prevent all text field use enter to submit the form
    $('#newOrder').find(':text').keydown(function(event){
        if(event.which == 13){
            event.preventDefault();
        }
    });
    
   
   //before submit the form, check first
   $('#newOrder').submit(function(event){
       //alert($('#sub').val());
       var sub = $('#sub').val();
       if(sub != "confirm_next"){
           event.preventDefault();
       }
   });

});

function checkForm ( button )
{
  $("#newOrder").attr('action', 'orderBar_act.php');
  //alert(button.name);
  if(button.name == "confirm_next")
        $('#sub').attr('value', 'confirm_next');
  
  if($('#unTaxTotal').val() != ($('#cash').val()-$('#change').val())){
      alert('Cash received does not match with the expected total amount');
      $('#cash').focus();
  }else if($('#unTaxTotal').val() == 0){
      var answer = confirm('Proceed the order with no receiving cash?');
      if(answer){   //proceed anyway
          $("#newOrder").submit();
      }else{    //stop & check again
          $('#barcode').focus();
      }
  }else{
      $("#newOrder").submit();
  }
  
}

function addNewProd (){
    //alert("addNew");
    var lineNum = Number($('#product').find('#lineNum').html());
    var newLine = $('#product').clone();

    //add new empty line
    $('#product').after(newLine);
    $('#product').attr('id', lineNum);
    $('#product').find('#lineNum').html(lineNum+1);

    //reset the content of the new line
    $('#product').find('#barcode').attr('value',"");
    $('#product').find('#ProdID').attr('value',"");
    $('#product').find('#ProdName').attr('value',"");
    $('#product').find('#prodColor').html("");
    $('#product').find('#ProdPrice').attr('value',"0");
    $('#product').find('#currCost').attr('value',"0");
    $('#product').find('#orderQTY').attr('value',"0");
    $('#product').find('#subCal').attr('value',"0");
    
    //refocus on new product line
    $('#product').find('#barcode').focus();
}

function restockNow(){
    $('#restock_dialog').dialog({modal: true, position: [30,10]});
    $('#confirm_next').removeAttr('disabled');
    $('#restock_now').attr('disabled','true');
    
}