<?php
session_start();
include "db_connect.php";
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body bgcolor="#f4f6f9">
    <center>
        <div class="container">
            <h1>Admin Dashboard</h1>
            <hr width="30%">
            <p>
                <a href="admin_users.php">Manage Users</a>
            </p>
            <p>
                <a href="admin_reports.php">Manage Reports</a>
            </p>
            <p>
                <a href="admin_flags.php">Flagged Reports</a>
            </p>
            <br>
            <hr width="60%">
            <p>
                <a href="view_reports.php">Back to Feed</a>
            </p>
            <p>
                <a href="logout.php">Logout</a>
            </p>
            <br>
        </div>
    </center>
</body>
</html>