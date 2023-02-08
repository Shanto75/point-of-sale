<?php
include('includes/config.php');
include('includes/function.php');

$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from investment where 1 and id = '$id'"));

if(!empty($row)){
	
	$arr = array(
		'id'=>$row['id'],
		'date'=>daterev($row['date'],'/','-'),
		'investor'=>$row['investor'],
		'amount'=>$row['amount'],
		'comments'=>$row['comments']
	);
	echo json_encode($arr);
}else {
    $arr1 = array("no" => "no");
    echo json_encode($arr1);

}



?>