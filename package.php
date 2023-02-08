<?php include("includes/header.php");?>

	<div class="area">
		<div class="panel-head">Package Details</div>
		<div class="panel">
		<div class="btn">	
		<a href="javascript:;"id="modal-launcher" class="btn-add">+ Add Package</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add a Package<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Package Name</td>
    	<td><input type="text" name="shopname" placeholder="Enter Shop Name"></td>
    	</tr>
        <tr>
        <td valign="top" style="padding-top:20px;" align="right">Product List</td>
        <td><textarea placeholder="Enter Shop Address" rows="5" cols="20"></textarea></td>
        </tr>
    	<tr>
    	<td align="right">Retail Price</td>
    	<td><input type="text" name="shopname" placeholder="Enter Shop Name"></td>
    	</tr>
    	<tr>
    	<td align="right">Wholesale Price</td>
    	<td><input type="text" name="shopname" placeholder="Enter Shop Email"></td>
    	</tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right">Description</td>
    	<td><textarea placeholder="Enter Shop Address" rows="5" cols="20"></textarea></td>
    	</tr>
        <tr>
        <td align="right">Vat</td>
        <td><input type="text" name="shopname" placeholder="Enter Shop Email"></td>
        </tr>
        <tr>
        <td align="right">Warranty</td>
        <td><input type="text" name="shopname" placeholder="Enter Shop Email"></td>
        </tr>
        <tr>
        <td align="right">Expirey Date</td>
        <td><input type="text" name="shopname" placeholder="Enter Shop Email"></td>
        </tr>
    	<tr>
    	<td valign="top" style="padding-top:20px;" align="right"> Comments</td>
    	<td><textarea placeholder="Enter Comments" rows="5" cols="20"></textarea></td>
    	</tr>
    	<tr>
    	
    	<td colspan="2" align="right"><input type="submit" value="Go"></td>
    	</tr>
    	</table>
    	</form>
    </div>
</div>
<!--View-->
<div id="modal2-background"></div>
<div id="modal2-content">
    <button id="modal2-close">Close Modal Window</button>
    <div class="modal-form">
    	View Package Details<hr/>
    	
    	<table class="tab">
    	<tr>
    	<td align="right">Package Name</td>
    	<td>Demo Shop Name</td>
    	</tr>
    	<tr>
    	<td valign="top" style="padding-top:15px;" align="right">Product List</td>
    	<td>Demo Shop Address</td>
    	</tr>
    	<tr>
    	<td align="right">Retail Price</td>
    	<td>Demo Shop Website</td>
    	</tr>
    	<tr>
    	<td align="right">Wholesale Price</td>
    	<td>Demo Shop Email</td>
    	</tr>
        <tr>
        <td valign="top" style="padding-top:15px;" align="right">Description</td>
        <td>Demo Shop Comments</td>
        </tr>
        <tr>
        <td align="right">Vat</td>
        <td>Demo Shop Email</td>
        </tr>
        <tr>
        <td align="right">Warranty</td>
        <td>Demo Shop Email</td>
        </tr>
        <tr>
        <td align="right">Expiery Date</td>
        <td>Demo Shop Email</td>
        </tr>
    	<tr>
    	<td valign="top" style="padding-top:15px;" align="right">Comments</td>
    	<td>Demo Shop Comments</td>
    	</tr>
    	<tr>
    	
    	<td colspan="2" align="right"></td>
    	</tr>
    	</table>
    	
    </div>
</div>
<!--Edit-->
<div id="modal3-background"></div>
<div id="modal3-content">
    <button id="modal3-close">Close Window</button>
    <div class="modalform">
    	Edit a Package<hr/>
    	<form action="" method="post" class="form">
        <table class="tab">
        <tr>
        <td align="right">Package Name</td>
        <td><input type="text" name="shopname" placeholder="Enter Shop Name"></td>
        </tr>
        <tr>
        <td valign="top" style="padding-top:20px;" align="right">Product List</td>
        <td><textarea placeholder="Enter Shop Address" rows="5" cols="20"></textarea></td>
        </tr>
        <tr>
        <td align="right">Retail Price</td>
        <td><input type="text" name="shopname" placeholder="Enter Shop Name"></td>
        </tr>
        <tr>
        <td align="right">Wholesale Price</td>
        <td><input type="text" name="shopname" placeholder="Enter Shop Email"></td>
        </tr>
        <tr>
        <td valign="top" style="padding-top:20px;" align="right">Description</td>
        <td><textarea placeholder="Enter Shop Address" rows="5" cols="20"></textarea></td>
        </tr>
        <tr>
        <td align="right">Vat</td>
        <td><input type="text" name="shopname" placeholder="Enter Shop Email"></td>
        </tr>
        <tr>
        <td align="right">Warranty</td>
        <td><input type="text" name="shopname" placeholder="Enter Shop Email"></td>
        </tr>
        <tr>
        <td align="right">Expirey Date</td>
        <td><input type="text" name="shopname" placeholder="Enter Shop Email"></td>
        </tr>
        <tr>
        <td valign="top" style="padding-top:20px;" align="right"> Comments</td>
        <td><textarea placeholder="Enter Comments" rows="5" cols="20"></textarea></td>
        </tr>
        <tr>
        
        <td colspan="2" align="right"><input type="submit" value="Go"></td>
        </tr>
        </table>
        </form>
    </div>
</div>

		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Package Name</th>
            <th>Retail Price</th>
            <th>Wholesale Price</th>
            <th>Warranty</th>
            <th>Expiery Date</th>
            
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <tr>
    		
       		<td>Demo Name</td>
            <td>Demo Phone</td>
            <td>Demo Email</td>
         <td>Demo Email</td>
            <td>Demo Address</td>
            <td>
            <a href="javascript:;"id="modal2-launcher" class="view">View</a></div>
            <a href="javascript:;"id="modal3-launcher" class="edit">Edit</a></div>
            <a href="javascript:;"id="" class="delete">Delete</a></div>
            </td>
    </tr>
     <tr>
       		
            <td>Demo Name</td>
            <td>Demo Phone</td>
            <td>Demo Email</td>
            <td>Demo Email</td>
            <td>Demo Address</td>
            <td>
            <a href="javascript:;"id="modal2-launcher" class="view">View</a></div>
            <a href="javascript:;"id="modal3-launcher" class="edit">Edit</a></div>
            <a href="javascript:;"id="" class="delete">Delete</a></div>
            </td>
    </tr>
    </tbody>
</table>
		</div>
		</div>
		</div>
		</div>
	<script type="text/javascript">
	$(document).ready( function () {
    $('#table_id').DataTable();
} );
	</script>
	<?php include("includes/footer.php");?>
</body>
</html>