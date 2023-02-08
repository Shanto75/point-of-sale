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
	if(isset($_GET['customer']) and strlen($_GET['customer'])>0){
		$whereclass .= " and supplier = '".$_GET['customer']."'";
		$c = $_GET['customer'];
	}else{
		$c = '';
	}
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
	$sql = mysqli_query($con,"SELECT `supplier`, sum(`payment`)as payment,  sum(`dis_amount`)as dis_amount, sum(`payable`)as payable, sum(balance)as balance  FROM `purchase` WHERE 1 and type='sale' and register_mode='sale' and count='1' $whereclass group by supplier");
?>
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
        <html>
        <head>
            <title>Sale Report</title>
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
                            <td height="30" align="center"><strong>Sales Report </strong></td>
                        </tr>
                        <tr>
                            <td height="30" align="center">&nbsp;</td>
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
										<td width="41"><strong>Customer</strong></td>
                                        <td width="116">&nbsp;<?php echo $_GET['customer']==''?'All':$_GET['customer']; ?></td>
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
										<th>Customer Name</th>
										<th>Total Cost</th>
										<th>Total Paid</th>            
										<th>Total Due</th>            
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
												<td><?= ucwords($customerName['name']) ?></td>
												<td><?= number_format((float)$row['payable'], 2, '.', '') ?></td>
												<td><?= number_format((float)$row['payment'], 2, '.', '') ?></td>
												<td><?= number_format((float)$row['balance'], 2, '.', '') ?></td>
											 
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
