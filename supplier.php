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
	
	$.post('check_supplier_details.php', {productid: val},
	function (data) {
		
		$("#name").val(data.name);
		$("#opening_balance").val(data.openBalance);
		$("#address").val(data.address);
		$("#category").val(data.category);
		$('#phone').val(data.phone);
		$('#email').val(data.email);
		$('#company_name').val(data.company_name);
		$('#comments').val(data.comments);

	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#name").val('');
		$("#address").val('');
		$("#category").val('');
		$('#phone').val('');
		$('#email').val('');
		$('#company_name').val('');
		$('#comments').val('');
		$("#hidden_field").html('');
}
function view_earning(val){
	$('#datatable tr').remove(); 
	$.post('view_customer_details.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>
<?php 
if (isset($_POST['name'])) {
	$_POST = $gump->sanitize($_POST);
	$gump->validation_rules(array(
		'name' => 'required|max_len,100|min_len,3',
		'address' => 'max_len,200',
		'phone' => 'alpha_numeric|max_len,20',
		'email' => 'max_len,50',
		'comments' => 'max_len,200'
	));

	$gump->filter_rules(array(
		'name' => 'trim|sanitize_string|mysqli_escape',
		'address' => 'trim|sanitize_string|mysqli_escape',
		'phone' => 'trim|sanitize_string|mysqli_escape',
		'email' => 'trim|sanitize_string|mysqli_escape',
		'comments' => 'trim|sanitize_string|mysqli_escape'
	));

	$validated_data = $gump->run($_POST);

	if ($validated_data === false) {
		$msg = $gump->get_readable_errors(true);
	} else {

	$name = $_POST['name'];
	$address = $_POST['address'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$comments = $_POST['comments'];
	$store_id = $_SESSION['store_id'];
	$date = date('Y-m-d');
	
	
	
	if(isset($_POST['id'])){
		$id = $_POST['id'];

		mysqli_query($con,"UPDATE `personinformation` SET `name`='$name',`address`='$address',`phone`='$phone',`email`='$email',`comments`='$comments' WHERE 1 and id = '$id'");

		if($_POST['opening_balance']!=''){
		     $openingbalance = $_POST['opening_balance'];
		     $pdata = mysqli_fetch_array(mysqli_query($con, "SELECT personid FROM openingbalance WHERE personid='$id'"));
		     if(!empty($pdata)){
		     @mysqli_query($con, "UPDATE openingbalance SET amount='$openingbalance' WHERE type='supplier' AND personid='$id'");

				}else{
					@mysqli_query($con, "INSERT INTO `openingbalance`(`personid`, `type`, `amount`,`date`)VALUES('$id','supplier','$openingbalance','$date')");
				}

		 }

		$msg = 'Supplier Update successfully!';
	}else{
		@mysqli_query($con,"INSERT INTO `personinformation`(`name`, `address`, `phone`, `email`, `company_name`, `type`, `comments`, `store_id`) VALUES ('$name','$address','$phone','$email','','supplier','$comments','$store_id')") or die();

		
		     $openingbalance = $_POST['opening_balance'];
		     $person= mysqli_fetch_array(mysqli_query($con, "SELECT id FROM personinformation WHERE name='$name' AND phone='$phone'"));
		     $personId = $person['id'];

		     @mysqli_query($con, "INSERT INTO `openingbalance`(`personid`, `type`, `amount`,`date`)VALUES('$personId','supplier','$openingbalance','$date')");
		
			
		$msg = 'Supplier inserted successfully!';
	}
	}
}



if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	$del = @mysqli_query($con,"delete from personinformation where id = '$id'");
	if($del){
		@mysqli_query($con, "DELETE FROM purchase WHERE supplier ='$id'");
		@mysqli_query($con, "DELETE FROM transaction WHERE name ='$id'");
		@mysqli_query($con, "DELETE FROM openingbalance WHERE personid ='$id'");
	}
	$msg = 'Data deleted successfully!';
}

?>
	<div class="area">
		<div class="panel-head">Supplier Details</div>
		<div class="panel">
						<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Supplier</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Supplier<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Supplier Name</td>
    	<td><input type="text" id="name" name="name" placeholder="Enter Supplier Name"></td>
    	</tr>
		<tr>
    	<td align="right">Supplier Opening Balance</td>
    	<td><input type="text" id="opening_balance" name="opening_balance"></td>
    	</tr>
    	<tr>
    	<td align="right">Supplier Phone</td>
    	<td><input type="text" id="phone" name="phone"  placeholder="Enter Supplier Phone"></td>
    	</tr>
    	<tr>
    	<td align="right">Supplier Email</td>
    	<td><input type="text" id="email" name="email" placeholder="Enter Supplier Email"></td>
    	</tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Supplier Address</td>
    	<td><textarea placeholder="Enter Supplier Address" id="address" name="address" rows="5" cols="20"></textarea></td>
    	</tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Supplier Comments</td>
    	<td><textarea placeholder="Enter Comments" id="comments" name="comments" rows="5" cols="20"></textarea></td>
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
    	View Supplier Details<hr/>

    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<?php
	if($msg!=''){
		echo '<div class="alert alert-success">'.$msg.'</div>';
	}
	$sql = mysqli_query($con,"select * from personinformation where type = 'supplier'");
	
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Supplier Name</th>
            <th>Supplier Phone</th>
            <th>Supplier Email</th>
            <th>Supplier Address</th>
            
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
	<?php 
	while($row = mysqli_fetch_array($sql)){
		?>
    <tr>
    		
       		<td><?= ucwords($row['name']); ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= $row['email'] ?></td>
         
            <td><?= $row['address'] ?></td>
            <td>
			<a onclick="return view_earning('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a></div>
            <a onclick="return checkproductdetails('<?= $row['id'] ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a></div>
            <a onclick="return confirm('Are you sure?')" href="supplier.php?id=<?= $row['id'] ?>&del=del" id="" class="delete">Delete</a></div>
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