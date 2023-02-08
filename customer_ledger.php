
<?php include("includes/header.php"); 
	$whereclass = '';
	$whereclass1 = '';
	
	
	if(isset($_GET['from']) and strlen($_GET['from'])>0){
		$from = modifydate($_GET['from'],'-','/');
		$whereclass .= " and date >= '".$from."'";
		$whereclass1 .= " and date >= '".$from."'";
	
		$f = $_GET['from'];
	}else{
		$f = '';
	}
	if(isset($_GET['to']) and strlen($_GET['to'])>0){
		$to = modifydate($_GET['to'],'-','/');
		$t = $_GET['to'];
		$whereclass .= " and date <= '".$to."'";
		$whereclass1 .= " and date <= '".$to."'";
		
	}else{
		$t = '';
	}
	
	if(isset($_GET['customer']) and $_GET['customer']!=''){
		$customer = $_GET['customer'];
		$whereclass .= "and supplier = '$customer'";
		$whereclass1 .= "and name = '$customer'";
		$whereclass2 .= "and personid = '$customer' and type='customer'";
	}else{
		$whereclass2 =" and type='customer'";
	}
	$datarr= array();

	
	$sql2 = mysqli_query($con,"SELECT `p_id`, `date`, `supplier`, `product_name`, `payment`, `payable`,`type` FROM `purchase` where 1 $whereclass and approve!='pending' and count=1 and type!='purchase' and register_mode!='return'");
	while($row2 = mysqli_fetch_array($sql2)){
		   $customerName= mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE type='customer' AND id='".$row2['supplier']."'"));

			$datarr[] = array($row2['date'],$customerName['name'],$row2['type'],$row2['payment'],$row2['payable'],$row2['p_id']);	

		
	}

     $sql4 = @mysqli_query($con, "SELECT * FROM openingbalance WHERE 1 $whereclass2");
	while ($r4 = mysqli_fetch_array($sql4)) {
		//echo "SELECT name FROM personinformation WHERE type='customer' AND id='".$r4['personid']."'";exit;
		 $customerName= mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE type='customer' AND id='".$r4['personid']."'"));
		$datarr[] = array('',$customerName['name'],'opening balance','',$r4['amount'],'');
	}
	

	/*$sql4 = mysqli_query($con,"SELECT `p_id`, `date`, `supplier`, `product_name`, `payment`, `payable`,`type` FROM `purchase` where 1 $whereclass and count=1 and type!='purchase' and register_mode='return'");
	while($row3 = mysqli_fetch_array($sql4)){
		
			$datarr[] = array($row3['date'],$row3['supplier'],$row3['type'],$row3['payable'],'',$row3['p_id']);	
		
	}*/
	//echo "SELECT * FROM transaction WHERE status='due'  AND type='sale' $whereclass1";exit;
	$sql3 = mysqli_query($con,"SELECT * FROM transaction WHERE status='due'  AND type='sale' $whereclass1  and approval!='pending'");
	while($r = mysqli_fetch_array($sql3)){
		$customerName= mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE type='customer' AND id='".$r['name']."'"));
			$datarr[] = array($r['date'],$customerName['name'],'Customer Due payment',$r['pay_amount'],'','');	
	}


	
