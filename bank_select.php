<?php 
include('includes/config.php');
?>
<select name="bank_name">
<?php
	$bquery = mysqli_query($con,"select * from bankinformation where person_type = 'owner'");
	while($br= mysqli_fetch_array($bquery)){
		echo '<option value="'.$br['id'].'">'.$br['bankname'].' ('.$br['accountnumber'].') </option>';
	}
?>
</select>
