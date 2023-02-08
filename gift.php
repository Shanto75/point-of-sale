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
	
	$.post('check_gift_details.php', {productid: val},
	function (data) {
		
		$("#customer_name").val(data.customer_name);
		$("#card_number").val(data.card_number);
		
		$("#amount").val(data.amount);
		$("#comments").val(data.comments);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#customer_name").val('');
		$("#card_number").val('');
		$("#amount").val('');
		$("#comments").val('');
		$("#hidden_field").html('');

}
</script>
<script type="text/javascript">
        $(function () {

            $("#customer_name").autocomplete("customer1.php", {
                width: 160,
                autoFill: true,
                selectFirst: true
            });
	})
function view_earning(val){
	$('#datatable tr').remove(); 
	$.post('view_gift_details.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>
<?php 
$msg = '';
if(isset($_POST['customer_name']) and isset($_POST['card_number']) and isset($_POST['amount'])){
	$customer_name = $_POST['customer_name'];
	$card_number = $_POST['card_number'];
	$amount = $_POST['amount'];
	$comments = $_POST['comments'];
	if(!isset($_POST['id'])){
		mysqli_query($con,"INSERT INTO `gift`(`customer_name`, `card_number`, `amount`, `comments`) VALUES ('$customer_name','$card_number','$amount','$comments')");
		$msg = 'Data Inserted Successfully!';
	}else{
		$id = $_POST['id'];
		mysqli_query($con,"UPDATE `gift` SET `customer_name`='$customer_name',`card_number`='$card_number',`amount`='$amount',`comments`='$comments' WHERE 1 and id = '$id'");
		$msg = 'Data updated successfully!';
	}
}

if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from gift where id = '$id'");
	$msg = 'Data deleted successfully!';
}
?>


	<div class="area">
		<div class="panel-head">Gift Card Details</div>
		<div class="panel">
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>			
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Gift Card</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Gift Card<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Customer Name</td>
    	<td><input type="text" name="customer_name" id="customer_name" ></td>
    	</tr>
        <tr>
        <td align="right">Card Number</td>
        <td><input type="text" name="card_number" id="card_number" ></td>
        </tr>
        <tr>
        <td align="right">Amount </td>
        <td><input type="text" name="amount" id="amount"></td>
        </tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right"> Comments</td>
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
    	View Gift Card Details<hr/>
    	
    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<!--View-->
<?php 
$sql = @mysqli_query($con,"select * from gift where 1");
?>

		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Card Number</th>
            <th>Amount</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
		<?php 
	while($row = @mysqli_fetch_array($sql)){
		?>	
    <tr>
    		
       		<td><?= ucwords($row['customer_name']) ?></td>
            <td><?= $row['card_number'] ?></td>
            <td><?= $row['amount'] ?></td>
            
            <td>
           <a onclick="return view_earning('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a></div>
            <a onclick="return checkproductdetails('<?= $row['id']; ?>');" href="javascript:;"id="modal-launcher" class="edit">Edit</a></div>
            <a href="gift.php?id=<?= $row['id'] ?>&del=del"id="" class="delete">Delete</a></div>
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
