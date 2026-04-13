<?php
session_start();
include "db_connect.php";
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
} ?>





<!--save update-->

<?php
if (isset($_POST['update_user_status'])) {
    $id = $_POST['user_id'];
    $status = $_POST['account_status'];
    $conn->query("UPDATE users SET account_status = '$status' WHERE user_id='$id'");
}
if (isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $verify = $_POST['verification_status'];
    $conn->query("UPDATE users SET verification_status = '$verify' WHERE user_id='$id'");
}
?>




<!--fetch all the users-->


<?php
if (isset($_GET['user_id']) && $_GET['user_id'] != "") {
    $id = intval($_GET['user_id']);
    $sql = "SELECT users.*, police.police_id, police.badge_no, police.rank, police.station_id FROM users LEFT JOIN police ON users.user_id=police.user_id  Where users.user_id = '$id' ORDER BY users.user_id DESC";
} else {
    $sql = "SELECT users.*, police.police_id, police.badge_no, police.rank, police.station_id FROM users LEFT JOIN police ON users.user_id=police.user_id ORDER BY users.user_id DESC";
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
    <title>Manage Users</title>
</head>

<body bgcolor="#f4f6f9">
    <h2>
        <center>Manage Users</center>
    </h2>
    <h4>
        <center><a href="admin.php">Back to main menu</a></center>
    </h4>
    <form method="GET">
        Search by User ID:
        <input type="number" name="user_id">
        <input type="submit" value="Search">
        <a href="admin_users.php">Reset</a>
    </form>
    <hr>
    <?php while ($u = $result->fetch_assoc()) { ?>
        <div style="border:1px solid black; padding:10px; margin-bottom:10px;">
            <p>
            <h3>User ID: <?php echo $u['user_id']; ?> </h3>
            </p>
            <p>First Name: <?php echo $u['first_name']; ?> </p>
            <p>Last Name: <?php echo $u['last_name']; ?> </p>
            <p>Email: <?php echo $u['email']; ?> </p>
            <p>Phone: <?php echo $u['phone']; ?> </p>
            <p>NID: <?php echo $u['NID']; ?> </p>
            <?php if ($u['role'] === 'police') { ?>
                <p>Police ID: <?php echo $u['police_id']; ?> </p>
                <p>Badge Number: <?php echo $u['badge_no']; ?> </p>
                <p>Rank: <?php echo $u['rank']; ?> </p>
                <p>Station ID: <?php echo $u['station_id']; ?> </p>
            <?php } ?>
            <p>Verification: <?php echo $u['verification_status']; ?></p>
            <form method="POST">
                <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
                <select name="verification_status">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                <input type="submit" name="update_user" value="Update">
            </form>
            <p>Status: <?php echo $u['account_status']; ?></p>
            <form method="POST">
                <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
                <select name="account_status">
                    <option value="active">Active</option>
                    <option value="banned">Banned</option>
                </select>
                <input type="submit" name="update_user_status" value="Update">
            </form>
        </div>
    <?php } ?>
</body>

</html>