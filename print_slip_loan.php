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
if(isset($_GET['data']))
{
	$store_id = $_SESSION['store_id'];
$data= urldecode($_GET['data']);
$data = unserialize($data);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <title><?= $data['slip'] ?></title>
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
<div id="printButton" style="text-align:right;margin:50px auto;width:280px">
<?php 
if($data['slip']=='Bank Load Payment'){
	echo '<a href="paybankloan.php">Cancel</a>';
}else{
	echo '<a href="paypersonalloan.php">Cancel</a>';
}
?>

<input name="print" type="button" value="Print"  onClick="printpage()">
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" valign="top">

            <table width="695" cellspacing="0" cellpadding="0" id="bordertable" border="1">
                <tr>
                    <td align="center"><h2><?= $data['slip'] ?></h2>
                        </strong>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>

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
						$nam = mysqli_fetch_array(mysqli_query($con,"select * from loan where id = '".$data['pay_to']."'"));
						if($data['slip']=='Bank Load Payment'){
							$nnma = $nam['bank_name'];
						}else{
							$nnma = $nam['name'];
						}						
							if($nnma!=''){
						?>
                            <tr>
                                <td width="5%" align="left" valign="top"><strong>&nbsp;&nbsp;TO:</strong></td>
                                <td width="95%" align="left" valign="top"><br/>
                                    <?php
                                    echo "Name: " . $nnma.'<br/>';
                                    
									
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
                                
                                <td  align="center" bgcolor="#CCCCCC">Date</td>
                                <td  align="center" bgcolor="#CCCCCC">Payment Amount</td>
                               
                            </tr>
                            <tr>
                                <td align="center">&nbsp;</td>
                                <td>&nbsp;</td>
                                
                               
                            </tr>
                            <?php

                                ?>
                                <tr>
                                    <td align="center"><?php echo $data['date'].' TK'; ?></td>
                                    <td align="center"><?php echo $data['payment'].' TK'; ?></td>
                                    
                                    
                                </tr>
                                
                            <tr>
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
                                <td width="62%" align="right" bgcolor="#CCCCCC"><strong>Pay Amount:&nbsp;&nbsp;</strong>
                                </td>
                                <td width="28%" bgcolor="#CCCCCC"><?php echo $data['payment'].' TK' ?>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="45%" align="left" valign="top"><br/>

									&nbsp;&nbsp;Payment Method :&nbsp;&nbsp;<strong>Cash</strong><br/>
									
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
                    <td align="center" bgcolor="#CCCCCC">
					
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