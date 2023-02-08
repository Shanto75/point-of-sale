<?php
include('includes/config.php');
include('includes/function.php');
$data = explode('&',$_POST['productid']);

$sql = mysqli_query($con,"select * from transaction where 1 and name = '".$data[0]."' and type = '".$data[1]."' and status = 'due'  and approval!='pending'");
$data = '';
$data .='<tr><td>Date</td><td>Payment Amount</td><td>Payment Type</td></tr>';
while($row = mysqli_fetch_array($sql)){
	$data .='<tr><td>'.daterev($row['date'],'/','-').'</td><td>'.$row['pay_amount'].'</td><td>'.$row['method'].'</td></tr>';
}
echo $data;
?>