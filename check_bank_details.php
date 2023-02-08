<?php
include('includes/config.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from bankinformation where 1 and id = '$id'"));
$openBlnc = mysqli_fetch_array(mysqli_query($con, "SELECT amount FROM openingbalance WHERE type='bank' AND personid='".$row['id']."'"));
if(!empty($row)){
	
	$arr = array(
		'id'=>$row['id'],
		'person'=>$row['person'],
		'person_type'=>$row['person_type'],
		'bankname'=>$row['bankname'],
		'accountname'=>$row['accountname'],
		'accountnumber'=>$row['accountnumber'],
		'openingBlnc'=>$openBlnc['amount'],
		'comments'=>$row['comments']
	);
	echo json_encode($arr);
}else {
    $arr1 = array("no" => "no");
    echo json_encode($arr1);

}



?>