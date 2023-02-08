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
	
	$.post('check_unit_details.php', {productid: val},
	function (data) {
		
		$("#unit").val(data.name);
		$("#comments").val(data.comments);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#unit").val('');
		
		$("#comments").val('');
		$("#hidden_field").html('');

}
function view_earning(val){
	$('#datatable tr').remove(); 
	$.post('view_unit.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>
<?php 
$msg = '';
if(isset($_POST['unit']) and $_POST['unit']!=''){
	$unit = $_POST['unit'];
	$comments = $_POST['comments'];
	if(!isset($_POST['id'])){
		$catchk = @mysqli_fetch_array(mysqli_query($con,"select * from unit where name = '$unit'"));
		if(empty($catchk)){
			@mysqli_query($con,"INSERT INTO `unit`(`name`, `store_id`, `comments`) VALUES ('$unit','1','$comments')");
			$msg = 'Unit Inserted!';
		}
	}else{
		$id = $_POST['id'];
		@mysqli_query($con,"UPDATE `unit` SET `name`='$unit',`comments`='$comments' WHERE 1 and id = '$id'");
		$msg = 'Unit Updated!';
	}
}
if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from unit where id = '$id'");
	$msg ='Data deleted successfully!';
}
?>
	<div class="area">
		<div class="panel-head">Unit Details</div>
		<div class="panel">
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>		
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Unit</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Customer<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Unit Name</td>
    	<td><input type="text" name="unit" id="unit" placeholder="Enter Unit Name"></td>
    	</tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Unit Comments</td>
    	<td><textarea placeholder="Enter Comments" name="comments" id ="comments" rows="5" cols="20"></textarea></td>
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
    	View Band Details<hr/>

    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<!--View-->
<?php 
$sql = mysqli_query($con,"select * from unit where 1");
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Unit Name</th>
            <th>Comments</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
	while($row = @mysqli_fetch_array($sql)){
		?>
    <tr>
    		
       		<td><?= ucwords($row['name']); ?></td>
       		<td><?= $row['comments']; ?></td>
            
            <td>
            <a onclick="return view_earning('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a></div>
            <a onclick="return checkproductdetails('<?= $row['id']; ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a></div>
            <a href="javascript:;"id="" class="delete">Delete</a></div>
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
