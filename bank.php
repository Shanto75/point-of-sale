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
	$.post('check_bank_details.php', {productid: val},
	function (data) {		
		$("#person").val(data.person);
		$("#person_type").val(data.person_type);
		$("#bankname").val(data.bankname);
		$('#accountname').val(data.accountname);
		$('#accountnumber').val(data.accountnumber);
		$('#opening_balance').val(data.openingBlnc);
		$('#comments').val(data.comments);
		
	}, 'json');

	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#person").val('');
		$("#person_type").val('');
		$("#bankname").val('');
		$('#accountname').val('');
		$('#accountnumber').val('');
		$('#comments').val('');
}
function view_earning(val){
	$('#datatable tr').remove(); 
	$.post('view_bank_details.php', {productid: val},
	function (data) {		
		$(data).appendTo('#datatable');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
</script>
<?php 
if (isset($_POST['person'])) {


	$person = $_POST['person'];
	$person_type = $_POST['person_type'];
	$bankname = $_POST['bankname'];
	$accountname = $_POST['accountname'];
	$accountnumber = $_POST['accountnumber'];
	$comments = $_POST['comments'];
	$store_id = $_SESSION['store_id'];
	$openingbalance = $_POST['opening_balance'];

	if(isset($_POST['id'])){
		$id = $_POST['id'];

		mysqli_query($con,"UPDATE `bankinformation` SET `person`='$person',`person_type`='$person_type',`bankname`='$bankname',`accountname`='$accountname',`accountnumber`='$accountnumber',`comments`='$comments' WHERE 1 and id = '$id'");

				if($_POST['opening_balance']!=''){
					$pdata = mysqli_fetch_array(mysqli_query($con, "SELECT personid FROM openingbalance WHERE personid='$id'"));
					if(!empty($pdata)){
						@mysqli_query($con, "UPDATE openingbalance SET amount='$openingbalance' WHERE type='bank' AND personid='$id'");
					}else{
						 @mysqli_query($con, "INSERT INTO `openingbalance`(`personid`, `type`, `amount`,`date`)VALUES('$id','bank','$openingbalance','$date')");
					}

				}
		$msg = 'Bank information Update successfully!';
	}else{
		@mysqli_query($con,"INSERT INTO `bankinformation`(`person`, `person_type`, `bankname`, `accountname`, `accountnumber`, `comments`, `store_id`) VALUES ('$person','$person_type','$bankname','$accountname','$accountnumber','$comments','$store_id')") or die();
		if($person_type == 'owner'){

			$bank_person= mysqli_fetch_array(mysqli_query($con, "SELECT id FROM bankinformation WHERE person='$person' AND accountnumber='$accountnumber'"));
		     $personId = $bank_person['id'];

			@mysqli_query($con, "INSERT INTO `openingbalance`(`personid`, `type`, `amount`,`date`)VALUES('$personId','bank','$openingbalance','$date')");
		}
		$msg = 'Bank information inserted successfully!';
	}
}
if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from bankinformation where id = '$id'");
	$msg = 'Data deleted successfully!';
}
?>
	<div class="area">
		<div class="panel-head">Bank Details</div>
		<div class="panel">
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Bank Info</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Bank Info<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Person Name</td>
    	<td><input type="text" class="form-control" id="person" name="person" placeholder="Person Name" required></td>
    	</tr>
		<tr>
        <td align="right">Person Type</td>
        <td>
            <select class="form-control" id="person_type" name="person_type">
			<option value="">Please select one</option>
			<option value="owner">owner</option>
			<option value="supplier">supplier</option>
			<option value="customer">customer</option>
			<option value="investor">investor</option>
			<option value="employee">employee</option>
		  </select>
        </td>
        </tr>
        <tr>
        <td align="right">Bank Name</td>
        <td><input type="text" class="form-control" id="bankname" name="bankname" placeholder=""></td>
        </tr>
        <tr>
        <td align="right">Account Name</td>
        <td><input type="text" class="form-control" id="accountname" name="accountname" placeholder=""></td>
        </tr>
        <tr>
        <td align="right">Account Number</td>
        <td><input type="text" class="form-control" id="accountnumber" name="accountnumber" placeholder="" required></td>
        </tr>
		<tr>
    	<td align="right">Bank Opening  Balance</td>
    	<td><input type="text" id="opening_balance" name="opening_balance"></td>
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
    	View Bank Details<hr/>
    	
    	<table id="datatable" class="tab">

    	</table>
    	
    </div>
</div>
<!--View-->


		<table id="table_id" class="display">
    <thead>
    <tr>
        <th>Person Name</th>
        <th>Person Type</th>
        <th>Bank Name</th>
        <th>Account Name</th>
        <th>Account Number</th>
        <th>Comments</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
	<?php 
	$sql = mysqli_query($con,"select * from bankinformation where 1");
	while($row = mysqli_fetch_array($sql)){
		?>
		    <tr>
        <td><?= ucwords($row['person']); ?></td>
        <td class="center"><?= $row['person_type'] ?></td>
        <td class="center"><?= $row['bankname'] ?></td>
        <td class="center"><?= $row['accountname'] ?></td>
        <td class="center"><?= $row['accountnumber'] ?></td>
        <td class="center"><?= $row['comments'] ?></td>
        <td class="center">
		<a onclick="return view_earning('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="view">View</a></div>		
            <a onclick="return checkproductdetails('<?= $row['id'] ?>');" href="javascript:;"id="modal-launcher" class="edit">
                
                Edit
            </a>
            <a class="delete" onclick="return confirm('Are you sure?')" href="bank.php?id=<?= $row['id'] ?>&del=del">
                
                Delete
            </a>
        </td>
    </tr>
	<?php 
	}
	?>
     
    </tbody>
</table>
		</div>
		</div>
		</div>
		</div>

	<?php include("includes/footer.php");?>
