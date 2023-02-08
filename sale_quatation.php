<?php include("includes/header.php");?>
<script type="text/javascript">

$(document).ready(function(){
$("#item").focus();
$("#item").keypress(function(event) {
	if(event.keyCode == 13) {
	  $("#item").blur();
      event.preventDefault();
	  $("#item").focus();
      return false;
    }
        
    });
})
function quantityupdate(data){
	//alert($("#" + data+'q').val()+' '+$("#" + data+'my_tot').val()+' '+$("#" + data+'to').val())
	pq = $("#" + data+'q').val();
	ps = $("#" + data+'s').val();
	
	if($("#" + data+'q').val()===''){
		$("#" + data+'to').val('0');
		if($("#" + data+'my_tot').val()===document.getElementById('grand_total').value){
			document.getElementById('grand_total').value = '0';					
			document.getElementById('main_grand_total').value = '0';
			document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
			
			if(document.getElementById('tax').value==''){
				document.getElementById('tax').value='0';
			}
			document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
			document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
			
			
			$("#" + data+'my_tot').val('0');
		}else{
			z = document.getElementById('grand_total').value - $("#" + data+'my_tot').val();
			document.getElementById('grand_total').value = z;					
			document.getElementById('main_grand_total').value = z;
			document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
			
			if(document.getElementById('tax').value==''){
				document.getElementById('tax').value='0';
			}
			document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
			document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
			
			
			$("#" + data+'my_tot').val('0');
		}
	}else{
		x = pq * ps;
		$("#" + data+'to').val(x);
		
			if($("#" + data+'my_tot').val()<$("#" + data+'to').val()){
				
				y = $("#" + data+'to').val() - $("#" + data+'my_tot').val();
				document.getElementById('grand_total').value = parseFloat(document.getElementById('grand_total').value) + parseFloat(y);					
				document.getElementById('main_grand_total').value = parseFloat(document.getElementById('grand_total').value);
				document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
				
				if(document.getElementById('tax').value==''){
				document.getElementById('tax').value='0';
				}
				document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
				document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
				
				
				$("#" + data+'my_tot').val(x);
			}
			else if($("#" + data+'my_tot').val()>$("#" + data+'to').val()){
				y =  $("#" + data+'my_tot').val() - $("#" + data+'to').val();
				document.getElementById('grand_total').value = parseFloat(document.getElementById('grand_total').value) - parseFloat(y);					
				document.getElementById('main_grand_total').value = parseFloat(document.getElementById('grand_total').value);
				document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
				if(document.getElementById('tax').value==''){
				document.getElementById('tax').value='0';
				}
				document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
				document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
				$("#" + data+'my_tot').val(x);
			}
	}

}
function discountupdate(data){
	pq = $("#" + data+'q').val();
	ps = $("#" + data+'s').val();
	dis1 = $("#" + data+'d').val();
	dis = ReplaceContentInContainer('discount');
	
	net = pq * ps;
	
	if(dis!=''){
		t2 = net - dis;
		t3 = net - dis1;
		$("#" + data+'to').val(t3);

		g = $("#grand_total").val();
		
		if(g==net){
			$("#main_grand_total").val(t2);
			document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
			if(document.getElementById('tax').value==''){
			document.getElementById('tax').value='0';
			}
			document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
			document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
			
		}else{
			gh = g - net;
			tgh = parseFloat(gh) + parseFloat(t2);
			$("#main_grand_total").val(tgh);
			document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
			if(document.getElementById('tax').value==''){
			document.getElementById('tax').value='0';
			}
			document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
			document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
		}
		
		
	}else{
		$("#" + data+'to').val(net);
		$("#main_grand_total").val($("#grand_total").val());
		document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
		if(document.getElementById('tax').value==''){
		document.getElementById('tax').value='0';
		}
		document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
		document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
		
	}
}
function priceupdate(data){
	
	pq = $("#" + data+'q').val();
	ps = $("#" + data+'s').val();
	
	if($("#" + data+'s').val()===''){
		$("#" + data+'to').val('0');
		if($("#" + data+'my_tot').val()===document.getElementById('grand_total').value){
			document.getElementById('grand_total').value = '0';					
			document.getElementById('main_grand_total').value = '0';
			document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
			
			if(document.getElementById('tax').value==''){
				document.getElementById('tax').value='0';
			}
			document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
			document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
			
			
			$("#" + data+'my_tot').val('0');
		}else{
			z = document.getElementById('grand_total').value - $("#" + data+'my_tot').val();
			document.getElementById('grand_total').value = z;					
			document.getElementById('main_grand_total').value = z;
			document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
			
			if(document.getElementById('tax').value==''){
				document.getElementById('tax').value='0';
			}
			document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
			document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
			
			
			$("#" + data+'my_tot').val('0');
		}
	}else{
		x = pq * ps;
		$("#" + data+'to').val(x);
		
			if($("#" + data+'my_tot').val()<$("#" + data+'to').val()){
				
				y = $("#" + data+'to').val() - $("#" + data+'my_tot').val();
				document.getElementById('grand_total').value = parseFloat(document.getElementById('grand_total').value) + parseFloat(y);					
				document.getElementById('main_grand_total').value = parseFloat(document.getElementById('grand_total').value);
				document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
				
				if(document.getElementById('tax').value==''){
				document.getElementById('tax').value='0';
				}
				document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
				document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
				
				
				$("#" + data+'my_tot').val(x);
			}
			else if($("#" + data+'my_tot').val()>$("#" + data+'to').val()){
				y =  $("#" + data+'my_tot').val() - $("#" + data+'to').val();
				document.getElementById('grand_total').value = parseFloat(document.getElementById('grand_total').value) - parseFloat(y);					
				document.getElementById('main_grand_total').value = parseFloat(document.getElementById('grand_total').value);
				document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
				if(document.getElementById('tax').value==''){
				document.getElementById('tax').value='0';
				}
				document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
				document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
				$("#" + data+'my_tot').val(x);
			}
	}
	
}
function ReplaceContentInContainer(dis) {
var x = 0;
var cusid_ele = document.getElementsByClassName(dis);
for (var i = 0; i < cusid_ele.length; ++i) {
    var item = cusid_ele[i].value;
	if(item!=''){
		x = parseFloat(x) + parseFloat(item);
	}
}
return x;
}

