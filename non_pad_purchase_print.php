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
echo $_GET['sid'];
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <title>Purchase Print</title>
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
        <!--
        .style1 {
            font-size: 10px
        }

        -->
    </style>
</head>

<body>
<input name="print" type="button" value="Print" id="printButton" onClick="printpage()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" valign="top">

            <table width="595" cellspacing="0" cellpadding="0" id="bordertable" border="1">
                <tr>
                    <td align="center"><strong>Purchase Invoice <br/>
                        </strong>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="67%" align="left" valign="top">&nbsp;&nbsp;&nbsp;Date: <?php
                                    $sid = $_GET['sid'];
                                    $line = mysqli_fetch_array(mysqli_query($con,"select * from purchase where p_id = '$sid'"));
                                    echo daterev($line['date'],'/','-');
                                    ?> <br/>
                                    <br/>
                                    <strong><br/>
                                        &nbsp;&nbsp;&nbsp;Receipt No: <?php echo $sid;

                                        ?> </strong><br/></td>
                                <td width="33%">
                                    <div align="center">
                                        <?php $shop = mysqli_fetch_array(mysqli_query($con,"select * from storeinformation where 1 and id = '$store_id'"));
                                        ?>
										<img width="100" height="80" src="images/<?php echo $shop['logo']; ?>" alt="<?php echo ucwords($shop['name']); ?>" /><br />
                                        <strong><?php echo ucwords($shop['name']); ?></strong><br/>
                                        <?php echo $shop['address'] ?><br/>
                                        Website<strong>:<?php echo $shop['website'] ?></strong><br>Email<strong>:<?php echo $shop['email']; ?></strong><br/>Phone
                                        <strong>:<?php echo $shop['phone']; ?></strong>
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
                            <tr>
                                <td width="5%" align="left" valign="top"><strong>&nbsp;&nbsp;TO:</strong></td>
                                <td width="95%" align="left" valign="top"><br/>
                                    <?php
                                    $customer = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM personinformation WHERE type='supplier' AND `id`= '".$line['supplier']."'"));

                                    echo "Name: " . $customer['name'].'<br/>';                                    

                                    echo $customer['address'];
                                    ?>
                                    <br/>
                                    <?php
                                    echo "Contact1: " . $customer['phone'] . "<br>";
                                    echo "Email: " . $customer['email'] . "<br>";
                                    ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td  align="center" bgcolor="#CCCCCC"><strong>No.</strong></td>
                                <td  bgcolor="#CCCCCC"><strong>Product</strong></td>
                                <td  bgcolor="#CCCCCC"><strong>Code</strong></td>
                                <td  bgcolor="#CCCCCC"><strong>Quantity</strong></td>
                                <td  bgcolor="#CCCCCC"><strong>Rate</strong></td>
                                <td  bgcolor="#CCCCCC">Discount</td>
                                <td  bgcolor="#CCCCCC"><strong>Total</strong></td>
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
                            $prod = mysqli_query($con,"select * from purchase where type = 'purchase' and p_id = '$sid'");
                            while ($row = mysqli_fetch_array($prod)) {
								$find_prod = mysqli_fetch_array(mysqli_query($con,"select * from product_details where name = '".$row['product_name']."'"));
                                $quantitysum[] = $row['quantity'];
								?>
                                <tr>
                                    <td align="center"><?php echo $i . "."; ?></td>
                                    <td><?php echo ucwords($row['product_name']); ?></td>
                                    <td><?php echo ($row['pcode']); ?></td>
                                    <td><?php echo $row['quantity'].' '.$find_prod['unit_type']; ?></td>
                                    <td><?php echo $row['purchase_cost'].' TK' ?></td>
                                    <td><?php echo $row['discount_prod'].' TK' ?></td>
                                    <td><?php echo $row['total'].' TK' ?></td>
                                </tr>

                                <?php
                                $i++;
                                $subtotal = $row['subtotal'];
                                $payment = $row['payment'];
                                $payable = $row['payable'];
                                $balance = $row['balance'];
                                $date = $row['due_date'];
                                $discount = $row['dis_amount'];
								$change = $row['change'];
								$mode = $row['mode'];
                                $mode_value = $row['mode_value'];
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
                                <td width="20%" bgcolor="#CCCCCC"><?php echo array_sum($quantitysum).''; ?>&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="82%" align="right" bgcolor="#CCCCCC"><strong>SubTotal:&nbsp;&nbsp;</strong>
                                </td>
                                <td width="18%" bgcolor="#CCCCCC"><?php echo $subtotal.' TK'; ?>&nbsp;</td>
                            </tr>
							<tr>
                                <td width="82%" align="right" bgcolor="#CCCCCC"><strong>Discount:&nbsp;&nbsp;</strong>
                                </td>
                                <td width="18%" bgcolor="#CCCCCC"><?php echo $discount.' TK' ?>&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="82%" align="right" bgcolor="#CCCCCC"><strong>Grand Total:&nbsp;&nbsp;</strong>
                                </td>
                                <td width="18%" bgcolor="#CCCCCC"><?php echo $payable.' TK' ?>&nbsp;</td>
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
                                    &nbsp;&nbsp;Paid Amount :&nbsp;&nbsp;<strong><?php echo ($payment+$change).' TK'; ?></strong><br/>									
                                       
										&nbsp;&nbsp;Change Amount :&nbsp;&nbsp;<strong><?php echo $change.' TK'; ?></strong><br/>
                                        <!--&nbsp;&nbsp;Due Date&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;:--> <?php
                                       // echo daterev($date,'/','-'); ?> 
                                    
									&nbsp;&nbsp;Payment Method :&nbsp;&nbsp;<strong><?php echo ucwords($mode); ?></strong><br/>
									<?php 
										if($mode!='cash'){
											echo "&nbsp;&nbsp;Value:&nbsp;&nbsp;<strong> $mode_value </strong><br/>";
										}
									?>
									 <span style="font-size:25px">&nbsp;Due Balance
                                        :&nbsp;&nbsp;<strong><?php echo $balance.' TK'; ?></strong></span><br/>
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
                    <td align="center" bgcolor="#CCCCCC">Thank you for Business with Us</td>
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