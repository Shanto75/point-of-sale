<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from expense where 1 and id = '$id'"));

if(!empty($row)){
	echo '<tr>
        <th align="right">Date</th>
        <td>'.daterev($row['date'],'/','-').'</td>
        </tr>
    	<tr>
    	<th align="right">Expense Head Name</th>
    	<td>'.($row['earning_head']==''?'N/A':$row['earning_head']).'</td>
    	</tr>
        <tr>
        <th align="right">Expense Amount</th>
        <td>'.($row['amount']==''?'N/A':$row['amount']).'</td>
        </tr>
    	<tr>
    	<th valign="top" style="padding-top:10px;" align="right">Comments</th>
    	<td>'.($row['comments']==''?'N/A':$row['comments']).'</td>
    	</tr>';
}


?>