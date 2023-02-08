<?php include("includes/header.php");?>
<script type="text/javascript">
function checkproductdetails(val){
	
	$.post('check_vat.php', {productid: val},
	function (data) {
		
		$("#vat_name").val(data.vat_name);
		$("#tax_reg").val(data.tax_reg);		
		$("#amount").val(data.amount);		
		$("#comments").val(data.comments);
		$("#status").val(data.status);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#vat_name").val('');
		$("#tax_reg").val('');		
		$("#amount").val('');		
		$("#comments").val('');
		$("#status").val('');
		$("#hidden_field").html('');

}
function branch_view(val){
	$('#branch_view tr').remove(); 
	$.post('view_vat.php', {productid: val},
	function (data) {		
		$(data).appendTo('#branch_view');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>
<?php 
$msg = '';
if(isset($_POST['vat_name'])&& isset($_POST['amount'])){
	$vat_name = $_POST['vat_name'];
	$tax_reg = $_POST['tax_reg'];
	$amount = $_POST['amount'];
	$comments = $_POST['comments'];
	$status = $_POST['status'];
	if(!isset($_POST['id'])){
		@mysqli_query($con,"INSERT INTO `vat`(`vat_name`, `amount`, `store_id`, `comments`,status,tax_reg) VALUES ('$vat_name','$amount','1','$comments','$status','$tax_reg')");
		$msg = 'Data inserted Successfully';
	}else{
		$id= $_POST['id'];
		$status = $_POST['status'];
		@mysqli_query($con,"UPDATE `vat` SET `vat_name`='$vat_name',`amount`='$amount',`comments`='$comments', status = '$status',tax_reg = '$tax_reg' WHERE 1 and id = '$id'");
		$msg = 'Data Updated Successfully';
	}
}

?>
	<div class="area">
		<div class="panel-head">Vat/Tax Details</div>
		<div class="panel">
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
		<div class="btn">	
		</div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Vat/Tax<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Vat/Tax Name</td>
    	<td><input type="text" name="vat_name" id="vat_name" placeholder="Enter vat name" required></td>
    	</tr>
		<tr>
    	<td align="right">Vat/Tax Reg No:</td>
    	<td>
    		<input type="text" name="tax_reg" id="tax_reg" required>
    	</td>
    	</tr>
    	<tr>
    	<td align="right">Vat/Tax Amount %</td>
    	<td>
    		<input type="text" name="amount" id="amount" placeholder="Enter amount" required>
    	</td>
    	</tr>
		<tr>
    	<td align="right">Vat/Tax Status</td>
    	<td>
    		<select name="status" id="status" required>				
				<option value="0">Active</option>
				<option value="1">Inactive</option>
			</select>
    	</td>
    	</tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Enter Branch Comments</td>
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
<!--View-->
<div id="modal2-background"></div>
<div id="modal2-content">
    <button id="modal2-close">Close Modal Window</button>
    <div class="modal-form">
    	View Vat Details<hr/>
    	
    	<table id="branch_view" class="tab">

    	</table>
    	
    </div>
</div>
<!--Edit-->
<?php 
	$sql = @mysqli_query($con,"SELECT * FROM `vat` WHERE 1");
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Vat/Tax Name</th>
            <th>Reg No</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
while($row = @mysqli_fetch_array($sql)){
	

?>
    <tr>
    		<td><?= ucwords($row['vat_name']) ?></td>
       		<td><?= $row['tax_reg']=='0'?'':$row['tax_reg'] ?></td>
       		<td><?= $row['amount'].' %' ?></td>
       		<td><?= $row['status']=='0'?'Active':'Inactive' ?></td>

            <td>
            <a onclick="return branch_view('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a>
            <a onclick="return checkproductdetails('<?= $row['id']; ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a>
            
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
