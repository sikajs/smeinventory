$(document).ready(function(){
    //default focus on barcode field
    $('#product').find('#barcode').focus();

    //use ajax to get item detail for each product
    $('.eachProdBC').live('keydown',function(event){
       if(event.which == 13){
          //get item detail and fill the fields
          var myParent = $(this);
          var prod = {code: $(this).val()};
	  
          $.getJSON("../ajax/getItemDetail.php", prod, function(result){
                    if(result == "no result"){
                        alert('Cannot find the product');
                    }else{
                        var target = myParent.parents('.eachProduct');
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
          event.preventDefault();
       }
    });

    //deal with modified qty
    $('.eachProduct').find('#orderQTY').live('keydown',function(event){
       if(event.which == 13){
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

    //calculate the discount
    $('#orig_discount').keydown(function(event){
       if(event.which == 13){
           var discounted = 0;
           discounted = Number($('#unTaxTotal').val()-$(this).val());
           $('#unTaxTotal').val(discounted);
           //event.preventDefault();
       }
    });

    //deal with the delete function
    $('.eachProduct').find('#delete').live('click',function(event){
        //alert($(this).parents('.eachProduct').attr('id'));
        if($(this).parents('.eachProduct').attr('id') != "product"){
           $(this).parents('.eachProduct').remove();
        }
    });

    //prevent all text field use enter to submit the form
    $('#returnOrder').find(':text').keydown(function(event){
        if(event.which == 13){
            event.preventDefault();
        }
    });
    
   
   //before submit the form, check first
   $('#returnOrder').submit(function(event){
       //alert($('#sub').val());
       var sub = $('#sub').val();
       if(sub != "confirm_next"){
           event.preventDefault();
       }
   });

});

function checkForm ( button )
{
  $("#returnOrder").attr('action', 'cro_act.php');
    //alert(button.name);
    if(button.name == "confirm_next")
        $('#sub').attr('value', 'confirm_next');
  $("#returnOrder").submit();
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
