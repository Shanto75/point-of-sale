<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from investment where 1 and id = '$id'"));

if(!empty($row)){
	echo '<tr>
        <th align="right">Date</th>
        <td>'.daterev($row['date'],'/','-').'</td>
        </tr>
    	<tr>
        <th align="right">Investor</th>
        <td>
            '.($row['investor']==''?'N/A':$row['investor']).'
        </td>
        </tr>
       
        <tr>
        <th align="right"> Amount</th>
        <td>'.($row['amount']==''?'N/A':$row['amount']).'</td>
        </tr>
    	<tr>
    	<th valign="top" style="padding-top:10px;" align="right">Comments</th>
    	<td>'.($row['comments']==''?'N/A':$row['comments']).'</td>
    	</tr>
    	<tr>
    	
    	<td colspan="2" align="right">
		<div id="hidden_field"></div>
		<input type="submit" value="Go"></td>
    	</tr>';
}


?>