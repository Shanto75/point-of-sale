<?php
include("includes/header.php");
$msg = '';
	$whereclass = '';
	if(isset($_GET['stock'])){
		if($_GET['stock']=='category' and $_GET['category']!=''){
			$whereclass .= " and category = '".$_GET['category']."' order by name asc";
			$msg = 'Short By '.$_GET['category'].' Category';
		}
		if($_GET['stock']=='brand' and $_GET['brand']!=''){
			$whereclass .= " and brand = '".$_GET['brand']."' order by name asc";
			$msg = 'Short By '.$_GET['brand'].' Brand';
		}
		if($_GET['stock']=='expire_date' and $_GET['expire_date']!=''){
			$expire_date = modifydate($_GET['expire_date'],'-','/');
			$whereclass .= " and expire_date < '".$expire_date."' order by name asc";
			$msg = 'Short By Expire date '.$_GET['expire_date'];
		}
		if($_GET['stock']=='product_name'){
			
			$whereclass .= " order by name asc";
		}
	}else{
		$whereclass .= " order by name asc";
	}
	$sql = mysqli_query($con,"SELECT * FROM `product_details` WHERE 1 $whereclass");

?>
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
	function stock_short(val){
		if(val=='category'){
			$("#brand").css("display", "none");
			$("#expire_date").css("display", "none");
			$("#category").css("display", "block");
		}else if(val=='brand'){
			$("#category").css("display", "none");
			$("#expire_date").css("display", "none");
			$("#brand").css("display", "block");
		}else if(val=='expire_date'){
			$("#expire_date").css("display", "block");
			$("#category").css("display", "none");
			$("#brand").css("display", "none");
		}
		else{
			$("#category").css("display", "none");
			$("#brand").css("display", "none");
			$("#expire_date").css("display", "none");
		}
	}
function sendForm() {
     document.myform.submit();
}
function checkproductdetails(val){
	$('#datatable tr').remove(); 
	$.post('view_product_details.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>

<div class="area">
		<div class="panel-head">Stock Reports</div>
		<div class="panel">
		
<?php include('left_menu.php'); ?>
<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Modal Window</button>
    <div class="modal-form">
    	View Payment Details<hr/>
    	
    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<div class="report_right">
<form style="overflow:hidden;margin-bottom: 30px;height: 50px;" id="myform" name="myform" action="" method="get">
<div style="inline:block;float:left;margin-right:10px" id="stock_short">
<span style="inline:block;float:left;margin-right:10px">
Sorting Stock By
<div class="select_style">
    <select  name="stock" id="dropdown" onchange="return stock_short(this.value);">
        <option value="product_name">Product Name</option>
        <option value="category">Category</option>
        <option value="brand">Brand</option>
        <option value="expire_date">Expire date</option>
    </select>
    <span></span>
    </div>
</span>	
	<span style="display:none;float:left" id="category">Select Category : &nbsp;&nbsp;
		<div class="select_style">
		<select name="category" id="dropdown" onchange="sendForm()">
			<option value="">Select one</option>
	<?php 
		$catlist = mysqli_query($con,"select * from category where 1");
		while($c = mysqli_fetch_array($catlist)){
			echo "<option value='".$c['category_name']."'>".$c['category_name']."</option>";
		}
	?>
		</select>
		<span></span>
		</div>
	</span>
	<span style="display:none;float:left" id="brand">Brand Name : &nbsp;&nbsp;
	<div class="select_style">
	<select  id="dropdown" name="brand" onchange="sendForm()" required>
			<option value="">Please select</option>
			<?php 
				$blist = mysqli_query($con,"select * from brand where 1");
				while($b = mysqli_fetch_array($blist)){
					echo "<option value='".$b['brand_name']."'>".$b['brand_name']."</option>";
				}
			?>
	</select>
	<span></span>
	</div>
	</span>
	<span style="display:none;float:left;" id="expire_date">Expire Date : &nbsp;&nbsp;<input id="" placeholder="dd/mm/yy" class="datepick" type="text" name="expire_date" required />
	<input type="submit" name="submits" value="submit"/>
	</span>
</div>
<br /><br />
</form>
<div style="overflow:hidden;padding:10px;margin-bottom:10px;text-align: right;" >			
		<a  href="generate_stock_report.php" class="btn-add">Export</a>
</div>
<div class="table_data">
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Category Name</th>
            <th>Retail Price</th>
            <th>Wholesale Price</th>
            <th>Warranty</th>
            <th>Expiery Date</th>
            <th>Stock Amount</th>            
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
	while($row = mysqli_fetch_array($sql)){
		$sa = @mysqli_fetch_array(@mysqli_query($con,"SELECT * FROM `stock` WHERE 1 and `product_name` = '".$row['name']."' and code='".$row['product_code']."'"));
		$stock_amount = $sa['quantity'];
?>
    <tr>
    		
       		<td><?= ucwords($row['name']) ?></td>
            <td><?= ucwords($row['category']) ?></td>
            <td><?= number_format((float)$row['sale_price'], 2, '.', '') ?></td>
            <td><?= number_format((float)$row['wholesale_price'], 2, '.', '') ?></td>
            <td><?= ($row['warranty']) ?></td>
         <td><?= daterev($row['expire_date'],'/','-') ?></td>
            <td><?= $stock_amount ?></td>
            <td>
            <a onclick="return checkproductdetails('<?= $row['id'] ?>');" href="javascript:;"id="modal-launcher" class="view">View</a></div>
            
            </td>
    </tr>
	<?php } ?>
    </tbody>
</table>
</div>
</div>

</div>
</div>
</body>
</html>
<?php 
	require('includes/footer.php');
?>