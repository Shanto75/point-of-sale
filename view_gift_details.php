<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from gift where 1 and id = '$id'"));

if(!empty($row)){
	echo '<tr>
    	<th align="right">Customer Name</th>
    	<td>'.($row['customer_name']==''?'N/A':$row['customer_name']).'</td>
    	</tr>
        <tr>
        <th align="right">Card Number</th>
        <td>'.($row['card_number']==''?'N/A':$row['card_number']).'</td>
        </tr>
        <tr>
        <th align="right">Amount </th>
        <td>'.($row['amount']==''?'N/A':$row['amount']).'</td>
        </tr>
    	<tr>
    	<th valign="top" style="padding-top:10px;" align="right"> Comments</th>
    	<td>'.($row['comments']==''?'N/A':$row['comments']).'</td>
    	</tr>';

}


?>