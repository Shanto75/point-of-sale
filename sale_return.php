<?php include("includes/header.php");?>
<style type="text/css">
	

</style>

<div class="area" >
	<div class="area">
		<div class="panel-head">Salse Return</div>
		<div class="panel"><h3>Input Invoice ID</h3>
		    <form class="sale_return_class" method="GET" action="add_sale_return.php">

			     <table >
			    	<tr>
			    		<td><input type=" text" name="inv"  required /></td>
			    		<td><input type="submit" name="invsub" value="Go"></td>
			    	</tr>
			     </table> 	
			</form>
		</div>
	</div>
</div>
	<?php include("includes/footer.php");?>