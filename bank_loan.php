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
	$('.view_loan').closest("tr").remove();
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
		$("#bankname").val(data.bankname);
		$("#acname").val(data.acname);
		$("#acnumber").val(data.acnumber);
		$("#branchname").val(data.branchname);
		$("#q1").val(data.amount);
		$("#q2").val(data.interest);
		$("#q3").val(data.payable_amount);
		$("#installment").val(data.installment);
	
		$("#comments").val(data.comments);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		
		$("#bankname").val('');
		$("#acname").val('');
		$("#acnumber").val('');
		$("#branchname").val('');
		$("#q1").val('');
		$("#q2").val('');
		$("#q3").val('');
		$("#installment").val('');
		$("#comments").val('');
		$("#hidden_field").html('');

}
</script>
<?php
$msg = '';
if(isset($_POST['bankname']) and isset($_POST['amount'])){
		$type = 'bank';
		$bankname = $_POST['bankname'];
		$bank_ac_name_name = $_POST['acname'];
		$bank_ac_name = $_POST['acnumber'];
		$branch_name = $_POST['branchname'];
		$payable_amount = $_POST['payable_amount'];
		$total_installment = $_POST['installment'];
		$date = modifydate($_POST['date'],'-','/');
		$amount = $_POST['amount'];
		$interest = $_POST['interest'];
		$comments = $_POST['comments'];
		if(!isset($_POST['id'])){
		$sql = "INSERT INTO `loan`(`date`,amount,interest, `payable_amount`, `type`, `bank_name`, `bank_ac_name`, `branch_name`, `total_installment`,bank_ac_name_name,comments) VALUES ('$date','$amount','$interest','$payable_amount','$type','$bankname','$bank_ac_name','$branch_name','$total_installment','$bank_ac_name_name','$comments')";
		if($bankname!='' and $payable_amount!=''){
			$d = mysqli_query($con,$sql);
			$msg = 'Bank Loan Added Successfully';
		}else{
			$msg = 'Please fill the form correctly';
		}
	}else{
		$id = $_POST['id'];
		mysqli_query($con,"UPDATE `loan` SET `date`='$date',`amount`='$amount',`interest`='$interest',`payable_amount`='$payable_amount', `bank_name`='$bankname',`bank_ac_name`='$bank_ac_name',`branch_name`='$branch_name',`total_installment`='$total_installment', `bank_ac_name_name`='$bank_ac_name_name',`comments`='$comments' WHERE 1 and id = '$id'");
		$msg = 'Loan updated successfully!';
	}
}
if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from brand where id = '$id'");
	$msg = 'Data deleted successfully!';
}
?>
	<div class="area">
		<div class="panel-head">Bank Loan Details</div>
		<div class="panel">
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>		
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Bank Loan</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Bank Loan<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
		<tr>
    	<td align="right">Date</td>
    	<td><input type="text" name="date" id="date" class="datepick" value="<?= date('d/m/Y') ?>" /></td>
    	</tr>
        <tr>
        <td align="right">Bank Name</td>
        <td><input type="text" name="bankname" id="bankname" ></td>
        </tr>
        <tr>
        <td align="right">A/C Name </td>
        <td><input type="text" name="acname" id="acname" ></td>
        </tr>
         <tr>
        <td align="right">A/C Number </td>
        <td><input type="text" name="acnumber" id="acnumber" ></td>
        </tr>
        <tr>
        <td align="right">Branch Name </td>
        <td><input type="text" name="branchname" id="branchname" ></td>
        </tr>
        <tr>
        <tr>
        <td align="right">Amount </td>
        <td><input type="text" id="q1" name="amount" oninput="calculate()" ></td>
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
        <td align="right">Toatl Installment</td>
        <td><input type="text" name="installment" id="installment"></td>
        </tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right"> Comments</td>
    	<td><textarea placeholder="Enter Comments" name="comments" id="comments" rows="5" cols="20"></textarea></td>
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
    	Bank Loan Details<hr/>
    	
    	<table id="view_loan" class="tab">

    	</table>
    	
    </div>
</div>
<!--View-->
<?php 
$sql = mysqli_query($con,"SELECT * FROM `loan` WHERE `type` = 'bank' ");
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Date</th>
            <th>Bank Name</th>
            <th>A/C Name</th>
            <th>A/C Number</th>
            <th>Amount</th>
            
            <th>Payable Amount</th>
            <th>Paid Amount</th>
            <th>Due Amount</th>
            
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
	<?php 
	while($row = @mysqli_fetch_array($sql)){
		$due = @mysqli_fetch_array(@mysqli_query($con,"select sum(pay_amount) as amount from transaction where type='loan_bank' and pay_to ='".$row['id']."'"));
		$due_amount = $row['payable_amount'] - $due['amount'];
		?>
    <tr>
    		
       		<td><?= daterev($row['date'],'/','-'); ?></td>
            <td><?= ucwords($row['bank_name']) ?></td>
            <td><?= ($row['bank_ac_name_name']) ?></td>
            <td><?= ($row['bank_ac_name']) ?></td>
            <td><?= ($row['amount']) ?></td>
            
			<td><?= ($row['payable_amount']) ?></td>
			<td><?= ($due['amount']) ?></td>
            <td><?= ($due_amount) ?></td>
            
           
            
            <td width="25%">
			<a onclick="return view_loan('<?= $row['id'] ?>')" href="javascript:;"id="modal2-launcher" class="view">View</a>
			
            <a onclick="return checkproductdetails('<?= $row['id']; ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a>
            <a onclick="return confirm('Are you sure?')" href="bank_loan.php?id=<?= $row['id'] ?>&del=del"id="" class="delete">Delete</a>
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
