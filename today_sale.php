<?php include("includes/header.php"); 
	$whereclass = '';
	
	if(isset($_GET['from']) and strlen($_GET['from'])>0){
		$from = modifydate($_GET['from'],'-','/');
		$whereclass .= " and date >= '".$from."'";
		$f = $_GET['from'];
	}else{
		$from = date('Y-m-d');
		$whereclass .= " and date >= '".$from."'";
		
	}
	if(isset($_GET['to']) and strlen($_GET['to'])>0){
		$to = modifydate($_GET['to'],'-','/');
		$t = $_GET['to'];
		$whereclass .= " and date <= '".$to."'";
	}else{
		$to = date('Y-m-d');
		$whereclass .= " and date <= '".$to."'";
		
	}
	$sql = mysqli_query($con,"select * from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and approve!='pending' and count = '1' group by p_id");
	
	$totalsale = @mysqli_fetch_array(mysqli_query($con,"select sum(payable) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass and approve!='pending'")); 
	$totalrec = @mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass and approve!='pending'")); 
	$totaldes = @mysqli_fetch_array(mysqli_query($con,"select sum(dis_amount) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass and approve!='pending'")); 
	$totalout = @mysqli_fetch_array(mysqli_query($con,"select sum(balance) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass and approve!='pending'")); 
	$totaltax = @mysqli_fetch_array(mysqli_query($con,"select sum(tax_amount) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' and count = '1' $whereclass and approve!='pending'"));
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
      <div class="panel-head">Today's Sales Report</div>
      <div class="panel">
      

   <div class="report_right">

<div style="overflow:hidden;padding:10px;margin-bottom:10px;" >	
		<h3>Today's Sale Report</h3>
</div>
   <div class="table_data">
		<table id="table_id" class="display">
		<thead>
			<tr>
				<th>Date</th>            
				<th>Invoice Id</th>
				<th>Customer</th>
				<th>Payable</th>
				<th>Discount</th>
				<th>Paid</th>
				<th>Due</th>
				<th>Total Vat</th>
				<th>Sold by</th>				
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
<?php 
while($row=mysqli_fetch_array($sql)){
	$sold = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `users` where id = '".$row['user']."'"));

	$customerName = mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE type='customer' AND id='".$row['supplier']."'"))
?>
		<tr>
			<td><?= daterev($row['date'],'/','-') ?></td>
			<td><?= $row['bill_no'] ?></td>
			<td><?= ucwords($customerName['name']) ?></td>
			<td><?= number_format((float)$row['payable'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['dis_amount'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['payment'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['balance'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['tax_amount'], 2, '.', '') ?> TK</td>
			<td><?= ucwords($sold['username']) ?></td>
			<td>
				<a onclick="return view_invoice_details('<?= $row['p_id']; ?>');" href="javascript:;"id="modal-launcher" class="view">View</a></div>
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
			<th align="left">Total Receive : </th>
			<td align="right"><?= number_format((float)$totalrec['total'], 2, '.', '') ?> TK</td>
		</tr>
		<tr>
			<th align="left">Total Discount : </th>
			<td align="right"><?= number_format((float)$totaldes['total'], 2, '.', '') ?> TK</td>
		</tr>
		<tr>
			<th align="left">Total Due : </th>
			<td align="right"><?= number_format((float)$totalout['total'], 2, '.', '') ?> TK</td>
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