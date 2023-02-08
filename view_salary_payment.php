<?php
include('includes/config.php');
include('includes/function.php');
$data = explode('&',$_POST['productid']);
$id = $data[0];
$month = $data[1];
$data = '';
$data .= '<tr><td>Date</td><td>Amount</td></tr>';

$sql = mysqli_query($con,"select * from salary where 1 and employee_id = '$id' and month = '$month'");
while($row = mysqli_fetch_array($sql)){
	$data .= '<tr><td>'.$row['date'].'</td><td>'.$row['amount'].'</td></tr>';
}
echo $data;

?>