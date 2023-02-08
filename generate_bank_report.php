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
	$whereclass1 = '';
	$whereclass2 ='';
	
	if(isset($_GET['from']) and strlen($_GET['from'])>0){
		$from = modifydate($_GET['from'],'-','/');
		$whereclass .= " and date >= '".$from."'";
		$whereclass1 .= " and date >= '".$from."'";
		$f = $_GET['from'];
	}else{
		$f = '';
	}
	if(isset($_GET['to']) and strlen($_GET['to'])>0){
		$to = modifydate($_GET['to'],'-','/');
		$t = $_GET['to'];
		$whereclass .= " and date <= '".$to."'";
		$whereclass1 .= " and date <= '".$to."'";
	}else{
		$t = '';
	}
	
	if(isset($_GET['bank']) and $_GET['bank']!=''){
		$bank = $_GET['bank'];
		$whereclass .= "and bankid = '$bank'";
		$whereclass1 .= "and bank = '$bank'";
		$whereclass2 .= "and personid = '$bank' and type='bank'";
	}else{
		$whereclass2 =" and type='bank'";
	}

	$datarr= array();
	$sql = mysqli_query($con,"select * from transfer where 1 $whereclass");
	while($row= mysqli_fetch_array($sql)){
		if($row['type']=='c2b'){
		$datarr[] = array($row['date'],$row['bank_name'],$row['ac_number'],'Cash to Bank',$row['amount'],'');	
		}else{
			$datarr[] = array($row['date'],$row['bank_name'],$row['ac_number'],'Bank to Cash','',$row['amount']);
		}
		
	}
	
	$sql2 = mysqli_query($con,"SELECT * FROM `purchase` where mode = 'check' $whereclass1 and approve!='pending'");
	while($row2 = mysqli_fetch_array($sql2)){
		$bankinfo = mysqli_fetch_array(mysqli_query($con,"select * from bankinformation where id = '".$row2['bank']."'"));
		if($row2['register_mode']=='sale'){
			$datarr[] = array($row2['date'],$bankinfo['bankname'],$bankinfo['accountnumber'],($row2['supplier']==''?'Guest':$row2['supplier']),$row2['payment'],'');	
		}else{
			$datarr[] = array($row2['date'],$bankinfo['bankname'],$bankinfo['accountnumber'],($row2['supplier']==''?'Guest':$row2['supplier']),'',$row2['payment']);	
		}
	}

//bank opening balance
     $sql4 = @mysqli_query($con, "SELECT * FROM openingbalance WHERE 1 $whereclass2");
	while ($r4 = mysqli_fetch_array($sql4)) {

		$bankOpen= mysqli_fetch_array(mysqli_query($con, "SELECT * FROM bankinformation WHERE id='".$r4['personid']."'"));

		 $datarr[] = array('',$bankOpen['bankname'],$bankOpen['accountnumber'],'Bank Opening Balance',$r4['amount'],'');
		}





	$sql3 = mysqli_query($con,"select * from transaction where status = 'due' $whereclass1 and approval!='pending' and method ='check'");
	while($r = mysqli_fetch_array($sql3)){
		$bankinfo = mysqli_fetch_array(mysqli_query($con,"select * from bankinformation where id = '".$r['bank']."'"));
		$personName = mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE id='".$r['name']."'"));
		if($r['type']=='sale'){
			$datarr[] = array($r['date'],$bankinfo['bankname'],$bankinfo['accountnumber'],$personName['name'],$r['pay_amount'],'');
		}elseif($r['type']=='purchase'){
			$datarr[] = array($r['date'],$bankinfo['bankname'],$bankinfo['accountnumber'],$personName['name'],'',$r['pay_amount']);
		}		
	}
	
	$data = '';
	$data .= '<table width="100%"><tr>
				<th>Date</th>            				
				<th>Bank Name</th>
				<th>Account Number</th>				
				<th>From/To</th>				
				<th>IN</th>
				<th>OUT</th>
	</tr>';
	$in = array();
	$out = array();
	for($i=0;$i<sizeof($datarr);$i++){
		
		$data .= '<tr>
			<td >'.daterev($datarr[$i][0],'/','-').'</td>
			<td >'.ucwords($datarr[$i][1]).'</td>			
			<td >'.($datarr[$i][2]).'</td>
			<td >'.ucwords($datarr[$i][3]).'</td>';
			$data .= '<td>'.$datarr[$i][4].'</td>'; 
			$in[] = $datarr[$i][4];
			$data .= '<td>'.$datarr[$i][5].'</td>'; 
			$out[] = $datarr[$i][5];
	$data .= '</tr>';
	}
	$data .='<tr>
		<td ><strong></strong></td>
		<td ><strong></strong></td>
		<td ><strong></strong></td>
		<td ><strong></strong></td>
		<td ><strong>IN '.array_sum($in).'</strong></td>
		<td ><strong>OUT '.array_sum($out).'</strong></td>
	</tr>';
	$data .= '</table>';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Mahedi Hasan');
$pdf->SetTitle('Bank Transaction Report');
$pdf->SetSubject('Bank Transaction Report');

$pdf_header_strng = 'From : '.$f.' To : '.$t;
$pdf->SetHeaderData('', '','Bank Transaction Report', $pdf_header_strng, array(0,64,255), array(0,64,128));
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
$pdf->Output('Bank Transaction report', 'I');	

?>
        