<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];

$row = mysqli_fetch_array(mysqli_query($con,"select * from personinformation where 1 and id = '$id'"));
$openBlnc = mysqli_fetch_array(mysqli_query($con, "SELECT amount FROM openingbalance WHERE   personid='".$row['id']."'"));

if(!empty($row)){
	echo '<tr>
    	<th align="right">Customer Name</th>
    	<td>'.(ucwords($row['name'])==''?'N/A':ucwords($row['name'])).'</td>
    	</tr>';
        
        if(!isset($_POST['type'])){

        echo '<tr>
           <th>Opening Balance</th>
           <td>'.$openBlnc['amount'].'</td>
        </tr>';
           }

    	echo '<tr>
    	<th align="right">Customer Phone</th>
    	<td>'.($row['phone']==''?'N/A':$row['phone']).'</td>
    	</tr>
    	<tr>
    	<th align="right">Customer Email</th>
    	<td>'.($row['email']==''?'N/A':$row['email']).'</td>
    	</tr>
    	<tr>
    	<th valign="top" style="padding-top:10px;" align="right">Customer Address</th>
    	<td>'.($row['address']==''?'N/A':$row['address']).'</td>
    	</tr>';

}


?>