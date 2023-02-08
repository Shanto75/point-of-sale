<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from bankinformation where 1 and id = '$id'"));
$blnce = mysqli_fetch_array(mysqli_query($con, "SELECT amount FROM openingbalance WHERE type='bank' AND personid='".$row['id']."'"));

if(!empty($row)){
	echo '<tr>
    	<th align="right">Person Name</th>
    	<td>'.($row['person']==''?'N/A':$row['person']).'</td>
    	</tr>

		<tr>
        <th align="right">Person Type</th>
        <td>
            '.($row['person_type']==''?'N/A':$row['person_type']).'
        </td>
        </tr>

        <tr>
        <th align="right">Opening Balance</th>
        <td>
            '.($blnce['amount']==''?'N/A':$blnce['amount']).'
        </td>
        </tr>

        <tr>
        <th align="right">Bank Name</th>
        <td>'.($row['bankname']==''?'N/A':$row['bankname']).'</td>
        </tr>

        <tr>
        <th align="right">Account Name</th>
        <td>'.($row['accountname']==''?'N/A':$row['accountname']).'</td>
        </tr>

        <tr>
        <th align="right">Account Number</th>
        <td>'.($row['accountnumber']==''?'N/A':$row['accountnumber']).'</td>
        </tr>

    	<tr>
    	<th valign="top" style="padding-top:10px;" align="right">Comments</th>
    	<td>'.($row['comments']==''?'N/A':$row['comments']).'</td>
    	</tr>';
}


?>