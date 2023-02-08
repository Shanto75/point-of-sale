<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from loan where 1 and id = '$id'"));

if(!empty($row)){
	if($row['type']=='personal'){
		$type = 'loan_personal';
	}elseif($row['type']=='bank'){
		$type = 'loan_bank';
	}else{
		$type = '';
	}
	$due = mysqli_fetch_array(mysqli_query($con,"select sum(pay_amount) as amount from transaction where type='$type' and pay_to ='".$id."'"));
	$dueamount = $row['payable_amount'] - $due['amount'];
	$arr = array(
		'id'=>$row['id'],
		'name'=>$row['name'],
		'date'=>daterev($row['date'],'/','-'),
		'return_date'=>daterev($row['return_date'],'/','-'),
		'amount'=>$row['amount'],
		'interest'=>$row['interest'],
		'payable_amount'=>$row['payable_amount'],
		'comments'=>$row['comments'],
		'bankname'=>$row['bank_name'],
		'acname'=>$row['bank_ac_name_name'],
		'acnumber'=>$row['bank_ac_name'],
		'branchname'=>$row['branch_name'],
		'interest'=>$row['interest'],
		'installment'=>$row['total_installment'],
		'due'=>$dueamount
	);
	echo json_encode($arr);
}else {
    $arr1 = array("no" => "no");
    echo json_encode($arr1);

}



?>