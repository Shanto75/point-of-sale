<?php include("includes/header.php"); 
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
	$sql = mysqli_query($con,"SELECT `date`,sum(`payable`)as payable, sum(`payment`)as payment, sum(`balance`)as balance, `count` FROM `purchase` WHERE 1 and `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass and approve != 'pending' ");
	$totalrec = @mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass and approve != 'pending'")); 
	$totalout = @mysqli_fetch_array(mysqli_query($con,"select sum(balance) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass and approve != 'pending'")); 
	//purchase/////////
	
	$sql2 = mysqli_query($con,"SELECT `date`,sum(`payable`) as payable, sum(`payment`)as payment, sum(`balance`)as balance, `count` FROM `purchase` where `delete` !='1' and type='purchase' and register_mode='purchase' and count = '1' $whereclass ");
	$totalrec2 = mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and type='purchase' and register_mode='purchase' and count = '1' $whereclass")); 
	$totalout2 = mysqli_fetch_array(mysqli_query($con,"select sum(balance) as total from purchase where `delete` !='1' and type='purchase' and register_mode='purchase' and count = '1' $whereclass")); 
	$partialpurch= @mysqli_fetch_array(@mysqli_query($con,"select sum(pay_amount)as total from transaction where type='purchase' and status='due'"));
	//Loan
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
	
	
	$expense = $partialpurch['total']+($totalrec2['total']==''?'0':$totalrec2['total'])+($loan_payment['amount']==''?'0':$loan_payment['amount'])+($total_sal['amount']==''?'0':$total_sal['amount']) + ($total_expense['amount']==''?'0':$total_expense['amount']);
	$earntotal = ($totalrec['total']==''?'0':$totalrec['total'])+($loan_total['amount']==''?'0':$loan_total['amount'])+($total_investment['amount']==''?'0':$total_investment['amount'])+($total_earn['amount']==''?'0':$total_earn['amount']);
	$profit = $earntotal - $expense;
	
