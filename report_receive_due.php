<?php
include("includes/header.php");



?>
<script type="text/javascript">
function payment_history(val1,val2){
	val = val1+'&'+val2;
	$('#datatable tr').remove(); 
	$.post('receive_payment_history.php', {productid: val},
	function (data) {
		$(data).fadeIn("slow").appendTo('#datatable');
		
		
	});
}
</script>

<div class="area">
		<div class="panel-head">Receive Due Report</div>
		<div class="panel">
		
<?php include('left_menu.php'); ?>
<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Modal Window</button>
    <div class="modal-form">
    	View Payment Details<hr/>    	
    	<table id="datatable" class="tab">
    	</table>    	
    </div>
</div>
<div class="report_right">
<div class="table_data">
<?php 
	$sql = @mysqli_query($con,"SELECT DISTINCT `p_id`, `supplier`, sum(`payment`)as payment, sum(`balance`)as balance, sum(`payable`)as payable FROM `purchase` WHERE 1 and `balance`>0 and (type='sale' or type='wholesale') and register_mode = 'sale' and count = '1' group by `supplier`");
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Customer Name</th>            
            <th>Payable Amount</th>
            <th>Paid Amount</th>
            <th>Due Amount</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
while($row = @mysqli_fetch_array($sql)){    
    $an = @mysqli_fetch_array(mysqli_query($con,"select sum(pay_amount) as pay_amount from transaction where type='sale' and name = '".$row['supplier']."' and status = 'due' and approval!='pending' group by name"));
    $customerName = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM personinformation WHERE type='customer' AND id='".$row['supplier']."'"));

    $openblnce = mysqli_fetch_array(mysqli_query($con, "SELECT amount FROM openingbalance  WHERE type='customer' AND personid='".$customerName['id']."'"));
    

    $pay = $an['pay_amount'];
    $payble = $row['payable'];
    $paid = $row['payment']+$pay;

    if(!empty($openblnce)){

        if($openblnce['amount'] < 0){
            $payble = $payble + $openblnce['amount'];
        }

    }
    $due = $payble-$paid;
?>
    <tr>
    		<td><?= ucwords($customerName['name']) ?></td>       		
       		<td><?= number_format((float)$payble, 2, '.', '') ?></td>
       		<td><?= number_format((float)$paid, 2, '.', '') ?></td>
       		<td><?= number_format((float)$due, 2, '.', '') ?></td>
            <td>
				<a onclick="return payment_history('<?= $row['supplier'] ?>','sale');" href="javascript:;"id="modal-launcher" class="view">Payment History</a>
            </td>
    </tr>
<?php } ?> 
    </tbody>
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