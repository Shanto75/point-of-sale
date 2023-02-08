<?php
ob_start();
session_start();
$msg = '';
if(!isset($_SESSION['id']) and $_SESSION['id']==''){
header("Location: login.php");
exit();
}
include('includes/config.php');
include('includes/function.php');
$store_id = $_SESSION['store_id'];
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
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
        <html>
        <head>
            <title>Sale Report</title>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        </head>
        <style type="text/css" media="print">
            .hide {
                display: none
            }
        </style>
        <script type="text/javascript">
            function printpage() {
                document.getElementById('printButton').style.visibility = "hidden";
                window.print();
                document.getElementById('printButton').style.visibility = "visible";
            }
        </script>
        <body>
        <input name="print" type="button" value="Print" id="printButton" onClick="printpage()">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center">
                    <div style="width:695px" align="right">
                        <?php $shop = mysqli_fetch_array(mysqli_query($con,"select * from storeinformation where 1 and id = '$store_id'"));
                        ?>
                        <strong><?php echo ucwords($shop['name']); ?></strong><br/>
						<?php echo $shop['address'] ?><br/>
						Website<strong>:<?php echo $shop['website'] ?></strong><br>Email<strong>:<?php echo $shop['email']; ?></strong><br/>Phone
						<strong>:<?php echo $shop['phone']; ?></strong>
						<br/>
                        <?php ?>
                    </div>
                    <table width="695" border="0" cellspacing="0" cellpadding="0">

                        <tr>
                            <td height="30" align="center"><strong>Profit/Lose Report </strong></td>
                        </tr>
                        <tr>
                            <td height="30" align="center">&nbsp;</td>
                        </tr>

                        <tr>
                            <td width="45">
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <td height="20">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="45"><strong>From</strong></td>
                                        <td width="393">&nbsp;<?php echo $_GET['from']; ?></td>
                                        <td width="41"><strong>To</strong></td>
                                        <td width="116">&nbsp;<?php echo $_GET['to']; ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td width="45">
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <td>
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

   $due1= @mysqli_fetch_array(@mysqli_query($con,"select sum(pay_amount)as total from transaction where type='sale' and status='due' and approval!='pending'"));

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
				<th>Payment Amount <?= number_format((float)$total_earn['amount'], 2, '.', '') ?> TK</th>				
			</tr>
		</thead>
	</table>
                            </td>
							
                        </tr>
						
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
						<tr>
                            <td>&nbsp;</td>
                        </tr>
						<tr>
                            <td>&nbsp;</td>
                        </tr>
						<tr>
                            <td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td><hr>Prepared By</td>
										<td><hr>Checked By</td>
										<td><hr>Manager</td>
									</tr>
								</table>
							</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
						<tr>
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td height="20px" bgcolor="#ddd" align="center"><?php include('footer_text.php');  ?></td>
									</tr>
								</table>
							</td>
						</tr>
                    </table>
                </td>
            </tr>
        </table>

        </body>
        </html>
