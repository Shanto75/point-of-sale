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
	
	if(isset($_GET['customer']) and $_GET['customer']!=''){
		$customer = $_GET['customer'];
		$whereclass .= "and supplier = '$customer'";
		$whereclass1 .= "and name = '$customer'";
		$whereclass2 .= "and personid = '$customer' and type='customer'";
	}else{
		$whereclass2 =" and type='customer'";
	}
	$datarr= array();

	
	$sql2 = mysqli_query($con,"SELECT `p_id`, `date`, `supplier`, `product_name`, `payment`, `payable`,`type` FROM `purchase` where 1 $whereclass and approve!='pending' and count=1 and type!='purchase' and register_mode!='return'");
	while($row2 = mysqli_fetch_array($sql2)){
		   $customerName= mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE type='customer' AND id='".$row2['supplier']."'"));

			$datarr[] = array($row2['date'],$customerName['name'],$row2['type'],$row2['payment'],$row2['payable'],$row2['p_id']);	

		
	}

     $sql4 = @mysqli_query($con, "SELECT * FROM openingbalance WHERE 1 $whereclass2");
	while ($r4 = mysqli_fetch_array($sql4)) {
		//echo "SELECT name FROM personinformation WHERE type='customer' AND id='".$r4['personid']."'";exit;
		 $customerName= mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE type='customer' AND id='".$r4['personid']."'"));
		$datarr[] = array('',$customerName['name'],'opening balance','',$r4['amount'],'');
	}
	
	/*$sql4 = mysqli_query($con,"SELECT `p_id`, `date`, `supplier`, `product_name`, `payment`, `payable`,`type` FROM `purchase` where 1 $whereclass and count=1 and type!='purchase' and register_mode='return'");
	while($row3 = mysqli_fetch_array($sql4)){
		
			$datarr[] = array($row3['date'],$row3['supplier'],$row3['type'],$row3['payable'],'',$row3['p_id']);	
		
	}*/

	$sql3 = mysqli_query($con,"select * from transaction where status = 'due' and type='sale' $whereclass1 and approval!='pending'");
	while($r = mysqli_fetch_array($sql3)){
		$customerName= mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE type='customer' AND id='".$r['name']."'"));

			$datarr[] = array($r['date'],$customerName['name'],'Customer Due payment',$r['pay_amount'],'','');	
	}
	
	$data = '';
	$data .= '<table  id="table_id" class="display">
		<thead>
			<tr>
				<th align="center">Date</th>            				
				<th align="center">Customer Name</th>
				<th align="center">Description</th>
				<th align="center">Bill No</th>				
				<th align="center">Dr</th>
				<th align="center">Cr</th>
			</tr>
		</thead>
		<tbody>';
	$in = array();
	$out = array();
	for($i=0;$i<sizeof($datarr);$i++){
		
		$data .= '<tr>
			<td >'.daterev($datarr[$i][0],'/','-').'</td>
			<td style="width:150px">'.ucwords($datarr[$i][1]).'</td>			
			<td >'.($datarr[$i][2]).'</td>
			<td >'.($datarr[$i][5]).'</td>';
			$data .= '<td>'.$datarr[$i][3].'</td>'; 
			$in[] = $datarr[$i][3];
			$data .= '<td>'.$datarr[$i][4].'</td>'; 
			$out[] = $datarr[$i][4];
	$data .= '</tr>';
	}
	$data .='</tbody><tr>
		<th></th>            				
		<th></th>
		<th></th>
		<th></th>
		<th ><strong>Dr '.array_sum($in).'</strong></th>
		<th ><strong>Cr '.array_sum($out).'</strong></th>
	</tr>';
	$data .= '</table>';
$data .='<br /><br />
	<table width="300px">
		<tr>';
			if(array_sum($in)>array_sum($out)){
				$data .= '<th><h2>Balance</h2></th>';
			}else{
				$data .= '<th><h2>Due Balance</h2></th>';
			}	
$data .= '<td><h2>'.(array_sum($in)-array_sum($out)).'</h2></td>
		</tr>
	</table>';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Mahedi Hasan');
$pdf->SetTitle('Customer Ledger Report');
$pdf->SetSubject('Customer Ledger Report');

if(isset($_GET['customer']) and $_GET['customer']!=''){
	$scusto =  'Supplier Name : '.$customerName['name'];
}else{
	$scusto='';
}
$pdf_header_strng = 'From : '.$f.' To : '.$t.' '.ucwords($scusto);
$pdf->SetHeaderData('', '','Customer Ledger Report', $pdf_header_strng, array(0,64,255), array(0,64,128));
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
$pdf->Output('Customer Ledger Report', 'I');	

?>
        