<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from loan where 1 and id = '$id'"));

if(!empty($row)){
	if($row['type']=='personal'){
		$type = 'loan_personal';
	}elseif($row['type']=='bank'){
		$type = 'loan_bank';
	}else{
		$type = '';
	}
	$due = mysqli_fetch_array(mysqli_query($con,"select sum(pay_amount) as amount from transaction where type='$type' and pay_to ='".$id."'"));
	$dueamount = $row['payable_amount'] - $due['amount'];
	if($row['type']=='bank'){
		echo '<tr class="view_loan"><th>Date</th><td>'.daterev($row['date'],'/','-').'</td></tr><tr class="view_loan"><th>Bank Name</th><td>'.(ucwords($row['bank_name'])==''?'N/A':ucwords($row['bank_name'])).'</td></tr><tr class="view_loan"><th>Account Name</th><td>'.(ucwords($row['bank_ac_name_name'])==''?'N/A':ucwords($row['bank_ac_name_name'])).'</td></tr><tr class="view_loan"><th>Account Number</th><td>'.($row['bank_ac_name']==''?'N/A':$row['bank_ac_name']).'</td></tr><tr class="view_loan"><th>Branch Name</th><td>'.($row['branch_name']==''?'N/A':$row['branch_name']).'</td></tr><tr class="view_loan"><th>Amount</th><td>'.($row['amount']==''?'N/A':$row['amount']).'</td></tr><tr class="view_loan"><th>Interest</th><td>'.($row['interest']==''?'N/A':$row['interest']).' % </td></tr><tr class="view_loan"><th>Payable Amount</th><td>'.($row['payable_amount']==''?'N/A':$row['payable_amount']).' </td></tr><tr class="view_loan"><th>Paid  Amount</th><td>'.($due['amount']==''?'N/A':$due['amount']).' </td></tr><tr class="view_loan"><th>Due  Amount</th><td>'.($dueamount==''?'N/A':$dueamount).' </td></tr><tr class="view_loan"><th>Comments</th><td>'.($row['comments']==''?'N/A':$row['comments']).' </td></tr>';
	}else{
		echo '<tr class="view_loan"><th>Date</th><td>'.daterev($row['date'],'/','-').'</td></tr><tr class="view_loan"><th>From</th><td>'.(ucwords($row['name'])==''?'N/A':ucwords($row['name'])).'</td></tr><tr class="view_loan"><th>Amount</th><td>'.($row['amount']==''?'N/A':$row['amount']).'</td></tr><tr class="view_loan"><th>Interest</th><td>'.($row['interest']==''?'N/A':$row['interest']).' % </td></tr><tr class="view_loan"><th>Payable Amount</th><td>'.($row['payable_amount']==''?'N/A':$row['payable_amount']).' </td></tr><tr class="view_loan"><th>Paid  Amount</th><td>'.($due['amount']==''?'N/A':$due['amount']).' </td></tr><tr class="view_loan"><th>Due  Amount</th><td>'.($dueamount==''?'N/A':$dueamount).' </td></tr><tr class="view_loan"><th>Comments</th><td>'.($row['comments']==''?'N/A':$row['comments']).' </td></tr>';
	}
}


?>