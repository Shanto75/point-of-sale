<?php 
include("includes/header.php");?>
<script type="text/javascript">
function checkproductdetails(val){
	
	$.post('check_vat.php', {productid: val},
	function (data) {
		
		$("#vat_name").val(data.vat_name);
		$("#amount").val(data.amount);		
		$("#comments").val(data.comments);
		$("#status").val(data.status);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#vat_name").val('');
		$("#amount").val('');		
		$("#comments").val('');
		$("#status").val('');
		$("#hidden_field").html('');
		$('#check1').closest("tr").remove();
			$('#check2').closest("tr").remove();
			$('#check3').closest("tr").remove();
}
function branch_view(val1,val2,val3,val4){
	$("#name").val(val1);
	$("#payable_amount").val(val2);
	$("#due_amount").val(val3);
	$("#paid_amount").val(val4);

	/*$('#branch_view tr').remove(); 
	$.post('view_vat.php', {productid: val},
	function (data) {		
		$(data).appendTo('#branch_view');		
	});*/
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function adjust_b(val){
	x = $("#payable_amount").val();
	y = $("#paid_amount").val();
	Z = $("#due_amount").val();
	if(val==''){
		val = 0;
	}
	
	paid = parseFloat(val)+parseFloat(y);
	due = parseFloat(x) - parseFloat(paid);	
	document.getElementById('due_amount').value = due;
}
function pay_type(val){
	
	if(val!='' || val!='cash'){
		if(val=='bkash'){
			$('#check1').closest("tr").remove();
			$('#check2').closest("tr").remove();
			$('#check3').closest("tr").remove();
				$('#card').closest("tr").remove();
				$('#giftcard').closest("tr").remove();
				$('<tr id="'+val+'"><td>Transaction Id</td><td><input type="text" style="width:120px" value="" name="'+val+'"/></td></tr>').fadeIn("slow").appendTo('#pay_what');
				
		}else if(val=='giftcard'){
			$('#check1').closest("tr").remove();
			$('#check2').closest("tr").remove();
			$('#check3').closest("tr").remove();
			$('#card').closest("tr").remove();
			$('#bkash').closest("tr").remove();
			$('<tr id="'+val+'"><td>Gift Card Number</td><td><input type="text" style="width:120px" value="" name="'+val+'"/></td></tr>').fadeIn("slow").appendTo('#pay_what');
		}else if(val=='card'){
			$('#check1').closest("tr").remove();
			$('#check2').closest("tr").remove();
			$('#check3').closest("tr").remove();
			$('#bkash').closest("tr").remove();
			$('#giftcard').closest("tr").remove();
			$('<tr id="'+val+'"><td>Card Number</td><td><input type="text" style="width:120px" value="" name="'+val+'"/></td></tr>').fadeIn("slow").appendTo('#pay_what');
		}else if(val=='check'){
			$('#bkash').closest("tr").remove();
			$('#giftcard').closest("tr").remove();
			$('#card').closest("tr").remove();
			$.ajax({url: "bank_select.php", success: function(result){
				$('<tr id="'+val+'1"><td>Bank Name</td><td>'+result+'</td></tr>').fadeIn("slow").appendTo('#pay_what');
				$('<tr id="'+val+'2"><td>Check Number</td><td><input type="text" value="" style="width:120px" name="check_number" /></td></tr>').fadeIn("slow").appendTo('#pay_what');
				$('<tr id="'+val+'3"><td>Check Date</td><td><input type="text" value="" class="datepick" style="width:120px" name="check_date" placeholder="yyyy-mm-dd" /></td></tr>').fadeIn("slow").appendTo('#pay_what');
				
			}});
			
			
			
		
		}else{
			$('#card').closest("tr").remove();
			$('#bkash').closest("tr").remove();
			$('#giftcard').closest("tr").remove();
			$('#check1').closest("tr").remove();
			$('#check2').closest("tr").remove();
			$('#check3').closest("tr").remove();
		}
	}else{
		$('#card').closest("tr").remove();
		$('#bkash').closest("tr").remove();
		$('#giftcard').closest("tr").remove();
	}
}
</script>
<?php 
$msg = '';
if(isset($_POST['name']) and strlen($_POST['name'])>0){
$payment_type = $_POST['payment_type'];
if($payment_type=='check'){
$bank_name = $_POST['bank_name'];
$check_number = $_POST['check_number'];
$check_date = $_POST['check_date'];	
}else{
$bank_name = '';
$check_number = '';
$check_date = '';
}
	$name = $_POST['name'];
	$status = 'due';

	$pay = $_POST['pay'];
	$type = 'purchase';
	$date = modifydate($_POST['date'],'-','/');
	mysqli_query($con,"INSERT INTO `transaction`(`pay_amount`, `date`, `type`, `store_id`, `name`,status,method,bank,check_number,check_date) VALUES ('$pay','$date','$type','1','$name','$status','$payment_type','$bank_name','$check_number','$check_date')");
	$msg = 'Amount has been paid';
	$data = urlencode(serialize($_POST));
	header('Location:print_slip.php?data='.$data);exit;
}
?>
	<div class="area">
		<div class="panel-head">Payment Details</div>
		<div class="panel">
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>		
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Payment<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab" id="pay_what">
    	<tr>
    	<td align="right">Date</td>
    	<td><input type="text" name="date" id="date" class="datepick" value="<?= date('d/m/Y') ?>" required></td>
    	</tr>
		<tr>
    	<td align="right">Supplier ID</td>
    	<td><input type="text" name="name" id="name" placeholder="Enter vat name" readonly  required></td>
    	</tr>
    	<tr>
    	<td align="right">Payable Amount</td>
    	<td>
    		<input type="text" name="payable_amount" id="payable_amount" placeholder="Enter amount" readonly="readonly">
    	</td>
    	</tr>
		<tr>
    	<td align="right">Paid Amount</td>
    	<td>
    		<input type="text" name="paid_amount" id="paid_amount" placeholder="Enter amount" readonly="readonly">
    	</td>
    	</tr>
		<tr>
    	<td align="right">Due Amount</td>
    	<td>
    		<input type="text" name="due_amount" id="due_amount" placeholder="Enter amount" readonly="readonly">
    	</td>
    	</tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Pay</td>
    	<td><input type="text" onkeyup="adjust_b(this.value);" name="pay" id="pay" placeholder="Enter amount" required></td>
    	</tr>
		<tr>
			<td width="50%">Payment type</td>
			<td width="50%">
				<select name="payment_type" id="payment_type" style="width: 130px" onchange="return pay_type(this.value)" required>
					<option value="" >select one</option>
					<option value="cash" selected="selected">Cash</option>
					<option value="card">Dedit/Credit Card</option>
					<option value="check">Check</option>								
				</select>
			</td>						
		</tr>

    	</table>
		<table class="tab">
			<tr>  
			<td style="width:150px"></td>
			<td colspan="2" align="right">
			<input type="hidden" value="Supplier Payment" name="slip"/>
			<input type="submit" value="Go"></td>
			</tr>
		</table>
    	</form>
    </div>
</div>
<!--View-->
<!--Edit-->
<?php 
	$sql = @mysqli_query($con,"SELECT DISTINCT `p_id`, `supplier`, sum(`payment`)as payment, sum(`balance`)as balance, sum(`payable`)as payable FROM `purchase` WHERE 1 and type='purchase' and register_mode = 'purchase' and count = '1' group by `supplier`");
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Supplier Name</th>            
            <th>Payable Amount</th>
            <th>Paid Amount</th>
            <th>Due Amount</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
while($row = @mysqli_fetch_array($sql)){
	$an = @mysqli_fetch_array(mysqli_query($con,"select sum(pay_amount) as pay_amount from transaction where type='purchase' and name = '".$row['supplier']."' and status = 'due' group by name"));
	$customerName = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM personinformation WHERE type='supplier' AND id='".$row['supplier']."'"));

	$openblnce = mysqli_fetch_array(mysqli_query($con, "SELECT amount FROM openingbalance  WHERE type='supplier' AND personid='".$customerName['id']."'"));


	$pay = $an['pay_amount'];
	$payble = $row['payable'];
	$paid = $row['payment']+$pay;
	
	if(!empty($openblnce)){

		if($openblnce['amount'] < 0){
			$paid = $paid + abs($openblnce['amount']);
		}else{
			$payble = $payble + $openblnce['amount'];
		}

	}
	$due = $payble-$paid;
	

?>
    <tr>
    		<td><?= ucwords($customerName['name']) ?></td>       		
       		<td><?= number_format((float)$payble, 2, '.', ''); ?> TK</td>
       		<td><?= number_format((float)$paid, 2, '.', ''); ?> TK</td>
       		<td><?= number_format((float)$due, 2, '.', ''); ?> TK</td>
            <td>
				<a onclick="return branch_view('<?= $row['supplier'] ?>','<?= number_format((float)$payble, 2, '.', '') ?>','<?= number_format((float)$due, 2, '.', '') ?>','<?= number_format((float)$paid, 2, '.', '') ?>');" href="javascript:;"id="modal-launcher" class="view">Payment</a>
            </td>
    </tr>
<?php } ?> 
    </tbody>
</table>
		</div>
		</div>
	<?php include("includes/footer.php");?>