<?php
include('includes/config.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from personinformation where 1 and id = '$id'"));
$openBlnc = mysqli_fetch_array(mysqli_query($con, "SELECT amount FROM openingbalance WHERE type='customer' AND personid='".$row['id']."'"));
if(!empty($row)){
	
	$arr = array(
		'id'=>$row['id'],
		'name'=>$row['name'],
		'openBalance'=>$openBlnc['amount'],
		'address'=>$row['address'],
		'phone'=>$row['phone'],
		'email'=>$row['email'],
		'company_name'=>$row['company_name'],
		'comments'=>$row['comments']
	);
	echo json_encode($arr);
}else {
    $arr1 = array("no" => "no");
    echo json_encode($arr1);

}



?>