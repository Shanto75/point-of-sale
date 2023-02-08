<?php include("includes/header.php");?>
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
	
	$.post('check_c2b_details.php', {productid: val},
	function (data) {
		
		$("#date").val(data.date);
		$("#bank").val(data.bankid)
		$("#amount").val(data.amount);
		$("#comments").val(data.comments);
	}, 'json');	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#date").val('');
		$("#bank_name").val('');
		$("#acname").val('');
		$("#acnumber").val('');
		$("#amount").val('');
		$("#comments").val('');
		$("#hidden_field").html('');
}
function view_earning(val){
	$('#datatable tr').remove(); 
	$.post('view_c2b_details.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>
<?php 
$msg = '';
if(isset($_POST['bank'])){
	$date = modifydate($_POST['date'],'-','/');
	$bank = $_POST['bank'];
	$bankquery= mysqli_fetch_array(mysqli_query($con,"select * from bankinformation where id = '$bank'"));
	$bank_name = $bankquery['bankname'];
	$acname = $bankquery['accountname'];
	$acnumber = $bankquery['accountnumber'];
	$amount = $_POST['amount'];
	$comments = $_POST['comments'];
	if(!isset($_POST['id'])){
		@mysqli_query($con,"INSERT INTO `transfer`(`date`, `bank_name`, `ac_name`, `ac_number`, `amount`, `comments`, `type`,bankid) VALUES ('$date','$bank_name','$acname','$acnumber','$amount','$comments','c2b','$bank')");
		$msg = 'Data Inserted Successfully!';
	}else{
		$id = $_POST['id'];
		@mysqli_query($con,"UPDATE `transfer` SET `date`='$date',`bank_name`='$bank_name',`ac_name`='$acname',`ac_number`='$acnumber',`amount`='$amount',`comments`='$comments', bankid= '$bank' WHERE 1 and id = '$id'");
		$msg = 'Data Updated Successfully!';
	}
}
if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from transfer where id = '$id'");
	$msg = 'Data deleted successfully!';
}
?>
	<div class="area">
		<div class="panel-head">Cash to Bank Details</div>
		<div class="panel">
						<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Cash to Bank</a></div>
	<!--Add-->
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Cash to Bank<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
        <tr>
        <td align="right">Date</td>
        <td><input type="text" name="date" class="datepick" id="date"></td>
        </tr>
		<tr>
			<td align="right">Bank</td>
			<td>
				<select id="bank" name="bank" required>
					<option value="">Select One</option>
					<?php
						$bq = mysqli_query($con,"select * from bankinformation where person_type = 'owner'");
						while($row= mysqli_fetch_array($bq)){
							echo '<option value="'.$row['id'].'">'.$row['bankname'].' ('.$row['accountnumber'].') </option>';
						}
					?>
				</select>
			</td>
		</tr>
        <tr>
        <td align="right">Transfer Amount</td>
        <td><input type="text" name="amount" id="amount" ></td>
        </tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Comments</td>
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
    <button id="modal2-close">Close Modal Window</button>
    <div class="modal2-form">
    	View Cash to Bank Details<hr/>
    	
    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<!--View-->
<?php 
$sql = @mysqli_query($con,"select * from transfer where 1 and type='c2b'");
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Date</th>
            <th>Bank Name </th>
            <th>Account Name </th>
            <th>A/C Number </th>
            <th>Transfer Amount</th>
            <th>Comments</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
		<?php 
	while($row = @mysqli_fetch_array($sql)){
		?>	
    <tr>
    		
       		<td><?= daterev($row['date'],'/','-') ?></td>
            <td><?= ucwords($row['bank_name']) ?></td>
            <td><?= ucwords($row['ac_name']) ?></td>
            <td><?= ($row['ac_number']) ?></td>
            <td><?= ($row['amount']) ?></td>
            <td><?= ($row['comments']) ?></td>
            <td>
            <a onclick="return view_earning('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a></div>		
            <a onclick="return checkproductdetails('<?= $row['id']; ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a></div>
            <a href="c2b.php?id=<?= $row['id'] ?>&del=del"id="" class="delete">Delete</a></div>
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
