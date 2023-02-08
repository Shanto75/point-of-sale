<?php 
ob_start();
session_start();
$msg = '';
if(!isset($_SESSION['id']) and $_SESSION['id']==''){
header("Location: login.php");
exit();
}
?>
<style type="text/css">
body{width:100%}
.barcode{width:24%;height: auto;float:left;margin-right:10px;text-align:center;padding: 20px 0px;}
.barcode p{font-size: 12px;
margin: 2px;}
</style>
<?php
include('includes/config.php');
include('includes/function.php');
if(isset($_POST['submit'])){
include('barcode/src/BarcodeGenerator.php');
include('barcode/src/BarcodeGeneratorPNG.php');
include('barcode/src/BarcodeGeneratorSVG.php');
include('barcode/src/BarcodeGeneratorHTML.php');
$item_number = $_POST['item_id'];
$amount = $_POST['amount'];
$itemsearch = mysqli_fetch_array(mysqli_query($con,"select * from `product_details` where id = '$item_number'"));
$name = $itemsearch['name'];
$item_id = $itemsearch['product_code'];
$price = $itemsearch['sale_price'];
$generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
$generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML();
$data = '';
	for($i=0;$i<$amount;$i++){
	$data .= '<div class="barcode" style="">';
	$data .= '<p>Name -'.$name.'</p>';
	$data .= '<img src="data:image/png;base64,' . base64_encode($generatorPNG->getBarcode($item_number, $generatorPNG::TYPE_CODE_128)) . '">';
	$data .= '<p>'.$item_id.'</p>';
	$data .= '<p>Price -'.$price.'</p>';
	$data .= '</div>';
	}
echo $data;exit;	
}
?>
<html>
	<head>
		<title>Multiple Barcode Genarate</title>
	</head>
	<body>
		<form action="" method="post">
			Product Name : <select name="item_id">
						<option value="">Please select One</option>
				<?php 
				
					$query = mysqli_query($con,"SELECT * FROM `product_details` where 1");
					while($row = mysqli_fetch_array($query)){
						echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
					}
				?>
			</select>
			Barcode Amount : <input type="text" name="amount"/>
			<input type="submit" name="submit" value="Go"/>
		</form>
	</body>
</html>