<?php include("includes/header.php"); 
	if(isset($_GET['id']) and $_GET['id']!=''){
		$id = $_GET['id'];
		@mysqli_query($con,"update purchase set approve = 'success', `date` = `check_date` where `p_id` = '$id'");
		echo 'Sale Approved!';
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
	$sql = mysqli_query($con,"select * from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and count = '1' and approve = 'pending' group by p_id");
	
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
      


   <div class="report_right">

	<br />
	</form>

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

	$customerName = mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE type='customer' AND id='".$row['supplier']."'"));
?>
		<tr>
			<td><?= daterev($row['date'],'/','-') ?></td>
			<td><?= $row['p_id'] ?></td>
			<td><?= ucwords($customerName['name']) ?></td>
			<td><?= number_format((float)$row['payable'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['dis_amount'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['payment'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['balance'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['tax_amount'], 2, '.', '') ?></td>
			<td><?= ucwords($sold['username']) ?></td>
			<td style="width:150px">
				<a  href="pending_sale.php?id=<?= $row['p_id'] ?>" class="view">Approve</a>
			</td>
		</tr>
<?php } ?>
		</tbody>
	</table>
	<br />
	<br />

   </div>
   </div>

</div>
</div>
</body>
</html>
<?php 
   require('includes/footer.php');
?>