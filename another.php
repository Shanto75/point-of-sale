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
if(isset($_GET['sid']))
{
	$store_id = $_SESSION['store_id'];
define("MAJOR", 'Taka');
define("MINOR", 'Paisa');
class toWords  {
           var $pounds;
           var $pence;
           var $major;
           var $minor;
           var $words = '';
           var $number;
           var $magind;
           var $units = array('','one','two','three','four','five','six','seven','eight','nine');
           var $teens = array('ten','eleven','twelve','thirteen','fourteen','fifteen','sixteen','seventeen','eighteen','nineteen');
           var $tens = array('','ten','twenty','thirty','forty','fifty','sixty','seventy','eighty','ninety');
           var $mag = array('','thousand','million','billion','trillion');
    function toWords($amount, $major=MAJOR, $minor=MINOR) {
             $this->major = $major;
             $this->minor = $minor;
             $this->number = number_format($amount,2);
             list($this->pounds,$this->pence) = explode('.',$this->number);
             $this->words = " $this->major $this->pence$this->minor";
             if ($this->pounds==0)
                 $this->words = "Zero $this->words";
             else {
                 $groups = explode(',',$this->pounds);
                 $groups = array_reverse($groups);
                 for ($this->magind=0; $this->magind<count($groups); $this->magind++) {
                      if (($this->magind==1)&&(strpos($this->words,'hundred') === false)&&($groups[0]!='000'))
                           $this->words = ' and ' . $this->words;
                      $this->words = $this->_build($groups[$this->magind]).$this->words;
                 }
             }
    }
    function _build($n) {
             $res = '';
             $na = str_pad("$n",3,"0",STR_PAD_LEFT);
             if ($na == '000') return '';
             if ($na{0} != 0)
                 $res = ' '.$this->units[$na{0}] . ' hundred';
             if (($na{1}=='0')&&($na{2}=='0'))
                  return $res . ' ' . $this->mag[$this->magind];
             $res .= $res==''? '' : ' and';
             $t = (int)$na{1}; $u = (int)$na{2};
             switch ($t) {
                     case 0: $res .= ' ' . $this->units[$u]; break;
                     case 1: $res .= ' ' . $this->teens[$u]; break;
                     default:$res .= ' ' . $this->tens[$t] . ' ' . $this->units[$u] ; break;
             }
             $res .= ' ' . $this->mag[$this->magind];
             return $res;
    }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <title>Sales Print</title>
    <style type="text/css" media="print">
        .hide {
            display: none
        }

    </style>
    <script type="text/javascript">
        function printpage() {
            document.getElementById('printButton').style.visibility = "hidden";
            window.print();
            document.getElementById('printButton').style.visibility = "visible";
        }
    </script>
    <style type="text/css">
	.fix{overflow:hidden}
    .main_content{width: 900px;
margin: 0px auto;
display: block;
background: transparent url("images/Invoixe.png") no-repeat scroll 0% 0% / 100% 100%;
height: 1000px; position:relative}
    .left{float:left}
    .right{float:right}
	.name{}
	.item_table{margin-left: 61px; width: 740px;}
	
    </style>
</head>
<?php 
$sid = $_GET['sid'];
$line = mysqli_fetch_array(mysqli_query($con,"select * from purchase where p_id = '$sid'"));
?>
<body>
	<div style="text-align:right;margin:10px auto;width:900px">
	<a href="sale.php">Cancel</a>
	<input name="print" type="button" value="Print" id="printButton" onClick="printpage()">
	</div>
	<div class="main_content fix">
		<div style="height:196px;"></div>
		<div style="width: 82%; margin-left: 62px;" class="fix">
			<div class="left name">
				<table style="width: 292px;">
				<?php 
				$customer = mysqli_fetch_array(mysqli_query($con,"select * from personinformation where type='customer' and name = '".$line['supplier']."'"));
				?>
					<tr><td width="100px"></td><td><?= ucwords($line['supplier']); ?></td></tr>
					<tr><td width="100px"></td><td><?= ucwords($customer['address']); ?></td></tr>
					
				</table>
			</div>
			<div class="right name">
				<table style="width: 292px;">
					<tr><td width="150px"><td height="20px"></td><tr>
					<tr><td width="150px"><td height="20px"><?= $sid ?></td><tr>
					<tr><td width="150px"><td height="20px"><?= daterev($line['date'],'/','-') ?></td><tr>
				</table>
			</div>
		</div>
		<div style="height:56px;"></div>
		<table class="item_table">
		 <?php
			$i = 1;
			$prod = mysqli_query($con,"select * from purchase where (type = 'sale' or type='wholesale') and p_id = '$sid'");
			while ($row = mysqli_fetch_array($prod)) {
				?>
				<tr>
					<td style="width: 30px;" align="center"><?php echo $i . "."; ?></td>
					<td style="width: 339px;" align="center"><?php echo ucwords($row['product_name']); ?></td>
					<td style="width: 103px;" align="center"><?php echo $row['quantity']; ?></td>
					<td style="width: 104px;" align="center"><?php echo $row['sale_price'].' TK' ?></td>
					
					<td style="width: 144px;" align="center"><?php echo $row['total'].' TK' ?></td>
				</tr>
		<?php 
		$payable = $row['payable'];
		} ?>

		</table>
		<div style="position: absolute;right: 188px;bottom: 140px;"><?= $payable ?></div>
		<div style="position: absolute;left: 167px;bottom: 108px;"><?php 
		    $obj = new toWords($payable);
			echo ucwords($obj->words);
		?></div>
	</div>
</body>
</html>
<?php


}
else echo"Error in processing printing the sales receipt";
?>