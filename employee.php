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
		$("#designation").val(data.designation);
		$("#salary").val(data.salary);
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
		$("#designation").val('designation');
		$("#salary").val('salary');
		$("#address").val('');
		$("#category").val('');
		$('#phone').val('');
		$('#email').val('');
		$('#company_name').val('');
		$('#comments').val('');
		$("#hidden_field").val('');
}
function view_earning(val){
	$('#datatable tr').remove(); 
	$.post('view_customer_details.php', {productid: val, type:'employee'},
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
	$designation = $_POST['designation'];
	$salary = $_POST['salary'];
	$address = $_POST['address'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$comments = $_POST['comments'];
	$store_id = $_SESSION['store_id'];
	if(isset($_POST['id'])){
		$id = $_POST['id'];

		mysqli_query($con,"UPDATE `personinformation` SET `name`='$name',`address`='$address',`phone`='$phone',`email`='$email',`comments`='$comments',designation = '$designation',salary = '$salary' WHERE 1 and id = '$id'");
		$msg = 'Employee Update successfully!';
	}else{
		@mysqli_query($con,"INSERT INTO `personinformation`(`name`, `address`, `phone`, `email`, `company_name`, `type`, `comments`, `store_id`,designation,salary) VALUES ('$name','$address','$phone','$email','','employee','$comments','$store_id','$designation','$salary')") or die();
		$msg = 'Employee inserted successfully!';
	}
	}
}



if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from personinformation where id = '$id'");
	$msg = 'Data deleted successfully!';
}

?>
	<div class="area">
		<div class="panel-head">Employee Details</div>
		<div class="panel">
						<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>		
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Employee</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Employee<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Employee Name</td>
    	<td><input type="text" id="name" name="name" placeholder="Enter Employee Name"></td>
    	</tr>
		<tr>
    	<td align="right">Employee Designation</td>
    	<td><input type="text" id="designation" name="designation" placeholder="Enter Employee Designation"></td>
    	</tr>
    	<tr>
    	<td align="right">Employee Phone</td>
    	<td><input type="text" id="phone" name="phone"  placeholder="Enter Employee Phone"></td>
    	</tr>
    	<tr>
    	<td align="right">Employee Email</td>
    	<td><input type="text" id="email" name="email" placeholder="Enter Employee Email"></td>
    	</tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Employee Address</td>
    	<td><textarea placeholder="Enter Employee Address" id="address" name="address" rows="5" cols="20"></textarea></td>
    	</tr>
		<tr>
    	<td align="right">Employee Salary</td>
    	<td><input type="text" id="salary" name="salary" placeholder="Enter Employee salary"></td>
    	</tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Employee Comments</td>
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
    	View Employee Details<hr/>

    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<?php
	if($msg!=''){
		echo '<div class="alert alert-success">'.$msg.'</div>';
	}
	$sql = mysqli_query($con,"select * from personinformation where type = 'employee'");
	
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Employee Designation</th>
            <th>Employee Phone</th>
            <th>Employee Email</th>
            <th>Employee Address</th>
            <th>Employee salary</th>
            
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
	<?php 
	while($row = mysqli_fetch_array($sql)){
		?>
    <tr>
    		
       		<td><?= ucwords($row['name']); ?></td>
       		<td><?= ucwords($row['designation']); ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= $row['email'] ?></td>
         
            <td><?= $row['address'] ?></td>
            <td><?= $row['salary'] ?></td>
            <td style="width:150px">
			<a onclick="return view_earning('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a></div>
            <a onclick="return checkproductdetails('<?= $row['id'] ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a></div>
            <a onclick="return confirm('Are you sure?')" href="customer.php?id=<?= $row['id'] ?>&del=del" id="" class="delete">Delete</a></div>
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