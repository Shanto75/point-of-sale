<?php include("includes/header.php");?>
<script type="text/javascript">
function toggle(source) {
  checkboxes = document.getElementsByName('barcode[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
<?php


?>
	<div class="area">
		<div class="panel-head">Generate Product Barcode</div>
		<div class="panel">
						<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>					
<?php 
$sql = mysqli_query($con,"select * from product_details where 1 and status = 'active' and product_code!=''")
?>
<form action="generate_barcode.php" method="post">
<div style="margin-bottom:10px" class="head_side">
	<input type="submit" value="Generate" />
</div>
		<table id="table_id" class="display">
    <thead>
        <tr>
			<th><input type="checkbox" onclick="toggle(this)"/></th>
            <th>UPC/EAN/ISBN</th>
            <th>Product Name</th>
            <th>Purchase Cost</th>         
            <th>Retail Price</th>
            <th>Wholesale Price</th>
            <th>Category</th>
            <th>Warranty</th>
            <th>Expiery Date</th>
            
           
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
         <td><?= ($row['wholesale_price']); ?></td>
            <td><?= ($row['category']); ?></td>
            <td><?= ($row['warranty']); ?></td>
            <td><?= daterev($row['expire_date'],'/','-'); ?></td>
            
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