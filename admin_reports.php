<?php
session_start();
include "db_connect.php";
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
} ?>





<!--save update-->

<?php
if (isset($_POST['update_report'])) {
    $id = $_POST['report_id'];
    $status = $_POST['report_status'];
    if ($_POST['report_status'] === 'rejected') {
        $conn->query("DELETE FROM reports WHERE report_id = '$id'");
    } else {
        $conn->query("UPDATE reports SET report_status = '$status' WHERE report_id='$id'");
    }
}
if (isset($_POST['update_report_visibility'])) {
    $id = $_POST['report_id'];
    $visibility = $_POST['visibility'];
    $conn->query("UPDATE reports SET visibility = '$visibility' WHERE report_id='$id'");
}
?>




<!--fetch all the reports-->


<?php

if (isset($_GET['report_id']) && $_GET['report_id'] != "") {
    $id = intval($_GET['report_id']);
    $sql = "SELECT reports.*, locations.district, locations.division, locations.area , crime_categories.category_name  
FROM reports 
JOIN locations ON reports.location_id=locations.location_id 
JOIN report_category ON report_category.report_id=reports.report_id
JOIN crime_categories ON crime_categories.category_id=report_category.category_id 
WHERE reports.report_id = '$id'
ORDER BY reports.report_id DESC";
} else {
    $sql = "SELECT reports.*, locations.district, locations.division, locations.area , crime_categories.category_name  
FROM reports 
JOIN locations ON reports.location_id=locations.location_id 
JOIN report_category ON report_category.report_id=reports.report_id
JOIN crime_categories ON crime_categories.category_id=report_category.category_id 
ORDER BY reports.report_id DESC";
}
$result = $conn->query($sql);
?>



<!--view box for admin-->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reports</title>
</head>

<body bgcolor="#f4f6f9">
    <h2>
        <center>Manage Reports</center>
    </h2>
    <h4>
        <center><a href="admin.php">Back to main menu</a></center>
    </h4>




    <form method="GET">
        Search by Report ID:
        <input type="number" name="report_id">
        <input type="submit" value="Search">
        <a href="admin_reports.php">Reset</a>
    </form>

    <hr>
    <?php


    //manual group by for categories for each report


    $reports = [];
    while ($r = $result->fetch_assoc()) {
        $id = $r['report_id'];
        if (!isset($reports[$id])) {
            $reports[$id] = [
                'report_id' => $r['report_id'],
                'user_id' => $r['user_id'],
                'description' => $r['description'],
                'submission_date' => $r['submission_date'],
                'area' => $r['area'],
                'district' => $r['district'],
                'division' => $r['division'],
                'report_status' => $r['report_status'],
                'visibility' => $r['visibility'],
                'categories' => []
            ];
        }
        if ($r['category_name']) {
            $reports[$id]['categories'][] = $r['category_name'];
        }
    }
    ?>
    <?php foreach ($reports as $report) { ?>
        <div style="border:1px solid black; padding:10px; margin-bottom:10px;">
            <p>
            <h3>Report ID: <?php echo $report['report_id']; ?> </h3>
            </p>
            <p>User ID: <?php echo $report['user_id']; ?> </p>
            <p>Description: <?php echo $report['description']; ?> </p>
            <p>Location: <?php echo $report['area'] . ", " . $report['division'] . ", " . $report['district']; ?> </p>
            <p>Category: <?php echo implode(", ", $report['categories']); ?> </p>
            <p>Submission Date: <?php echo $report['submission_date']; ?> </p>
            <p>Report Status: <?php echo $report['report_status']; ?> </p>
            <form method="POST">
                <input type="hidden" name="report_id" value="<?php echo $report['report_id']; ?>">
                <select name="report_status">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                <input type="submit" name="update_report" value="Update">
            </form>
            <p>Visibility: <?php echo $report['visibility']; ?></p>
            <form method="POST">
                <input type="hidden" name="report_id" value="<?php echo $report['report_id']; ?>">
                <select name="visibility">
                    <option value="visible">Visible</option>
                    <option value="hidden">Hidden</option>
                </select>
                <input type="submit" name="update_report_visibility" value="Update">
            </form>
        </div>
    <?php } ?>
</body>

</html>