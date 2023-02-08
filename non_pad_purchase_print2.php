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
<div id="print_div" style="text-align:right;width:280px">
<a href="purchase.php">Cancel</a>

<input name="print" type="button" value="Print" id="printButton" onClick="printpage()">
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" valign="top">

            <table width="695" cellspacing="0" cellpadding="0" id="bordertable" border="1">
                <tr>
                    <td align="center"><strong>Purchase Quatation <br/>
                        </strong>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="67%" align="left" valign="top">&nbsp;&nbsp;&nbsp;Date: <?php
                                    $sid = $_GET['sid'];
                                    $line = mysqli_fetch_array(mysqli_query($con,"select * from purchase_quatation where p_id = '$sid'"));
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
                                    echo $line['supplier'];
                                    $customer = mysqli_fetch_array(mysqli_query($con,"select * from personinformation where type='supplier' and name = '".$line['supplier']."'"));                                    

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
                                <td bgcolor="#CCCCCC">Discount</td>
                                <td bgcolor="#CCCCCC"><strong>Total</strong></td>
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
                            $prod = mysqli_query($con,"select * from purchase_quatation where type = 'purchase' and p_id = '$sid'");
                            while ($row = mysqli_fetch_array($prod)) {
								$find_prod = mysqli_fetch_array(mysqli_query($con,"select * from product_details where name = '".$row['product_name']."'"));
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