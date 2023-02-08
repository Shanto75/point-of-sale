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
$store_id = $_SESSION['store_id'];
	$whereclass = '';
	if(isset($_GET['from']) and strlen($_GET['from'])>0){
		$from = modifydate($_GET['from'],'-','/');
		$whereclass .= " and date >= '".$from."'";
		$f = $_GET['from'];
	}else{
		$f = '';
	}
	if(isset($_GET['to']) and strlen($_GET['to'])>0){
		$to = modifydate($_GET['to'],'-','/');
		$t = $_GET['to'];
		$whereclass .= " and date <= '".$to."'";
	}else{
		$t = '';
	}
	$sql = mysqli_query($con,"select * from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='return' $whereclass group by p_id");
	
	$totalsale = @mysqli_fetch_array(mysqli_query($con,"select sum(payable) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='return' $whereclass and count =1")); 
	$totalrec = @mysqli_fetch_array(mysqli_query($con,"select sum(payment) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='return' $whereclass and count =1")); 
	$totaldes = @mysqli_fetch_array(mysqli_query($con,"select sum(dis_amount) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='return' $whereclass and count =1")); 
	$totalout = @mysqli_fetch_array(mysqli_query($con,"select sum(balance) as total from purchase where `delete` !='1' and (type='sale' or type='wholesale') and register_mode='return' $whereclass and count =1")); 
?>
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
        <html>
        <head>
            <title>Sale Return Report</title>
            <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        </head>
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
        <body>
        <input name="print" type="button" value="Print" id="printButton" onClick="printpage()">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center">
                    <div style="width:695px" align="right">
                        <?php $shop = mysqli_fetch_array(mysqli_query($con,"select * from storeinformation where 1 and id = '$store_id'"));
                        ?>
                        <strong><?php echo ucwords($shop['name']); ?></strong><br/>
						<?php echo $shop['address'] ?><br/>
						Website<strong>:<?php echo $shop['website'] ?></strong><br>Email<strong>:<?php echo $shop['email']; ?></strong><br/>Phone
						<strong>:<?php echo $shop['phone']; ?></strong>
						<br/>
                        <?php ?>
                    </div>
                    <table width="695" border="0" cellspacing="0" cellpadding="0">

                        <tr>
                            <td height="30" align="center"><strong>Sales Return Report </strong></td>
                        </tr>
                        <tr>
                            <td height="30" align="center">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="right">
                                <table width="300" border="0" cellspacing="0" cellpadding="0">
                                    <tr>

									
                                        <td width="150"><strong>Total Sales </strong></td>
                                        <td width="150">
                                            &nbsp;<?php echo number_format((float)$totalsale['total'], 2, '.', ''); ?></td>
                                    </tr>
									<tr>
										<td width="150"><strong>Total Discount </strong></td>
                                        <td width="150">
                                            &nbsp;<?php echo number_format((float)$totaldes['total'], 2, '.', ''); ?></td>
									</tr>
                                    <tr>
                                        <td><strong>Received Amount</strong></td>
                                        <td>
                                            &nbsp;<?php echo number_format((float)$totalrec['total'], 2, '.', ''); ?></td>
                                    </tr>
                                    <tr>
                                        <td width="150"><strong>Total OutStanding </strong></td>
                                        <td width="150">
                                            &nbsp;<?php echo number_format((float)$totalout['total'], 2, '.', ''); ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td width="45">
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <td height="20">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="45"><strong>From</strong></td>
                                        <td width="393">&nbsp;<?php echo $_GET['from']; ?></td>
                                        <td width="41"><strong>To</strong></td>
                                        <td width="116">&nbsp;<?php echo $_GET['to']; ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td width="45">
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td><strong>Date</strong></td>
                                        <td><strong>Sales ID </strong></td>
                                        <td><strong>Customer</strong></td>
                                        <td><strong>Paid</strong></td>
                                        <td><strong>Disount</strong></td>
                                        <td><strong>Balance</strong></td>
                                        <td><strong>Total</strong></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <?php
                                   
                                    while ($row = mysqli_fetch_array($sql)) {
                                        $customerName = mysqli_fetch_array(mysqli_query($con, "SELECT name FROM personinformation WHERE type='customer' AND id='".$row['supplier']."'"));
                                        ?>

                                        <tr>
                                            <td><?= daterev($row['date'],'/','-') ?></td>
                                            <td><?php echo $row['p_id']; ?></td>
                                            <td><?= ucwords($customerName['name']) ?></td>
                                            <td><?php echo number_format((float)$row['payment'], 2, '.', '') ?>TK</td>
                                            <td><?php echo number_format((float)$row['dis_amount'], 2, '.', ''); ?>TK</td>
                                            <td><?php echo number_format((float)$row['balance'], 2, '.', ''); ?>TK</td>
                                            <td><?php echo number_format((float)$row['subtotal'], 2, '.', ''); ?>TK</td>
                                        </tr>


                                        <?php
                                    }
                                    ?>
                                </table>
                            </td>
							
                        </tr>
						<tr>
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td height="20px" bgcolor="#ddd" align="center"><?php include('footer_text.php'); ?></td>
									</tr>
								</table>
							</td>
						</tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        </body>
        </html>
