
<?php include("includes/header.php"); 
	$whereclass = '';
	$whereclass1 = '';
	$whereclass2 ='';
	
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
	
	if(isset($_GET['bank']) and $_GET['bank']!=''){
		$bank = $_GET['bank'];
		$whereclass .= "and bankid = '$bank'";
		$whereclass1 .= "and bank = '$bank'";
		$whereclass2 .= "and personid = '$bank' and type='bank'";
	}else{
		$whereclass2 =" and type='bank'";
	}

	$datarr= array();
	$sql = mysqli_query($con,"select * from transfer where 1 $whereclass");
	while($row= mysqli_fetch_array($sql)){
		if($row['type']=='c2b'){
		$datarr[] = array($row['date'],$row['bank_name'],$row['ac_number'],'Cash to Bank',$row['amount'],'');	
		}else{
			$datarr[] = array($row['date'],$row['bank_name'],$row['ac_number'],'Bank to Cash','',$row['amount']);
		}
		
	}
	
	$sql2 = mysqli_query($con,"SELECT * FROM `purchase` where mode = 'check' $whereclass1 and approve!='pending'");
	while($row2 = mysqli_fetch_array($sql2)){
		$bankinfo = mysqli_fetch_array(mysqli_query($con,"select * from bankinformation where id = '".$row2['bank']."'"));

		$personName = mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE id='".$row2['supplier']."'"));

		if($row2['register_mode']=='sale'){
			$datarr[] = array($row2['date'],$bankinfo['bankname'],$bankinfo['accountnumber'],$personName['name'],$row2['payment'],'');	
		}else{
			$datarr[] = array($row2['date'],$bankinfo['bankname'],$bankinfo['accountnumber'],$personName['name'],'',$row2['payment']);	
		}
	}

//bank opening balance
     $sql4 = @mysqli_query($con, "SELECT * FROM openingbalance WHERE 1 $whereclass2");
	while ($r4 = mysqli_fetch_array($sql4)) {

		$bankOpen= mysqli_fetch_array(mysqli_query($con, "SELECT * FROM bankinformation WHERE id='".$r4['personid']."'"));

		 $datarr[] = array('',$bankOpen['bankname'],$bankOpen['accountnumber'],'Bank Opening Balance',$r4['amount']);
		}





	$sql3 = mysqli_query($con,"select * from transaction where status = 'due' $whereclass1 and approval!='pending' and method ='check'");
	while($r = mysqli_fetch_array($sql3)){
		$bankinfo = mysqli_fetch_array(mysqli_query($con,"select * from bankinformation where id = '".$r['bank']."'"));
		$personName = mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE id='".$r['name']."'"));
		if($r['type']=='sale'){
			$datarr[] = array($r['date'],$bankinfo['bankname'],$bankinfo['accountnumber'],$personName['name'],$r['pay_amount'],'');
		}elseif($r['type']=='purchase'){
			$datarr[] = array($r['date'],$bankinfo['bankname'],$bankinfo['accountnumber'],$personName['name'],'',$r['pay_amount'],'');
		}		
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
      <div class="panel-head">Bank Transaction Report</div>
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
				<td width="10%">Bank : </td>
				<td width="5%"><select name="bank">
					<option value="">All</option>
					<?php 
						$bquery = mysqli_query($con,"select * from bankinformation where person_type = 'owner'");
						while($br= mysqli_fetch_array($bquery)){
							if($_GET['bank']==$br['id']){
							echo '<option value="'.$br['id'].'" selected="selected">'.$br['bankname'].' ('.$br['accountnumber'].') </option>';
							}else{
							echo '<option value="'.$br['id'].'">'.$br['bankname'].' ('.$br['accountnumber'].') </option>';	
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
		<a  href="generate_bank_report.php?from=<?= $f ?>&to=<?= $t ?>&bank=<?= $_GET['bank']==''?'':$_GET['bank'] ?>" class="btn-add">Export/Print</a>
</div>
   <div class="table_data">
		<table id="table_id" class="display">
		<thead>
			<tr>
				<th>Date</th>            				
				<th>Bank Name</th>
				<th>Account Number</th>				
				<th>From/To</th>				
				<th>IN</th>
				<th>OUT</th>
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
	<td align="center"><?= ucwords($datarr[$i][3]) ?></td>
	<td align="center"><?php 
			echo $datarr[$i][4]; 
			$in[] = $datarr[$i][4];
		?>
	</td>
	<td align="center"><?php 
			echo $datarr[$i][5]; 
			$out[] = $datarr[$i][5];
		?>
	</td>
</tr>
<?php
}	
?>

		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>IN <?= array_sum($in); ?></td>
				<td>OUT <?= array_sum($out); ?></td>
			</tr>
		</tfoot>
	</table>
	<br />
	<table>
		<tr>
			<td>Balance : </td>
			<td><?= (array_sum($in)-array_sum($out)) ?></td>
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