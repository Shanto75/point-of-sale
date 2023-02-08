<?php include("includes/header.php");?>

<?php 
$shop = mysqli_fetch_array(mysqli_query($con,"select * from storeinformation"));


if(isset($_POST['submit'])){
	$shopname = $_POST['shopname'];
	$email = $_POST['email']==''?'no@demo.com':$_POST['email'];
	$phone = $_POST['phone'];
	$subject = $_POST['subject'];
	$body = $_POST['body'];
	$body = $body.'<br/> Shop Name : '.$shopname.'<br/> Customer Email : '.$email.'<br /> Phone : '.$phone;
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'To: CEO <ceo@isovix.com>' . "\r\n";
	$headers .= 'From: '.$email.' <'.$email.'>' . "\r\n";
	

	$d = mail($to, $subject, $message, $headers);
	if($d){
		$msg = 'An Email has been send!';
	}else{
		$msg = 'Opps.. something went worng.';
	}
}
?>


	<div class="area">
	<div class="panel-head">Contact Us</div>
	<div class="panel">
	<?php 
	if($msg!=''){
		echo '<h2>'.$msg.'</h2>';
	}
	?>
	<form action="" method="post">
		<table>
			<tr>
				<td>Shop Name</td>
				<td><input type="text" value="<?= $shop['name'] ?>" name="shopname" required/></td>
			</tr>
			<tr>
				<td>Email</td>
				<td><input type="text" value="<?= $shop['email'] ?>" name="email"/></td>
			</tr>
			<tr>
				<td>Phone Number</td>
				<td><input type="text" value="<?= $shop['phone'] ?>" name="phone"/></td>
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
				<td><input type="submit" name="submit" value="send" /></td>
			</tr>
		</table>
	</form>

	</div>
	</div>


	<?php include("includes/footer.php");?>

