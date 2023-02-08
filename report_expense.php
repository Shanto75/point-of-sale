
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
	
	$sql = mysqli_query($con,"SELECT `date`, `earning_head`, sum(`amount`)as amount FROM `expense` WHERE 1 $whereclass group by earning_head");
	
	$total = @mysqli_fetch_array(mysqli_query($con,"SELECT `date`, `earning_head`, sum(`amount`)as amount FROM `expense` WHERE 1 $whereclass")); 

?>

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
      <div class="panel-head">Expense Report</div>
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
				
				<td width="5%" valign="left"><input class="btn btn-info" type="submit" name="Submit" value="Show">
				</td>
			</tr>
		
	</table>
	<br />
	<br />
	</form>
   <div class="table_data">
		<table id="table_id" class="display">
		<thead>
			<tr>
				<th>Date</th>            				
				<th>Head</th>
				<th>Amount</th>				
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
<?php 
while($row = mysqli_fetch_array($sql)){
	
?>
		<tr>
			<td><?= daterev($row['date'],'/','-') ?></td>
			<td><?= ucwords($row['earning_head']) ?></td>
			<td><?= number_format((float)$row['amount'], 2, '.', '') ?></td>
			<td>
				<a onclick="return view_invoice_details('<?= $row['date']; ?>');" href="javascript:;"id="modal-launcher" class="view">View</a></div>
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
			<td align="right"><?= number_format((float)$total['amount'], 2, '.', '') ?> TK</td>
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