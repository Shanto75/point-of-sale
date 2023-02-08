<?php
include('includes/config.php');
include('includes/function.php');
$id = $_POST['productid'];
$whereclass = '';
$whereclass .= " and date >= '".$id."'";
$whereclass .= " and date <= '".$id."'";
$sql = mysqli_query($con,"SELECT `date`, `earning_head`, amount FROM `expense` WHERE 1 $whereclass");

$data = "";
$data .= "<tr>
				<th>Date</th>            				
				<th>Head</th>
				<th>Amount</th>				
				
		
		</tr>";
while($row = mysqli_fetch_array($sql)){

	$data .= "<tr>
			<td border='1'>".$row['date']."</td>
			<td>".ucwords($row['earning_head'])."</td>
			<td>".$row['amount']."</td>
			
			
			
			
			</tr>";
}
echo $data;


?>