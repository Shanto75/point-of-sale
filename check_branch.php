<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from branch where 1 and id = '$id'"));

if(!empty($row)){
	
	$arr = array(
		'id'=>$row['id'],
		'branch_name'=>$row['branch_name'],
		'branch_phone'=>$row['branch_phone'],
		'branch_manager'=>$row['branch_manager'],
		'branch_email'=>$row['branch_email'],
		'branch_address'=>$row['branch_address'],
		'comments'=>$row['comments'],
		
	);
	echo json_encode($arr);
}else {
    $arr1 = array("no" => "no");
    echo json_encode($arr1);

}



?>