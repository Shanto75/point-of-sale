<?php include("includes/header.php"); 
	$whereclass = '';
	if(isset($_GET['seller']) and strlen($_GET['seller'])>0){		
		$whereclass .= " and user_id = '".$_GET['seller']."'";
		$c = $_GET['seller'];
	}else{
		$c = '';
	}
	if(isset($_GET['from']) and strlen($_GET['from'])>0){
		$from = modifydate($_GET['from'],'-','/').'00:00:00';
		$whereclass .= " and login_time >= '".$from."'";
		$f = $_GET['from'];
	}else{
		$f = '';
	}
	if(isset($_GET['to']) and strlen($_GET['to'])>0){
		$to = modifydate($_GET['to'],'-','/').'23:59:59';
		$t = $_GET['to'];
		$whereclass .= " and login_time <= '".$to."'";
	}else{
		$t = '';
	}
	$group = "order by login_time desc";
	$sql = mysqli_query($con,"select * from login_details where 1 $whereclass $group");
	
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
    	View Invoice Details<hr/>

    	<table border="1" id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<div class="area">
      <div class="panel-head">Seller Report</div>
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
				<td width="5%">Seller </td>
				
				<td width="4%">
				
				<select  name="seller" >
					<option value="">Please Select</option>
					<?php 
						$sql2 = mysqli_query($con,"select * from users where 1");
						while($x = mysqli_fetch_array($sql2)){
							if(isset($_GET['seller']) and $_GET['seller']==$x['id']){
								echo '<option value= "'.$x['id'].'" selected="selected">'.ucwords($x['username']).'</option>';
							}else{
								echo '<option value= "'.$x['id'].'">'.$x['username'].'</option>';
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
		<a  href="download_user_log_report.php?from=<?= $f ?>&to=<?= $t ?>&seller=<?= $_GET['seller'] ?>" class="btn-add">Export/Print</a>
</div>
   <div class="table_data">
		<table id="table_id" class="display">
		<thead>
			<tr>
				<th>Date</th>            
				<th>User Name</th>
				<th>Login Time</th>
				<th>Logout Time</th>
				<th>Duration</th>				
			</tr>
		</thead>
		<tbody>
<?php 
while($row = mysqli_fetch_array($sql)){
	$sold = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `users` where id = '".$row['user_id']."'"));
	$date = explode(' ',$row['login_time']);
	$currentDateTime = $row['login_time'];
	$currentDateTime2 = $row['logout_time']>'1990-01-01 01:10:10'?$row['logout_time']:'';
	if($currentDateTime2!=''){
		$logout_time = date('h:i A', strtotime($currentDateTime2));
	}else{
		$logout_time = '';
	}
	$login_time = date('h:i A', strtotime($currentDateTime));
	
	
?>
		<tr>
			<td><?= daterev($date[0],'/','-') ?></td>
			<td><?= $sold['username'] ?></td>
			<td><?= $login_time ?></td>
			<td><?= $logout_time ?> </td>
			<td><?= $row['time'] ?> </td>
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