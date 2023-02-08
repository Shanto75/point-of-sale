<?php include("includes/header.php");?>

<?php 
$shop = mysqli_fetch_array(mysqli_query($con,"select * from storeinformation"));


if(isset($_POST['submit'])){
	$type = $_POST['type'];
	$email = $_POST['email']==''?'no@demo.com':$_POST['email'];
	
	$subject = $_POST['subject'];
	$body = $_POST['body'];
	$sql = mysqli_query($con,"select * from personinformation where type = '$type'");
	$i =0;
	$e = 0;
	while($row = mysqli_fetch_array($sql)){
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		// Additional headers
		$headers .= 'To: CEO <ceo@isovix.com>' . "\r\n";
		$headers .= 'From: '.$email.' <'.$email.'>' . "\r\n";
		$d = mail($to, $subject, $message, $headers);
		if($d){
			$i++;
		}else{
			$e++;
		}		
	}
	$msg = 'Total email send '.$i.' Failed '.$e;
	
}
?>


	<div class="area">
	<div class="panel-head">Bulk Email</div>
	<div class="panel">
	<?php 
	if($msg!=''){
		echo '<h2>'.$msg.'</h2>';
	}
	?>
	<form action="" method="post">
		<table>
			<tr>
				<td>Email to</td>
				<td>
					<select name="type">
						<option value="supplier">Supplier</option>
						<option value="customer">Customer</option>
						<option value="investor">Investor</option>
						<option value="employee">Employee</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>From Address</td>
				<td><input type="text" value="<?= $shop['email'] ?>" name="email" required/></td>
			</tr>

			<tr>
				<td>Subject</td>
				<td><input type="text" value="" name="subject" required/></td>
			</tr>
			<tr>
				<td>Body</td>
				<td><textarea name="body" rows="5" cols="50" required></textarea></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="send" /></td>
			</tr>
		</table>
	</form>

	</div>
	</div>


	<?php include("includes/footer.php");?>

