<?php
session_start();
include "db_connect.php";
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
} ?>





<!--save update-->

<?php
if (isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $status = $_POST['account_status'];
    $conn->query("UPDATE users SET account_status= '$status' WHERE user_id='$id'");
}
if (isset($_POST['update_report'])) {
    $id = $_POST['report_id'];
    $visibility = $_POST['visibility'];
    $conn->query("UPDATE reports SET visibility = '$visibility' WHERE report_id='$id'");
}
if (isset($_POST['update_flag'])) {
    $id = $_POST['flag_id'];
    $status = $_POST['flag_status'];
    $conn->query("UPDATE report_flags SET flag_status= '$status' WHERE flag_id='$id'");
}
?>




<!--fetch all the reports by users-->


<?php
$sql = "SELECT report_flags.flag_id, report_flags.report_id, report_flags.reason, report_flags.flag_status, report_flags.flag_date , reports.*, users.*
FROM report_flags
JOIN reports ON reports.report_id=report_flags.report_id
JOIN  users ON reports.user_id=users.user_id
ORDER BY report_flags.flag_id DESC";
$result = $conn->query($sql);
?>



<!--view box for admin-->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flag Reports</title>
</head>
<body bgcolor="#f4f6f9">
    <h2>
        <center>Flagged Reports</center>
    </h2>
    <h4><center><a href="admin.php">Back to main menu</a></center></h4>


<!--manual group by for categories for each report-->

<?php while($f = $result->fetch_assoc()){ ?>
<div style="border:1px solid black; padding:10px; margin-bottom:10px;">
<p><h3>Flag ID: <?php echo $f['flag_id']; ?> </h3></p>
<p>Report ID: <?php echo $f['report_id']; ?> </p>
<p>User ID : <?php echo $f['user_id']; ?> </p>
<p>Reason: <?php echo $f['reason']; ?> </p>
<p>Flag Date: <?php echo $f['flag_date']; ?> </p>
 

<p>Flag Status: <?php echo $f['flag_status']; ?> </p>
<form method="POST">
<input type="hidden" name="flag_id" value="<?php echo $f['flag_id']; ?>">
<select name="flag_status">
<option value="pending">Pending</option>
<option value="reviewed">Reviewed</option>
</select>
<input type="submit" name="update_flag" value="Update">
</form>




<p>Report Visibility: <?php echo $f['visibility']; ?> </p>
<form method="POST">
<input type="hidden" name="report_id" value="<?php echo $f['report_id']; ?>">
<select name="visibility">
<option value="visible">Visible</option>
<option value="hidden">Hidden</option>
</select>
<input type="submit" name="update_report" value="Update">
</form>



<p>User Status: <?php echo $f['account_status']; ?> </p>
<form method="POST">
<input type="hidden" name="user_id" value="<?php echo $f['user_id']; ?>">
<select name="account_status">
<option value="active">Active</option>
<option value="Banned">Banned</option>
</select>
<input type="submit" name="update_user" value="Update">
</form>
</div>
<?php } ?>
</body>
</html>