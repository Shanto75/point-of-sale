<?php 
include('includes/config.php');
$q = strtolower($_GET["q"]);
if (!$q) return;
$sql = mysqli_query($con,"select * from earning_head where 1");

while($row = mysqli_fetch_array($sql)){
	 if (strpos(strtolower($row['head']), $q) !== false) {
        echo $row['head']."\n";
    }
}

?>