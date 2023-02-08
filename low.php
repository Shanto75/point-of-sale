<?php include("includes/header.php");?>

<script type="text/javascript">


function checkproductdetails(val){
	$('#datatable tr').remove(); 
	$.post('view_product_details.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>

<?php 
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
	<div class="area">
		<div class="panel-head">Low Stock</div>
		<div class="panel">
		<div class="btn">	

</div>
<!--View-->
<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Modal Window</button>
    <div class="modal-form">
    	View Product Details<hr/>
    	
    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<h3>

</h3>
<?php 
if($msg !=''){
	echo '<h2>'.$msg.'</h2>';
}
?>
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
		$sa = @mysqli_fetch_array(@mysqli_query($con,"SELECT * FROM `stock` WHERE 1 and `product_name` = '".$row['name']."'"));
		$stock_amount = $sa['quantity'];
		if($stock_amount<=$row['minquantity']){
?>
    <tr>
    		
       		<td><?= ucwords($row['name']) ?></td>
            <td><?= ucwords($row['category']) ?></td>
            <td><?= ($row['sale_price']) ?></td>
            <td><?= ($row['wholesale_price']) ?></td>
            <td><?= ($row['warranty']) ?></td>
         <td><?= daterev($row['expire_date'],'/','-') ?></td>
            <td><?= $stock_amount ?></td>
            <td>
            <a onclick="return checkproductdetails('<?= $row['id'] ?>');" href="javascript:;"id="modal-launcher" class="view">View</a></div>
            
            </td>
    </tr>
	<?php }} ?>
    </tbody>
</table>
		</div>
		</div>
		</div>
		</div>

	<?php include("includes/footer.php");?>