function pay_type(val){
	if(val!='' || val!='cash'){
		if(val=='bkash'){
				$('#card').closest("tr").remove();
				$('#giftcard').closest("tr").remove();
				$('<tr id="'+val+'"><td>Transaction Id</td><td><input type="text" style="width:120px" value="" name="'+val+'"/></td></tr>').fadeIn("slow").appendTo('#pay_what');
				
		}else if(val=='giftcard'){
			$('#card').closest("tr").remove();
			$('#bkash').closest("tr").remove();
			$('<tr id="'+val+'"><td>Gift Card Number</td><td><input type="text" style="width:120px" value="" name="'+val+'"/></td></tr>').fadeIn("slow").appendTo('#pay_what');
		}else if(val=='card'){
			$('#bkash').closest("tr").remove();
			$('#giftcard').closest("tr").remove();
			$('<tr id="'+val+'"><td>Card Number</td><td><input type="text" style="width:120px" value="" name="'+val+'"/></td></tr>').fadeIn("slow").appendTo('#pay_what');
		}
		else{
			$('#card').closest("tr").remove();
			$('#bkash').closest("tr").remove();
			$('#giftcard').closest("tr").remove();
		}
	}else{
		$('#card').closest("tr").remove();
		$('#bkash').closest("tr").remove();
		$('#giftcard').closest("tr").remove();
	}
}
				
