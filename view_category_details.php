<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$row = mysqli_fetch_array(mysqli_query($con,"select * from category where 1 and id = '$id'"));

if(!empty($row)){
	echo '<tr>
    	<th align="right">Category Name</th>
    	<td>'.($row['category_name']==''?'N/A':$row['category_name']).'</td>
    	</tr>
        <tr>
        <th align="right">Category Description</th>
        <td>
            '.($row['description']==''?'N/A':$row['description']).'
        </td>
    	<tr>
    	<th valign="top" style="padding-top:10px;" align="right">Comments</th>
    	<td>'.($row['comments']==''?'N/A':$row['comments']).'</td>
    	</tr>';

}


?>