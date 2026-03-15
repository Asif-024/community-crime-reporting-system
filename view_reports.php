<?php
include "db_connect.php";
$sql = "SELECT * FROM reports";
$result = $conn -> query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>View Reports</title>
</head>
<body>
<h1>All Crime Reports</h1>
<table border = "1" >
<tr>
<th>Report ID</th>
<th>Description</th>
<th>Date</th>
</tr>
<?php   
while($row = $result->fetch_assoc())
    { ?>
    <tr> <td> <?php echo $row['report_id']?></td>
    <td> <?php echo $row['description']?></td>
    <td> <?php echo $row['submission_date']?></td>
    </tr>
<?php    }
?>
</table>
</body>
</html>