<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from branch where 1 and id = '$id'"));

if(!empty($row)){
	echo '<tr>
    	<th align="right">Branch Name</th>
    	<td>'.$row['branch_name'].'</td>
    	</tr>
    	<tr>
    	<th valign="top" style="padding-top:20px;" align="right">Branch Address</th>
    	<td>'.$row['branch_address'].'</td>
    	</tr>
    	<tr>
    	<th align="right">Branch Manager Name</th>
    	<td>
    		'.$row['branch_manager'].'
    	</td>
    	</tr>
    	<tr>
    	<th align="right">Branch Phone</th>
    	<td>'.$row['branch_phone'].'</td>
    	</tr>

    	<tr>
    	<th align="right">Branch Email</th>
    	<td>'.$row['branch_email'].'</td>
    	</tr>

    	<tr>
    	<th valign="top" style="padding-top:10px;" align="right">Enter Branch Comments</th>
    	<td>'.$row['comments'].'</td>
    	</tr>';

}


?>