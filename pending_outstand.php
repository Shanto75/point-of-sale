<?php include("includes/header.php");?>
<script type="text/javascript">

</script>
<?php 
$msg='';
if(isset($_GET['id']) and $_GET['id']!=''){
	$id= $_GET['id'];
	@mysqli_query($con,"update transaction set approval='success', `date` = `check_date` where id='$id'");
	$msg = 'Data Updated successfully!';
}
?>
	<div class="area">
		<div class="panel-head"> Pending Outstanding Payment</div>
		<div class="panel">
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>		

<!--Edit-->
<?php 
	$sql = @mysqli_query($con,"SELECT * FROM `transaction` WHERE 1 and approval='pending'");
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Date</th>            
            <th>Customer Name</th>            
            <th>Payment Amount</th>
            <th>Bank Name</th>
            <th>Check Number</th>
            <th>Check Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
while($row = @mysqli_fetch_array($sql)){	
	$bank = mysqli_fetch_array(mysqli_query($con,"select * from bankinformation where id = '".$row['bank']."'"));

    $customerName = mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE type='customer' AND id='".$row['name']."'"));
?>
    <tr>
    		<td><?= daterev($row['date'],'/','-') ?></td>       		
    		<td><?= ucwords($customerName['name']) ?></td> 
       		<td><?= number_format((float)$row['pay_amount'], 2, '.', ''); ?> TK</td>
       		<td><?= ucwords($bank['bankname']) ?></td>
       		<td><?= ($row['check_number']) ?></td>
       		<td><?= daterev($row['check_date'],'/','-') ?></td>
       		<td><span style="color:red">Not Approve</span></td>
            <td>
				<a  href="pending_outstand.php?id=<?= $row['id']; ?>"id="modal-launcher" class="view">Approve</a>
            </td>
    </tr>
<?php } ?> 
    </tbody>
</table>
		</div>
		</div>
	<?php include("includes/footer.php");?>
