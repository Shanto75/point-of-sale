<?php include("includes/header.php");?>
<script type="text/javascript">
function checkproductdetails(val){
	
	$.post('check_vat.php', {productid: val},
	function (data) {
		
		$("#vat_name").val(data.vat_name);
		$("#amount").val(data.amount);		
		$("#comments").val(data.comments);
		$("#status").val(data.status);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#vat_name").val('');
		$("#amount").val('');		
		$("#comments").val('');
		$("#status").val('');
		$("#hidden_field").html('');

}
function branch_view(val){
	$('#branch_view tr').remove(); 
	$.post('view_user.php', {productid: val},
	function (data) {		
		$(data).appendTo('#branch_view');		
	});
	
	//$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}

$(function () {

	$("#employee_name").autocomplete("employee1.php", {
		width: 180,
		autoFill: true,
		selectFirst: true
	});
});

</script>
<?php 
$msg = '';
if(isset($_POST['submit'])){
if(isset($_POST['id'])){
	$id = $_POST['id'];
	$access = implode(',',$_POST['access']);
	$user = $_POST['username'];
	$employee = $_POST['employee_name'];
	$type = 'user';
	@mysqli_query($con,"UPDATE `users` SET `username`='$user',`usertype`='$type',`access`='$access',`employee_name`='$employee' WHERE 1 and id = '$id'");
	$msg = 'User update successfully!';
}else{
	$access = implode(',',$_POST['access']);
	$user = $_POST['username'];
	$employee = $_POST['employee_name'];
	$pass = $_POST['password'];
	$type = 'user';
	if($user!='' and $pass!=''){
		$pass = md5($_POST['password']);
		@mysqli_query($con,"INSERT INTO `users`(`username`, `password`, `usertype`,access,employee_name) VALUES ('$user','$pass','$type','$access','$employee')");
		$msg = 'User Created successfully';
	}else{
		$msg = 'You do not left empty all those field';
	}
}
}


if(isset($_GET['del']) and $_GET['id']!=''){
	$id = $_GET['id'];
	@mysqli_query($con,"delete from users where id = '$id'");
	$msg = 'Data deleted successfully!';
}
?>
	<div class="area">
		<div class="panel-head">Users Details</div>
		<div class="panel">
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add User</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content" style="left:30%;">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add User<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Employee Name</td>
    	<td><input type="text" name="employee_name" id="employee_name" placeholder="Enter employee name" required></td>
    	</tr>    	
    	<tr>
    	<td align="right">User Nane</td>
    	<td>
    		<input type="text" name="username" id="username" placeholder="Enter username" required>
    	</td>
    	</tr>
		<tr>
    	<td align="right">Password</td>
    	<td>
    		<input type="password" name="password" id="password" placeholder="Enter password" required>
    	</td>
    	</tr>
		<tr>
    	<td align="right" valign="top" style="padding-top:40px;">Access rights</td>
    	<td>
    		<table>
				<tr>
					<td><h2>Stock</h2>
						<table>
							<tr>
								<td><input type="checkbox" name="access[]" value="stock.php" />Stock</td>
								<td><input type="checkbox" name="access[]" value="low.php" />Low Stock</td>
								<td><input type="checkbox" name="access[]" value="damage.php" />Damage Stock</td>
							</tr>
						</table>
					</td>					
				</tr>
				<tr>
					<td><h2>Purchase</h2>
						<table>
							<tr>
								<td><input type="checkbox" name="access[]" value="purchase.php" />Purchase</td>
								<td><input type="checkbox" name="access[]" value="purchase_return.php" />Purchase Return</td>
								
							</tr>
						</table>
					</td>					
				</tr>
				<tr>
					<td><h2>Sales</h2>
						<table>
							<tr>
								<td><input type="checkbox" name="access[]" value="sale.php" />Sale</td>
								<td><input type="checkbox" name="access[]" value="sale_return.php" />Sale Return</td>
								<td><input type="checkbox" name="access[]" value="invoice_free.php" />Chalan</td>
								
							</tr>
						</table>
					</td>					
				</tr>
				<tr>
					<td><h2>Accounts</h2>
						<table>
							<tr>
								<td><input type="checkbox" name="access[]" value="earnhead.php" />Earning Head</td>
								<td><input type="checkbox" name="access[]" value="earning.php" />Earnings</td>
								<td><input type="checkbox" name="access[]" value="expensehead.php" />Expense Head</td>
								<td><input type="checkbox" name="access[]" value="expense.php" />Expense</td>
								
							</tr>
							<tr>
								<td><input type="checkbox" name="access[]" value="bank.php" />Bank</td>
								<td><input type="checkbox" name="access[]" value="investment.php" />Investment</td>
								<td><input type="checkbox" name="access[]" value="payment.php" />Payment</td>
								<td><input type="checkbox" name="access[]" value="outstanding.php" />Outstanding</td>
								
							</tr>
						</table>
					</td>					
				</tr>
				<tr>
					<td><h2>Loan</h2>
						<table>
							<tr>
								<td><input type="checkbox" name="access[]" value="personal_loan.php" />Personal Loan</td>
								<td><input type="checkbox" name="access[]" value="bank_loan.php" />Bank Loan</td>
								<td><input type="checkbox" name="access[]" value="paypersonalloan.php" />Pay Personal Loan</td>
								<td><input type="checkbox" name="access[]" value="paybankloan.php" />Pay Bank Loan</td>								
							</tr>
						</table>
					</td>					
				</tr>
				<tr>
					<td><h2>Cash Transfer</h2>
						<table>
							<tr>
								<td><input type="checkbox" name="access[]" value="c2b.php" />Cash to Bank</td>
								<td><input type="checkbox" name="access[]" value="b2c.php" />Bank To Cash</td>
							</tr>
						</table>
					</td>					
				</tr>
				<tr>
					<td><h2>Salary</h2>
						<table>
							<tr>
								<td><input type="checkbox" name="access[]" value="salary_payment.php" />Salary</td>
								
							</tr>
						</table>
					</td>					
				</tr>	
				<tr>
					<td><h2>Giftcard</h2>
						<table>
							<tr>
								<td><input type="checkbox" name="access[]" value="gift.php" />Giftcard</td>								
							</tr>
						</table>
					</td>					
				</tr>	
				<tr>
					<td><h2>Reports</h2>
						<table>
							<tr>
								<td><input type="checkbox" name="access[]" value="purchase_report.php" />Purchase Report</td>
								<td><input type="checkbox" name="access[]" value="report_purchase_return.php" />Purchase Return Report</td>
								<td><input type="checkbox" name="access[]" value="report_sales.php" />Sales Report</td>
								<td><input type="checkbox" name="access[]" value="report_sales_return.php" />Sales Return Report</td>
								
							</tr>
							<tr>
								<td><input type="checkbox" name="access[]" value="report_receive.php" />Receive Report</td>
								<td><input type="checkbox" name="access[]" value="report_receive_due.php" />Receive Due Report</td>
								<td><input type="checkbox" name="access[]" value="report_payment.php" />Payment Report</td>
								<td><input type="checkbox" name="access[]" value="report_payment_due.php" />Payment Due Report</td>
								
							</tr>
							<tr>
								<td><input type="checkbox" name="access[]" value="report_customer.php" />Customer Report</td>
								<td><input type="checkbox" name="access[]" value="report_supplier.php" />Supplier Report</td>
								<td><input type="checkbox" name="access[]" value="report_employee.php" />Employee Report</td>
								<td><input type="checkbox" name="access[]" value="salary_report.php" />Salary Report</td>
								
							</tr>
							<tr>
								<td><input type="checkbox" name="access[]" value="report_expense.php" />Expense Report</td>
								<td><input type="checkbox" name="access[]" value="report_earn.php" />Earning Report</td>
								<td><input type="checkbox" name="access[]" value="low_stock_report.php" />Low Stock Report</td>
								<td><input type="checkbox" name="access[]" value="stock_report.php" />Stock Report</td>
								
							</tr>
							<tr>
								<td><input type="checkbox" name="access[]" value="profit_lose.php" />Profit/Loss Report</td>
								<td><input type="checkbox" name="access[]" value="report_user_log.php" />User Log Report</td>
								<td><input type="checkbox" name="access[]" value="customer_ledger.php" />Customer Ledger Report</td>
								<td><input type="checkbox" name="access[]" value="supplier_ledger.php" />Supplier Ledger Report</td>
								
							</tr>
						</table>
					</td>					
				</tr>				
				<tr>
					<td><h2>Configuration</h2>
						<table>
							<tr>
								<td><input type="checkbox" name="access[]" value="shop.php" />Shop</td>
								<td><input type="checkbox" name="access[]" value="branches.php" />Branches</td>
								<td><input type="checkbox" name="access[]" value="customer.php" />Customer</td>
								<td><input type="checkbox" name="access[]" value="supplier.php" />Supplier</td>
								
							</tr>
							<tr>
								<td><input type="checkbox" name="access[]" value="investor.php" />Investor</td>
								<td><input type="checkbox" name="access[]" value="employee.php" />Employee</td>
								<td><input type="checkbox" name="access[]" value="product.php" />Product</td>
								<td><input type="checkbox" name="access[]" value="inactive.php" />Inactive Product</td>
								
							</tr>
							<tr>
								<td><input type="checkbox" name="access[]" value="category.php" />Category</td>
								<td><input type="checkbox" name="access[]" value="brand.php" />Brand</td>
								<td><input type="checkbox" name="access[]" value="unit.php," />Unit</td>
								<td><input type="checkbox" name="access[]" value="dynamic_field.php" />Dynamic Field</td>
								
							</tr>
							<tr>
								<td><input type="checkbox" name="access[]" value="vat.php" />Vat</td>
								<td><input type="checkbox" name="access[]" value="barcode_setting.php" />Barcode Setting</td>
								<td><input type="checkbox" name="access[]" value="users.php" />Users</td>
								<td><input type="checkbox" name="access[]" value="backup.php,online.php" />Data Manage</td>
								
							</tr>
						</table>
					</td>					
				</tr>
				
			</table>
    	</td>
    	</tr>

    	<tr>
    	
    	<td colspan="2" align="right">
		<div id="hidden_field"></div>
		<input type="submit" name="submit" value="Go"></td>
    	</tr>
    	</table>
    	</form>
    </div>
</div>
<!--View-->
<div id="modal2-background"></div>
<div id="modal2-content">
    <button id="modal2-close">Close Modal Window</button>
    <div class="modal-form">
    	View/Edit User Details<hr/>
		<div id="branch_view"></div>
    	
    </div>
</div>
<!--Edit-->
<?php 
	$sql = @mysqli_query($con,"SELECT * FROM `users` WHERE 1 and usertype='user'");
?>
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>User Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php 
while($row = @mysqli_fetch_array($sql)){
	

?>
    <tr>
    		<td><?= ucwords($row['employee_name']) ?></td>
       		<td><?= $row['username'] ?></td>
       		

            <td>
            
            <a onclick="return branch_view('<?= $row['id']; ?>');" href="javascript:;"id="modal2-launcher" class="edit">Edit</a></div>
            <a onclick="return confirm('Are you sure?')" href="users.php?id=<?= $row['id'] ?>&del=del" id="" class="delete">Delete</a></div>
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
