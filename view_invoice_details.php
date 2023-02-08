<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$sql = mysqli_query($con,"select * from purchase where 1 and p_id = '$id'");

$data = "";
$data .= "<tr>
		<th>Product Name</th>
		<th>Quantity</th>
		<th>Unit Price</th>
		
		
		<th>Total</th>
		<th>Discount</th>
		
		</tr>";
while($row = mysqli_fetch_array($sql)){
	if($row['type']=='sale' or $row['type']=='wholesale'){
		$da = $row['sale_price'];
	}else{
		$da = $row['purchase_cost'];
	}
	$data .= "<tr>
			<td border='1'>".$row['product_name']."</td>
			<td>".$row['quantity']."</td>
			<td>".$da."</td>
			
			
			<td>".$row['total']."</td>
			<td>".($row['discount_prod']=='0'?'N/A':$row['discount_prod'])."</td>
			
			</tr>";
}
echo $data;


?>