</script>
    <script type="text/javascript">
        $(function () {

            $("#supplier").autocomplete("customer1.php", {
                width: 250,
                autoFill: true,
                selectFirst: true
            });

            $("#item").autocomplete("stock_purchse.php", {
                width: 432,
                autoFill: true,
                mustMatch: false,
                selectFirst: true
            });
			
	$("#item").result(function(event, data, formatted){
		$("#item").blur();
		event.preventDefault();
		$("#item").focus();		
	});

    $("#item").blur(function () 
    {
		
		
		reg = $("#register_type").val();
		vall = $(this).val();
		var res = vall.split("<>");
		nval= res[0]+'&'+res[1];
		$.post('check_item_details.php', {stock_name1: nval, reg:reg},
                    function (data) {

                        if (data.sell != undefined){
							code = data.name;
							product_code = data.code;
                            quty = 1;
                            sell = data.sell;
                            disc = data.stock;
                            total = data.sell*1;
                            item = data.guid;
                            main_total = data.sell*1;
							if ($("#" + item).length > 0)
							{
								
								eq = parseFloat($("#" + item+'q').val())+parseFloat('1');
								etotal = parseFloat(data.sell) + parseFloat($("#" + item+'my_tot').val());
								
								if($("#" + item+'d').val()==''){
									$("#" + item+'d').val('0')
								}
								
								distotal = parseFloat(etotal) - parseFloat($("#" + item+'d').val());
								$("#" + item+'q').val(eq);
								$("#" + item+'to').val(distotal);
								$("#" + item+'my_tot').val(etotal);
								
								document.getElementById('grand_total').value = parseFloat(document.getElementById('grand_total').value) + parseFloat(data.sell);
								
								
								document.getElementById('main_grand_total').value = parseFloat(document.getElementById('main_grand_total').value) + parseFloat(data.sell);
								document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
								
								if(document.getElementById('tax').value==''){
									document.getElementById('tax').value='0';
								}
								
								document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
								document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
								
								
								//if($("#" + item+'p').val()<)
								//alert($("#" + item+'st').val()+' '+$("#" + item+'q').val()+' '+$("#" + item+'s').val()+' '+$("#" + item+'p').val()+' '+$("#" + item+'to').val());
								$("#item").val('');
							}else{
									$('<tr bgcolor="#f3f3f3" id=' + item + '><td class="center tdpad"><input type=hidden value=' + product_code + ' name="pcode[]" ><input type=hidden value=' + item + ' id=' + item + 'id ><input type=button value="Delete" id=' + item + ' onclick=reduce_balance("' + item + '");$(this).closest("tr").remove(); ></td><td class="center tdpad"><input name="stock_name[]" id=' + item + 'st value="'+ code +'" class="center tdpad" style="border:none" type="text"/></td><td class="center tdpad"><input onkeypress="return numbersonly(event);" onkeyup=quantityupdate("' + item + '") name=quty[] value=' + quty + ' id=' + item + 'q class="center tdpad" size="5" type="text"/></td><td class="center tdpad"><input name=sell[] value=' + sell + ' id=' + item + 's onkeyup=priceupdate("' + item + '") class="center tdpad" size="5" type="text"/></td><td class="center tdpad"><input onkeypress="return numbersonly(event);" onkeyup=discountupdate("' + item + '"); name=discount_prod[] value="" id=' + item + 'd class="center tdpad discount" size="5" type="text"/></td><td class="center tdpad"><input name=stock[] readonly="readonly" value=' + disc + ' id=' + item + 'p class="center tdpad" size="5" type="text"/></td><td class="center tdpad"><input name=jibi[] readonly="readonly" value=' + total + ' id=' + item + 'to class="center tdpad" size="8" type="text"/><input type=hidden name=total[] id=' + item + 'my_tot value=' + main_total + '></td></tr>').fadeIn("slow").appendTo('#item_copy_final');
								$("#item").val('');
								$('#empty').hide();
								if (document.getElementById('grand_total').value == "") {
                                document.getElementById('grand_total').value = main_total;
								document.getElementById('main_grand_total').value = main_total;
								document.getElementById('total_amount').value = main_total;
								} else {
									document.getElementById('main_grand_total').value = parseFloat(document.getElementById('main_grand_total').value) + parseFloat(main_total);
									document.getElementById('grand_total').value = parseFloat(document.getElementById('grand_total').value) + parseFloat(main_total);
									document.getElementById('total_amount').value = document.getElementById('main_grand_total').value;
								}
								if(document.getElementById('tax').value=='' || document.getElementById('tax').value==0){
									document.getElementById('tax').value=0;
									
								}
								document.getElementById('tax_amount').value = (parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
								
								document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value)+(parseFloat(document.getElementById('main_grand_total').value)*parseFloat(document.getElementById('tax').value)/100);
								
							}

							
						}
                        
					}, 'json');
		
    });
	
			
 /*           $("#item").blur(function () {
                document.getElementById('total').value = document.getElementById('sell').value * document.getElementById('quty').value
            });
            $("#item").blur(function () {


                $.post('check_item_details.php', {stock_name1: $(this).val()},
                    function (data) {

                        $("#sell").val(data.sell);
                        $("#stock").val(data.stock);
                        $('#guid').val(data.guid);
                        if (data.sell != undefined)
                            $("#0").focus();


                    }, 'json');


            });*/
            $("#supplier").result(function(event, data, formatted)
			{
                $.post('check_customer_details1.php', {stock_name1: $(this).val()},
                    function (data) {

                        $("#address").val(data.address);
                        $("#contact1").val(data.phone);

                        if (data.address != undefined)
                            $("#0").focus();

                    }, 'json');


            });



            var hauteur = 0;
            $('.code').each(function () {
                if ($(this).height() > hauteur) hauteur = $(this).height();
            });

            $('.code').each(function () {
                $(this).height(hauteur);
            });
        });

    </script>
    <script>
        /*$.validator.setDefaults({
         submitHandler: function() { alert("submitted!"); }
         });*/

        function numbersonly(e) {
            var unicode = e.charCode ? e.charCode : e.keyCode
            if (unicode != 8 && unicode != 46 && unicode != 37 && unicode != 27 && unicode != 38 && unicode != 39 && unicode != 40 && unicode != 9) { //if the key isn't the backspace key (which we should allow)
                if (unicode < 48 || unicode > 57)
                    return false
            }
        }


    </script>
    <script type="text/javascript">
        function remove_row(o) {
            var p = o.parentNode.parentNode;
            p.parentNode.removeChild(p);
        }
        function add_values() {
            if (unique_check()) {

                if (document.getElementById('edit_guid').value == "") {
                    if (document.getElementById('item').value != "" && document.getElementById('quty').value != "" && document.getElementById('total').value != "") {

                        if (document.getElementById('quty').value != 0) {
                            code = document.getElementById('item').value;

                            quty = document.getElementById('quty').value;
                            sell = document.getElementById('sell').value;
                            disc = document.getElementById('stock').value;
                            total = document.getElementById('total').value;
                            item = document.getElementById('guid').value;
                            main_total = document.getElementById('posnic_total').value;

                            $('<tr id=' + item + '><td><input type=hidden value=' + item + ' id=' + item + 'id ><input type=text name="stock_name[]"  id=' + item + 'st style="width: 150px" class="form-control" ></td><td><input type=text name=quty[] readonly="readonly" value=' + quty + ' id=' + item + 'q class="form-control" style="width:100px" ></td><td><input type=text name=sell[] readonly="readonly" value=' + sell + ' id=' + item + 's class="form-control" style="width:100px"  ></td><td><input type=text name=stock[] readonly="readonly" value=' + disc + ' id=' + item + 'p class="form-control" style="width:100px" ></td><td><input type=text name=jibi[] readonly="readonly" value=' + total + ' id=' + item + 'to class="form-control" style="width: 120px;margin-left:20px;text-align:right;" ><input type=hidden name=total[] id=' + item + 'my_tot value=' + main_total + '> </td><td><input type=button value="" id=' + item + ' style="width:30px;border:none;height:30px;background:url(images/edit_new.png)" class="round" onclick="edit_stock_details(this.id)"  ></td><td><input type=button value="" id=' + item + ' style="width:30px;border:none;height:30px;background:url(images/close_new.png)" class="round" onclick=reduce_balance("' + item + '");$(this).closest("tr").remove(); ></td></tr>').fadeIn("slow").appendTo('#item_copy_final');
                            document.getElementById('quty').value = "";
                            document.getElementById('sell').value = "";
                            document.getElementById('stock').value = "";
                            document.getElementById('total').value = "";
                            document.getElementById('item').value = "";
                            document.getElementById('guid').value = "";
                            if (document.getElementById('grand_total').value == "") {
                                document.getElementById('grand_total').value = main_total;
                            } else {
                                document.getElementById('grand_total').value = parseFloat(document.getElementById('grand_total').value) + parseFloat(main_total);
                            }
                            document.getElementById('main_grand_total').value = parseFloat(document.getElementById('grand_total').value);
                            document.getElementById(item + 'st').value = code;
                            document.getElementById(item + 'to').value = total;
                        } else {
                            alert('No Stock Available For This Item');
                        }
                    } else {
                        alert('Please Select An Item');
                    }
                } else {
                    id = document.getElementById('edit_guid').value;
                    document.getElementById(id + 'st').value = document.getElementById('item').value;
                    document.getElementById(id + 'q').value = document.getElementById('quty').value;
                    document.getElementById(id + 's').value = document.getElementById('sell').value;
                    document.getElementById(id + 'p').value = document.getElementById('stock').value;
                    document.getElementById('grand_total').value = parseFloat(document.getElementById('grand_total').value) + parseFloat(document.getElementById('posnic_total').value) - parseFloat(document.getElementById(id + 'my_tot').value);
                    document.getElementById('main_grand_total').value = parseFloat(document.getElementById('grand_total').value);
                    document.getElementById(id + 'to').value = document.getElementById('total').value;
                    document.getElementById(id + 'id').value = id;

                    document.getElementById(id + 'my_tot').value = document.getElementById('posnic_total').value
                    document.getElementById('quty').value = "";
                    document.getElementById('sell').value = "";
                    document.getElementById('stock').value = "";
                    document.getElementById('total').value = "";
                    document.getElementById('item').value = "";
                    document.getElementById('guid').value = "";
                    document.getElementById('edit_guid').value = "";
                }
            }
            discount_amount();
        }
        function total_amount() {
            balance_amount();

            document.getElementById('total').value = document.getElementById('sell').value * document.getElementById('quty').value
            document.getElementById('posnic_total').value = document.getElementById('total').value;
            //  document.getElementById('total').value = '$ ' + parseFloat(document.getElementById('total').value).toFixed(2);
            if (document.getElementById('item').value === "") {
                document.getElementById('item').focus();
            }
        }
        function edit_stock_details(id) {
            document.getElementById('item').value = document.getElementById(id + 'st').value;
            document.getElementById('quty').value = document.getElementById(id + 'q').value;
            document.getElementById('sell').value = document.getElementById(id + 's').value;
            document.getElementById('stock').value = document.getElementById(id + 'p').value;
            document.getElementById('total').value = document.getElementById(id + 'to').value;

            document.getElementById('guid').value = id;
            document.getElementById('edit_guid').value = id;

        }
        function unique_check() {
            if (!document.getElementById(document.getElementById('guid').value) || document.getElementById('edit_guid').value == document.getElementById('guid').value) {
                return true;

            } else {

                alert("This Item is already added In This Purchase");
                document.getElementById('item').focus();
                document.getElementById('quty').value = "";
                document.getElementById('sell').value = "";
                document.getElementById('stock').value = "";
                document.getElementById('total').value = "";
                document.getElementById('item').value = "";
                document.getElementById('guid').value = "";
                document.getElementById('edit_guid').value = "";
                return false;
            }
        }
        function quantity_chnage(e) {
            var unicode = e.charCode ? e.charCode : e.keyCode
            if (unicode != 13 && unicode != 9) {
            }
            else {
                add_values();

                document.getElementById("item").focus();

            }
            if (unicode != 27) {
            }
            else {

                document.getElementById("item").focus();
            }
        }
        function formatCurrency(fieldObj) {
            if (isNaN(fieldObj.value)) {
                return false;
            }
            fieldObj.value = '$ ' + parseFloat(fieldObj.value).toFixed(2);
            return true;
        }
        function balance_amount() {
            if (document.getElementById('payable_amount').value != "" && document.getElementById('payment').value != "") {
				
                data = parseFloat(document.getElementById('payable_amount').value);
                document.getElementById('balance').value = data - parseFloat(document.getElementById('payment').value);
				ball = document.getElementById('balance').value;
				document.getElementById('balance').value = parseFloat(ball).toFixed(2);
                if (parseFloat(document.getElementById('payable_amount').value) >= parseFloat(document.getElementById('payment').value)) {
					document.getElementById('change').value = '';
                } else {
                    if (document.getElementById('payable_amount').value != "") {
                        document.getElementById('balance').value = '000.00';
                        document.getElementById('change').value = parseFloat(document.getElementById('payment').value)- parseFloat(document.getElementById('payable_amount').value);
						chang = document.getElementById('change').value;
						document.getElementById('change').value = parseFloat(chang).toFixed(2);
                       // document.getElementById('payment').value = parseFloat(document.getElementById('payable_amount').value);
                    } else {
                        document.getElementById('balance').value = '000.00';
                        document.getElementById('payment').value = "";
                    }
                }
            } else {
                document.getElementById('balance').value = "";
            }


        }
        function stock_size() {
            if (parseFloat(document.getElementById('quty').value) > parseFloat(document.getElementById('stock').value)) {
                document.getElementById('quty').value = parseFloat(document.getElementById('stock').value);

                console.log();
            }
        }
        function discount_amount() {

            if (document.getElementById('main_grand_total').value != "") {
                document.getElementById('disacount_amount').value = parseFloat(document.getElementById('main_grand_total').value) * (parseFloat(document.getElementById('discount').value)) / 100;

            }
            if (document.getElementById('discount').value == "") {
                document.getElementById('disacount_amount').value = "";
            }
            discont = parseFloat(document.getElementById('disacount_amount').value);
            if (document.getElementById('disacount_amount').value == "") {
                discont = 0;
            }
            document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value) - discont;
            document.getElementById('total_amount').value = parseFloat(document.getElementById('main_grand_total').value) - discont;
			
			if(document.getElementById('tax').value==''){
			document.getElementById('tax').value='0';
			}
			
			document.getElementById('payable_amount').value = parseFloat(document.getElementById('payable_amount').value)+(parseFloat(document.getElementById('payable_amount').value)*parseFloat(document.getElementById('tax').value)/100);
			document.getElementById('tax_amount').value = (parseFloat(document.getElementById('total_amount').value)*parseFloat(document.getElementById('tax').value)/100);
			
            if (parseFloat(document.getElementById('payment').value) > parseFloat(document.getElementById('payable_amount').value)) {
                document.getElementById('payment').value = parseFloat(document.getElementById('payable_amount').value);

            }

        }
        function discount_as_amount() {
			if(document.getElementById('disacount_amount').value==''){
				document.getElementById('payable_amount').value = document.getElementById('main_grand_total').value;
			}
            if (parseFloat(document.getElementById('disacount_amount').value) > parseFloat(document.getElementById('grand_total').value))
                document.getElementById('disacount_amount').value = "";

            if (document.getElementById('main_grand_total').value != "") {
                if (parseFloat(document.getElementById('disacount_amount').value) < parseFloat(document.getElementById('grand_total').value)) {
                    discont = parseFloat(document.getElementById('disacount_amount').value);

                    document.getElementById('payable_amount').value = parseFloat(document.getElementById('main_grand_total').value) - discont;
                    document.getElementById('total_amount').value = parseFloat(document.getElementById('main_grand_total').value) - discont;
					
					if(document.getElementById('tax').value==''){
					document.getElementById('tax').value='0';
					}
					
					document.getElementById('payable_amount').value = parseFloat(document.getElementById('payable_amount').value)+(parseFloat(document.getElementById('payable_amount').value)*parseFloat(document.getElementById('tax').value)/100);
					document.getElementById('tax_amount').value = (parseFloat(document.getElementById('total_amount').value)*parseFloat(document.getElementById('tax').value)/100);
					
					
                    if (parseFloat(document.getElementById('payment').value) > parseFloat(document.getElementById('payable_amount').value)) {
                        document.getElementById('payment').value = parseFloat(document.getElementById('payable_amount').value);

                    }
                } else {
                    // document.getElementById('disacount_amount').value=parseFloat(document.getElementById('grand_total').value)-1;
                }
            }
        }
        function reduce_balance(id) {
            var minus = parseFloat(document.getElementById(id + "to").value);
            document.getElementById('grand_total').value = parseFloat(document.getElementById('grand_total').value) - minus;
            document.getElementById('main_grand_total').value = parseFloat(document.getElementById('grand_total').value);
            discount_amount();
            console.log(id);
        }
        function discount_type() {
            if (document.getElementById('round').checked) {
                document.getElementById("discount").readOnly = true;
                document.getElementById("disacount_amount").readOnly = false;
                if (parseFloat(document.getElementById('grand_total')) != "") {
                    document.getElementById('disacount_amount').value = "";
                    document.getElementById('discount').value = "";
                    discount_amount();
                }
            } else {
                document.getElementById("discount").readOnly = false;
                document.getElementById("disacount_amount").readOnly = true;
            }
        }
