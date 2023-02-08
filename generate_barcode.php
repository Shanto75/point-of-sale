<style type="text/css">
body{width:100%;}
@media print {
      body, html {
          width: 100%;
      }
}
.barcode{width:20%;height: auto;float:left;margin-right:30px;text-align:center;padding: 20px 0px;}
.barcode p{font-size: 12px;
margin: 2px;}
</style>
<input type="button" value="Print" onclick="window.print();"/><br />
<?php
ob_start();
session_start();
$msg = '';
if(!isset($_SESSION['id']) and $_SESSION['id']==''){
header("Location: login.php");
exit();
}
$store_id = $_SESSION['store_id'];
include('includes/config.php');
include('barcode/src/BarcodeGenerator.php');
include('barcode/src/BarcodeGeneratorPNG.php');
include('barcode/src/BarcodeGeneratorSVG.php');
include('barcode/src/BarcodeGeneratorHTML.php');


if(!empty($_POST['barcode'])){
	$generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
	$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG();
	$generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML();
	
	$barcode = implode(',',$_POST['barcode']);

	$sql = mysqli_query($con,"select * from product_details where id in ($barcode)");
	$data = '';
	$bcode = @mysqli_fetch_array(@mysqli_query($con,"select * from barcode_setup where id = 1"));
	$arr = array('name'=>'Item Name','product_code'=>'Code','category'=>'Category','sale_price'=>'Retail Price','wholesale_price'=>'Wholesale Price','brand'=>'Brand','warranty'=>'Warranty','expire_date'=>'Expire Date','size'=>'Size','company'=>'Company Name');
	$company = mysqli_fetch_array(mysqli_query($con,"select * from storeinformation where id = '1'"));
	while($row = mysqli_fetch_array($sql)){
		
		$name = $row['name'];
		$code = $row['product_code'];
		$price = number_format((float)$row['sale_price'], 2, '.', '');
		$qtychk = mysqli_fetch_array(mysqli_query($con,"select * from stock where 1 and code = '$code' and product_name = '$name'"));
		for($i=0;$i<$qtychk['quantity'];$i++){
		if($code!=''){		
			$data .= '<div class="barcode" style="">';
			if($bcode['row1']!=''){
				
				if($bcode['row1']=='sale_price' or $bcode['row1']=='wholesale_price')
					$data .= '<p>'.$arr[$bcode['row1']].'-'.number_format((float)$row[$bcode['row1']], 2, '.', '').'</p>';
				elseif($bcode['row1']=='company')
					$data .= '<p>'.$company['name'].'</p>';
				else
					$data .= '<p>'.$arr[$bcode['row1']].'-'.$row[$bcode['row1']].'</p>';
			}
			$data .= '<img width="140px" src="data:image/png;base64,' . base64_encode($generatorPNG->getBarcode($code, $generatorPNG::TYPE_CODE_128)) . '">';
			if($bcode['row2']!=''){
				if($bcode['row2']=='sale_price' or $bcode['row2']=='wholesale_price')
					$data .= '<p>'.$arr[$bcode['row2']].'-'.number_format((float)$row[$bcode['row2']], 2, '.', '').'</p>';
				elseif($bcode['row2']=='company')
					$data .= '<p>'.$company['name'].'</p>';
				else
					$data .= '<p>'.$arr[$bcode['row2']].'-'.$row[$bcode['row2']].'</p>';
			}
			if($bcode['row3']!=''){
				if($bcode['row3']=='sale_price' or $bcode['row3']=='wholesale_price')
					$data .= '<p>'.$arr[$bcode['row3']].'-'.number_format((float)$row[$bcode['row3']], 2, '.', '').'</p>';
				elseif($bcode['row3']=='company')
					$data .= '<p>'.$company['name'].'</p>';
				else
					$data .= '<p>'.$arr[$bcode['row3']].'-'.$row[$bcode['row3']].'</p>';
			}
			if($bcode['row4']!=''){
				if($bcode['row4']=='sale_price' or $bcode['row4']=='wholesale_price')
					$data .= '<p>'.$arr[$bcode['row4']].'-'.number_format((float)$row[$bcode['row4']], 2, '.', '').'</p>';
				elseif($bcode['row4']=='company')
					$data .= '<p>'.$company['name'].'</p>';
				else
					$data .= '<p>'.$arr[$bcode['row4']].'-'.$row[$bcode['row4']].'</p>';
			}
			if($bcode['row5']!=''){
				if($bcode['row5']=='sale_price' or $bcode['row5']=='wholesale_price')
					$data .= '<p>'.$arr[$bcode['row5']].'-'.number_format((float)$row[$bcode['row5']], 2, '.', '').'</p>';
				elseif($bcode['row5']=='company')
					$data .= '<p>'.$company['name'].'</p>';
				else
					$data .= '<p>'.$arr[$bcode['row5']].'-'.$row[$bcode['row5']].'</p>';
			}			
			$data .= '</div>';
		}
		break;
	}
	}
	echo $data;
	
}else{
	echo 'Something went wrong!';
}




?>