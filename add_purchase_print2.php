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
<div id="print_div" style="text-align:right;margin:50px auto;width:280px">
<a href="purchase.php">Cancel</a>
<a href="non_pad_purchase_print2.php?sid=<?= $_GET['sid'] ?>">Non Pad</a>
<input name="print" type="button" value="Print" id="printButton" onClick="printpage()">
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" valign="top">

            <table width="695" cellspacing="0" cellpadding="0" id="bordertable" border="1">
                <tr>
                    <td align="center"><h2>Purchase Quatation </h2>
                        </strong>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="50%" align="left" valign="top">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<?php 
									$sid = $_GET['sid'];
                                    $line = mysqli_fetch_array(mysqli_query($con,"select * from purchase_quatation where p_id = '$sid'"));
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
                                <td align="right" width="50%">
                                  <table>
									<tr>
										<td>Date : </td>
										<td><?= daterev($line['date'],'/','-') ?></td>
									</tr>
									<tr>
										<td>Time : </td>
										<td><?php 
											if($line['time']!='00:00:00'){
												echo date('h:i A', strtotime($line['time']));
											}else{
												echo '';
											}
										?></td>
									</tr>
									<tr>
										<td>Invoice No: </td>
										<td><?= $line['bill_no'] ?></td>
									</tr>
									
									<tr>
										<td>Prepared By :  </td>
										<td>
											<?php 
												$prepa= mysqli_fetch_array(mysqli_query($con,"select *from users where id = '".$line['user']."'"));
												echo $prepa['username'];
											?>
										</td>
									</tr>
								  </table> 
                               </td>
										
                                
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td  align="center" bgcolor="#CCCCCC">No</td>
                                <td  align="center" bgcolor="#CCCCCC">Product</td>
                                <td  align="center" bgcolor="#CCCCCC">Code</td>
                                <td  align="center" bgcolor="#CCCCCC">Quantity</td>
                                <td  align="center" bgcolor="#CCCCCC">Rate</td>
                                <td  align="center" bgcolor="#CCCCCC">Discount</td>
                                <td  align="center" bgcolor="#CCCCCC">Total</td>
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
                                <td width="80%" align="right" bgcolor="#CCCCCC"><strong>SubTotal:&nbsp;&nbsp;</strong>
                                </td>
                                <td width="20%" bgcolor="#CCCCCC"><?php echo $subtotal.' TK'; ?>&nbsp;</td>
                            </tr>
							<tr>
                                <td width="80%" align="right" bgcolor="#CCCCCC"><strong>Discount:&nbsp;&nbsp;</strong>
                                </td>

                                <td width="20%" bgcolor="#CCCCCC"><?php echo $discount.' TK' ?>&nbsp;</td>
                            </tr>
							<?php 
								if($tax!=='' && $tax_dis!=''){
								?>
								<tr>
									<td width="80%" align="right" bgcolor="#CCCCCC"><strong><?= ucwords($tax_dis) ?> &nbsp;<?= $tax ?> % :&nbsp;&nbsp;</strong></td>
									<td width="20%" bgcolor="#CCCCCC"><?= $tax_amount ?> TK</td>
								</tr>
								
							<?php
								}
							?>
                            <tr>
                                <td width="80%" align="right" bgcolor="#CCCCCC"><strong>Grand Total:&nbsp;&nbsp;</strong>
                                </td>
                                <td width="20%" bgcolor="#CCCCCC"><?php echo $payable.' TK' ?>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
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