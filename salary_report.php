<?php
include("includes/header.php");

$msg = '';
$group = '';
	$whereclass = '';
	if(isset($_GET['employee_id']) and strlen($_GET['employee_id'])>0){
		$whereclass .="and employee_id = '".$_GET['employee_id']."'";
		$group = "group by `month`";
	}
	if(isset($_GET['salary_monty'])){
		if(strlen($_GET['salary_monty'])>0){
			$whereclass .=" and month = '".$_GET['salary_monty']."'";
			$group = "group by `employee_id`";
		}else{
			$whereclass .="";
		}		
	}else{
		$ads = date('m');
		$math = date('F', mktime(0,0,0,$ads, 1, date('Y')));
		$whereclass .=" and month = '".$math."'";
		$group = "group by `employee_id`";
	}
	if(isset($_GET['year']) and strlen($_GET['year'])>0){
		$whereclass .=" and year = '".$_GET['year']."'";
		$group = "group by `employee_id`";
	}
	
	$sql = mysqli_query($con,"SELECT `id`, `employee_id`, sum(`amount`)as amount, month FROM `salary` WHERE 1  $whereclass $group");

?>

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
<div class="area">
		<div class="panel-head">Salary Reports</div>
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
			<td width="20%">Select Employee</td>
				<td width="20%">
				<select name="employee_id" style="width:180px" >
					<option value="">Select employee</option>
				<?php 
					$sql2 = mysqli_query($con,"select * from personinformation where type='employee'");
					while($row = mysqli_fetch_array($sql2)){
						if(isset($_GET['employee_id']) and strlen($_GET['employee_id'])>0 and $_GET['employee_id']==$row['id']){
							echo '<option value="'.$row['id'].'" selected="selected">'.$row['name'].' ('.$row['designation'].')'.'</option>';
						}else{
							echo '<option value="'.$row['id'].'">'.$row['name'].' ('.$row['designation'].')'.'</option>';
						}
						
					}
				?>
				</select>
			</td>
			<td width="20%">Salary Month</td>
			<td width="20%">
				<select name="salary_monty" style="width:180px">
					<option value="">Please select</option>
				<?php
					for ($m=1; $m<=12; $m++) {
					 $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
						 $d = date('m');
						 $match = date('F', mktime(0,0,0,$d, 1, date('Y')));
						 if(isset($_GET['salary_monty']) and strlen($_GET['salary_monty'])>0 and $_GET['salary_monty']==$month){
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
<div class="table_data">
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
</body>
</html>
<?php 
	require('includes/footer.php');
?>