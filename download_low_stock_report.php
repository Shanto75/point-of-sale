<?php
ob_start();
session_start();
$msg = '';
if(!isset($_SESSION['id']) and $_SESSION['id']==''){
header("Location: login.php");
exit();
}
include('includes/config.php');
include('includes/function.php');
require_once('tcpdf/examples/lang/eng.php');
require_once('tcpdf/tcpdf.php');

	$whereclass = '';
	if(isset($_GET['stock'])){
		if($_GET['stock']=='category' and $_GET['category']!=''){
			$whereclass .= " and category = '".$_GET['category']."' order by name asc";
			$msg = 'Short By '.$_GET['category'].' Category';
		}
		if($_GET['stock']=='brand' and $_GET['brand']!=''){
			$whereclass .= " and brand = '".$_GET['brand']."' order by name asc";
			$msg = 'Short By '.$_GET['brand'].' Brand';
		}
		if($_GET['stock']=='expire_date' and $_GET['expire_date']!=''){
			$expire_date = modifydate($_GET['expire_date'],'-','/');
			$whereclass .= " and expire_date < '".$expire_date."' order by name asc";
			$msg = 'Short By Expire date '.$_GET['expire_date'];
		}
		if($_GET['stock']=='product_name'){
			
			$whereclass .= " order by name asc";
		}
	}else{
		$whereclass .= " order by name asc";
	}
	
	$sql = mysqli_query($con,"SELECT * FROM `product_details` WHERE 1 $whereclass");
$data = '';
$data .='<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Category Name</th>
            <th>Retail Price</th>
            <th>Wholesale Price</th>
            <th>Warranty</th>
            <th>Expiery Date</th>
            <th>Stock Amount</th>            
            
        </tr>
    </thead>
    <tbody>';

while($row=mysqli_fetch_array($sql)){
	$sa = @mysqli_fetch_array(@mysqli_query($con,"SELECT * FROM `stock` WHERE 1 and `product_name` = '".$row['name']."'"));
		$stock_amount = $sa['quantity'];
		if($stock_amount<=$row['minquantity']){
			
		$data .='<tr>
				
				<td>'. ucwords($row['name']) .'</td>
				<td>'. ucwords($row['category']) .'</td>
				<td>'. ($row['sale_price']) .'</td>
				<td>'. ($row['wholesale_price']) .'</td>
				<td>'. ($row['warranty']) .'</td>
			 <td>'. daterev($row['expire_date'],'/','-') .'</td>
				<td>'. $stock_amount .'</td>
				
		</tr>';
		}
}
$data .='</tbody></table>';


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Mahedi Hasan');
$pdf->SetTitle('Low Stock Report');
$pdf->SetSubject('Low Stock Report');
$pdf_header_strng = '';
$pdf->SetHeaderData('', '','Low Stock Report', $pdf_header_strng, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


$pdf->setLanguageArray($l);
$pdf->setFontSubsetting(true);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->AddPage();

//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));


$html = $data;

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$pdf->Output('Low Stock report', 'I');	
?>

