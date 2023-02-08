<?php include("includes/header.php");?>
<style type="text/css">
	.select_style 
{
	background: #FFF;
	overflow: hidden;
	display: inline-block;
	color: #fff;
	font-size: 15px;
	-webkit-border-radius: 5px 4px 4px 5px/5px 5px 4px 4px;
	-moz-border-radius: 5px 4px 4px 5px/5px 5px 4px 4px;
	border-radius: 5px 4px 4px 5px/5px 5px 4px 4px;
	-webkit-box-shadow: 0 0 5px rgba(123, 123, 123, 0.2);
	-moz-box-shadow: 0 0 5px rgba(123,123,123,.2);
	box-shadow: 0 0 5px rgba(123, 123, 123, 0.2);
	border: solid 1px #ccc;
	font-family: "helvetica neue",arial;
	position: relative;
	top:7px; ;
	cursor: pointer;
	padding-right:20px;

}
.select_style span
{
	position: absolute;
	right: 10px;
	width: 8px;
	height: 8px;
	background: url(http://projects.authenticstyle.co.uk/niceselect/arrow.png) no-repeat;
	top: 50%;
	margin-top: -4px;
}
.select_style select
{
	-webkit-appearance: none;
	appearance:none;
	width:120%;
	background:none;
	background:transparent;
	border:none;
	outline:none;
	cursor:pointer;
	padding:7px 10px;
}
#dropdown 
{
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
</style>
<script type="text/javascript">
$(function () {

	$("#category").autocomplete("category1.php", {
		width: 250,
		autoFill: true,
		selectFirst: true
	});
	$("#brand").autocomplete("brand1.php", {
		width: 250,
		autoFill: true,
		selectFirst: true
	});
});
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
		$("#product_code").val(data.product_code);
		$("#category").val(data.category);
		$('#purchase_cost').val(data.purchase_cost);
		$('#sale_price').val(data.sale_price);
		$('#wholesale_price').val(data.wholesale_price);
		$('#quantity').val(data.quantity);
		$('#minquantity').val(data.minquantity);
		$('#unit').val(data.unit_type);
		$('#status').val(data.status);
		$('#brand').val(data.brand);
		$('#description').val(data.description);
		$('#vat').val(data.vat);
		$('#warranty').val(data.warranty);
		$('#expire_date').val(data.expire_date);
		$('#comments').val(data.comments);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
	$("#name").val('');
	$("#product_code").val('');
	$("#category").val('');
	$('#purchase_cost').val('');
	$('#sale_price').val('');
	$('#wholesale_price').val('');
	$('#quantity').val('');
	$('#unit_type').val('');
	$('#status').val('');
	$('#brand').val('');
	$('#description').val('');
	$('#vat').val('');
	$('#warranty').val('');
	$('#expire_date').val('');
	$('#comments').val('');
	$('#hidden_field').html('');
}
function view_product(val){
	$('#datatable tr').remove(); 
	$.post('view_product_details.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});

	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function view_product1(){
	$('#file_upload').html();
	var data = '<form action="csvupload.php" method="post" enctype="multipart/form-data"><table><tr><td>Import csv file</td><td><input type="file" name="csv"/></td></tr><tr><td></td><td><input type="submit" value="upload"/></td></tr></table></form>'
	$(data).appendTo('#file_upload');
}
function toggle(source) {
  checkboxes = document.getElementsByName('barcode[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
<?php
if(isset($_POST['name'])){
	
	$name = trim($_POST['name']);
	$product_code = trim($_POST['product_code']);
	$category = trim($_POST['category']);
	$catchek = mysqli_fetch_array(mysqli_query($con,"select * from category where category_name = '$category'"));
	if(empty($catchek)){
		mysqli_query($con,"insert into category (category_name) values ('$category')");
	}
	$purchase_cost = trim($_POST['purchase_cost']);
	$sale_price = trim($_POST['sale_price']);
	$wholesale_price = trim($_POST['wholesale_price']);
	$quantity = trim($_POST['quantity']);
	$minquantity = trim($_POST['minquantity']);
	$unit = trim($_POST['unit']);
	$brand = trim($_POST['brand']);
	$bndchek = mysqli_fetch_array(mysqli_query($con,"select * from brand where brand_name = '$brand'"));
	if(empty($bndchek)){
		mysqli_query($con,"insert into brand (brand_name) values ('$brand')");
	}
	$vat = trim($_POST['vat']);
	$warranty = trim($_POST['warranty']);
	if($_POST['expire_date']!=''){
	$expire_date = explode('/',$_POST['expire_date']);
	$expire_date = $expire_date[2].'-'.$expire_date[1].'-'.$expire_date[0];}
	else{
		$expire_date='';
	}
	$description = mysqli_real_escape_string($con,$_POST['description']);
	$comments = mysqli_real_escape_string($con,$_POST['comments']);
	$product_add_date = date('Y-m-d');
	$product_update_date = date('Y-m-d');
	$store_id = $_SESSION['store_id'];
	$status = $_POST['status'];
	if(isset($_POST['custom1'])){
		$custom1 = $_POST['custom1'];
	}else{
		$custom1 ='';
	}
	if(isset($_POST['custom2'])){
		$custom2 = $_POST['custom2'];
	}else{
		$custom2 ='';
	}
	if(isset($_POST['custom3'])){
		$custom3 = $_POST['custom3'];
	}else{
		$custom3 ='';
	}
	if(isset($_POST['custom4'])){
		$custom4 = $_POST['custom4'];
	}else{
		$custom4 ='';
	}
	if(isset($_POST['custom5'])){
		$custom5 = $_POST['custom5'];
	}else{
		$custom5 ='';
	}
	$img = '';
if(basename($_FILES["photo"]["name"]!='')){
		
		$target_dir = "product_image/";
		$target_file = $target_dir . basename($_FILES["photo"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

		if($imageFileType == "jpg" or $imageFileType == "png" or $imageFileType == "jpeg"
		or $imageFileType == "gif" ) {
			
				$image = explode('.',$_FILES["photo"]["name"]);
				$img = $image[0].time().'.'.$image[1];
				$imagpath = $target_dir.$img;
				@move_uploaded_file($_FILES["photo"]["tmp_name"], $imagpath);
			
		}else{
			$img = '';
		}
	}
	if(!isset($_POST['id'])){
	
		$max = mysqli_fetch_array(mysqli_query($con,"SELECT max(id) as id FROM `product_details` WHERE 1 "));
		$max = $max['id'] + 1;
		$autoid = "SD" . $max . "";

		
		
		
		$sql = mysqli_query($con,"INSERT INTO `product_details`(`name`, `product_code`, `category`, `purchase_cost`, `sale_price`, `wholesale_price`, `unit_type`, `brand`, `description`, `vat`, `warranty`, `expire_date`, `product_add_date`, `product_update_date`, `comments`, `store_id`,stock_id,status,minquantity,`custom1`, `custom2`, `custom3`, `custom4`, `custom5`,`image`) VALUES ('$name','$product_code','$category','$purchase_cost','$sale_price','$wholesale_price','$unit','$brand','$description','$vat','$warranty','$expire_date','$product_add_date','','$comments','$store_id','$autoid','$status','$minquantity','$custom1','$custom2','$custom3','$custom4','$custom5','$image')") or die(mysqli_error($con));
		if($sql){
			$max = mysqli_fetch_array(mysqli_query($con,"SELECT max(id) as id FROM `product_details` WHERE 1 "));

			@mysqli_query($con,"INSERT INTO `stock`(`product_name`, `quantity`, `category`, `unit`, `store_id`,`code`,`product_id`) VALUES ('$name','$quantity','$category','$unit','$store_id','$product_code','".$max['id']."')");
		

			$msg = 'Product Inserted successfully!';
		}
	}else{
		if($img ==''){
		$ma = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `product_details` WHERE 1 and id='".$_POST['id']."'"));
		$img = $ma['image'];
		}
		$pid = $_POST['id'];
		
		$checkstock = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM stock WHERE product_id='$pid'")) ;

		if(!empty($checkstock)){
			@mysqli_query($con,"UPDATE `stock` SET `product_name`='$name',`quantity`='$quantity',`category`='$category',`unit`='$unit',`store_id`='$store_id',`code`='$product_code' WHERE 1 and product_id ='".$checkstock['product_id']."'");
		}else{
			@mysqli_query($con,"INSERT INTO `stock`(`product_name`, `quantity`, `category`, `unit`, `store_id`,`code`,`product_id`) VALUES ('$name','$quantity','$category','$unit','$store_id','$product_code','$pid')");
		}


		$sql = mysqli_query($con,"UPDATE `product_details` SET `name`='$name',`product_code`='$product_code',`category`='$category',`purchase_cost`='$purchase_cost',`sale_price`='$sale_price',`wholesale_price`='$wholesale_price',`unit_type`='$unit',`brand`='$brand',`description`='$description',`vat`='$vat',`warranty`='$warranty',`expire_date`='$expire_date',`product_update_date`='$product_update_date',`comments`='$comments',`store_id`='$store_id',minquantity = '$minquantity',`custom1` = '$custom1',`custom2` = '$custom2',`custom3` = '$custom3',`custom4` = '$custom4',`custom5`= '$custom5', status = '$status', `image` = '$img' WHERE 1 and id = '$pid'") or die(mysqli_error($con));
		if($sql){
			$msg = 'Product Updated successfully!';
		}
	}
}
if(isset($_GET['act']) and $_GET['act']=='del'){
	$id = $_GET['id'];
	$pname = mysqli_fetch_array(mysqli_query($con,"select name from product_details where id= '$id'"));
	@mysqli_query($con,"delete from stock where product_name = '".$pname['name']."'");
	@mysqli_query($con,"delete from product_details where id = '$id'");
	$msg = 'Data deleted successfully!';
}

?>
	<div class="area">
		<div class="panel-head">Product Details</div>
		<div class="panel">
						<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>			
		<div class="btn">	
		<a onclick="emptyform()" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Product</a></div>
		<div style="margin-right:10px" class="btn">	
		<a onclick="return view_product1();" href="javascript:;"id="modal2-launcher" class="btn-add">+ Add Bulk Product</a></div>
		<div style="margin-right:10px" class="btn">	
		<a href="export_product.php" class="btn-add">Export Product</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Product<hr/>
    	<form action="" method="post" enctype="multipart/form-data" class="form">
    	<table class="tab">
    	<tr> 
    	<td align="right">Product Name</td>
    	<td><input type="text" id="name" name="name" placeholder="Enter Product Name" required></td>
    	</tr>
        <tr>
        <td align="right">Product Code</td>
        <td><input type="text" id="product_code" name="product_code" placeholder="Enter Product code" required></td>
        </tr>
        <tr>
        <td align="right">Product Category</td>
        <td>
            <input class="form-control form" id="category" name="category" placeholder="Enter Category" type="text" required>
        </td>
        </tr>
         <tr>
        <td align="right">Purchase Cost</td>
        <td><input class="form-control form" id="purchase_cost" name="purchase_cost" placeholder="Enter Purchase cost" type="text" required></td>
        </tr>
        <tr>
        <td align="right">Retail Sell Price</td>
        <td><input class="form-control form" id="sale_price" name="sale_price" placeholder="Enter Sale Price" type="text" required></td>
        </tr>
        <tr>
        <td align="right">Wholesale Price</td>
        <td><input class="form-control form" id="wholesale_price" name="wholesale_price" placeholder="Enter Wholesale Price" type="text"></td>
        </tr>
        <tr>
        <td align="right">Stock</td>
        <td><input class="form-control form" id="quantity" name="quantity" placeholder="Enter Quantity" type="text"></td>
        </tr>
		<tr>
        <td align="right">Minimum Stock Quantity</td>
        <td><input class="form-control form" id="minquantity" name="minquantity" placeholder="Enter  Quantity" type="text"></td>
        </tr>
       
        <tr>
        <td align="right">Unit type</td>
        <td>
            <select style="" class="form-control form" id="unit" name="unit">
               <?php 
			$qunit = mysqli_query($con,"select * from unit where 1");
			echo '<option value="">Please select</option>';
			while($row = mysqli_fetch_array($qunit)){
				echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
			}
		  ?>
            </select>
        </td>
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
         <tr>
        <td align="right">Brand Name</td>
        <td>
            <input class="form-control form" id="brand" name="brand" placeholder="Enter Brand" type="text">
        </td>
        </tr>
        <tr>
        <td valign="top" style="padding-top:20px;" align="right">Description</td>
        <td><textarea rows="5" cols="20" id="description" name="description" type="text"></textarea></td>
        </tr>
    	<tr>
    	<td align="right">Vat</td>
    	<td><input class="form-control form" id="vat" name="vat" type="text"></td>
    	</tr>
    	<tr>
    	<td align="right">Warranty</td>
    	<td><input class="form-control form" id="warranty" name="warranty" type="text"></td>
    	</tr>
        <tr>
        <td align="right">Expiery Date</td>
        <td><input class="form-control form datepick" id="expire_date" name="expire_date" type="text"></td>
        </tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Comments</td>
    	<td><textarea  id="comments" name="comments" rows="5" cols="20" type="text"></textarea></td>
    	</tr>
		<tr>
    	<td valign="top" align="right">Image</td>
    	<td><input type="file" name="photo"></td>
    	</tr>
<?php 
	$dyn = mysqli_query($con,"select * from custom_field where 1");
	if(mysqli_num_rows($dyn)>0){
	$dydata = '';
	while($d = mysqli_fetch_array($dyn)){
		$dydata .='<tr><td align="right">'.$d['title'].'</td>';
		if($d['type']=='input'){
			$dydata .= '<td><input type="text" name="'.$d['name'].'" /></td>';
		}elseif($d['type']=='radio' or $d['type']=='checkbox'){
			$ar = array('value1','value2','value3','value4','value5');
			$dydata .= '<td>';
			for($k=0;$k<sizeof($ar);$k++){
				if($d[$ar[$k]]!=''){
					$dydata .= '<input type="'.$d['type'].'" name="'.$d['name'].'" value="'.$d[$ar[$k]].'" />&nbsp;&nbsp;'.$d[$ar[$k]];
				}
			}
			$dydata .= '</td>';
		}else if($d['type']=='select'){
			$ar = array('value1','value2','value3','value4','value5');
			$dydata .= '<td><select id="'.$d['name'].'" name="'.$d['name'].'">';
			for($k=0;$k<sizeof($ar);$k++){
				if($d[$ar[$k]]!=''){
					$dydata .= '<option value="'.$d[$ar[$k]].'" />'.$d[$ar[$k]].'</option>';
				}
			}
			$dydata .= '</select></td>';
		}
		$dydata .='</tr>';
	}
	}
	echo $dydata;
?>		

		<tr>
    	
    	<td colspan="2" align="right">
		<div id="hidden_field"></div>
		<input type="submit" value="Save"></td>
    	</tr>
        
    	  	</table>
    	</form>
    </div>
</div>
<div id="modal2-background"></div>
<div id="modal2-content">
    <button id="modal2-close">Close Modal Window</button>
    <div class="modal2-form">
    	View Product Details<hr/>
		<div id="file_upload"></div>
    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<!--View-->
<?php 
$whereclass = '';
if(isset($_GET['status']) and $_GET['status']!=''){
	$status = $_GET['status'];
	$whereclass .=" and status = '$status'";
}
$sql = mysqli_query($con,"select * from product_details where 1 $whereclass");
?>
<form action="" method="get">
Status : 
	<div class="select_style"><select id="dropdown" name="status">
		<option value="">Select All</option>
		<option value="active" <?= $_GET['status']=='active'?'selected="selected"':'' ?>>Active</option>
		<option value="inactive" <?= $_GET['status']=='inactive'?'selected="selected"':'' ?>>Inactive</option>		
	</select><span></span></div>
	<input type="submit" value="submit"/>
</form>
<br />
<form action="generate_barcode.php" method="post">
<div style="margin-bottom:10px" class="head_side">
	<input type="submit" value="Generate Barcode" />
</div>
		<table id="table_id" class="display">
    <thead>
        <tr>
			<th><input type="checkbox" onclick="toggle(this)"/></th>
            <th>UPC/EAN/ISBN</th>
            <th>Product Name</th>
            <th>Purchase Cost</th>         
            <th>Retail Price</th>
            
            <th>Category</th>
            <th>status</th>

            
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
	while($row = @mysqli_fetch_array($sql)){
		?>  
  <tr>
    		<td><input type="checkbox" name="barcode[]" value="<?= $row['id'] ?>" /></td>			
       		<td><?= ($row['product_code']); ?></td>
       		<td><?= ucwords($row['name']); ?></td>
            <td><?= ($row['purchase_cost']); ?></td>
            <td><?= ($row['sale_price']); ?></td>
         
            <td><?= ($row['category']); ?></td>
			<td width="50px"><?= $row['status']=='active'?'<span style="color:Green">'.ucwords($row['status']).'</span>':'<span style="color:red">'.ucwords($row['status']).'</span>'; ?></td>
            <td width="180px">
			<a onclick="return view_product('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a></div>
            <a onclick="return checkproductdetails('<?= $row['id'] ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a></div>
            <a onclick="return confirm('Are you sure?')" href="product.php?id=<?= $row['id'] ?>&act=del" id="" class="delete">Delete</a></div>
            </td>
    </tr>
	<?php } ?>
    </tbody>
</table>
</form>
		</div>
		</div>
		</div>
		</div>
<?php 
	include("includes/footer.php");

?>