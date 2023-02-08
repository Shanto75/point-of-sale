<?php
include('includes/config.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from product_details where 1 and id = '$id'"));
$data = '';
if(!empty($row)){
	$stock = @mysqli_fetch_array(mysqli_query($con,"select quantity from stock where product_name = '".$row['name']."' and code='".$row['product_code']."'"));
	$expire_date = explode('-',$row['expire_date']);
	$expire_date = $expire_date[2].'/'.$expire_date[1].'/'.$expire_date[0];
	
	$data .= '<tr>
    	<th align="right">Product Name</th>
    	<td>'.($row['name']==''?'N/A':$row['name']).'</td>
    	</tr>
       
        <tr>
        <th align="right">Product Code</th>
        <td>'.($row['product_code']==''?'N/A':$row['product_code']).'</td>
        </tr>
        <tr>
        <th align="right">Product Category</th>
        <td>'.($row['category']==''?'N/A':$row['category']).'</td>
        </tr>
        <tr>
        <th align="right">Purchase Cost</th>
        <td>'.($row['purchase_cost']==''?'N/A':$row['purchase_cost']).'</td>
        </tr>
        <tr>
        <th align="right">Retail Sell Price</th>
        <td>'.($row['sale_price']==''?'N/A':$row['sale_price']).'</td>
        </tr>
        <tr>
        <th align="right">Wholesale Price</th>
        <td>'.($row['wholesale_price']==''?'N/A':$row['wholesale_price']).'</td>
        </tr>
        <tr>
        <th align="right">Stock Amount</th>
        <td>'.($stock['quantity']==''?'N/A':$stock['quantity']).'</td>
        </tr>
		<tr>
        <th align="right">Minimum Stock Amount</th>
        <td>'.($row['minquantity']==''?'N/A':$row['minquantity']).'</td>
        </tr>
        <tr>
        <th align="right">Unit Type</th>
        <td>'.($row['unit_type']==''?'N/A':$row['unit_type']).'</td>
        </tr>
        <tr>
        <th align="right">Brand Name</th>
        <td>'.($row['brand']==''?'N/A':$row['brand']).'</td>
        </tr>
    	<tr>
    	<th valign="top" style="padding-top:15px;" align="right">Description</th>
    	<td>'.($row['description']==''?'N/A':$row['description']).'</td>
    	</tr>
    	<tr>
    	<th align="right">Vat</th>
    	<td>'.($row['vat']==''?'N/A':$row['vat']).'</td>
    	</tr>
    	<tr>
    	<th align="right">Warranty</th>
    	<td>'.($row['warranty']==''?'N/A':$row['warranty']).'</td>
    	</tr>
        <tr>
        <th align="right">Expiery Date</th>
        <td>'.($expire_date==''?'N/A':$expire_date).'</td>
        </tr>
        <tr>
        <th valign="top" style="padding-top:10px;" align="right">Comments</th>
        <td>'.($row['comments']==''?'N/A':$row['comments']).'</td>
        </tr>';
	if($row['image']!=''){
		$data .= '<tr><th align="right">Image</th><td><img width="200" height="150" src="product_image/'.$row['image'].'" alt="'.$row['name'].'" /></td></tr>';
	}
	$arr = array('custom1','custom2','custom3','custom4','custom5');
	for($i=0;$i<sizeof($arr);$i++){
		if($row[$arr[$i]]!=''){
			
			$title = mysqli_fetch_array(mysqli_query($con,"select * from custom_field where 1 and `key` = '".$arr[$i]."'"));
		
			$data .= '<tr><th align="right">'.$title['title'].'</th><td>'.$row[$arr[$i]].'</td></tr>';
		
			
		}
	}
echo $data;
}


?>