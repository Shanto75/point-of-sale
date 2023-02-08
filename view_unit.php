<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from unit where 1 and id = '$id'"));

if(!empty($row)){
	echo '<tr>
    	<th align="right">Brand Name</th>
    	<td>'.($row['name']==''?'N/A':$row['name']).'</td>
    	</tr>
    	<tr>
    	<th valign="top" style="padding-top:10px;" align="right">Brand Comments</th>
    	<td>'.($row['comments']==''?'N/A':$row['comments']).'</td>
    	</tr>';

}


?>