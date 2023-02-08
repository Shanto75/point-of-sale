<?php
include("includes/header.php");
$msg = '';
	$whereclass = '';
	if(isset($_GET['customer']) and strlen($_GET['customer'])>0){
		$whereclass .= " and supplier = '".$_GET['customer']."'";
		$c = $_GET['customer'];
	}else{
		$c = '';
	}
	if(isset($_GET['from']) and strlen($_GET['from'])>0){
		$from = modifydate($_GET['from'],'-','/');
		$f = $_GET['from'];
		$whereclass .= " and date >= '".$from."'";
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
	$sql = mysqli_query($con,"SELECT `supplier`, sum(`payment`)as payment,  sum(`dis_amount`)as dis_amount, sum(`payable`)as payable, sum(balance)as balance  FROM `purchase` WHERE 1 and type='purchase' and register_mode='purchase' and count='1' $whereclass group by supplier");

	
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
$(function () {

            $("#customer").autocomplete("supplier1.php", {
                width: 170,
                autoFill: true,
                selectFirst: true
            });
});
	function stock_short(val){
		if(val=='category'){
			$("#brand").css("display", "none");
			$("#expire_date").css("display", "none");
			$("#category").css("display", "block");
		}else if(val=='brand'){
			$("#category").css("display", "none");
			$("#expire_date").css("display", "none");
			$("#brand").css("display", "block");
		}else if(val=='expire_date'){
			$("#expire_date").css("display", "block");
			$("#category").css("display", "none");
			$("#brand").css("display", "none");
		}
		else{
			$("#category").css("display", "none");
			$("#brand").css("display", "none");
			$("#expire_date").css("display", "none");
		}
	}
function sendForm() {
     document.myform.submit();
}
function checkproductdetails(val){
	$('#datatable tr').remove(); 
	$.post('view_product_details.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>

<div class="area">
		<div class="panel-head">Payment Reports</div>
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
<form id="myform" name="myform" action="" method="get">
	<table>
		<tr>
			<td >Supplier Name</td>
			<td >
				<input type="text" id="customer" value="<?= $c ?>" name="customer"/>
			</td>
			<td>From</td>
			<td><input type="text" class="datepick" value="<?= $f ?>" name="from"/></td>
			<td>To</td>
			<td><input type="text" class="datepick" value="<?= $t ?>" name="to"/></td>
			
			<td><input type="submit" value="go"></td>
		</tr>
		<tr height="10"></tr>
	</table>
</form>
<div style="overflow:hidden;padding:10px;margin-bottom:10px;" >	
		<a  href="download_payment_report.php?from=<?= $f ?>&to=<?= $t ?>&customer=<?= $c ?>" class="btn-add">PDF Download</a>		
</div>
<div class="table_data">
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Supplier Name</th>
            <th>Total Cost</th>
            <th>Total Paid</th>            
            <th>Total Due</th>            
        </tr>
    </thead>
    <tbody>
<?php 
	while($row = mysqli_fetch_array($sql)){

			$an = @mysqli_fetch_array(mysqli_query($con,"select sum(pay_amount) as pay_amount from transaction where type='purchase' and name = '".$row['supplier']."' and status = 'due' group by name"));
			$customerName = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM personinformation WHERE type='supplier' AND id='".$row['supplier']."'"));


	$openblnce = mysqli_fetch_array(mysqli_query($con, "SELECT amount FROM openingbalance  WHERE type='supplier' AND personid='".$customerName['id']."'"));
	


	$pay = $an['pay_amount'];
	$payble = $row['payable'];
	$paid = $row['payment']+$pay;
	
	if(!empty($openblnce)){

		if($openblnce['amount'] < 0){
			$paid = $paid + abs($openblnce['amount']);
		}else{
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

         
    </tr>
	<?php  } ?>
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