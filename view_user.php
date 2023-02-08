<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from users where 1 and id = '$id'"));
$data = '';
$access = array(
			'Stock' => 'stock.php',
			'Low Stock' => 'low.php',
			'Damage Stock' => 'damage.php',
			'Purchase' => 'purchase.php',
			'Purchase Return' => 'purchase_return.php',
			'Sale' => 'sale.php',
			'Sale Return' => 'sale_return.php',
			'Chalan' => 'invoice_free.php',
			'Earning Head' => 'earnhead.php',
			'Earnings' => 'earning.php',
			'Expense Head' => 'expensehead.php',
			'Expense' => 'expense.php',
			'Bank' => 'bank.php',
			'Investment' => 'investment.php',
			'Payment' => 'payment.php',
			'Outstanding' => 'outstanding.php',
			'Personal Loan' => 'personal_loan.php',
			'Bank Loan' => 'bank_loan.php',
			'Pay Personal Loan' => 'paypersonalloan.php',
			'Pay Bank Loan' => 'paybankloan.php',
			'Cash to Bank' => 'c2b.php',
			'Bank To Cash' => 'b2c.php',
			'Salary' => 'salary_payment.php',
			'Giftcard' => 'gift.php',
			'Purchase Report' => 'purchase_report.php',
			'Purchase Return Report' => 'report_purchase_return.php',
			'Sales Report' => 'report_sales.php',
			'Sales Return Report' => 'report_sales_return.php',
			'Receive Report' => 'report_receive.php',
			'Receive Due Report' => 'report_receive_due.php',
			'Payment Report' => 'report_payment.php',
			'Payment Due Report' => 'report_payment_due.php',
			'Customer Report' => 'report_customer.php',
			'Customer Ledger' => 'customer_ledger.php',
			'Supplier Ledger' => 'supplier_ledger.php',
			'Supplier Report' => 'report_supplier.php',
			'Employee Report' => 'report_employee.php',
			'Salary Report' => 'salary_report.php',
			'Expense Report' => 'report_expense.php',
			'Earning Report' => 'report_earn.php',
			'Low Stock Report' => 'low_stock_report.php',
			'Stock Report' => 'stock_report.php',
			'Profit/Loss Report' => 'profit_lose.php',
			'User Log Report' => 'report_user_log.php',
			'Shop' => 'shop.php',
			'Branches' => 'branches.php',
			'Customer' => 'customer.php',
			'Supplier' => 'supplier.php',
			'Investor' => 'investor.php',
			'Employee' => 'employee.php',
			'Product' => 'product.php',
			'Inactive Product' => 'inactive.php',
			'Category' => 'category.php',
			'Brand' => 'brand.php',
			'Unit' => 'unit.php',
			'Dynamic Field' => 'dynamic_field.php',
			'Vat' => 'vat.php',
			'Barcode Setting' => 'barcode_setting.php',
			'Users' => 'users.php',

			'Data Manage' => 'backup.php,online.php'
	);
if(!empty($row)){
	$data .= '<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Employee Name</td>
    	<td><input type="text" name="employee_name" id="employee_name" value="'.$row['employee_name'].'" required></td>
    	</tr>    	
    	<tr>
    	<td align="right">User Nane</td>
    	<td>
    		<input type="text" name="username" id="username" value="'.$row['username'].'" required>
    	</td>
    	</tr>
		<tr>
    	<td align="right">Access rights</td>
    	<td>
    		<table>';
		foreach($access as $key=>$val){
			if (preg_match("/".$val."/i", $row['access'])){
			$data .= '<tr><td><input type="checkbox" checked = "checked" name="access[]" value="'.$val.'" /> '.$key.' </td></tr>';
			}else{
			$data .= '<tr><td><input type="checkbox" name="access[]" value="'.$val.'" /> '.$key.' </td></tr>';
			}
		}	
		$data .='</table>
    	</td>
    	</tr>

    	<tr>
    	
    	<td colspan="2" align="right">
		<input type="hidden" name="id" value="'.$row['id'].'" />
		<input type="submit" name="submit" value="Edit"></td>
    	</tr>
    	</table>
    	</form>';
	echo $data;

}


?>