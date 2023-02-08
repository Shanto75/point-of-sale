<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from transfer where 1 and id = '$id'"));

if(!empty($row)){
	echo '<tr>
        <th align="right">Date</th>
        <td>'.daterev($row['date'],'/','-').'</td>
        </tr>
    	<tr>
    	<th align="right">Bank Name</th>
    	<td>'.($row['bank_name']==''?'N/A':$row['bank_name']).'</td>
    	</tr>
        <tr>
        <th align="right">A/C Name</th>
        <td>'.($row['ac_name']==''?'N/A':$row['ac_name']).'</td>
        </tr>		
        <tr>
        <th align="right">A/C Number</th>
        <td>'.($row['ac_number']==''?'N/A':$row['ac_number']).'</td>
        </tr>
        <tr>
        <th align="right">Transfer Amount</th>
        <td>'.($row['amount']==''?'N/A':$row['amount']).'</td>
        </tr>
    	<tr>
    	<th valign="top" style="padding-top:10px;" align="right">Comments</th>
    	<td>'.($row['comments']==''?'N/A':$row['comments']).'</td>
    	</tr>';
}


?>