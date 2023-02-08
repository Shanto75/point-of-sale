<?php include("includes/header.php");?>
<?php 
$msg = '';
$store_id = $_SESSION['store_id'];
if(isset($_POST['submit'])){
	$store_name = $_POST['sname'];
	$store_address = $_POST['saddress'];
	$store_website = $_POST['swebsite'];
	$store_phone = $_POST['sphone'];
	$store_email = $_POST['semail'];
	$woner_name = $_POST['wname'];
	$woner_address = $_POST['waddress'];
	$woner_phone = $_POST['wphone'];
	
	$wchk = mysqli_fetch_array(mysqli_query($con,"select * from wonerinformation where id = '$store_id'"));
	if(empty($wchk)){
		@mysqli_query($con,"INSERT INTO `wonerinformation`(`name`, `address`, `phone`, `store_id`) VALUES ('$woner_name','$woner_address','$woner_phone','$store_id')");
	}else{
		@mysqli_query($con,"UPDATE `wonerinformation` SET `name`='$woner_name',`address`='$woner_address',`phone`='$woner_phone' WHERE 1 and id = '1'");
	}
	$schk = mysqli_fetch_array(mysqli_query($con,"select * from storeinformation where id = '$store_id'"));
	if(empty($schk)){
		@mysqli_query($con,"INSERT INTO `storeinformation`(`name`,`website`, `email`, `phone`, `woner_id`, `address`) VALUES ('$store_name','$store_website','$store_email','$store_phone','1','$store_address')");
	}else{
		@mysqli_query($con,"UPDATE `storeinformation` SET `name`='$store_name',`website`='$store_website',`email`='$store_email',`phone`='$store_phone',`address`='$store_address' WHERE 1 and id  = '$store_id'");
	}
	$msg = 'Information Update Successfully!';
	
}
if(isset($_POST['imageupload'])){
	$target_dir = "images/";
	$target_file = $target_dir . basename($_FILES["file"]["name"]);
	$file_name = basename($_FILES["file"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);


		$check = getimagesize($_FILES["file"]["tmp_name"]);
		if($check !== false) {
			//echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			$msg = "File is not an image.";
			$uploadOk = 0;
		}

	// Check file size
	if ($_FILES["file"]["size"] > 500000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	if (file_exists($target_file)) {
		unlink($target_file);
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	   move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
	}
	$schk = mysqli_fetch_array(mysqli_query($con,"select * from storeinformation where id = '$store_id'"));
	if($schk['logo']!=''){
		if(file_exists('images/'.$schk['logo'])){
			unlink('images/'.$schk['logo']);
		}
	}
	@mysqli_query($con,"update storeinformation set logo = '$file_name' where id = '$store_id'");
	$msg = 'Logo is Updated!';
}
?>
	<div class="area">
		<div class="panel-head">Store Setup</div>
		<div class="panel">
            <!-- content starts -->
		<?php 
		if($msg!=''){
		echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
		}
		?>
<?php 

$store = @mysqli_fetch_array(mysqli_query($con,"select * from storeinformation where id = '$store_id'"));
$woner = @mysqli_fetch_array(mysqli_query($con,"select * from wonerinformation where id = '".$store['woner_id']."'"));

?>
	<div class="alert alert-info"><h3>Store Setup</h3></div>
    <form action="" method="POST" id="login-form" style="height: 287px;" class="cmxform" autocomplete="off">

        <table>
            <tr>
                <td>
                        <label >Store Name</label>                        
                </td>
				<td><input type="text" name="sname" id="name" class="form-control"
                               value="<?= $store['name'] ?>" autofocus required/>
				</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>                    
                        <label> Store Address</label>                        
                </td>
				<td>
					<input type="text" name="saddress" id="address" class="form-control"
                               value="<?= $store['address'] ?>" autofocus required/>
				</td>
            </tr>
			<tr></tr>
            <tr>
                <td>                    
                        <label>Store Website</label>
                        
                </td>
				<td><input type="text" name="swebsite" id="website" class="form-control"
                               value="<?= $store['website'] ?>" autofocus/></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>
                    
                        <label>Store Phone</label>
                        
                </td>
				<td><input type="text" name="sphone" id="phone" class="form-control"
                               value="<?= $store['phone'] ?>" autofocus required/></td>
            </tr>
			<tr></tr>
            <tr>
                <td>                    
                        <label>Store Email</label>
                        
                </td>
				<td><input type="email" name="semail" id="" class="form-control"
                               value="<?= $store['email'] ?>" autofocus /></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>
                    
                        <label>Store Owner Name</label>
                        
                </td>
				<td><input type="text" name="wname" id="phone" class="form-control"
                               value="<?= $woner['name'] ?>" autofocus required/></td>
				
            </tr>
			<tr></tr>
            <tr>
                <td>                    
                        <label>Store Owner Address</label>
                        
                </td>
				<td><input type="text" name="waddress" id="website" class="form-control"
                               value="<?= $woner['address'] ?>" autofocus/></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>
                   
                        <label>Store Owner Phone</label>
                        
                </td>
				<td><input type="text" name="wphone" id="phone" class="form-control"
                               value="<?= $woner['phone'] ?>" autofocus required/></td>
            </tr>			
            <tr></tr>
            <tr>
                <td>


                    <!--<a href="dashboard.php" class="button round blue image-right ic-right-arrow">LOG IN</a>-->
                    <input type="submit" class="button round blue image-right ic-right-arrow" name="submit"
                           value="Update"/>
                </td>
                <td><a href="index.php" class="button blue round side-content">Dashboard</a></td>
            </tr>
        </table>

    </form>
    <div style="float: right;margin-top: -341px;">
        <form action="" method="POST" id="login-form" class="cmxform" enctype="multipart/form-data">
			<p>Current Logo</p>
			<img src="images/<?= $store['logo'] ?>" width="100" height="100" />
            <p>Upload Logo</p>
            <input type="file" name="file" id="file"><br><br>
            <input type="submit" name="imageupload" value="Submit" class="button round blue image-right ic-right-arrow">
        </form>
    </div>	
</div>
</div>

    <hr>

	<?php include("includes/footer.php");?>
