<?php include("includes/header.php");?>
<script type="text/javascript">
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
function view_loan(val){
	$('#view_loan tr').remove(); 
	$.post('view_loan.php', {productid: val},
	function (data) {
		$(data).fadeIn("slow").appendTo('#view_loan');
		
		
	});
	
	
}
function payment_history(val){
	
	$('#view_loan tr').remove(); 
	$.post('payment_history.php', {productid: val},
	function (data) {
		$(data).fadeIn("slow").appendTo('#view_loan');
		
		
	});
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
if(isset($_POST['name']) and isset($_POST['payable_amount'])){
	
		
		$name = $_POST['name'];
		
		$date = modifydate($_POST['date'],'-','/');
		$amount = $_POST['amount'];
		$interest = $_POST['interest'];
		$payable_amount = $_POST['payable_amount'];
		$return_date = modifydate($_POST['return_date'],'-','/');
		$comments = $_POST['comments'];
		if(!isset($_POST['id'])){
			$query = "INSERT INTO `loan`(`name`, `date`, `amount`, `interest`, `payable_amount`, `return_date`,comments,type) VALUES ('$name','$date','$amount','$interest','$payable_amount','$return_date','$comments','personal')";
			if($name!='' and $payable_amount!=''){
				$d = mysqli_query($con,$query);
				$msg = 'Loan Inserted!';
			}else{
				$msg = 'Please fill the form correctly';
			}
		}else{
			$id = $_POST['id'];
			@mysqli_query($con,"UPDATE `loan` SET `name`='$name',`date`='$date',`amount`='$amount',`interest`='$interest',`payable_amount`='$payable_amount',`return_date`='$return_date',`comments`='$comments' WHERE 1 and id = '$id'");
			$msg = 'Loan Updated';
		}
	}
	
	if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from loan where id = '$id'");
	$msg = 'Data deleted successfully!';
}
?>


	<div class="area">
		<div class="panel-head">Personal Loan Details</div>
		<div class="panel">
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Personal Loan</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Personal Loan<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Date</td>
    	<td><input type="text" name="date" id="date" class="datepick" value="<?= date('d/m/Y') ?>" /></td>
    	</tr>
        <tr>
        <td align="right">From</td>
        <td><input type="text" name="name" id="name" ></td>
        </tr>
        <tr>
        <td align="right">Amount </td>
        <td><input type="text" id="q1" name="amount" oninput="calculate()"></td>
        </tr>
         <tr>
        <td align="right">Interest </td>
        <td><input type="text" id="q2" name="interest" oninput="calculate()"></td>
        </tr>
         <tr>
        <td align="right">Payable Amount </td>
        <td><input type="text" id="q3" name="payable_amount" ></td>
        </tr>
        <tr>
        <td align="right">Return Date </td>
        <td><input type="text" name="return_date" placeholder="dd/mm/yy" id="return_date" class="datepick" ></td>
        </tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right"> Comments</td>
    	<td><textarea  name="comments" id="comments" rows="5" cols="20"></textarea></td>
    	</tr>
    	<tr>
    	<td colspan="2" align="right">
		<div id="hidden_field"></div>
		<input type="submit" value="Go"></td>
    	</tr>
    	</table>
    	</form>
    </div>
</div>
		<div id="modal2-background"></div>
<div id="modal2-content">
    <button id="modal2-close">Close Window</button>
    <div class="modalform">
    	Personal Loan Details<hr/>
    	
    	<table id="view_loan" class="tab">

    	</table>
    	
    </div>
</div>
<!--View-->
<?php 
$sql = mysqli_query($con,"SELECT * FROM `loan` WHERE `type` = 'personal' ");
?>

		<table id="table_id" class="display">
    <thead>

        <tr>
            <th>Date</th>
            <th>From</th>
            <th>Amount</th>
            <th>Interest</th>
            <th>Payable Amount</th>
            <th>Paid Amount</th>
            <th>Due Amount</th>
            <th>Return Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
	<?php 
	while($row = @mysqli_fetch_array($sql)){
		$due = @mysqli_fetch_array(@mysqli_query($con,"select sum(pay_amount) as amount from transaction where type='loan_personal' and pay_to ='".$row['id']."'"));
		$due_amount = $row['payable_amount'] - $due['amount'];
		?>
    <tr>
    		
       		<td><?= daterev($row['date'],'/','-'); ?></td>
            <td><?= ucwords($row['name']) ?></td>
            <td><?= ($row['amount']) ?></td>
            <td><?= ($row['interest']) ?> %</td>
            <td><?= ($row['payable_amount']) ?></td>
            <td><?= ($due['amount']) ?></td>
            <td><?= ($due_amount) ?></td>
            <td><?= daterev($row['return_date'],'/','-'); ?></td>
            
            <td width="25%">
            <a onclick="return view_loan('<?= $row['id'] ?>')" href="javascript:;"id="modal2-launcher" class="view">view</a></div>
            <a onclick="return checkproductdetails('<?= $row['id']; ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a></div>
            <a onclick="return confirm('Are you sure?')" href="personal_loan.php?id=<?= $row['id'] ?>&del=del"id="" class="delete">Delete</a></div>
			<a onclick="return payment_history('<?= $row['id'] ?>')" href="javascript:;"id="modal2-launcher" class="view">Payment History</a>
            </td>
    </tr>
	<?php } ?>
    </tbody>
</table>
		</div>
		</div>
		</div>
		</div>

	<?php include("includes/footer.php");?>
