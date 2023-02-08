<?php include("includes/header.php");?>
<script type="text/javascript">
        $(function () {
            $("#head").autocomplete("ajax_expense.php", {
                width: 160,
                autoFill: true,
                
                selectFirst: true
            });
        });

    </script>
	<script type="text/javascript">
function setSelectedValue(selectObj, valueToSet) {
    for (var i = 0; i < selectObj.options.length; i++) {
        if (selectObj.options[i].text== valueToSet) {
            selectObj.options[i].selected = true;
            return;
        }
    }
}
function checkproductdetails(val){
	
	$.post('check_expense_details.php', {productid: val},
	function (data) {
		
		$("#date").val(data.date);
		$("#head").val(data.head);
		$("#amount").val(data.amount);
		$("#comments").val(data.comments);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		
		$("#head").val('');
		$("#amount").val('');
		
		$("#comments").val('');
		$("#hidden_field").html('');

}
function view_earning(val){
	$('#datatable tr').remove(); 
	$.post('view_expense_details.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>
<?php 
if(isset($_POST['head']) and isset($_POST['amount'])){
	$head = $_POST['head'];
	$date = modifydate($_POST['date'],'-','/');
	$amount = $_POST['amount'];
	$comments = $_POST['comments'];
	if(!isset($_POST['id'])){
		mysqli_query($con,"INSERT INTO `expense`(`date`, `earning_head`, `amount`, `store_id`,comments) VALUES ('$date','$head','$amount','1','$comments')");
		echo 'Data inserted Successfully!';
	}else{
		$id = $_POST['id'];
		mysqli_query($con,"UPDATE `expense` SET `date`='$date',`earning_head`='$head',`amount`='$amount',comments = '$comments' WHERE 1 and id = '$id'");
		echo 'Data Updated Successfully!';
	}
}
if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from expense where id = '$id'");
	echo 'Data deleted successfully!';
}
?>
	<div class="area">
		<div class="panel-head">Expense Details</div>
		<div class="panel">
		<div class="btn">	
		<a href="javascript:;"id="modal-launcher" class="btn-add">+ Add Expense</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Expense<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
        <tr>
        <td align="right">Date</td>
        <td><input type="text" name="date" class="datepick" id="date" value="<?= date('d/m/Y') ?>"></td>
        </tr>
    	<tr>
    	<td align="right">Expense Head Name</td>
    	<td><input type="text" name="head" id="head"></td>
    	</tr>
        <tr>
        <td align="right">Expense Amount</td>
        <td><input type="text" name="amount" id="amount"></td>
        </tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Comments</td>
    	<td><textarea placeholder="Enter Comments" name="comments" id="comments" rows="5" cols="20"></textarea></td>
    	</tr>
    	<tr>
    	
    	<td colspan="2" align="right">
		<div id="hidden_field"></div>
		<input type="submit" value="Go"></td>
    	</tr>
    	</table>
    	</form>
    </div>
</div>
<div id="modal2-background"></div>
<div id="modal2-content">
    <button id="modal2-close">Close Modal Window</button>
    <div class="modal2-form">
    	View Earning<hr/>
    	
    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<!--View-->
<?php 
$sql = @mysqli_query($con,"select * from expense where 1");
?>

		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Date</th>
            <th>Expense Head Name</th>
            <th>Expense Amount</th>
            <th>Comments</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
		<?php 
	while($row = @mysqli_fetch_array($sql)){
		?>
    <tr>
    		
       		<td><?= daterev($row['date'],'/','-') ?></td>
            <td><?= ucwords($row['earning_head']) ?></td>
            <td><?= ($row['amount']) ?></td>
            <td><?= ($row['comments']) ?></td>
            
            <td>  
			<a onclick="return view_earning('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a></div>	
            <a onclick="return checkproductdetails('<?= $row['id']; ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a></div>
            <a onclick="return confirm('Are you sure?')" href="expense.php?id=<?= $row['id'] ?>&del=del" id="" class="delete">Delete</a></div>
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
