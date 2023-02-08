<?php 
ob_start();
include('includes/config.php');
$target_dir = "csv/";
$file_name = 'bulk.csv';
$target_file = $target_dir.$file_name;
$d = move_uploaded_file($_FILES["csv"]["tmp_name"], $target_file);
if($d){
	
		if(file_exists('csv/bulk.csv')){
		$file = fopen("csv/bulk.csv","r");
$i = 0;
		while(! feof($file))
		  {
			
		  $data = fgetcsv($file);
		  if($i>0){
		if(strlen($data[0])>0){
		
		
		$category = $data[2];
		$catchek = mysqli_fetch_array(mysqli_query($con,"select * from category where category_name = '$category'"));
		if(empty($catchek)){
			mysqli_query($con,"insert into category (category_name) values ('$category')");
		}
		$brand = $data[7];
		$bndchek = mysqli_fetch_array(mysqli_query($con,"select * from brand where brand_name = '$brand'"));
		if(empty($bndchek)){
			mysqli_query($con,"insert into brand (brand_name) values ('$brand')");
		}
		$max = mysqli_fetch_array(mysqli_query($con,"SELECT max(id) as id FROM `product_details` WHERE 1 "));
		$max = $max['id'] + 1;
		$autoid = "SD" . $max . "";

		$sql = "INSERT INTO `product_details`(`name`, `product_code`, `category`, `purchase_cost`, `sale_price`, `wholesale_price`, `unit_type`, `brand`, `description`, `warranty`, `expire_date`, `comments`, `minquantity`,status,stock_id) VALUES ('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."','".$data[10]."','".$data[11]."','".$data[12]."','active','$autoid')";
		@mysqli_query($con,$sql);
		$max = mysqli_fetch_array(mysqli_query($con,"SELECT max(id) as id FROM `product_details` WHERE 1 "));
		
		$sql2 = "INSERT INTO `stock`(`product_name`, `quantity`, `category`, `unit`, `store_id`,`code`,`product_id`) VALUES ('".$data[0]."','".$data[13]."','".$data[2]."','".$data[6]."','1','".$data[1]."','".$max['id']."')";
		@mysqli_query($con,$sql2);
		  }
		  }
$i++;
		  }

		fclose($file);
		unlink('csv/bulk.csv');
		header('Location:product.php');
		}	
	
}else{
	echo 'file could not upload!';
}





?>