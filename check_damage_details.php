<?php
include('includes/config.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from damage_stock where 1 and id = '$id'"));

if(!empty($row)){
	
echo '        <tr>
        <td align="right">Product Name</td>
        <td>'.($row['name']==''?'N/A':$row['name']).'</td>
        </tr>
        <tr>
        <td align="right">Quantity</td>
        <td>'.($row['quantity']==''?'N/A':$row['quantity']).'</td>
        </tr>
		<tr>
        <td align="right">Date</td>
        <td>'.($row['date']==''?'N/A':$row['date']).'</td>
        </tr>
        <tr>
        <td valign="top" style="padding-top:15px;" align="right">Comments</td>
        <td>'.($row['comments']==''?'N/A':$row['comments']).'</td>
        </tr>';
			
}



?>