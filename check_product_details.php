<?php
include('includes/config.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from product_details where 1 and id = '$id'"));

if(!empty($row)){
	$stock = @mysqli_fetch_array(mysqli_query($con,"select quantity from stock where product_name = '".$row['name']."' and code='".$row['product_code']."'"));
	$expire_date = explode('-',$row['expire_date']);
	$expire_date = $expire_date[2].'/'.$expire_date[1].'/'.$expire_date[0];
	$arr = array(
		'id'=>$row['id'],
		'name'=>$row['name'],
		'product_code'=>$row['product_code'],
		'category'=>$row['category'],
		'purchase_cost'=>$row['purchase_cost'],
		'sale_price'=>$row['sale_price'],
		'wholesale_price'=>$row['wholesale_price'],
		'quantity'=>$stock['quantity'],
		'minquantity'=>$row['minquantity'],
		'unit_type'=>$row['unit_type'],
		'status'=>$row['status'],
		'brand'=>$row['brand'],
		'description'=>$row['description'],
		'vat'=>$row['vat'],
		'warranty'=>$row['warranty'],
		'expire_date'=>$expire_date,
		'comments'=>$row['comments'],
		'status'=>$row['status']
	);
	echo json_encode($arr);
}else {
    $arr1 = array("no" => "no");
    echo json_encode($arr1);

}



?>