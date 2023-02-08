<?php
include('includes/config.php');
$id = $_POST['stock_name1'];
 
$row = mysqli_fetch_array(mysqli_query($con,"select * from personinformation where 1 and name = '$id' and type='customer'"));

if(!empty($row)){
	
	$arr = array(
		'id'=>$row['id'],
		'name'=>$row['name'],
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