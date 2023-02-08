<?php 
include('includes/config.php');
$q = strtolower($_GET["q"]);
if (!$q) return;
$sql = mysqli_query($con,"select * from product_details where 1 and status='active'");
$dname_list = array();
while($row = mysqli_fetch_array($sql)){
	 if (strpos(strtolower($row['name']), $q) !== false or strpos(strtolower($row['product_code']), $q) !== false) {
        echo $row['name'].'<>'.$row['product_code'].''."\n";
    }
}

?>