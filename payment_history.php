<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$sql = mysqli_query($con,"select * from transaction where 1 and pay_to = '$id'");
$data = '';
$data .='<tr><td>Date</td><td>Payment Amount</td></tr>';
while($row = mysqli_fetch_array($sql)){
	$data .='<tr><td>'.daterev($row['date'],'/','-').'</td><td>'.$row['pay_amount'].'</td></tr>';
}
echo $data;
?>