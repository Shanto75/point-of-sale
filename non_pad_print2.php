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
function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return ucwords($string);
}
if(isset($_GET['sid']))
{
	$store_id = $_SESSION['store_id'];

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
            document.getElementById('print_div').style.visibility = "hidden";
            window.print();
            document.getElementById('print_div').style.visibility = "visible";
        }
    </script>
    <style type="text/css">
        <!--
        .style1 {
            font-size: 10px
        }

        -->
    </style>
</head>

<body>
<div id="print_div" style="text-align:right;margin:10px auto;width:280px">
<a href="sale.php">Cancel</a>
<input name="print" type="button" value="Print" id="printButton" onClick="printpage()">
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" valign="top">

            <table width="695" cellspacing="0" cellpadding="0" id="bordertable" border="1">
                <tr>
                    <td align="center"><h2>Price Quatation</h2>
                        </strong>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="67%" align="left" valign="top"><?php
                                    $sid = $_GET['sid'];
                                    $line = mysqli_fetch_array(mysqli_query($con,"select * from purchase_quatation where p_id = '$sid'"));
                                    echo daterev($line['date'],'/','-').'<br />';
									if($line['time']!='00:00:00'){
										echo date('h:i A', strtotime($line['time']));
									}else{
										echo '';
									}
                                    ?> <br/>
                                    
                                    <strong>
                                        Invoice No: <?php echo $line['bill_no'];

                                        ?> </strong><br/>
									<strong>
                                        Challan No: <?php echo $line['challan_no'];

                                        ?> </strong><br/>
									<strong>
                                        Order No: <?php echo $line['order_no'];

                                        ?> </strong><br/>
									</td>
                                <td align="right" width="33%">
                                    <div align="right" style="line-height:1.2em">
                                        <?php $shop = mysqli_fetch_array(mysqli_query($con,"select * from storeinformation where 1 and id = '$store_id'"));
                                        ?>
										<img width="100" height="80" src="images/<?php echo $shop['logo']; ?>" alt="<?php echo ucwords($shop['name']); ?>" /><br />
                                        <strong><?php echo ucwords($shop['name']); ?></strong><br/>
                                        <?php echo $shop['address'] ?><br/>
										Phone
                                        <strong>:<?php echo $shop['phone']; ?></strong><br>
										Email<strong>:<?php echo $shop['email']; ?></strong><br/>
                                        Website<strong>:<?php echo $shop['website'] ?></strong>
                                        <br/>
                                        <?php ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="90" align="left" valign="top"><br/>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
						<?php 
							if($line['supplier']!=''){
						?>
                            <tr>
                                <td width="5%" align="left" valign="top"><strong>&nbsp;&nbsp;TO:</strong></td>
                                <td width="95%" align="left" valign="top"><br/>
                                    <?php
                                    echo "Name: " . $line['supplier'].'<br/>';
                                    $customer = mysqli_fetch_array(mysqli_query($con,"select * from personinformation where type='customer' and name = '".$line['supplier']."'"));                                    
                                    echo $customer['address'];
                                    ?>
                                    <br/>
                                    <?php
                                    echo "Contact: " . $customer['phone'] . "<br>";
                                    echo "Email: " . $customer['email'] . "<br><br />";
									
                                    ?></td>
                            </tr>
						<?php } ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="12%" align="center" bgcolor="#CCCCCC">No</td>
                                <td width="22%" align="center" bgcolor="#CCCCCC">Product</td>
                                <td width="22%" align="center" bgcolor="#CCCCCC">Code</td>
                                <td width="18%" align="center" bgcolor="#CCCCCC">Quantity</td>
                                <td width="19%" align="center" bgcolor="#CCCCCC">Rate</td>
                                <td width="11%" align="center" bgcolor="#CCCCCC">Discount</td>
                                <td width="18%" align="center" bgcolor="#CCCCCC">Total</td>
                            </tr>
                            <tr>
                                <td align="center">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php
                            $i = 1;
							$quantitysum = array();
                            $prod = mysqli_query($con,"select * from purchase_quatation where (type = 'sale' or type='wholesale') and p_id = '$sid'");
                            while ($row = mysqli_fetch_array($prod)) {
								$find_prod = mysqli_fetch_array(mysqli_query($con,"select * from product_details where name = '".$row['product_name']."'"));
                                $quantitysum[] = $row['quantity'];
								?>
                                <tr>
                                    <td align="center"><?php echo $i . "."; ?></td>
                                    <td align="center"><?php echo ucwords($row['product_name']); ?></td>
                                    <td align="center"><?php echo ($row['pcode']); ?></td>
                                    <td align="center"><?php echo $row['quantity'].' '.$find_prod['unit_type']; ?></td>
                                    <td align="center"><?php echo $row['sale_price'].' TK' ?></td>
                                    <td align="center"><?php echo $row['discount_prod'].' TK' ?></td>
                                    <td align="center"><?php echo $row['total'].' TK' ?></td>
                                </tr>
                                <?php
                                $i++;
                                $subtotal = $row['subtotal'];
                                $payment = $row['payment'];
                                $payable = $row['payable'];
                                $balance = $row['balance'];
                                $date = $row['due_date'];
                                $discount = $row['dis_amount'];
                                $mode = $row['mode'];
                                $mode_value = $row['mode_value'];
                                $tax = $row['tax'];
                                $tax_amount = $row['tax_amount'];
                                
                                $tax_dis = $row['tax_dis'];
                                $change = $row['change'];
                            }
                            ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
                                <td width="80%" align="right" bgcolor="#CCCCCC"><strong>Total Quantity:&nbsp;&nbsp;</strong>
                                </td>
                                <td width="20%" bgcolor="#CCCCCC"><?php echo array_sum($quantitysum).' KG'; ?>&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="62%" align="right" bgcolor="#CCCCCC"><strong>SubTotal:&nbsp;&nbsp;</strong>
                                </td>
                                <td width="28%" bgcolor="#CCCCCC"><?php echo $subtotal.' TK'; ?>&nbsp;</td>
                            </tr>
							<tr>
                                <td width="62%" align="right" bgcolor="#CCCCCC"><strong>Discount:&nbsp;&nbsp;</strong>
                                </td>

                                <td width="28%" bgcolor="#CCCCCC"><?php echo $discount.' TK' ?>&nbsp;</td>
                            </tr>
							<?php 
								if($tax!=='' && $tax_dis!=''){
								?>
								<tr>
									<td width="62%" align="right" bgcolor="#CCCCCC"><strong><?= ucwords($tax_dis) ?> &nbsp;<?= $tax ?> % :&nbsp;&nbsp;</strong></td>
									<td width="28%" bgcolor="#CCCCCC"><?= $tax_amount ?> TK</td>
								</tr>
								
							<?php
								}
							?>
                            <tr>
                                <td width="62%" align="right" bgcolor="#CCCCCC"><strong>Grand Total:&nbsp;&nbsp;</strong>
                                </td>
                                <td width="28%" bgcolor="#CCCCCC"><?php echo $payable.' TK' ?>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="45%" align="left" valign="top"><br/>
									&nbsp;&nbsp;Amount In Word :&nbsp;&nbsp;<strong><?php echo convert_number_to_words($payable).' Taka Only' ?></strong><br/>
                                    
									</td>
									
                                <td width="7%" align="right"><br/>
                                    <br/>
                                    <br/>
                                    </td>
                            </tr>
                        </table>
                    </td>
                </tr>
				<tr>
					<td>
						<table width="100%" style="height:100px">
							<tr>
								<td valign="bottom" align="right"><span style="border-top:1px solid #000">Authorised Signature</span></td>
							</tr>
						</table>
					</td>
				</tr>
                <tr>
                    <td align="center" bgcolor="#CCCCCC">
					<?php 
						if($tax!=='' && $tax_dis!=''){
							$reg = mysqli_fetch_array(mysqli_query($con,"select * from vat where 1"));
							echo 'Vat Reg No : '.$reg['tax_reg'].'<br />';
						}
					?>
						Thank you for business with us
					</td>
					
                </tr>
				<tr>
                    <td align="center" bgcolor="white" style="padding-top:10px"><?php include('footer_text.php'); ?></td>
					
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>
<?php


}
else echo"Error in processing printing the sales receipt";
?>