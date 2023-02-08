<?php
include("includes/header.php");
if(isset($_POST['submit'])){
	if(file_exists('backup/dump.sql')){
		unlink('backup/dump.sql');		
	}
	
	if(basename($_FILES["backup"]["name"]!='')){
		$target_dir = "backup/";
		$target_path = $target_dir."dump.sql";
		move_uploaded_file($_FILES["backup"]["tmp_name"], $target_path) or die('not uploaded');
	}
	@mysqli_query($con,"DROP TABLE `bankinformation`, `barcode_setup`, `branch`, `brand`, `category`, `custom_field`, `damage_stock`, `earning`, `earning_head`, `expense`, `expense_head`, `gift`, `investment`, `loan`, `login_details`, `personinformation`, `product_details`, `purchase`, `salary`, `stock`, `storeconfiguration`, `storeinformation`, `store_login`, `transaction`, `transfer`, `unit`, `users`, `vat`, `wonerinformation`;");
	if(file_exists('backup/dump.sql')){
	
		$filename = 'backup/dump.sql';
		$templine = '';
		$lines = file($filename);
		foreach ($lines as $line)
		{
			if (substr($line, 0, 2) == '--' || $line == '')
				continue;
			$templine .= $line;
			if (substr(trim($line), -1, 1) == ';')
			{    
				@mysqli_query($con,$templine);  
				$templine = '';
			}
		}
		 echo "Tables imported successfully";	
	}else{
		echo 'You have no file';
	}
}
?>
	<div class="area">
		<div class="panel-head">Get Backup</div>
		<div class="panel"><h3>Upload Your Backup</h3>
		<div class="rl">
		<form action="" method="post" enctype="multipart/form-data">
			Upload : <input type="file" name="backup"/>
			<input type="submit" name="submit" value="Submit"/>
		</form>
		</div>
		</div>
		</div>
<?php 
	require('includes/footer.php');
?>