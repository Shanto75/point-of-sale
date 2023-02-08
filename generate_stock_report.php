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

	if(isset($_GET['stock_filter']) and $_GET['stock_filter']!=''){
		$whereclass .= " and parent = '".$_GET['stock_filter']."'";
	}
	
	$sql = mysqli_query($con,"SELECT * FROM `product_details` WHERE 1 order by name asc");
	
?>
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/html4/loose.dtd">
        <html>
        <head>
            <title>Stock Report</title>
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
                    <div style="width:100%" align="right">
                        <?php $shop = mysqli_fetch_array(mysqli_query($con,"select * from storeinformation where 1 and id = '$store_id'"));
                        ?>
                        <strong><?php echo ucwords($shop['name']); ?></strong><br/>
						<?php echo $shop['address'] ?><br/>
						Website<strong>:<?php echo $shop['website'] ?></strong><br>Email<strong>:<?php echo $shop['email']; ?></strong><br/>Phone
						<strong>:<?php echo $shop['phone']; ?></strong>
						<br/>
                        <?php ?>
                    </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>
                            <td height="30" align="center"><strong>Stock Report </strong></td>
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
                            <td width="45">
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="1" cellspacing="0" cellpadding="0">
                                    <tr>
                                       <th>UPC/EAN/ISBN</th>
										<th>Product Name</th>
										<th>Stock Name</th>
										<th>Category Name</th>
										<th>Retail Price</th>
										<th>Wholesale Price</th>
										<th>Purchase Price</th>
										<th>Stock Amount</th> 
										<th>Total Price</th>
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
								$stock = array();
								$retail = array();
								while($row = mysqli_fetch_array($sql)){
									$stock = mysqli_fetch_array(mysqli_query($con,"select * from stock where 1 and product_name = '".$row['name']."' and code = '".$row['product_code']."'"));
		
									$st = @mysqli_fetch_array(@mysqli_query($con,"SELECT * FROM `stock_manager` WHERE 1 and `id` = '".$stock['parent']."'"));
									$stock_amount = $stock['quantity'];
									
									$stock[] = $stock_amount;
									$retail[] = $row['purchase_cost']*$stock_amount;
									
							?>
								<tr>
									<td><?= ($row['product_code']) ?></td>
			
									<td><?= ucwords($row['name']) ?></td>
									<td><?= ucwords($st['name']) ?></td>
									<td><?= ucwords($row['category']) ?></td>
									<td><?= ($row['sale_price']) ?></td>
									<td><?= ($row['wholesale_price']) ?></td>
									<td><?= ($row['purchase_cost']) ?></td>

									<td><?= $stock_amount ?></td>
									<td align="center"><?= ($row['purchase_cost']*$stock_amount) ?></td>
										
								</tr>
								<?php } ?>
								<tfoot>
									<tr>
										<th>UPC/EAN/ISBN</th>
										<th>Product Name</th>
										<th>Stock Name</th>
										<th>Category Name</th>
										<th>Retail Price</th>
										<th>Wholesale Price</th>
										<th>Purchase Price </th>
										<th>Stock Amount</th>   
										<th>Total Price <?= array_sum($retail) ?> TK</th>   
                                    </tr>
								</tfoot>
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