?>
<script type="text/javascript">
function view_invoice_details(val){
	
	$('#datatable tr').remove(); 
	$.post('view_invoice_details.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
        $(function () {

            $("#customer").autocomplete("customer1.php", {
                width: 250,
                autoFill: true,
                selectFirst: true
            });
		});
</script>
<style type="text/css">
   .show_hide {
    display:none;
}
</style>
<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Modal Window</button>
    <div class="modal-form">
    	View Invoice Details<hr/>

    	<table border="1" id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<div class="area">
      <div class="panel-head">Profit/Lose Report</div>
      <div class="panel">
      
<?php include('left_menu.php'); ?>

   <div class="report_right">
   <form action="" method="get">
   <table width="400px" class="tab form" border="0" cellspacing="0" cellpadding="0">
		
			<tr>

				
				<td width="2%">From</td>
				
				<td width="2%"><input class="form-control datepick" name="from" value="<?= $f ?>" type="text" id="from_sales_date"
						   style="width:160px;"></td>
				
				<td width="2%">To</td>
				
				<td width="2%"><input class="form-control datepick" name="to" value="<?= $t ?>" type="text" id="to_sales_date" style="width:160px;">
				</td>
				
				<td width="2%" valign="left"><input class="btn btn-info" type="submit" name="Submit" value="Show">
				</td>
			</tr>
		
	</table>
	<br />
	<br />
	</form>
<div style="overflow:hidden;padding:10px;margin-bottom:10px;" >	
		<a onclick="" href="generate_profit_lose.php?from=<?= $f ?>&to=<?= $t ?>" class="btn-add">Print</a>
		
</div>
   <div class="table_data">
      <h2>Profit/Lose Report</h2>
		<table border=1 width="940px" class="tab">
		<thead>
			<tr>				     			
				<th>Total Earn</th>
				<th>Total Expense</th>
				<th>Profit/Lose</th>	
			</tr>
		</thead>
		<tbody>
		<tr>
			<td align="center"><?= number_format((float)$earntotal, 2, '.', '') ?> TK</td>			
			<td align="center"><?= number_format((float)$expense, 2, '.', '') ?> TK</td>
			<td align="center"><?= number_format((float)$profit, 2, '.', '') ?> TK</td>
			
		</tr>
		</tbody>
		<thead>
			<tr>
				<th>Total Earn</th>
				<th>Total Expense</th>
				<th>Profit/Lose</th>

			</tr>
		</thead>
	</table>
   
   
   <h2>Sale</h2>
		<table border=1 width="940px" class="tab">
		<thead>
			<tr>
				<th>Total Receiveble</th>            				
				<th>Total Received</th>
				<th>Total Due</th>
			

			</tr>
		</thead>
		<tbody>
<?php 
while($row=mysqli_fetch_array($sql)){

   $due1= @mysqli_fetch_array(@mysqli_query($con,"select sum(pay_amount)as total from transaction where type='sale' and status='due'  and approval!='pending'"));

	$tpaid = $totalrec['total']+$due1['total'];
    $tout = $totalout['total']-$due1['total'];
	
?>
		<tr>
			<td align="center"><?= number_format((float)$row['payable'], 2, '.', '') ?></td>			
			<td align="center"><?= number_format((float)$tpaid, 2, '.', '') ?> TK</td>
			<td align="center"><?= number_format((float)$tout, 2, '.', '') ?> TK</td>
			
		</tr>
<?php } ?>
		</tbody>
		<thead>
			<tr>
				<th>Total Receiveble</th>            				
				<th>Total Received</th>
				<th>Total Due</th>
			

			</tr>
		</thead>
	</table>
	
   <h2>Purchase</h2>
		<table border=1 width="940px" class="display">
		<thead>
			<tr>
				<th>Total Payable</th>            				
				<th>Total Payment</th>
				<th>Total Due</th>
			</tr>
		</thead>
		<tbody>
<?php 
while($row2=mysqli_fetch_array($sql2)){
	
 $due2= @mysqli_fetch_array(@mysqli_query($con,"select sum(pay_amount)as total from transaction where type='purchase' and status='due'"));

	$tpaid2 = $totalrec2['total']+$due2['total'];
    $tout2 = $totalout2['total']-$due2['total'];

?>
		<tr>
			<td align="center"><?= number_format((float)$row2['payable'], 2, '.', '') ?> TK</td>			
			<td align="center"><?= number_format((float)$tpaid2, 2, '.', '') ?> TK</td>
			<td align="center"><?= number_format((float)$tout2, 2, '.', '') ?> TK</td>
			
		</tr>
<?php } ?>
		</tbody>
		<thead>
			<tr>
				<th>Total Payable</th>            				
				<th>Total Payment</th>
				<th>Total Due</th>
			</tr>
		</thead>
	</table>

	   <h2>Loan</h2>
		<table border=1 width="940px" class="display">
		<thead>
			<tr>
				<th>Date</th>            				
				<th>Receive Amount</th>
				
			</tr>
		</thead>
		<tbody>
<?php 
while($row3=mysqli_fetch_array($sql3)){
	
?>
		<tr>
			<td align="center"><?= daterev($row3['date'],'/','-') ?></td>			
			<td align="center"><?= number_format((float)$row3['amount'], 2, '.', '') ?> TK</td>
			
			
		</tr>
<?php } ?>
		</tbody>
		<thead>
			<tr>
				<th>Date</th>            
				<th>Receive Amount <?= number_format((float)$loan_total['amount'], 2, '.', '') ?> TK</th>
				
			</tr>
		</thead>
	</table>
	
		   <h2>Loan Payment</h2>
		<table border=1 width="940px" class="display">
		<thead>
			<tr>
				<th>Date</th>		
				<th>Payment Amount</th>
				
			</tr>
		</thead>
		<tbody>
<?php 
while($row4=mysqli_fetch_array($sql4)){
	
?>
		<tr>
			<td align="center"><?= daterev($row4['date'],'/','-') ?></td>			
			<td align="center"><?= number_format((float)$row4['amount'], 2, '.', '') ?> TK</td>
			
			
		</tr>
<?php } ?>
		</tbody>
		<thead>
			<tr>
				<th>Date</th>            
				<th>Payment Amount <?= number_format((float)$loan_payment['amount'], 2, '.', '') ?> TK</th>
				
			</tr>
		</thead>
	</table>
	
			   <h2>Investment</h2>
		<table border=1 width="940px" class="display">
		<thead>
			<tr>
				<th>Date</th>		
				<th>Receive Amount</th>				
			</tr>
		</thead>
		<tbody>
<?php 
while($row5=mysqli_fetch_array($sql5)){
	
?>
		<tr>
			<td align="center"><?= daterev($row5['date'],'/','-') ?></td>			
			<td align="center"><?= number_format((float)$row5['amount'], 2, '.', '') ?> TK</td>
			
			
		</tr>
<?php } ?>
		</tbody>
		<thead>
			<tr>
				<th>Date</th>            
				<th>Receive Amount <?= number_format((float)$total_investment['amount'], 2, '.', '') ?> TK</th>
				
			</tr>
		</thead>
	</table>
	
	<h2>Salary</h2>
		<table border=1 width="940px" class="display">
		<thead>
			<tr>
				<th>Date</th>		
				<th>Payment Amount</th>				
			</tr>
		</thead>
		<tbody>
<?php 
while($row6=mysqli_fetch_array($sql6)){
	
?>
		<tr>
			<td align="center"><?= daterev($row6['date'],'/','-') ?></td>			
			<td align="center"><?= number_format((float)$row6['amount'], 2, '.', '') ?> TK</td>
			
			
		</tr>
<?php } ?>
		</tbody>
		<thead>
			<tr>
				<th>Date</th>            
				<th>Payment Amount <?= number_format((float)$total_sal['amount'], 2, '.', '') ?> TK</th>
				
			</tr>
		</thead>
	</table>
	
		<h2>Other Earnings</h2>
		<table border=1 width="940px" class="display">
		<thead>
			<tr>
				<th>Date</th>		
				<th>Receive Amount</th>				
			</tr>
		</thead>
		<tbody>
<?php 
while($row7=mysqli_fetch_array($sql7)){
	
?>
		<tr>
			<td align="center"><?= daterev($row7['date'],'/','-') ?></td>			
			<td align="center"><?= number_format((float)$row7['amount'], 2, '.', '') ?> TK</td>
			
			
		</tr>
<?php } ?>
		</tbody>
		<thead>
			<tr>
				<th>Date</th>            
				<th>Receive Amount <?= number_format((float)$total_earn['amount'], 2, '.', '') ?> TK</th>
				
			</tr>
		</thead>
	</table>
	
			<h2> Other Expense</h2>
		<table border=1 width="940px" class="display">
		<thead>
			<tr>
				<th>Date</th>		
				<th>Payment Amount</th>				
			</tr>
		</thead>
		<tbody>
<?php 
while($row8=mysqli_fetch_array($sql8)){
	
?>
		<tr>
			<td align="center"><?= daterev($row8['date'],'/','-') ?></td>			
			<td align="center"><?= number_format((float)$row8['amount'], 2, '.', '') ?> TK</td>
			
			
		</tr>
<?php } ?>
		</tbody>
		<thead>
			<tr>
				<th>Date</th>            
				<th>Payment Amount <?= number_format((float)$total_expense['amount'], 2, '.', '') ?> TK</th>				
			</tr>
		</thead>
	</table>
   </div>
   </div>

</div>
</div>
</body>
</html>
<?php 
   require('includes/footer.php');
?>