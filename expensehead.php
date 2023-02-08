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
	
	$.post('check_expense_head.php', {productid: val},
	function (data) {
		
		$("#head").val(data.head);
		$("#comments").val(data.comments);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#head").val('');
		
		$("#comments").val('');
		$("#hidden_field").html('');

}
function view_earning(val){
	$('#datatable tr').remove(); 
	$.post('view_expense.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>
<?php 
$msg = '';
if(isset($_POST['head']) and $_POST['head']!=''){
	$head = $_POST['head'];
	$comments = $_POST['comments'];
	if(!isset($_POST['id'])){
		mysqli_query($con,"INSERT INTO `expense_head`(`head`, `store_id`, `comments`) VALUES ('$head','1','$comments')");
		$msg = 'Data inserted Successfully!';
	}else{
		$id = $_POST['id'];
		mysqli_query($con,"UPDATE `expense_head` SET `head`='$head',`comments`='$comments' WHERE 1 and id = '$id'");
		$msg = 'Data Updated Successfully!';
	}
}
if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from expense_head where id = '$id'");
	$msg ='Data deleted successfully!';
}
?>
	<div class="area">
		<div class="panel-head">Expense Head Details</div>
		<div class="panel">
				<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Expense Head</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Expense Head<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Expense Head Name</td>
    	<td><input type="text" name="head" id="head" ></td>
    	</tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Expense Head Comments</td>
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
    	View Expense Head<hr/>
    	
    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<!--View-->
<?php 
$sql = mysqli_query($con,"select * from expense_head where 1");
?>

		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Expense Head Name</th>
            <th>Expense Head Comments</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
	<?php 
	while($row = @mysqli_fetch_array($sql)){
		?>
    <tr>
    		
       		<td><?= $row['head'] ?></td>
       		<td><?= $row['comments'] ?></td>
            
            <td>
          <a onclick="return view_earning('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a></div>
            <a onclick="return checkproductdetails('<?= $row['id']; ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a></div>
            <a onclick="return confirm('Are you sure?')" href="earnhead.php?id=<?= $row['id'] ?>&del=del" id="" class="delete">Delete</a></div>
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
