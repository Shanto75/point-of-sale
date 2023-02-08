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
	$whereclassOne = '';
	if(isset($_GET['customer']) and strlen($_GET['customer'])>0){		
		$whereclass .= " and supplier = '".$_GET['customer']."'";
		$whereclassOne .= " and name = '".$_GET['customer']."'";
		$c = $_GET['customer'];
	}else{
		$c = '';
	}
	if(isset($_GET['from']) and strlen($_GET['from'])>0){
		$from = modifydate($_GET['from'],'-','/');
		$whereclass .= " and date >= '".$from."'";
		$whereclassOne .= " and date >= '".$from."'";
		$f = $_GET['from'];
	}else{
		$f = '';
	}
	if(isset($_GET['to']) and strlen($_GET['to'])>0){
		$to = modifydate($_GET['to'],'-','/');
		$t = $_GET['to'];
		$whereclass .= " and date <= '".$to."'";
		$whereclassOne .= " and date <= '".$to."'";
	}else{
		$t = '';
	}
	$sql = mysqli_query($con,"select * from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and approve!='pending' group by p_id");
	
	$totalsale = @mysqli_fetch_array(mysqli_query($con,"select sum(payable) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and approve!='pending' and count =1")); 
	$totalrec = @mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and approve!='pending' and count =1")); 
	$totaldes = @mysqli_fetch_array(mysqli_query($con,"select sum(dis_amount) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and approve!='pending' and count =1")); 
	$totalout = @mysqli_fetch_array(mysqli_query($con,"select sum(balance) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='sale' $whereclass and approve!='pending' and count =1")); 
	$due1= @mysqli_fetch_array(@mysqli_query($con,"select sum(pay_amount)as total from transaction where type='sale' and status='due' $whereclassOne  and approval!='pending'"));
$tpaid = $totalrec['total']+$due1['total'];
$tout = $totalout['total']-$due1['total'];

$data = '';
$data .='<table id="table_id" class="display">
		<thead>
			<tr>
				<th>Date</th>            
				<th>Invoice Id</th>
				<th>Customer</th>
				<th>Payable</th>
				<th>Discount</th>
				<th>Paid</th>
				<th>Due</th>
				<th>Received by</th>				
				
			</tr>
		</thead>
		<tbody>';

while($row=mysqli_fetch_array($sql)){
	$sold = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `users` where id = '".$row['user']."'"));
	$customerName = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM personinformation WHERE type='customer' AND id='".$row['supplier']."'"));
			/*if($row['balance']>1){
		$due = @mysqli_fetch_array(@mysqli_query($con,"select sum(pay_amount)as total from transaction where type='sale' and status='due' and name = '".$row['supplier']."'"));
		
		$paymnt = $row['payment']+$due['total'];
		$bal = $row['balance']-$due['total'];
	}else{
		$paymnt = $row['payment'];
		$bal = $row['balance'];
	}*/
	$data .='<tr><td>'.daterev($row['date'],'/','-').'</td>';
	$data .='<td>'.$row['bill_no'].'</td>
			<td>'.ucwords($customerName['name']).'</td>
			<td>'.number_format((float)$row['payable'], 2, '.', '') .' TK</td>
			<td>'. number_format((float)$row['dis_amount'], 2, '.', '') .' TK</td>
			<td>'. number_format((float)$row['payment'], 2, '.', '') .' TK</td>
			<td>'. number_format((float)$row['balance'], 2, '.', '') .' TK</td>
			<td>'. ucwords($sold['username']) .'</td></tr>';
}
$data .='</tbody></table>';
$data .='<br /><br /><table width="200px">
		<tr>
			<th align="left">Total Sales : </th>
			<td align="right">'. number_format((float)$totalsale['total'], 2, '.', '') .' TK</td>
		</tr>		
		<tr>
			<th align="left">Total Payment : </th>
			<td align="right">'. number_format((float)$tpaid, 2, '.', '') .' TK</td>
		</tr>
		<tr>
			<th align="left">Total Discount : </th>
			<td align="right">'. number_format((float)$totaldes['total'], 2, '.', '') .' TK</td>
		</tr>
		<tr>
			<th align="left">Total Due : </th>
			<td align="right">'. number_format((float)$tout, 2, '.', '') .' TK</td>
		</tr>
	</table>';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Mahedi Hasan');
$pdf->SetTitle('Customer Report');
$pdf->SetSubject('Customer Report');
$pdf_header_strng = 'From : '.$f.' To : '.$t;
$pdf->SetHeaderData('', '','Customer Report', $pdf_header_strng, array(0,64,255), array(0,64,128));
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
$pdf->Output('Customer report', 'I');	
?>

