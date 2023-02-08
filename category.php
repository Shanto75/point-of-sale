<?php include("includes/header.php");?>
<script type="text/javascript">
        $(function () {
            $("#category").autocomplete("stock_category.php", {
                width: 160,
                autoFill: true,
                
                selectFirst: true
            });
        });

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
	
	$.post('check_category_details.php', {productid: val},
	function (data) {
		
		$("#category").val(data.category);
		$("#description").val(data.description);
		$("#comments").val(data.comments);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#category").val('');
		$("#description").val('');
		$("#comments").val('');
		$("#hidden_field").html('');

}
function view_earning(val){
	$('#datatable tr').remove(); 
	$.post('view_category_details.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>
<?php 
$msg = '';
if(isset($_POST['category']) and $_POST['category']!=''){
	$category = $_POST['category'];
	$description = $_POST['description'];
	$comments = $_POST['comments'];
	if(!isset($_POST['id'])){
		$catchk = @mysqli_fetch_array(mysqli_query($con,"select * from category where category_name = '$category'"));
		if(empty($catchk)){
			@mysqli_query($con,"INSERT INTO `category`(`category_name`, `comments`, `store_id`, `description`) VALUES ('$category','$comments','1','$description')");
			$msg = 'Category Inserted!';
		}
	}else{
		$id = $_POST['id'];
		@mysqli_query($con,"UPDATE `category` SET `category_name`='$category', `comments`='$comments', `description`='$description' WHERE 1 and id = '$id'");
		$msg = 'Category Updated!';
	}
}
if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from category where id = '$id'");
	$msg = 'Data deleted successfully!';
}


?>
	<div class="area">
		<div class="panel-head">Category Details</div>
		<div class="panel">
						<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>	
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Category</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Category<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Category Name</td>
    	<td><input type="text" name="category" id="category" placeholder="Enter category Name" required></td>
    	</tr>
        <tr>
        <td align="right">Category Description</td>
        <td>
            <textarea name="description" id="description" rows="5" cols="20"></textarea>
        </td>
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
    	View Category Details<hr/>

    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<!--View-->
<?php 
$sql = mysqli_query($con,"select * from category where 1");
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Category Name</th>
            <th>Category Description</th>
            <th>Category Comments</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
	while($row = @mysqli_fetch_array($sql)){
		?>
    <tr>
    		
       		<td style="text-align:center"><?= ucwords($row['category_name']); ?></td>
       		<td style="text-align:center"><?= $row['description']; ?></td>
       		<td style="text-align:center"><?= $row['comments']; ?></td>
            <td style="text-align:center">
           <a onclick="return view_earning('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a></div>
            <a onclick="return checkproductdetails('<?= $row['id']; ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a></div>
            <a href="category.php?id=<?= $row['id'] ?>&del=del"  class="delete">Delete</a></div>
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
