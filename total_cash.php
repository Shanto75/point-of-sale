<?php include("includes/header.php");?>
	<script type="text/javascript">
</script>
<?php 
	$whereclass = '';	
	if(isset($_GET['from']) and strlen($_GET['from'])>0){
		$from = modifydate($_GET['from'],'-','/');
		$whereclass .= " and date >= '".$from."'";
		$f = $_GET['from'];
	}else{
		$f = '';
	}
	if(isset($_GET['to']) and strlen($_GET['to'])>0){
		$to = modifydate($_GET['to'],'-','/');
		$t = $_GET['to'];
		$whereclass .= " and date <= '".$to."'";
	}else{
		$t = '';
	}
	//sales///////
	$sql = mysqli_query($con,"SELECT `date`, sum(`payment`)as payment, sum(`balance`)as balance, `count` FROM `purchase` WHERE 1 and `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass group by date");
	$totalrec = @mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass and approve != 'pending'")); 
	$totalout = @mysqli_fetch_array(mysqli_query($con,"select sum(balance) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass and approve != 'pending'")); 
	$outstanding = @mysqli_fetch_array(mysqli_query($con,"select sum(pay_amount)as total from transaction where type= 'sale' and status = 'due' and approval!='pending'"));
	//purchase/////////
	$sql2 = mysqli_query($con,"SELECT `date`, sum(`payment`)as payment, sum(`balance`)as balance, `count` FROM `purchase` where `delete` !='1' and type='purchase' and register_mode='purchase' and count = '1' $whereclass group by date");
	$totalrec2 = mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and type='purchase' and register_mode='purchase' and count = '1' $whereclass and approve != 'pending'")); 
	$totalout2 = mysqli_fetch_array(mysqli_query($con,"select sum(balance) as total from purchase where `delete` !='1' and type='purchase' and register_mode='purchase' and count = '1' $whereclass and approve != 'pending'")); 
	$paymen = @mysqli_fetch_array(mysqli_query($con,"select sum(pay_amount)as total from transaction where type= 'purchase' and status = 'due'"));
	//Loan/////////
	$sql3 = mysqli_query($con,"SELECT  `date`, sum(`amount`)as amount  FROM `loan` WHERE 1 $whereclass group by date");
	$loan_total = mysqli_fetch_array(mysqli_query($con,"SELECT  `date`, sum(`amount`)as amount  FROM `loan` WHERE 1 $whereclass"));
	//Loan Payment/////
	$sql4 = mysqli_query($con,"SELECT sum(`pay_amount`)as amount, `date`, `payment_type` FROM `transaction` WHERE 1 and (type='loan_personal' or type = 'loan_bank') $whereclass group by date");
	$loan_payment = mysqli_fetch_array(mysqli_query($con,"SELECT sum(`pay_amount`)as amount, `date`, `payment_type` FROM `transaction` WHERE 1 and (type='loan_personal' or type = 'loan_bank') $whereclass"));
	//Investment////////
	$sql5 = mysqli_query($con,"SELECT `date`, sum(`amount`)as amount FROM `investment` WHERE 1 $whereclass group by date");
	$total_investment = mysqli_fetch_array(mysqli_query($con,"SELECT `date`, sum(`amount`)as amount FROM `investment` WHERE 1 $whereclass"));
	//salary/////
	$sql6 = mysqli_query($con,"SELECT `date`, sum(`amount`)as amount  FROM `salary` WHERE 1 $whereclass group by date");
	$total_sal = mysqli_fetch_array(mysqli_query($con,"SELECT `date`, sum(`amount`)as amount  FROM `salary` WHERE 1 $whereclass "));
	//other Earnings/////
	$sql7 = mysqli_query($con,"SELECT `date`, sum(`amount`)as amount FROM `earning` WHERE 1 $whereclass group by date");
	$total_earn = mysqli_fetch_array(mysqli_query($con,"SELECT `date`, sum(`amount`)as amount FROM `earning` WHERE 1 $whereclass "));
	//other Expense/////
	$sql8 = mysqli_query($con,"SELECT `date`, sum(`amount`)as amount FROM `expense` WHERE 1 $whereclass group by date");
	$total_expense = mysqli_fetch_array(mysqli_query($con,"SELECT `date`, sum(`amount`)as amount FROM `expense` WHERE 1 $whereclass "));

	$expense = ($totalrec2['total']==''?'0':$totalrec2['total'])+($paymen['total']==''?'0':$paymen['total'])+($loan_payment['amount']==''?'0':$loan_payment['amount'])+($total_sal['amount']==''?'0':$total_sal['amount']) + ($total_expense['amount']==''?'0':$total_expense['amount']);
	
	$earntotal = ($totalrec['total']==''?'0':$totalrec['total'])+($outstanding['total']==''?'0':$outstanding['total'])+($loan_total['amount']==''?'0':$loan_total['amount'])+($total_investment['amount']==''?'0':$total_investment['amount'])+($total_earn['amount']==''?'0':$total_earn['amount']);
	
	$profit = $earntotal - $expense;
	
	$c2b = mysqli_fetch_array(mysqli_query($con,"select sum(amount)as amount from transfer where 1 and type='c2b'"));
	
	if(!empty($c2b)){
		$camount = $c2b['amount'];
	}else{
	$camount = 0;
	}	
	$b2c = mysqli_fetch_array(mysqli_query($con,"select sum(amount)as amount from transfer where 1 and type='b2c'"));
	if(!empty($b2c)){
		$bamount = $b2c['amount'];
	}else{
		$bamount = 0;
	}
	
	$saletotalrecbank = @mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass and mode = 'check' and approve != 'pending'")); 
	$outstand1 = @mysqli_fetch_array(mysqli_query($con,"select sum(pay_amount)as total from transaction where type= 'sale' and status = 'due' and method = 'check' and approval!='pending'"));
	
	$purchasetotalrec2bank = mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and type='purchase' and register_mode='purchase' and count = '1' $whereclass and mode = 'check' and approve != 'pending'")); 
	$paym1 = @mysqli_fetch_array(mysqli_query($con,"select sum(pay_amount)as total from transaction where type= 'purchase' and status = 'due' and method = 'check'"));
	
	
	$bank = ($camount+$saletotalrecbank['total']+$outstand1['total']) - ($purchasetotalrec2bank['total']+$bamount+$paym1['total']);
	
	$cash = $profit - $bank;