function chkdata(){
	var frm = document.saleform;
	with(frm){
		if(payable.value==''){
			payable.focus();
			alert('Select a item');
			return false;
		}
		if(payable.value<payment.value){					
			payment.value = payable.value;	
			balance.value = 0;			
		}
		if(payment_type.value=='giftcard'){		
			if(giftcard.value!=''){
				card = giftcard.value
				person = supplier.value;
				if(person!=''){
					
					dat = card+'&'+person;
					$.post('check_giftcard.php', {stock_name1: dat},
                    function (data) {
					if(data!='error' && payable.value<data){
						return true;
					}else{
						alert('Invalid Giftcard Number');
					}
                    
                    });
				}else{
					alert('Please insert customer name');
					return false;
				}								
			}else{
				alert('Gift Card Value is NULL');
				return false;
			}
			
		}
		return true;
	}
}

    </script>
<?php 
	if(isset($_POST['submit11'])){
	$max = mysqli_fetch_array(mysqli_query($con,"SELECT max(id) as id FROM `purchase_quatation` WHERE 1 "));
	$max = $max['id'] + 10;
	$autoid = "BILL-" . $max . "";
	$autoid1 = "SL-" . $max . "";
		
		
		
		$p_id = $autoid1;
		$store_id = $_SESSION['store_id'];
		$register_mode = 'sale';
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$bill_no = $_POST['bill_no'];
		$supplier = $_POST['supplier'];
		$address = $_POST['address'];
		$contact = $_POST['contact'];
		$chkcustomer = mysqli_fetch_array(mysqli_query($con,"select * from personinformation where type = 'customer' and name = '$supplier'"));
		if(empty($chkcustomer)){
			@mysqli_query($con,"INSERT INTO `personinformation`(`name`, `address`, `phone`, `type`, `store_id`) VALUES ('$supplier','$address','$contact','customer','$store_id')");
		}
		$payment = $_POST['payment'];
		$balance = $_POST['balance']==''?'0':$_POST['balance'];
		$subtotal = $_POST['main_grand_total'];
		$discount = $_POST['discount'];
		$dis_amount = $_POST['dis_amount']==''?'0':$_POST['dis_amount'];
		$payable = $_POST['payable'];
		$tax = $_POST['tax'];
		$tax_amount = $_POST['tax_amount'];
		$tax_dis = $_POST['tax_dis'];
		$mode= $_POST['payment_type'];
		if($mode=='bkash'){
			$mode_value = $_POST['bkash'];
		}elseif($mode=='giftcard'){
			$mode_value = $_POST['giftcard'];
			@mysqli_query($con,"update gift set amount = amount-$payment where card_number = '$mode_value'");
		}elseif($mode=='card'){
			$mode_value = $_POST['card'];
		}
		else{
			$mode_value = '';
		}
		$description = '';
		$duedate = '';
		$user = $_SESSION['id'];
		$type = $_POST['register_type'];
		$change = $_POST['change'];
		$purchase_cost = '';
		$challan_no = $_POST['challan_no'];
		$order_no = $_POST['order_no'];
		$k = 1;
		if(isset($_POST['stock_name'])){
		for($i=0;$i<sizeof($_POST['stock_name']);$i++){
			$product_name = $_POST['stock_name'][$i];
			$product_code = $_POST['pcode'][$i];
			$quty = $_POST['quty'][$i];
			$discount_prod = $_POST['discount_prod'][$i]==''?'0':$_POST['discount_prod'][$i];
			$sale_price = $_POST['sell'][$i];
			$stock = $_POST['stock'][$i];
			$total = $_POST['jibi'][$i];
			$openning_stock = $stock;
			$closing_stock = $stock - $quty;
			$udate = date('Y-m-d');
			if($stock>$quty){
				if($sale_price!='' and $product_name!=''){
					
					@mysqli_query($con,"INSERT INTO `purchase_quatation`(`p_id`, `date`, `supplier`, `product_name`, `quantity`, `purchase_cost`, `sale_price`, `openning_stock`, `closing_stock`, `total`, `payment`, `balance`, `mode`, `description`, `due_date`, `subtotal`, `count`, `bill_no`, `store_id`,user,type,discount,dis_amount,payable,tax,tax_dis,register_mode,discount_prod,mode_value,`change`,`time`,`tax_amount`,`challan_no`,`order_no`,`pcode`) VALUES ('$p_id','$date','$supplier','$product_name','$quty','$purchase_cost','$sale_price','$openning_stock','$closing_stock','$total','$payment','$balance','$mode','$description','$duedate','$subtotal','$k','$bill_no','$store_id','$user','$type','$discount','$dis_amount','$payable','$tax','$tax_dis','$register_mode','$discount_prod','$mode_value','$change','$time','$tax_amount','$challan_no','$order_no','$product_code')");
					//@mysqli_query($con,"UPDATE `stock` SET `quantity`= '$closing_stock' WHERE 1 and product_name = '$product_name'");
				}else{
					echo 'You have no product selected!';exit;
				}
				$k++;
			}else{
				echo "<br><font color=green size=+1 >There is no enough stock deliver for $product_name! Please add stock !</font>";
			}
			$m++;
		}
			header("Location:add_sales_print2.php?sid=$p_id");
		}else{
			$msg = 'You have no product selected!';
		}
		
		//echo "<script type='text/javascript'>window.open('add_sales_print.php?sid=$p_id','myNewWinsr','width=620,height=800,toolbar=0,menubar=no,status=no,resizable=yes,location=no,directories=no');</script>";
		
			
		
		
	}

