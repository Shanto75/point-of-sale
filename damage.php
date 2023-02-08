<?php include("includes/header.php");?>
	<script type="text/javascript">
	$(function () {
		$("#name").autocomplete("stock_purchse.php", {
                width: 160,
                autoFill: true,
                mustMatch: false,
                selectFirst: true
            });
	})
	
function setSelectedValue(selectObj, valueToSet) {
    for (var i = 0; i < selectObj.options.length; i++) {
        if (selectObj.options[i].text== valueToSet) {
            selectObj.options[i].selected = true;
            return;
        }
    }
}
function checkproductdetails(val){

	$.post('check_damage_details.php', {productid: val},
	function (data) {
		$(data).appendTo('#damage_table');
	});
	
	
}
function emptyform(){
		$("#name").val('');
		$("#quantity").val('');
		$("#comments").val('');
		$("#hidden_field").html('');
}
</script>
<?php 
$msg = '';
if(isset($_POST['name']) and $_POST['name']!=''){
	
	$name1 = explode('<>',$_POST['name']);
	$name = $name1[0];
	$code = $name1[1];
	$quantity = $_POST['quantity'];
	$comments = $_POST['comments'];
	$date = date('Y-m-d');
	if($quantity!=''){
		
		@mysqli_query($con,"UPDATE `stock` SET `quantity`=`quantity`-'$quantity' WHERE 1 and product_name = '$name' and code='$code'");
		@mysqli_query($con,"INSERT INTO `damage_stock`(`name`, `quantity`, `comments`, `store_id`,date,code) VALUES ('$name','$quantity','$comments','1','$date','$code')");
		$msg = 'Data Inserted Successfully';
	}
}
if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from damage_stock where id = '$id'");
	$msg ='Data deleted successfully!';
}
?>
    <div class="area">
        <div class="panel-head">Damage Product Details</div>
        <div class="panel">
				<?php 
					if($msg!=''){
						echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
					}
				?>
        <div class="btn">   
        <a onclick="return emptyform()" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Damage Product</a></div>
    <!--Add-->  
        <div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
        Add a Damage Product<hr/>
        <form action="" method="post" class="form">
        <table class="tab">
        <tr> 
        <td align="right">Product Name</td>
        <td><input type="text" name="name" id="name" placeholder="Enter Product Name"></td>
        </tr>
        <tr>
        <td align="right">Damage Quantity</td>
        <td><input type="text" name="quantity" id="quantity" placeholder="Enter Quantity"></td>
        </tr>
        <tr>
        <td valign="top" style="padding-top:20px;" align="right">Comments</td>
        <td><textarea name="comments" id="comments" placeholder="Enter Comments" rows="5" cols="20"></textarea></td>
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
        View Damage Product<hr/>
        
        <table id="damage_table" class="tab">

        
        </table>
        
    </div>
</div>
<!--Edit-->
<?php 
$sql = mysqli_query($con,"select * from damage_stock where 1");
?>

        <table id="table_id" class="display">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Damage Quantity</th>
            <th>Date</th>
            <th>Available Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
	while($row = mysqli_fetch_array($sql)){
		$sa = @mysqli_fetch_array(@mysqli_query($con,"SELECT * FROM `stock` WHERE 1 and `product_name` = '".$row['name']."' and code='".$row['code']."'"));
		$stock_amount = $sa['quantity'];
?>
    <tr>
            
            <td><?= $row['name'].' '.$row['code'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= daterev($row['date'],'/','-') ?></td>
            <td><?= $stock_amount ?></td>
        
            <td>
            <a onclick="return checkproductdetails(<?= $row['id'] ?>)" href="javascript:;"id="modal2-launcher" class="view">View</a></div>
           
            <a href="damage.php?id=<?= $row['id'] ?>&del=del"id="" class="delete">Delete</a></div>
            </td>
    </tr>
	<?php } ?>

    </tbody>
</table>
        </div>
        </div>
        </div>
        </div>
    <script type="text/javascript">
    $(document).ready( function () {
    $('#table_id').DataTable();
} );
    </script>
    <?php include("includes/footer.php");?>
</body>
</html>
