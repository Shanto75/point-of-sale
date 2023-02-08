<?php include("includes/header.php");?>
<script type="text/javascript">
        $(function () {
            $("#name").autocomplete("stock_purchse.php", {
                width: 160,
                autoFill: true,
                mustMatch: true,
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
	
	$.post('check_product_details.php', {productid: val},
	function (data) {
		
		$("#name").val(data.name);
		$("#status").val(data.status);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#name").val('');
		$("#status").val('');
		$("#hidden_field").html('');

}
</script>
<?php 
$msg = '';
if(isset($_POST['name']) and $_POST['name']!=''){
	$name = $_POST['name'];
	$status = $_POST['status'];
		
		@mysqli_query($con,"UPDATE `product_details` SET `status`='$status' WHERE 1 and name = '$name'");
		$msg = 'Product Updated!';

}
if(isset($_GET['id']) and strlen($_GET['id'])>0){
	$id = $_GET['id'];
	@mysqli_query($con,"UPDATE `product_details` SET `status`='active' WHERE 1 and id = '$id'");
		$msg = 'Product Updated!';
}
?>
	<div class="area">
		<div class="panel-head">Inactive Product Details</div>
		<div class="panel">
				<?php 
					if($msg!=''){
						echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
					}
				?>
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Inactive Product</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add an Inactive Product<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Product Name</td>
    	<td><input type="text" name="name" id="name" placeholder="Enter Product Name"></td>
    	</tr>
    	<tr>
        <td align="right">Status</td>
        <td>
            <select name="status" id="status">
                <option value="">Please Select</option>
                <option value="active">Active</option>
                <option value="inactive">inactive</option>
            </select>
        </td>
        </tr>
    	
    	<td colspan="2" align="right"><div id="hidden_field"></div><input type="submit" value="Go"></td>
    	</tr>
    	</table>
    	</form>
    </div>
</div>
<!--View-->
<?php 
$sql = mysqli_query($con,"select * from product_details where 1 and status != 'active'")
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
	while($row = @mysqli_fetch_array($sql)){
		?> 
    <tr>
    		
       		<td><?= ucwords($row['name']); ?></td>
            
            <td>
           
            <a href="inactive.php?id=<?= $row['id'] ?>" class="edit">Active</a></div>
            
            </td>
    </tr>
	<?php } ?>

    </tbody>
</table>
		</div>
		</div>
		</div>
		</div>
<?php 
	include("includes/footer.php");

?>