<?php
include('includes/config.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from category where 1 and id = '$id'"));

if(!empty($row)){
	
	$arr = array(
		'id'=>$row['id'],
		'category'=>$row['category_name'],
		'description'=>$row['description'],
		'comments'=>$row['comments']
	);
	echo json_encode($arr);
}else {
    $arr1 = array("no" => "no");
    echo json_encode($arr1);

}



?>