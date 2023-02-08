<?php include("includes/header.php"); 
$msg = '';
if(isset($_GET['del']) and $_GET['del']!=''){
	$did= $_GET['del'];
	$ustok = @mysqli_query($con, "select * from purchase where p_id='$did'");
	while($r = mysqli_fetch_array($ustok)){
		 $qty   = $r['quantity'];
		 $pname = $r['product_name'];
		 $pcd   = $r['pcode'];

	@mysqli_query($con,"UPDATE `stock` SET `quantity`=  `quantity`+'$qty' WHERE 1 and product_name ='$pname' and code ='$pcd'");
	}

	@mysqli_query($con,"DELETE FROM `purchase` WHERE 1 and p_id = '$did'");
	$msg = 'Data Deleted successfully!';
}
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
	$sql = mysqli_query($con,"select * from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and count = '1' and approve!='pending'");
	
	$totalsale = @mysqli_fetch_array(mysqli_query($con,"select sum(payable) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' and approve!='pending' $whereclass")); 
	$totalrec = @mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' and approve!='pending' $whereclass")); 
	$totaldes = @mysqli_fetch_array(mysqli_query($con,"select sum(dis_amount) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' and approve!='pending' $whereclass")); 
	$totalout = @mysqli_fetch_array(mysqli_query($con,"select sum(balance) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' and approve!='pending' $whereclass")); 
	$totaltax = @mysqli_fetch_array(mysqli_query($con,"select sum(tax_amount) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' and approve!='pending' $whereclass"));
	$due1= @mysqli_fetch_array(@mysqli_query($con,"select sum(pay_amount)as total from transaction where type='sale' and status='due' and approval!='pending'"));
$tpaid = $totalrec['total']+$due1['total'];
$tout = $totalout['total']-$due1['total'];
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
      <div class="panel-head">Product Sales Report</div>
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
<div style="overflow:hidden;padding:10px;margin-bottom:10px;text-align: right;" >	
		<a  href="generate_sales_report.php?from=<?= $f ?>&to=<?= $t ?>" class="btn-add">Print</a>
		<!--<a  href="download_sales_report.php?from=<?= $f ?>&to=<?= $t ?>" class="btn-add">Export</a>-->
</div>
   <div class="table_data">
		<table id="table_id" class="display" style="font-size: 14px;">
		<thead>
			<tr>
				<th>Date</th>            
				<th>Customer</th>
				<th>Payable</th>				
				<th>Paid</th>
				<th>Due</th>
				<th>Total Vat</th>
				<th>Sold by</th>				
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
<?php 
//echo "SELECT name FROM personinformation WHERE `id` = '".$row['supplier']."'";exit;
while($row=mysqli_fetch_array($sql)){
	$sold = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `users` where id = '".$row['user']."'"));
	$customerName = mysqli_fetch_array(mysqli_query($con,"SELECT name FROM personinformation WHERE `id` = '".$row['supplier']."'"));
	
		
	/*	if($row['balance']>1){
		$due = @mysqli_fetch_array(@mysqli_query($con,"select sum(pay_amount)as total from transaction where type='sale' and status='due' and name = '".$row['supplier']."'"));
		
		$paymnt = $row['payment']+$due['total'];
		$bal = $row['balance']-$due['total'];
	}else{
		$paymnt = $row['payment'];
		$bal = $row['balance'];
	}  */
	
?>
		<tr>
			<td><?= daterev($row['date'],'/','-') ?></td>
			
			<td><?= ucwords($customerName['name']) ?></td>
			<td><?= number_format((float)$row['payable'], 2, '.', '') ?> TK</td>
			
			<td><?= number_format((float)$row['payment'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['balance'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['tax_amount'], 2, '.', '') ?></td>
			<td><?= ucwords($sold['username']) ?></td>
			<td style="width:200px">
				<a onclick="return view_invoice_details('<?= $row['p_id']; ?>');" href="javascript:;"id="modal-launcher" class="view">View</a>
				<a target="_blank" href="add_sales_print.php?sid=<?= $row['p_id'] ?>" class="view">Invoice</a>
				<a target="_blank" href="sale_edit.php?id=<?= $row['p_id'] ?>" id="" class="edit">Edit</a>
				<a href="report_sales.php?del=<?= $row['p_id'] ?>" id="" class="delete">Delete</a>
			</td>
		</tr>
<?php } ?>
		</tbody>
	</table>
	<br />
	<br />
	<table>
		<tr>
			<th align="left">Total Sales : </th>
			<td align="right"><?= number_format((float)$totalsale['total'], 2, '.', '') ?> TK</td>
		</tr>
		<tr>
			<th align="left">Total Tax : </th>
			<td align="right"><?= number_format((float)$totaltax['total'], 2, '.', '') ?> TK</td>
		</tr>
		<tr>
			<th align="left">Total Payment : </th>
			<td align="right"><?= number_format((float)$tpaid, 2, '.', '') ?> TK</td>
		</tr>
		<tr>
			<th align="left">Total Discount : </th>
			<td align="right"><?= number_format((float)$totaldes['total'], 2, '.', '') ?> TK</td>
		</tr>
		<tr>
			<th align="left">Total Due : </th>
			<td align="right"><?= number_format((float)$tout, 2, '.', '') ?> TK</td>
		</tr>		
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