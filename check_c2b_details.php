<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from transfer where 1 and id = '$id'"));
if(!empty($row)){	
	$arr = array(
		'id'=>$row['id'],
		'date'=>daterev($row['date'],'/','-'),
		'bank_name'=>$row['bank_name'],
		'ac_name'=>$row['ac_name'],
		'ac_number'=>$row['ac_number'],
		'amount'=>$row['amount'],
		'comments'=>$row['comments'],
		'bankid'=>$row['bankid']
	);
	echo json_encode($arr);
}else {
    $arr1 = array("no" => "no");
    echo json_encode($arr1);
}

?>