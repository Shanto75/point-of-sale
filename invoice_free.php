<?php include("includes/header.php");?>

<div class="area" >
	<div class="panel-head">Chalan</div>
	<div class="panel fix">
	<form action="chalan.php" method="get">
		<table>
			<tr>
				<td>Invoice Number</td>
				<td><input type="text" name="inv" value="" required/></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="submit" value="Go" /></td>
			</tr>
		</table>
	</form>
</div>	
</div>
	<?php include("includes/footer.php");?>