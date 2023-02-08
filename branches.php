<?php include("includes/header.php");?>
<script type="text/javascript">
$(function () {

            $("#branch_manager").autocomplete("employee1.php", {
                width: 250,
                autoFill: true,
                selectFirst: true
            });
});
function checkproductdetails(val){
	
	$.post('check_branch.php', {productid: val},
	function (data) {
		
		$("#branch_name").val(data.branch_name);
		$("#branch_address").val(data.branch_address);
		$("#branch_manager").val(data.branch_manager);
		$("#branch_phone").val(data.branch_phone);
		$("#branch_email").val(data.branch_email);		
		$("#comments").val(data.comments);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#branch_name").val('');
		$("#branch_address").val('');
		$("#branch_manager").val('');
		$("#branch_phone").val('');
		$("#branch_email").val('');		
		$("#comments").val('');
		$("#hidden_field").html('');

}
function branch_view(val){
	$('#branch_view tr').remove(); 
	$.post('view_branch.php', {productid: val},
	function (data) {		
		$(data).appendTo('#branch_view');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>
<?php 
$msg = '';
if(isset($_POST['branch_name'])){
	$branch_name = $_POST['branch_name'];
	$branch_address = $_POST['branch_address'];
	$branch_manager = $_POST['branch_manager'];
	$branch_phone = $_POST['branch_phone'];
	$branch_email = $_POST['branch_email'];
	$comments = $_POST['comments'];
	if(!isset($_POST['id'])){
		@mysqli_query($con,"INSERT INTO `branch`(`branch_name`, `branch_phone`, `branch_manager`, `branch_email`, `branch_address`, `store_id`, `comments`) VALUES ('$branch_name','$branch_phone','$branch_manager','$branch_email','$branch_address','1','$comments')");
		$msg = 'Data inserted Successfully';
	}else{
		$id= $_POST['id'];
		@mysqli_query($con,"UPDATE `branch` SET `branch_name`='$branch_name',`branch_phone`='$branch_phone',`branch_manager`='$branch_manager',`branch_email`='$branch_email',`branch_address`='$branch_address',`comments`='$comments' WHERE 1 and id = '$id'");
		$msg = 'Data Updated Successfully';
	}
}
if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from branch where id = '$id'");
	echo 'Data deleted successfully!';
}
?>
	<div class="area">
		<div class="panel-head">Branches Details</div>
		<div class="panel">
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Branch</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Branch<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Branch Name</td>
    	<td><input type="text" name="branch_name" id="branch_name" placeholder="Enter branch name" required></td>
    	</tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Branch Address</td>
    	<td><textarea placeholder="Enter Branch Address" name="branch_address" id="branch_address" rows="5" cols="20"></textarea></td>
    	</tr>
    	<tr>
    	<td align="right">Branch Manager Name</td>
    	<td>
    		<input type="text" name="branch_manager" id="branch_manager" placeholder="Enter branch manager name" required>
    	</td>
    	</tr>
    	<tr>
    	<td align="right">Branch Phone</td>
    	<td><input type="text" name="branch_phone" id="branch_phone" placeholder="Enter branch phone"></td>
    	</tr>

    	<tr>
    	<td align="right">Branch Email</td>
    	<td><input type="text" name="branch_email" id="branch_email" placeholder="Enter branch email"></td>
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
    	View Branch Details<hr/>
    	
    	<table id="branch_view" class="tab">

    	</table>
    	
    </div>
</div>
<!--Edit-->
<?php 
	$sql = @mysqli_query($con,"SELECT * FROM `branch` WHERE 1");
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Branch Name</th>
            <th>Branch Phone</th>
            <th>Manager Name</th>
            <th>Branch Email</th>
            <th>Branch Address</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
while($row = @mysqli_fetch_array($sql)){
	

?>
    <tr>
    		<td><?= ucwords($row['branch_name']) ?></td>
       		<td><?= $row['branch_phone'] ?></td>
            <td><?= ucwords($row['branch_manager']) ?></td>
            <td><?= $row['branch_email'] ?></td>
            <td><?= $row['branch_address'] ?></td>
            <td>
            <a onclick="return branch_view('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a></div>
            <a onclick="return checkproductdetails('<?= $row['id']; ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a></div>
            <a onclick="return confirm('Are you sure?')" href="branches.php?id=<?= $row['id'] ?>&del=del" id="" class="delete">Delete</a></div>
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
