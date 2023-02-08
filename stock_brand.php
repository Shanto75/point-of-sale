<?php 
include('includes/config.php');
$q = strtolower($_GET["q"]);
if (!$q) return;
$sql = mysqli_query($con,"select * from brand where 1");

while($row = mysqli_fetch_array($sql)){
	 if (strpos(strtolower($row['brand_name']), $q) !== false) {
        echo $row['brand_name']."\n";
    }
}

?>