<?php 
include('includes/config.php');
$q = strtolower($_GET["q"]);
if (!$q) return;
$sql = mysqli_query($con,"select * from category where 1");

while($row = mysqli_fetch_array($sql)){
	 if (strpos(strtolower($row['category_name']), $q) !== false) {
        echo $row['category_name']."\n";
    }
}

?>