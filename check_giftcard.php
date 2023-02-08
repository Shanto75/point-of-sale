<?php
error_reporting(0);
include('includes/config.php');
$data = explode('&',$_POST['stock_name1']);
$cardnumber = $data[0];
$name = $data[1];
$row = @mysqli_fetch_array(@mysqli_query($con,"SELECT * FROM `gift` where card_number = '$cardnumber' and customer_name= '$name'"));
if(!empty($row)){
	echo $row['amount'];
}else{
	echo 'error';
}



?>