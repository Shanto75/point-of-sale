<?php include("includes/header.php");?>

	<script type="text/javascript">
/*function setSelectedValue(selectObj, valueToSet) {
    for (var i = 0; i < selectObj.options.length; i++) {
        if (selectObj.options[i].text== valueToSet) {
            selectObj.options[i].selected = true;
            return;
        }
    }
}
function checkproductdetails(val){
	
	$.post('check_product_details.php', {productid: val},
	function (data) {
		
		$("#name").val(data.name);
		$("#status").val(data.status);
	}, 'json');
	
	$('#hidden_field').html('<input type="hidden" name="id" value='+val+' />');
}
function emptyform(){
		$("#name").val('');
		$("#status").val('');
		$("#hidden_field").html('');

}
*/
function handleclick(obj){
	var val = obj.value;
	if(val=='input'){
		$("#take_value").html('');
		$('#take_value').css('display','none');
	}else if(val=='radio' || val=='checkbox' || val=='select'){
		
		$("#take_value").html('');
		$('#take_value').css('display','block');
		$('<table><tr><td>Value1<td><td><input type="text" name="value1"/></td></tr><tr><td>Value2<td><td><input type="text" name="value2"/></td></tr><tr><td>Value3<td><td><input type="text" name="value3"/></td></tr><tr><td>Value4<td><td><input type="text" name="value4"/></td></tr><tr><td>Value5<td><td><input type="text" name="value5"/></td></tr></table>').fadeIn("slow").appendTo('#take_value');
	}
}
</script>
<?php 
$msg = '';
if(isset($_POST['key']) and strlen($_POST['key'])>0){
	$key = $_POST['key'];
	$title = $_POST['title'];
	
	$type = $_POST['type'];
	if($type!='input'){
		$value1= $_POST['value1'];
		$value2= $_POST['value2'];
		$value3= $_POST['value3'];
		$value4= $_POST['value4'];
		$value5= $_POST['value5'];
		@mysqli_query($con,"INSERT INTO `custom_field`(`key`, `type`, `name`, `title`, `value1`, `value2`, `value3`, `value4`, `value5`) VALUES ('$key','$type','$key','$title','$value1','$value2','$value3','$value4','$value5')");
		$msg = 'Data has been inserted!';
	}else{
		@mysqli_query($con,"INSERT INTO `custom_field`(`key`, `type`, `name`, `title`) VALUES ('$key','$type','$key','$title')");
		$msg = 'Data has been inserted!';
	}
}

$sql = mysqli_query($con,"select * from custom_field where 1");

if(isset($_GET['del']) and $_GET['id']!=''){
	$ids = $_GET['id'];
	@mysqli_query($con,"DELETE FROM `custom_field` WHERE 1 and id = '$ids'");
	$msg = 'Data deleted successfully';
}

?>
	<div class="area">
		<div class="panel-head">Dynamic Field</div>
		<div class="panel">
				<?php 
					if($msg!=''){
						echo '<p style="color: #4F8A10;
    background-color: #DFF2BF;border:1px solid #ddd;padding:10px;border-radius:5px;text-align:center;font-size:15px;">'.$msg.'</p>';
					}
				?>
		<div class="btn">	
		<a onclick="return emptyform();" href="javascript:;"id="modal-launcher" class="btn-add">+ Add Field</a></div>
	<!--Add-->	
		<div id="modal-background"></div>
<div id="modal-content">
    <button id="modal-close">Close Window</button>
    <div class="modalform">
    	Add an Inactive Product<hr/>
    	<form action="" method="post" class="form">
    	<table class="tab">
    	<tr>
    	<td align="right">Select a custom field</td>
    	<td><select name="key" required>
		<option>Please select</option>
			<?php 
				$arr = array('custom1','custom2','custom3','custom4','custom5');
				for($i=0;$i<sizeof($arr);$i++){
					$ck = @mysqli_fetch_array(@mysqli_query($con,"select * from custom_field where `key` = '".$arr[$i]."'"));
					
					if(empty($ck)){
						echo '<option value="'.$arr[$i].'">'.$arr[$i].'</option>';
					}
				}
			?>
			</select>
		</td>
    	</tr>
		
    	<tr>
        <td valign="top">Title</td>
        <td>
            <input type="text" name="title" id="title"/>
        </td>
        </tr>
		
		<tr>
        <td valign="top">Type</td>
        <td>
            <input type="radio" onclick="handleclick(this)" name="type" id="type" value="input"/>&nbsp;&nbsp;Text&nbsp;&nbsp;<input onclick="handleclick(this)" type="radio" name="type" id="type" value="radio"/>&nbsp;&nbsp;Radio&nbsp;&nbsp;<input onclick="handleclick(this)" type="radio" name="type" id="type" value="checkbox"/>&nbsp;&nbsp;Checkbox&nbsp;&nbsp;<input onclick="handleclick(this)" type="radio" name="type" id="type" value="select"/>&nbsp;&nbsp;Select<br />
			<div style="margin-top:20px;display:none" id="take_value">
			
			</div>
        </td>
        </tr>
		
    	
    	<td colspan="2" align="right"><input type="submit" value="Go"></td>
    	</tr>
    	</table>
    	</form>
    </div>
</div>

		<table id="table_id" class="display">
    <thead>
        <tr>
            <th>Field No</th>
            <th>Title</th>
            <th>Type</th>
           
            <th>Value</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
<?php 
	while($row = @mysqli_fetch_array($sql)){
		$val = ($row['value1']==''?'':$row['value1'].',').($row['value2']==''?'':$row['value2'].',').($row['value3']==''?'':$row['value3'].',').($row['value4']==''?'':$row['value4'].',').($row['value5']==''?'':$row['value5'].',')
		?> 
    <tr>
    		
       		<td><?= ($row['key']); ?></td>
       		<td><?= ($row['title']); ?></td>
       		<td><?= ($row['type']); ?></td>
       		<td><?= ($val); ?></td>
            
            <td>
           
            <a href="dynamic_field.php?id=<?= $row['id'] ?>&del=del" class="delete">Delete</a></div>
            
            </td>
    </tr>
	<?php } ?>

    </tbody>
</table>
		</div>
		</div>
		</div>
		</div>
<?php 
	include("includes/footer.php");

?>