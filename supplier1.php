<?php 
include('includes/config.php');
$q = strtolower($_GET["q"]);
if (!$q) return;
$sql = mysqli_query($con,"select * from personinformation where type = 'supplier'");
while($row = mysqli_fetch_array($sql)){
	if (strpos(strtolower($row['name']), $q) !== false) {
        echo $row['name']."\n";

    }
}
?>