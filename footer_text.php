<?php
$serv = $_SERVER['PHP_SELF'];
if(preg_match("/add_sales_print.php/i", $serv)){
	echo 'Maintenanced by<br /> Face of Art Technologies Limited.';
}else{
echo 'Maintenanced by Face of Art Technologies Limited.';
}
?>