?>
	<div class="area">
		<div class="panel-head">Cash to Bank Details</div>
		<div class="panel">
						<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
		
	<!--Add-->	

<!--View-->
<?php 

?>
		<table width="100%" border=1 class="display">
    <thead>
        <tr>
            <th>Total Amount</th>
            <th>Bank</th>
            <th>Cash</th>
            <th>Investment</th>

        </tr>
    </thead>
    <tbody>
		
    <tr>
    		
       		<td align="center"><?= number_format((float)$profit, 2, '.', ''); ?></td>
       		<td align="center"><?= number_format((float)$bank, 2, '.', ''); ?></td>
       		<td align="center"><?= number_format((float)$cash, 2, '.', ''); ?></td>
			<td align="center"><?= number_format((float)($total_investment['amount']==''?'0':$total_investment['amount']), 2, '.', ''); ?></td>

    </tr>
	
    </tbody>
</table>
<br />
<br />
<table width="100%" border=1 class="display">
    <thead>
        <tr>
            <th>Bank Name</th>
            <th>Account Number</th>  
            <th>Total Amount</th>

        </tr>
    </thead>
    <tbody>
<?php 
	$banklist = mysqli_query($con,"select * from bankinformation where person_type= 'owner'");
	while($row = mysqli_fetch_array($banklist)){
		$id = $row['id'];
		$name = $row['bankname'];
		$account_number = $row['accountnumber'];
		
		$banksale1 = @mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass and mode = 'check' and bank= '$id' and approve != 'pending'"));
		$outstand = @mysqli_fetch_array(mysqli_query($con,"select sum(pay_amount)as total from transaction where type= 'sale' and status = 'due' and bank = '$id' and approval!='pending'"));
		
		$bankpurchase1 = mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and type='purchase' and register_mode='purchase' and count = '1' $whereclass and mode = 'check' and bank = '$id' and approve != 'pending'"));
		$payment = @mysqli_fetch_array(mysqli_query($con,"select sum(pay_amount)as total from transaction where type= 'purchase' and bank = '$id'"));
		$c2b = mysqli_fetch_array(mysqli_query($con,"select sum(amount)as amount from transfer where 1 and type='c2b' and bank_name = '$name' and ac_number = '$account_number'"));
	
		if(!empty($c2b)){
			$camount = $c2b['amount'];
		}else{
		$camount = 0;
		}	
		$b2c = mysqli_fetch_array(mysqli_query($con,"select sum(amount)as amount from transfer where 1 and type='b2c' and bank_name = '$name' and ac_number = '$account_number'"));
		if(!empty($b2c)){
			$bamount = $b2c['amount'];
		}else{
			$bamount = 0;
		}
		$bank = ($camount+$banksale1['total']+$outstand['total']) - ($bankpurchase1['total']+$bamount+$payment['total']);
		echo '<tr>
			<td>'.ucwords($name).'</td>
			<td>'.$account_number.'</td>
			<td>'.number_format($bank,2).'</td>
		</tr>';
		
		
	}
?>
	</tbody>
</table>
		</div>
		</div>
		</div>
		</div>

	<?php include("includes/footer.php");?>
