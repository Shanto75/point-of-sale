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
	if(isset($_GET['seller']) and strlen($_GET['seller'])>0){		
		$whereclass .= " and user_id = '".$_GET['seller']."'";
		$c = $_GET['seller'];
	}else{
		$c = '';
	}
	if(isset($_GET['from']) and strlen($_GET['from'])>0){
		$from = modifydate($_GET['from'],'-','/').'00:00:00';
		$whereclass .= " and login_time >= '".$from."'";
		$f = $_GET['from'];
	}else{
		$f = '';
	}
	if(isset($_GET['to']) and strlen($_GET['to'])>0){
		$to = modifydate($_GET['to'],'-','/').'23:59:59';
		$t = $_GET['to'];
		$whereclass .= " and login_time <= '".$to."'";
	}else{
		$t = '';
	}
	$group = "order by login_time desc";
	$sql = mysqli_query($con,"select * from login_details where 1 $whereclass $group");	
$data = '';
$data .='<table id="table_id" class="display">
		<thead>
			<tr>
				<th>Date</th>            
				<th>User Name</th>
				<th>Login Time</th>
				<th>Logout Time</th>
				<th>Duration</th>				
			</tr>
		</thead>
		<tbody>';

while($row=mysqli_fetch_array($sql)){
	$sold = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `users` where id = '".$row['user_id']."'"));
	$date = explode(' ',$row['login_time']);
	$currentDateTime = $row['login_time'];
	$currentDateTime2 = $row['logout_time']>'1990-01-01 01:10:10'?$row['logout_time']:'';
	if($currentDateTime2!=''){
		$logout_time = date('h:i A', strtotime($currentDateTime2));
	}else{
		$logout_time = '';
	}
	$login_time = date('h:i A', strtotime($currentDateTime));
			
	$data .='<tr>
			<td>'. daterev($date[0],'/','-') .'</td>
			<td>'. $sold['username'] .'</td>
			<td>'. $login_time .'</td>
			<td>'. $logout_time .' </td>
			<td>'. $row['time'] .' </td>
		</tr>';
		
}
$data .='</tbody></table>';


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Mahedi Hasan');
$pdf->SetTitle('User Log Report');
$pdf->SetSubject('User Log Report');
$pdf_header_strng = 'From : '.$f.' To : '.$t;
$pdf->SetHeaderData('', '','User Log Report', $pdf_header_strng, array(0,64,255), array(0,64,128));
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
$pdf->Output('Stock report', 'I');	
?>

