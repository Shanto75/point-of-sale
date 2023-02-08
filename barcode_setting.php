<?php include("includes/header.php");?>
<?php 
$msg = '';
if(isset($_POST['submit'])){
	$row1 = $_POST['row1'];
	$row2 = $_POST['row2'];
	$row3 = $_POST['row3'];
	$row4 = $_POST['row4'];
	$row5 = $_POST['row5'];
	@mysqli_query($con,"UPDATE `barcode_setup` SET `row1`='$row1',`row2`='$row2',`row3`='$row3',`row4`='$row4',`row5`='$row5' WHERE 1 and id = 1");
	$msg = 'Barcode Setup successfully!';
}
?>
	<div class="area">
		<div class="panel-head">Barcode Setting</div>
		<div class="panel">
            <!-- content starts -->
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
<?php 
$row = @mysqli_fetch_array(@mysqli_query($con,"select * from barcode_setup where id = 1"));


?>
	<div class="alert alert-info"><h3>Barcode Setup</h3></div>
    <form action="" method="POST" id="login-form" style="height: 287px;" class="cmxform" autocomplete="off">

        <table>
            <tr>
                <td>
                        <label >Row 1</label>                        
                </td>
				<td><select name="row1">
						<option value="">please select</option>
						<option value="name" <?= $row['row1']=='name'?'selected="selected"':'' ?> >Product Name</option>
						<option value="product_code" <?= $row['row1']=='product_code'?'selected="selected"':'' ?>>Product Code</option>
						<option value="category" <?= $row['row1']=='category'?'selected="selected"':'' ?>>Product Category</option>
						<option value="sale_price" <?= $row['row1']=='sale_price'?'selected="selected"':'' ?>>Retail Price</option>
						<option value="wholesale_price" <?= $row['row1']=='wholesale_price'?'selected="selected"':'' ?>>Wholesale Price</option>
						<option value="brand" <?= $row['row1']=='brand'?'selected="selected"':'' ?>>Brand Name</option>
						<option value="warranty" <?= $row['row1']=='warranty'?'selected="selected"':'' ?>>Warranty</option>
						<option value="expire_date" <?= $row['row1']=='expire_date'?'selected="selected"':'' ?>>Expire Date</option>
					<select>
				</td>
			</tr>
			<tr>
				
                <td>                    
                        <label> Row 2</label>                        
                </td>
				<td>
					<select name="row2">
						<option value="">please select</option>
						<option value="name" <?= $row['row2']=='name'?'selected="selected"':'' ?> >Product Name</option>
						<option value="product_code" <?= $row['row2']=='product_code'?'selected="selected"':'' ?>>Product Code</option>
						<option value="category" <?= $row['row2']=='category'?'selected="selected"':'' ?>>Product Category</option>
						<option value="sale_price" <?= $row['row2']=='sale_price'?'selected="selected"':'' ?>>Retail Price</option>
						<option value="wholesale_price" <?= $row['row2']=='wholesale_price'?'selected="selected"':'' ?>>Wholesale Price</option>
						<option value="brand" <?= $row['row2']=='brand'?'selected="selected"':'' ?>>Brand Name</option>
						<option value="warranty" <?= $row['row2']=='warranty'?'selected="selected"':'' ?>>Warranty</option>
						<option value="expire_date" <?= $row['row2']=='expire_date'?'selected="selected"':'' ?>>Expire Date</option>
					<select>
				</td>
            </tr>
			
            <tr>
                <td>                    
                        <label>Row 3</label>
                        
                </td>
				<td><select name="row3">
						<option value="">please select</option>
						<option value="name" <?= $row['row3']=='name'?'selected="selected"':'' ?> >Product Name</option>
						<option value="product_code" <?= $row['row3']=='product_code'?'selected="selected"':'' ?>>Product Code</option>
						<option value="category" <?= $row['row3']=='category'?'selected="selected"':'' ?>>Product Category</option>
						<option value="sale_price" <?= $row['row3']=='sale_price'?'selected="selected"':'' ?>>Retail Price</option>
						<option value="wholesale_price" <?= $row['row3']=='wholesale_price'?'selected="selected"':'' ?>>Wholesale Price</option>
						<option value="brand" <?= $row['row3']=='brand'?'selected="selected"':'' ?>>Brand Name</option>
						<option value="warranty" <?= $row['row3']=='warranty'?'selected="selected"':'' ?>>Warranty</option>
						<option value="expire_date" <?= $row['row3']=='expire_date'?'selected="selected"':'' ?>>Expire Date</option>
					<select>                    
            </tr>
			<tr>
                <td>                    
                        <label>Row 4</label>
                        
                </td>
				<td><select name="row4">
						<option value="">please select</option>
						<option value="name" <?= $row['row4']=='name'?'selected="selected"':'' ?> >Product Name</option>
						<option value="product_code" <?= $row['row4']=='product_code'?'selected="selected"':'' ?>>Product Code</option>
						<option value="category" <?= $row['row4']=='category'?'selected="selected"':'' ?>>Product Category</option>
						<option value="sale_price" <?= $row['row4']=='sale_price'?'selected="selected"':'' ?>>Retail Price</option>
						<option value="wholesale_price" <?= $row['row4']=='wholesale_price'?'selected="selected"':'' ?>>Wholesale Price</option>
						<option value="brand" <?= $row['row4']=='brand'?'selected="selected"':'' ?>>Brand Name</option>
						<option value="warranty" <?= $row['row4']=='warranty'?'selected="selected"':'' ?>>Warranty</option>
						<option value="expire_date" <?= $row['row4']=='expire_date'?'selected="selected"':'' ?>>Expire Date</option>
					<select>                    
            </tr>	
			<tr>
                <td>                    
                        <label>Row 5</label>
                        
                </td>
				<td><select name="row5">
						<option value="">please select</option>
						<option value="name" <?= $row['row5']=='name'?'selected="selected"':'' ?> >Product Name</option>
						<option value="product_code" <?= $row['row5']=='product_code'?'selected="selected"':'' ?>>Product Code</option>
						<option value="category" <?= $row['row5']=='category'?'selected="selected"':'' ?>>Product Category</option>
						<option value="sale_price" <?= $row['row5']=='sale_price'?'selected="selected"':'' ?>>Retail Price</option>
						<option value="wholesale_price" <?= $row['row5']=='wholesale_price'?'selected="selected"':'' ?>>Wholesale Price</option>
						<option value="brand" <?= $row['row5']=='brand'?'selected="selected"':'' ?>>Brand Name</option>
						<option value="warranty" <?= $row['row5']=='warranty'?'selected="selected"':'' ?>>Warranty</option>
						<option value="expire_date" <?= $row['row5']=='expire_date'?'selected="selected"':'' ?>>Expire Date</option>
					<select>                    
            </tr>			
            <tr>
                <td>


                    <!--<a href="dashboard.php" class="button round blue image-right ic-right-arrow">LOG IN</a>-->
                    <input type="submit" class="button round blue image-right ic-right-arrow" name="submit"
                           value="Update"/>
                </td>
                
            </tr>
        </table>

    </form>
    
</div>
</div>

    <hr>

	<?php include("includes/footer.php");?>
