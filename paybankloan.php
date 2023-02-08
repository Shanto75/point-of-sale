<?php include("includes/header.php");?>
<style type="text/css">
table td{
	padding:10px
}
	.select_style 
{
	background: #FFF;
	overflow: hidden;
	display: inline-block;
	color: #fff;
	font-size: 15px;
	-webkit-border-radius: 5px 4px 4px 5px/5px 5px 4px 4px;
	-moz-border-radius: 5px 4px 4px 5px/5px 5px 4px 4px;
	border-radius: 5px 4px 4px 5px/5px 5px 4px 4px;
	-webkit-box-shadow: 0 0 5px rgba(123, 123, 123, 0.2);
	-moz-box-shadow: 0 0 5px rgba(123,123,123,.2);
	box-shadow: 0 0 5px rgba(123, 123, 123, 0.2);
	border: solid 1px #ccc;
	font-family: "helvetica neue",arial;
	position: relative;
	top:7px; ;
	cursor: pointer;
	padding-right:20px;

}
.select_style span
{
	position: absolute;
	right: 10px;
	width: 8px;
	height: 8px;
	background: url(http://projects.authenticstyle.co.uk/niceselect/arrow.png) no-repeat;
	top: 50%;
	margin-top: -4px;
}
.select_style select
{
	-webkit-appearance: none;
	appearance:none;
	width:120%;
	background:none;
	background:transparent;
	border:none;
	outline:none;
	cursor:pointer;
	padding:7px 10px;
}
#dropdown 
{
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
</style>
<script type="text/javascript">
function find_pay_to(val){
	$('.cngst').closest("tr").remove();
	$.post('check_loan_payment.php', {productid: val},
	function (data) {
		$('<tr class="cngst"><td>Total Payable Amount</td><td><input type="text" readonly="readonly" value="'+data.payable_amount+'" /></td></tr><tr class="cngst"><td>Total Due</td><td><input type="text" id="due" readonly="readonly" value="'+data.due+'" /><input type="hidden" id="due2" value="'+data.due+'" /></td></tr><tr class="cngst"><td>Pay</td><td><input type="text" id="pay" name="payment" onkeyup="return adjustbalance(this.value)" /></td></tr><tr class="cngst"><td>&nbsp;&nbsp;</td><td><input type="hidden" name="slip" value="Bank Load Payment"/><input type="submit" value="submit" /></td></tr>').fadeIn("slow").appendTo('#personal_loan_payment');
		
		
	}, 'json');
	
	
}
function adjustbalance(val){
	x = $('#due').val();
	y = $('#due2').val();
	if(val!='' && y!=''){
		z = parseFloat(y)-parseFloat(val);
		$('#due').val(z);
	}else{
		$('#due').val($('#due2').val());
	}
}
function calculate() {
    var myq3 = document.getElementById('q1').value; 
    var myu3 = document.getElementById('q2').value;
    var result = document.getElementById('q3'); 
	if(myu3==''){
		result.value = myq3;
	}
	if(myq3!='' && myu3!=''){
		x = parseInt(myu3)*parseInt(myq3);
		y = parseInt(x)/100;
		r = parseInt(y)+parseInt(myq3);
		result.value = r.toFixed(2);

	}
}
</script>
	<script type="text/javascript">
function setSelectedValue(selectObj, valueToSet) {
    for (var i = 0; i < selectObj.options.length; i++) {
        if (selectObj.options[i].text== valueToSet) {
            selectObj.options[i].selected = true;
            return;
        }
    }
}
function checkproductdetails(val){
	
	$.post('check_loan_personal.php', {productid: val},
	function (data) {
		
		$("#date").val(data.date);
		$("#name").val(data.name);
		$("#q1").val(data.amount);
		$("#q2").val(data.interest);
		$("#q3").val(data.payable_amount);
		$("#return_date").val(data.return_date);
		$("#comments").val(data.comments);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#date").val('');
		$("#name").val('');
		$("#q1").val('');
		$("#q2").val('');
		$("#q3").val('');
		$("#return_date").val('');
		$("#comments").val('');
		$("#hidden_field").html('');

}
</script>
<?php
if(isset($_POST['pay_to']) and isset($_POST['payment'])){
	$pay_to = $_POST['pay_to'];
	$payment = $_POST['payment'];
	$date = modifydate($_POST['date'],'-','/');
	if($pay_to!='' && $payment!=''){
		@mysqli_query($con,"INSERT INTO `transaction`(`pay_to`, `pay_amount`, `date`, `type`, `store_id`) VALUES ('$pay_to','$payment','$date','loan_bank','1')");
		$msg = 'Payment has been successfull!';
		$data = urlencode(serialize($_POST));
		header('Location:print_slip_loan.php?data='.$data);exit;
	}else{
		$msg= 'Opps..Something is Wrong';
	}
}
?>


	<div class="area">
		<div class="panel-head">Personal Loan Payment</div>
		<div class="panel">
		<form action="" method="post">
				<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
			<table id="personal_loan_payment">
				<tr>
					<td>Pay To : </td>
					<td><div class="select_style"><select id="dropdown" name="pay_to" onchange="find_pay_to(this.value)">
						<option value="">Please Select one</option>
						<?php
							$sql = mysqli_query($con,"select * from loan where type='bank'");
							while($row = mysqli_fetch_array($sql)){
								echo '<option value="'.$row['id'].'">'.$row['bank_name'].'(AC-'.$row['bank_ac_name'].')'.'</option>';
							}
						?>
					</select><span></span></div>
					</td>
				</tr>
				<tr>
					<td>Date</td>
					<td><input type="text" class="datepick" name="date" value="<?= date('d/m/Y') ?>" /></td>
				</tr>
			</table>
			</form>
		</div>
	</div>

	<?php include("includes/footer.php");?>
