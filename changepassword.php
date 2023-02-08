<?php include("includes/header.php");?>
<style type="text/css">
	.select_style 
{
	background: #FFF;
	overflow: hidden;
	display: inline-block;
	color: #fff;
	font-size: 15px;
	-webkit-border-radius: 5px 4px 4px 5px/5px 5px 4px 4px;
	-moz-border-radius: 5px 4px 4px 5px/5px 5px 4px 4px;
	border-radius: 5px 4px 4px 5px/5px 5px 4px 4px;
	-webkit-box-shadow: 0 0 5px rgba(123, 123, 123, 0.2);
	-moz-box-shadow: 0 0 5px rgba(123,123,123,.2);
	box-shadow: 0 0 5px rgba(123, 123, 123, 0.2);
	border: solid 1px #ccc;
	font-family: "helvetica neue",arial;
	position: relative;
	top:7px; ;
	cursor: pointer;
	padding-right:20px;

}
.select_style span
{
	position: absolute;
	right: 10px;
	width: 8px;
	height: 8px;
	background: url(http://projects.authenticstyle.co.uk/niceselect/arrow.png) no-repeat;
	top: 50%;
	margin-top: -4px;
}
.select_style select
{
	-webkit-appearance: none;
	appearance:none;
	width:120%;
	background:none;
	background:transparent;
	border:none;
	outline:none;
	cursor:pointer;
	padding:7px 10px;
}
#dropdown 
{
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
</style>
	<script type="text/javascript">
	function chkpass(){
		frm = document.changepass;
		with(frm){
			if(pass.value=='' && cp.value==''){
				alert('password type correctly');
				return false;
			}
			if(pass.value!=cp.value){
				alert('Password Not match');
				return false;
			}
			return true;
		}
		
		
	}
	</script>

<?php
   $msg = '';
   $ms = '';
    if(isset($_POST['submit1'])){
      
      $password = mysqli_real_escape_string($con,md5($_POST['oldpass']));
      $confirmPassword = mysqli_real_escape_string($con,md5($_POST['pass']));
	  $userid = $_SESSION['id'];
	  $chk = @mysqli_fetch_array(mysqli_query($con,"select * from users where id = '$userid' and password = '$password'"));
	  if(!empty($chk)){
		  @mysqli_query($con,"update users set password = '$confirmPassword ' where id = '$userid'");
		  $msg = 'Password Change successfully!';		  
	  }else{
		  $ms = 'Password Not Match';
	  }
    }
    


   ?>


	<div class="area">
		<div class="panel-head">Personal Loan Payment</div>
		<div class="panel">
		<form action="" method="post" name="changepass">
				<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
				if($ms!=''){
		echo '<p style="color: red;
    border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$ms.'</p>';
		}
		?>
			<table id="personal_loan_payment">
				<tr>
					<td>Enter Old Password</td>
					<td><input name="oldpass" placeholder="Enter Old Password" type="password"></td>
				</tr>
				<tr>
					<td>Enter New Password</td>
					<td><input name="pass"  placeholder="Enter New Password" type="password"></td>
				</tr>
				<tr>
					<td>Confirm Password</td>
					<td><input name="cp" placeholder="Enter New Password" type="password"></td>
				</tr>
				<tr>
					<td></td>
					<td><input name="submit1" onclick="return chkpass();" value="Change" type="submit"></td>
				</tr>
			</table>
			</form>
		</div>
	</div>

	<?php include("includes/footer.php");?>
