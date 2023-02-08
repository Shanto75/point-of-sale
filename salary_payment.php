<?php include("includes/header.php");?>
<style type="text/css">
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
		$('<tr class="cngst"><td>Total Payable Amount</td><td><input type="text" readonly="readonly" value="'+data.payable_amount+'" /></td></tr><tr class="cngst"><td>Total Due</td><td><input type="text" id="due" readonly="readonly" value="'+data.due+'" /><input type="hidden" id="due2" value="'+data.due+'" /></td></tr><tr class="cngst"><td>Pay</td><td><input type="text" id="pay" name="payment" onkeyup="return adjustbalance(this.value)" /></td></tr><tr class="cngst"><td>&nbsp;&nbsp;</td><td><input type="submit" value="submit" /></td></tr>').fadeIn("slow").appendTo('#personal_loan_payment');
		
		
	}, 'json');
	
	
}


</script>
	<script type="text/javascript">

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
$msg = '';
if(isset($_POST['employee_id']) and isset($_POST['amount'])){
	$employee_id = $_POST['employee_id'];
	$amount = $_POST['amount'];
	$salary_monty = $_POST['salary_monty'];
	$year = $_POST['year'];
	$mode = $_POST['mode'];
	$note = $_POST['note'];
	$date = date('Y-m-d');
	if($employee_id!='' && $amount!=''){
		@mysqli_query($con,"INSERT INTO `salary`(`employee_id`, `month`, `date`, `year`, `amount`, `notes`, `store_id`, `mode`) VALUES ('$employee_id','$salary_monty','$date','$year','$amount','$note','1','$mode')");
		$msg = 'Payment has been successfull!';
		$data = urlencode(serialize($_POST));
		header('Location:print_slip_salary.php?data='.$data);exit;
	}else{
		$msg= 'Opps..Something is Wrong';
	}
}
?>


	<div class="area">
		<div class="panel-head">Employee Salary Payment</div>
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
					<td>Select Employee</td>
					<td>&nbsp;&nbsp;</td>
					<td><div class="select_style"><select id="dropdown" name="employee_id" style="width:160px" required>
							<option value="">Select employee</option>
						<?php 
							$sql = mysqli_query($con,"select * from personinformation where type='employee'");
							while($row = mysqli_fetch_array($sql)){
								echo '<option value="'.$row['id'].'">'.$row['name'].' ('.$row['designation'].')'.'</option>';
							}
						?>
						</select><span></span></div>
					</td>
				</tr>
				<tr></tr>
				<tr>
					<td>Salary Month</td>
					<td>&nbsp;&nbsp;</td>
					<td>
						<div class="select_style"><select id="dropdown" name="salary_monty" style="width:160px">
						<?php
							for ($m=1; $m<=12; $m++) {
							 $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
								 $d = date('m');
								 $match = date('F', mktime(0,0,0,$d, 1, date('Y')));
								 if($month==$match){
									 echo '<option value="'.$month.'" selected="selected">'.$month.'</option>';
								 }else{
									 echo '<option value="'.$month.'">'.$month.'</option>';
								 }								 
							 }
						?>
						</select><span></span></div>
					</td>
				</tr>
				<tr></tr>
				<tr>
					<td>Year</td>
					<td>&nbsp;&nbsp;</td>
					<td>
						<div class="select_style"><select id="dropdown" name="year" style="width:160px">
						<?php
							for ($m=2014; $m<=2025; $m++) {
							
								 $d = date('Y');								
								 if($m==$d){
									 echo '<option value="'.$m.'" selected="selected">'.$m.'</option>';
								 }else{
									 echo '<option value="'.$m.'">'.$m.'</option>';
								 }								 
							 }
						?>
						</select><span></span></div>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Amount</td>
					<td>&nbsp;&nbsp;</td>
					<td>
						<input type="text" name="amount" style="width:180px" />
					</td>
				</tr>
				<tr>
					<td>Payment Mode</td>
					<td>&nbsp;&nbsp;</td>
					<td>
						<input type="radio" name="mode" value="Bank" />&nbsp;&nbsp;Bank&nbsp;&nbsp;<input type="radio" name="mode" value="Cash" />&nbsp;&nbsp;Cash
					</td>
				</tr>
				<tr></tr>
				<tr>
					<td>Notes</td>
					<td>&nbsp;&nbsp;</td>
					<td>
						<textarea name="note" rows="5" cols="15"></textarea>
					</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;</td>
					<td>&nbsp;&nbsp;</td>
					<td>
					<input type="hidden" value="Salary Payment" name="slip"/>
						<input type="submit" name="" value="Pay" />
					</td>
				</tr>
			</table>
			</form>
		</div>
	</div>

	<?php include("includes/footer.php");?>
