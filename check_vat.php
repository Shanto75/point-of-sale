<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from vat where 1 and id = '$id'"));

if(!empty($row)){
	
	$arr = array(
		'id'=>$row['id'],
		'vat_name'=>$row['vat_name'],
		'tax_reg'=>$row['tax_reg'],
		'amount'=>$row['amount'],
		'status'=>$row['status'],
		'comments'=>$row['comments']
		
	);
	echo json_encode($arr);
}else {
    $arr1 = array("no" => "no");
    echo json_encode($arr1);

}



?>