?>	
<div class="area" >
	<div class="panel-head">Sale Quatation</div>
	<div class="panel fix">
	<form action="" method="post" name="saleform">
		<div class="prodleft left ">
			<?php 
				if($msg!=''){
					echo '<p style="color: #D8000C;
background-color: #FFBABA;;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
				}
			?>
			<div class="item_search2 ">
				<span>Register Type </span>&nbsp;<select id="register_type" name="register_type"/>
					<option value="sale" selected="selected">Sale</option>
					<option value="wholesale">Wholesale</option>
				</select>
				
			</div>
			<div class="item_search2 ">
				<span>Find/Scan Item OR Receipt </span>&nbsp;<input id="item" type="text" size="50"/>
			</div>
            <div class="scan">
			<table border="0" cellspacing="0" cellpadding="0" id="item_copy_final">

				<tr bgcolor="" class="scan">
					<td class="center" width="10%" style="padding:10px">Delete</td>
					<td class="center" width="20%">Item Name</td>
					<td class="center" width="10%">Quantity</td>
					<td class="center" width="10%">Price</td>
					<td class="center" width="10%">Discount</td>
					<td class="center" width="10%">Exact Stock</td>
					<td class="center" width="10%">Total</td>
				</tr>


			</table>
            </div>
			<div id="empty" style="margin-top:10px;text-align:center">You have no item selected</div>
		</div>
		<div class="prodright left fix">
			<div class="customer_details fix">
				<table>
					<tr>
						<td>Customer: </td>
						<td><input name="supplier" placeholder="Start typing customer name.." type="text" id="supplier"
								   maxlength="200" class="form-control" style="width:250px" /></td>
					</tr>
					<tr><td>Address: </td>
						<td><input name="address" placeholder="" type="text" id="address"
								   maxlength="200" class="form-control" style="width:250px" /></td>
					</tr>
					<tr>
						<td>Contact: </td>
						<td><input name="contact" placeholder="" type="text" id="contact1"
								   maxlength="200" class="form-control"
								   onkeypress="return numbersonly(event)" style="width:250px "/></td>
					</tr>
				</table>
			</div>
			<hr />
			<div class="totalamount fix">
				<table>
					<tr>
						<td width="50%">Grand Total</td>
						<td width="50%"><input type="hidden" readonly="readonly" id="grand_total"
											   name="subtotal">
							<input type="text" id="main_grand_total" name="main_grand_total" readonly="readonly"
								   class="form-control" style="text-align:right;width: 120px"></td>
					</tr>
					<tr>
						<td width="50%">Discount %</td>
						<td width="50%"><input type="text" maxlength="3" class="form-control"
											 onkeyup=" discount_amount(); "
											 onkeypress="return numbersonly(event);" name="discount"
											 id="discount" style="text-align:right;width: 120px"></td>
					</tr>
					<tr>
						<td width="50%">Discount Amount</td>
						<td width="50%"><input type="text"
												   onkeypress="return numbersonly(event);"
												   onkeyup=" discount_as_amount(); " class="form-control"
												   id="disacount_amount" name="dis_amount" style="text-align:right;width: 120px"></td>
					</tr>
					<tr>
						<td width="50%">Total Amount</td>
						<td width="50%"><input type="text"
												   onkeypress="return numbersonly(event);"
												   class="form-control"
												   id="total_amount" style="text-align:right;width: 120px"></td>
					</tr>
				</table>
				<?php 
					$taxchk = mysqli_query($con,"select * from vat where status = 0");
					if(mysqli_num_rows($taxchk)>0){
						while($tda = mysqli_fetch_array($taxchk)){
							echo '<hr /><table>
								<tr><td width="50%">'.$tda['vat_name'].' '.$tda['amount'].' %</td><td width="50%"><input type="text" name="tax_amount" style="text-align:right;width: 120px" id="tax_amount"/>
								<input type="hidden" id="tax" name="tax" value="'.$tda['amount'].'" /><input type="hidden" id="tax_dis" name="tax_dis" value="'.$tda['vat_name'].'" />
								</td></tr>
							</table>';
						}
					}else{
						echo '<input type="hidden" id="tax" name="tax" value="" /><input type="hidden" id="tax_dis" name="tax_dis" value="" />';
						echo '<input type="hidden" id="tax_amount" name="tax_amount" value="0" />';
					}
				?>
				
				<hr />
				<table>					
					<tr>
						<td width="50%">Payable Amount</td>
						<td width="50%"><input type="hidden" readonly="readonly" id="grand_total">
							<input type="text" id="payable_amount" readonly="readonly" name="payable"
								   class="form-control" style="text-align:right;width: 120px"></td>
					</tr>
					<tr>
						<td width="50%">Due Balance</td>
						<td width="50%"><input type="text" class="form-control" readonly="readonly" id="balance"
										   name="balance" style="text-align:right;width: 120px"></td>
					</tr>
					<tr>
						<td width="50%">Change Balance</td>
						<td width="50%"><input type="text" class="form-control" readonly="readonly" id="change"
										   name="change" style="text-align:right;width: 120px"></td>
					</tr>
				</table>
				<hr />
				<table id="pay_what">
					<tr>
					<?php 
					$max = mysqli_fetch_array(mysqli_query($con,"SELECT max(id) as id FROM `purchase` WHERE 1 "));
					$max = $max['id'] + 10;
					$autoid = "INV-" . $max . "";
					$autochallan = "CH-" . $max . "";
					$autoorder = "OR-" . $max . "";
					?>
						<td width="50%">Invoice No#</td>
						<td width="50%"><input type="text" class="form-control" onkeyup=" balance_amount(); "
										    value="<?= $autoid ?>" name="bill_no" id="" style="width: 120px" required></td>
					</tr>
					<tr>
						<td width="50%">Challan No#</td>
						<td width="50%"><input type="text" class="form-control" onkeyup=" balance_amount(); "
										    value="<?= $autochallan ?>" name="challan_no" id="" style="width: 120px" required></td>
					</tr>
					<tr>
						<td width="50%">Order No#</td>
						<td width="50%"><input type="text" class="form-control" onkeyup=" balance_amount(); "
										    value="<?= $autoorder?>" name="order_no" id="" style="width: 120px" required></td>
					</tr>
					<tr>
						<td width="50%">Payment</td>
						<td width="50%"><input type="text" class="form-control" onkeyup=" balance_amount(); "
										   onkeypress="return numbersonly(event);" name="payment" id="payment" style="width: 120px" ></td>
					</tr>
					<tr>
						<td width="50%">Payment type</td>
						<td width="50%">
							<select name="payment_type" id="payment_type" style="width: 130px" onchange="return pay_type(this.value)" required>
								<option value="" >select one</option>
								<option value="cash" selected="selected">Cash</option>
								<option value="bkash">Bkash</option>
								<option value="giftcard">Gift Card</option>
								<option value="card">Dedit/Credit Card</option>
								
							</select>
						</td>						
					</tr>

				</table>
				<hr />
				<table width="280">
					<tr>
						<td align="right"><input type="submit" onclick="return chkdata();" name="submit11" value="Add Sale Quatation" /><td>
					</tr>
				</table>
			</div>
			</form>
		</div>
			
	</div>
</div>
	<?php include("includes/footer.php");?>