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
if(isset($_GET['inv']))
{
	$store_id = $_SESSION['store_id'];

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <title>Chalan Print</title>
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
<div id="printButton" style="text-align:right;margin:50px auto;width:595px">
<a href="invoice_free.php">Cancel</a>
<a href="challan2.php?inv=<?= $_GET['inv'] ?>">Pad</a>
<input name="print" type="button" value="Print"  onClick="printpage()">
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" valign="top">

            <table width="695" cellspacing="0" cellpadding="0" id="bordertable" border="1">
                <tr>
                    <td align="center"><h2>Challan </h2>
                        </strong>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="50%" align="left" valign="top">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<?php 
									$sid = $_GET['inv'];
                                    $line = mysqli_fetch_array(mysqli_query($con,"select * from purchase where p_id = '$sid' or bill_no= '$sid'"));
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
										<td>Challan No: </td>
										<td><?= $line['challan_no'] ?></td>
									</tr>
									<tr>
										<td>Order No: </td>
										<td><?= $line['order_no'] ?></td>
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
                                <td  align="center" bgcolor="#CCCCCC"><strong>No.</strong></td>
                                <td  bgcolor="#CCCCCC"><strong>Product</strong></td>
                                <td  bgcolor="#CCCCCC"><strong>Product Code</strong></td>
                                <td  bgcolor="#CCCCCC"><strong>Quantity</strong></td>

                            </tr>
                            <tr>
                                <td align="center">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>

                            </tr>
                            <?php
                            $i = 1;
							$quantitysum = array();
                            $prod = mysqli_query($con,"select * from purchase where (type = 'sale' or type='wholesale') and p_id = '$sid' or bill_no= '$sid' ");
                            while ($row = mysqli_fetch_array($prod)) {
								$find_prod = mysqli_fetch_array(mysqli_query($con,"select * from product_details where name = '".$row['product_name']."'"));
                                $quantitysum[] = $row['quantity'];
								?>
                                <tr>
                                    <td align="center"><?php echo $i . "."; ?></td>
                                    <td><?php echo ucwords($row['product_name']); ?></td>
                                    <td><?php echo ucwords($find_prod['product_code']); ?></td>
                                    <td><?php echo $row['quantity'].' '.$find_prod['unit_type']; ?></td>

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
                            }
                            ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>

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
                    <td align="center" bgcolor="#CCCCCC">Thank you for business with us</td>
					
                </tr>
				<tr>
                    <td align="center" bgcolor="white" style="padding-top:10px">Developed and Maintainced by iSovix Technology Ltd.<br />www.isovix.com</td>
					
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