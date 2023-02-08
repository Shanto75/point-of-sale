<?php 
ob_start();
date_default_timezone_set("Asia/Dhaka");
include('includes/config.php');
session_start();
$id = $_SESSION['login'];
$data = mysqli_fetch_array(mysqli_query($con,"select * from `login_details` where id = '$id'"));
$login_time = $data['login_time'];
$logout_time = date('Y-m-d H:i:s');
$datetime1 = new DateTime($login_time);
$datetime2 = new DateTime($logout_time);
$interval = $datetime1->diff($datetime2);
$elapsed = $interval->format('%h hours %i minutes %S seconds');
$time = $elapsed;
@mysqli_query($con,"UPDATE `login_details` SET `logout_time`='$logout_time',`time`='$time' WHERE 1 and id = '$id'");
session_destroy();
header("Location: login.php");
exit();
?>