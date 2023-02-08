<?php include("includes/header.php");?>

<script type="text/javascript">
	
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
if(isset($_POST['submit'])){
	$employee_id = $_POST['employee_id'];
	$year = $_POST['year'];
	$sql = mysqli_query($con,"SELECT `id`, `employee_id`, sum(`amount`)as amount, month FROM `salary` WHERE 1 and employee_id = '$employee_id' and year = '$year' group by `month`");	
}
?>
	<div class="area">
		<div class="panel-head">Employee Salary Report</div>
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
<form id="myform" name="myform" action="" method="post">
	<table>
		<tr>
			<td width="20%">Select Employee</td>
			<td width="20%">
				<select name="employee_id" style="width:180px" required>
					<option value="">Select employee</option>
				<?php 
					$sql2 = mysqli_query($con,"select * from personinformation where type='employee'");
					while($row = mysqli_fetch_array($sql2)){
						echo '<option value="'.$row['id'].'">'.$row['name'].' ('.$row['designation'].')'.'</option>';
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
			<td><input type="submit" name="submit" value="go"></td>
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
<?php 
if(isset($_POST['submit'])){
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
<?php } ?>
		</div>
		</div>
		</div>
		</div>

	<?php include("includes/footer.php");?>

