<?php include("includes/header.php");?>

<script type="text/javascript">
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
function checkproductdetails(val,mon){
	dat = val+'&'+mon;
	
	$('#datatable tr').remove(); 
	$.post('view_salary_payment.php', {productid: dat},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>

<?php 
$msg = '';
	$whereclass = '';
	if(isset($_GET['salary_monty']) and strlen($_GET['salary_monty'])>0){
		$whereclass .=" and month = '".$_GET['salary_monty']."'";
	}else{
		$ads = date('m');
		$math = date('F', mktime(0,0,0,$ads, 1, date('Y')));
		$whereclass .=" and month = '".$math."'";
	}
	if(isset($_GET['year']) and strlen($_GET['year'])>0){
		$whereclass .=" and year = '".$_GET['year']."'";
	}
	$sql = mysqli_query($con,"SELECT `id`, `employee_id`, sum(`amount`)as amount, month FROM `salary` WHERE 1  $whereclass group by `employee_id`");
?>
	<div class="area">
		<div class="panel-head">Monthwise Salary Report</div>
		<div class="panel">
		<div class="btn">	

</div>
<!--View-->
<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Modal Window</button>
    <div class="modal-form">
    	View Payment Details<hr/>
    	
    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<h3>
<form id="myform" name="myform" action="" method="get">
	<table>
		<tr>
			<td width="20%">Salary Month</td>
			<td width="20%">
				<select name="salary_monty" style="width:180px">
				<?php
					for ($m=1; $m<=12; $m++) {
					 $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
						 $d = date('m');
						 $match = date('F', mktime(0,0,0,$d, 1, date('Y')));
						 if($month==$match){
							 echo '<option value="'.$month.'" selected="selected">'.$month.'</option>';
						 }else{
							 echo '<option value="'.$month.'">'.$month.'</option>';
						 }								 
					 }
				?>
				</select>
			</td>
			<td width="5%"><td>
			<td width="10%">Year</td>
			<td>
				<select name="year" style="width:180px">
				<?php
					for ($m=2014; $m<=2025; $m++) {
					
						 $d = date('Y');								
						 if($m==$d){
							 echo '<option value="'.$m.'" selected="selected">'.$m.'</option>';
						 }else{
							 echo '<option value="'.$m.'">'.$m.'</option>';
						 }								 
					 }
				?>
				</select>
			</td>
			<td><input type="submit" value="go"></td>
		</tr>
		<tr height="10"></tr>
	</table>
</form>
</h3>
<?php 
if($msg !=''){
	echo '<h2>'.$msg.'</h2>';
}
?>

		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Month</th>
            <th>Salary</th>
            <th>Paid</th>
            <th>Due</th>    
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
	while($row = mysqli_fetch_array($sql)){
		
		$sa = @mysqli_fetch_array(@mysqli_query($con,"SELECT * FROM `personinformation` WHERE 1 and `id` = '".$row['employee_id']."'"));
		
		$name = $sa['name'];
		$designation = $sa['designation'];
		$salary = $sa['salary'];
		$due = $salary - $row['amount'];
?>
    <tr>    		
       		<td><?= ucwords($name) ?></td>
            <td><?= ucwords($designation) ?></td>
            <td><?= ucwords($row['month']) ?></td>
            <td><?= ($salary) ?></td>
            <td><?= ($row['amount']) ?></td>
            <td><?= ($due) ?></td>
            <td>
            <a onclick="return checkproductdetails('<?= $row['employee_id'] ?>','<?= $row['month'] ?>');" href="javascript:;"id="modal-launcher" class="view">View</a></div>
            
            </td>
    </tr>
	<?php } ?>
    </tbody>
</table>
		</div>
		</div>
		</div>
		</div>

	<?php include("includes/footer.php");?>

