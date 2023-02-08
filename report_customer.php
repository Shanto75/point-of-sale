<?php include("includes/header.php"); 
	$whereclass = '';
	$whereclassOne = '';
	if(isset($_GET['customer']) and strlen($_GET['customer'])>0){		
		$whereclass .= " and supplier = '".$_GET['customer']."'";
		$whereclassOne .= " and name = '".$_GET['customer']."'";
		$c = $_GET['customer'];
	}else{
		$c = '';
	}
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
	$sql = mysqli_query($con,"select * from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and approve!='pending' group by p_id");
	
	$totalsale = @mysqli_fetch_array(mysqli_query($con,"select sum(payable) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and approve!='pending' and count = 1")); 
	$totalrec = @mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and approve!='pending' and count = 1")); 
	$totaldes = @mysqli_fetch_array(mysqli_query($con,"select sum(dis_amount) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and approve!='pending' and count = 1")); 
	$totalout = @mysqli_fetch_array(mysqli_query($con,"select sum(balance) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and approve!='pending' and count = 1")); 
	$due1= @mysqli_fetch_array(@mysqli_query($con,"select sum(pay_amount)as total from transaction where type='sale' and status='due' $whereclassOne  and approval!='pending'"));
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
                width: 180,
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
      <div class="panel-head">Customer Report</div>
      <div class="panel">
      
<?php include('left_menu.php'); ?>

   <div class="report_right">
   <form action="" method="get">
   <table width="400px" class="tab form" border="0" cellspacing="0" cellpadding="0">
		
			<tr>

				
				<td width="4%">From</td>
				
				<td width="4%"><input class="form-control datepick" name="from" value="<?= $f ?>" type="text" id="from_sales_date"
						   style="width:160px;"></td>
				
				<td width="4%">To</td>
				
				<td width="5%"><input class="form-control datepick" name="to" value="<?= $t ?>" type="text" id="to_sales_date" style="width:160px;">
				</td>
				<td width="5%">Customer</td>
				
				<td width="4%">
					<select name="customer">
						<option value="">Please select One</option>
						<?php
						$nameSql =mysqli_query($con, "SELECT * FROM personinformation WHERE type='customer'");
                        while ($r = mysqli_fetch_array($nameSql)) {
                        	if(isset($_GET['customer']) and $_GET['customer']==$r['id']){

                        		echo '<option value="'.$r['id'].'" selected="selected">'.ucwords($r['name']).'</option>';
                        	}else{
                        		echo '<option value="'.$r['id'].'">'.ucwords($r['name']).'</option>';
                        	}
                        	
                        }
						

                         
						?>
					</select>
				</td>
				<td width="5%" valign="left"><input class="btn btn-info" type="submit" name="Submit" value="Show">
				</td>
			</tr>
		
	</table>
	<br />
	<br />
	</form>
<div style="overflow:hidden;padding:10px;margin-bottom:10px;text-align: right;" >			
		<a  href="download_customer_report.php?from=<?= $f ?>&to=<?= $t ?>&customer=<?= $c ?>" class="btn-add">Export/Print</a>
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
				<th>Received by</th>				
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
<?php 
while($row=mysqli_fetch_array($sql)){
	$sold = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `users` where id = '".$row['user']."'"));
	$customerName = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM personinformation WHERE type='customer' AND id='".$row['supplier']."'"));
		/*	if($row['balance']>1){
		$due = @mysqli_fetch_array(@mysqli_query($con,"select sum(pay_amount)as total from transaction where type='sale' and status='due' and name = '".$row['supplier']."'"));
		
		$paymnt = $row['payment']+$due['total'];
		$bal = $row['balance']-$due['total'];
	}else{
		$paymnt = $row['payment'];
		$bal = $row['balance'];
	}*/
?>
		<tr>
			<td><?= daterev($row['date'],'/','-') ?></td>
			<td><?= $row['bill_no'] ?></td>
			<td><?= ucwords($customerName['name']) ?></td>
			<td><?= number_format((float)$row['payable'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['dis_amount'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['payment'], 2, '.', '') ?> TK</td>
			<td><?= number_format((float)$row['balance'], 2, '.', '') ?> TK</td>
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