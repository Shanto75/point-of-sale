<?php
include('includes/config.php');

$val = explode('&',$_POST['stock_name1']);
$data = $val[0];
$where = '';
if($val[1]!='undefined'){
	$code = $val[1];
	$where .= " and product_code = '$code'";
}




$row = mysqli_fetch_array(mysqli_query($con,"select * from product_details where 1 and (name = '" . $data . "' or product_code = '".$data."') $where"));
$qty = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `stock` where 1 and product_name = '" . $row['name'] . "' and code='".$row['product_code']."'"));
if(!empty($row)){
	
		$arr = array("code"=>$row['product_code'], "name"=>$row['name'], "cost" => $row['purchase_cost'], "sell" => $row['sale_price'], "stock" => $qty['quantity'], "guid" => $row['stock_id']);
	
    echo json_encode($arr);
}else{
	$arr1 = array("no" => "no");
    echo json_encode($arr1);
}
?>