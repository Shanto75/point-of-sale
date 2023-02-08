<?php
ob_start();
session_start();
$msg = '';
if(!isset($_SESSION['id']) and $_SESSION['id']==''){
header("Location: login.php");
exit();
}
include('includes/config.php');
include('includes/function.php');
function outputCSV($data) {
    $output = fopen("php://output", "w");
    foreach ($data as $row) {
        fputcsv($output, $row); // here you can change delimiter/enclosure
    }
    fclose($output);
}
$store_id = $_SESSION['store_id'];

	$sql = mysqli_query($con,"SELECT name,product_code,category,purchase_cost,sale_price,wholesale_price,unit_type,brand,description,warranty,expire_date,comments,minquantity FROM `product_details` WHERE 1");
	$arr = array();
	$arr[] = array('name','product_code','category','purchase_cost','sale_price','wholesale_price','unit_type','brand','description','warranty','expire_date','comments','minquantity');
	while($row = mysqli_fetch_assoc($sql)){
		$arr[] = $row;
	}	
	
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=purchase_report_isovix.csv");
// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
outputCSV($arr);
?>

