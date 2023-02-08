<?php include("includes/header.php");?>
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
	
	$.post('check_investment_details.php', {productid: val},
	function (data) {
		
		$("#date").val(data.date);
		$("#investor").val(data.investor);
		$("#amount").val(data.amount);
		$('#comments').val(data.comments);


	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		
		$("#investor").val('');
		$("#amount").val('');
		$('#comments').val('');
}
	$(function () {
		$("#investor").autocomplete("investor_search.php", {
			width: 160,
			autoFill: true,
			selectFirst: true
		});
	});
function view_earning(val){
	$('#datatable tr').remove(); 
	$.post('view_investment_details.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>
<?php 
$msg ='';
if (isset($_POST['investor'])) {

	$investor = $_POST['investor'];
	$date = modifydate($_POST['date'],'-','/');
	$amount = $_POST['amount'];
	$comments = $_POST['comments'];
	$store_id = $_SESSION['store_id'];
	if(isset($_POST['id'])){
		$id = $_POST['id'];

		mysqli_query($con,"UPDATE `investment` SET `date`='$date',`investor`='$investor',`amount`='$amount',`comments`='$comments' WHERE 1 and id = '$id'");
		$msg = 'Investment Update successfully!';
	}else{
		@mysqli_query($con,"INSERT INTO `investment`(`date`, `investor`, `amount`, `comments`, `store_id`) VALUES ('$date','$investor','$amount','$comments','$store_id')") or die();
		$msg = 'Investment inserted successfully!';
	}
}


if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from investment where id = '$id'");
	$msg = 'Data deleted successfully!';
}

?>
	<div class="area">
		<div class="panel-head">Investment Details</div>
		<div class="panel">
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
		<div class="btn">	
		<a href="javascript:;"id="modal-launcher" class="btn-add">+ Add Investment</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Investment<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
        <tr>
        <td align="right">Date</td>
        <td><input type="text" class="form-control datepicker" id="date" name="date" value="<?= date('d/m/Y') ?>" required></td>
        </tr>
    	<tr>
        <td align="right">Investor</td>
        <td>
            <input type="text" class="form-control" id="investor" name="investor">
        </td>
        </tr>
       
        <tr>
        <td align="right"> Amount</td>
        <td><input type="text" class="form-control" id="amount" name="amount" placeholder="Enter Amount"></td>
        </tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Comments</td>
    	<td><textarea id="comments" class="form-control" name="comments"></textarea></td>
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
    	View Cash to Bank Details<hr/>
    	
    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<!--View-->

		<table id="table_id" class="display">
   <thead>
    <tr>
        <th>Date</th>
        <th>Investor</th>
        <th>Amount</th>
        <th>Comments</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
	<?php 
	$sql = mysqli_query($con,"select * from investment where 1");
	while($row = mysqli_fetch_array($sql)){
		?>	
    <tr>
		<td class="center"><?= daterev($row['date'],'/','-') ?></td>
        <td><?= ucwords($row['investor']); ?></td>
        
        <td class="center"><?= $row['amount'] ?></td>
        <td class="center"><?= $row['comments'] ?></td>

            
            <td>
           <a onclick="return view_earning('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a></div>	
            <a onclick="return checkproductdetails('<?= $row['id']; ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a></div>
            <a onclick="return confirm('Are you sure?')" href="investment.php?id=<?= $row['id'] ?>&del=del" id="" class="delete">Delete</a></div>
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
