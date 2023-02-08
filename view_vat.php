<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from vat where 1 and id = '$id'"));
if($row['status']==0){
	$status = 'Active';
}else{
	$status = 'Inactive';
}
if(!empty($row)){
	echo '<tr>
    	<th align="right">Vat/Tax Name</th>
    	<td>'.$row['vat_name'].'</td>
    	</tr> 
		<tr>
    	<th align="right">Vat/Tax Reg No</th>
    	<td>
    		'.($row['tax_reg']=='0'?'N/A':$row['tax_reg']).'
    	</td>
    	</tr>		
    	<tr>
    	<th align="right">Vat/Tax Amount</th>
    	<td>
    		'.$row['amount'].' %
    	</td>
    	</tr>
		<tr>
    	<th align="right">Vat/Tax Status</th>
    	<td>
    		'.$status.'
    	</td>
    	</tr>
    	<tr>
    	<th valign="top" style="padding-top:10px;" align="right">Enter Branch Comments</td>
    	<td>'.$row['comments'].'</th>
    	</tr>';

}


?>