?>
<style type="text/css">
	.select_style 
{
	background: #FFF;
	overflow: hidden;
	display: inline-block;
	color: #fff;
	font-size: 15px;
	-webkit-border-radius: 5px 4px 4px 5px/5px 5px 4px 4px;
	-moz-border-radius: 5px 4px 4px 5px/5px 5px 4px 4px;
	border-radius: 5px 4px 4px 5px/5px 5px 4px 4px;
	-webkit-box-shadow: 0 0 5px rgba(123, 123, 123, 0.2);
	-moz-box-shadow: 0 0 5px rgba(123,123,123,.2);
	box-shadow: 0 0 5px rgba(123, 123, 123, 0.2);
	border: solid 1px #ccc;
	font-family: "helvetica neue",arial;
	position: relative;
	top:7px; ;
	cursor: pointer;
	padding-right:20px;

}
.select_style span
{
	position: absolute;
	right: 10px;
	width: 8px;
	height: 8px;
	background: url(http://projects.authenticstyle.co.uk/niceselect/arrow.png) no-repeat;
	top: 50%;
	margin-top: -4px;
}
.select_style select
{
	-webkit-appearance: none;
	appearance:none;
	width:120%;
	background:none;
	background:transparent;
	border:none;
	outline:none;
	cursor:pointer;
	padding:7px 10px;
}
#dropdown 
{
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
</style>
<script type="text/javascript">
function view_invoice_details(val){
	
	$('#datatable tr').remove(); 
	$.post('view_expense_ajax.php', {productid: val},
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

<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Modal Window</button>
    <div class="modal-form">
    	View Expense Details<hr/>

    	<table border="1" id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<div class="area">
      <div class="panel-head">Customer Ledger</div>
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
				<td width="10%">Customer : </td>
				<td width="5%"><select name="customer">
					<option value="">All</option>
					<?php 
						$bquery = mysqli_query($con,"select * from personinformation where type = 'customer' and name!=''");
						while($br= mysqli_fetch_array($bquery)){
							if($_GET['customer']==$br['id']){
							echo '<option value="'.$br['id'].'" selected="selected">'.ucwords($br['name']).' </option>';
							}else{
							echo '<option value="'.$br['id'].'">'.ucwords($br['name']).' </option>';	
							}							
						}
					?>
				</select>
				</td>
				
				<td width="5%" valign="left"><input class="btn btn-info" type="submit" name="Submit" value="Show">
				</td>
			</tr>
		
	</table>
	</form>
<div style="overflow:hidden;padding:10px;margin-bottom:10px;text-align: right;" >	
		<a  href="generate_customer_ledger.php?from=<?= $f ?>&to=<?= $t ?>&customer=<?= $_GET['customer']==''?'':urlencode($_GET['customer']) ?>" class="btn-add">Export/Print</a>
</div>
   <div class="table_data">
		<table id="table_id" class="display">
		<thead>
			<tr>
				<th>Date</th>            				
				<th>Customer Name</th>
				<th>Description</th>									
				<th>Bill No</th>									
				<th>Dr</th>
				<th>Cr</th>
			</tr>
		</thead>
		<tbody>
<?php 
$in= array();
$out= array();
for($i=0;$i<sizeof($datarr);$i++){

?>
<tr>
	<td align="center"><?= daterev($datarr[$i][0],'/','-') ?></td>
	<td align="center"><?= ucwords($datarr[$i][1]) ?></td>
	<td align="center"><?= ($datarr[$i][2]) ?></td>
	<td align="center"><?= ($datarr[$i][5]) ?></td>
	<td align="center"><?php 
			echo $datarr[$i][3]; 
			$in[] = $datarr[$i][3];
		?>
	</td>
	<td align="center"><?php 
			echo $datarr[$i][4]; 
			$out[] = $datarr[$i][4];
		?>
	</td>
</tr>
<?php
}	
?>

		</tbody>
		<tfoot>
			<tr>
				<th></th>            				
				<th></th>
				<th></th>
				<th></th>
				<td>Dr <?= array_sum($in); ?></td>
				<td>Cr <?= array_sum($out); ?></td>
			</tr>
		</tfoot>
	</table>
	<br />
	<table width="300px">
		<tr>
		<?php
			if(array_sum($in)>array_sum($out)){
				echo '<th><h2>Balance</h2></th>';
			}else{
				echo '<th><h2>Due Balance</h2></th>';
			}
		?>
			
			<td><h2><?= (array_sum($in)-array_sum($out)) ?></h2></td>
		</tr>
	</table>
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