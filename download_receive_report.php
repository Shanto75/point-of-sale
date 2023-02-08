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
	if(isset($_GET['customer']) and strlen($_GET['customer'])>0){
		$whereclass .= " and supplier = '".$_GET['customer']."'";
		$c = $_GET['customer'];
	}else{
		$c = '';
	}
	if(isset($_GET['from']) and strlen($_GET['from'])>0){
		$from = modifydate($_GET['from'],'-','/');
		$whereclass .= " and date >= '".$from."'";
		$f = $_GET['from'];
	}else{
		$f = '';
	}
	if(isset($_GET['to']) and strlen($_GET['to'])>0){
		$to = modifydate($_GET['to'],'-','/');
		$t = $_GET['to'];
		$whereclass .= " and date <= '".$to."'";
	}else{
		$t = '';
	}
	
	$sql = mysqli_query($con,"SELECT `supplier`, sum(`payment`)as payment,  sum(`dis_amount`)as dis_amount, sum(`payable`)as payable, sum(balance)as balance  FROM `purchase` WHERE 1 and type='sale' and register_mode='sale' and count='1' $whereclass AND approve!='pending' group by supplier");
	
$data = '';
$data .='<div class="table_data">
		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Total Cost</th>
            <th>Total Paid</th>            
            <th>Total Due</th>            
        </tr>
    </thead>
    <tbody>';

while($row = @mysqli_fetch_array($sql)){	
	$an = @mysqli_fetch_array(mysqli_query($con,"select sum(pay_amount) as pay_amount from transaction where type='sale' and name = '".$row['supplier']."' and status = 'due' and approval!='pending' group by name"));
	$customerName = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM personinformation WHERE type='customer' AND id='".$row['supplier']."'"));

	$openblnce = mysqli_fetch_array(mysqli_query($con, "SELECT amount FROM openingbalance  WHERE type='customer' AND personid='".$customerName['id']."'"));
	

	$pay = $an['pay_amount'];
	$payble = $row['payable'];
	$paid = $row['payment']+$pay;

	if(!empty($openblnce)){

		if($openblnce['amount'] < 0){
			$payble = $payble + $openblnce['amount'];
		}

	}
	$due = $payble-$paid;
	
	$data .='<tr>
    		
       		<td>'. ucwords($customerName['name']) .'</td>
            
            <td>'. number_format((float)$payble, 2, '.', '') .'</td>
            <td>'. number_format((float)$paid, 2, '.', '') .'</td>
            <td>'. number_format((float)$due, 2, '.', '') .'</td>
         
    </tr>';
	
}
$data .='</tbody></table>';



$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Mahedi Hasan');
$pdf->SetTitle('Receive Report');
$pdf->SetSubject('Receive Report');
$pdf_header_strng = 'From : '.$f.' To : '.$t;
$pdf->SetHeaderData('', '','Receive Report', $pdf_header_strng, array(0,64,255), array(0,64,128));
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
$pdf->Output('Receive Report', 'I');	
?>


