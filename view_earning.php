<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from earning_head where 1 and id = '$id'"));

if(!empty($row)){
	echo '<tr>
    	<th align="right">Earning Head Name</th>
    	<td>'.($row['head']==''?'N/A':$row['head']).'</td>
    	</tr>
    	<tr>
    	<th valign="top" style="padding-top:10px;" align="right">Earning Head Comments</th>
    	<td>'.($row['comments']==''?'N/A':$row['comments']).'</td>
    	</tr>';

}


?>