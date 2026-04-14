<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}
$user_id = $_SESSION['user_id'];




// update report


if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['update'])) {
    $id = intval($_POST['report_id']);
    $desc = $conn->real_escape_string($_POST['new_description']);
    $conn->query("UPDATE reports SET description = '$desc' WHERE report_id = '$id'");
}

// delete report


if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delete'])) {
    $id = intval($_POST['report_id']);
    $conn->query("UPDATE reports SET visibility = 'hidden' WHERE report_id = '$id'");
}


//fetching law enforcement status


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    if ($_SESSION['role'] === 'police') {
        $id = intval($_POST['report_id']);
        $status = $conn->real_escape_string($_POST['status']);
        $conn->query("UPDATE reports SET police_status = '$status' WHERE report_id = $id");
    }
}



//fetching law enforcement flagging status


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['flag_report'])) {
    if ($_SESSION['role'] === 'police') {
        $id = intval($_POST['report_id']);
        $reason = $conn->real_escape_string($_POST['reason']);
        $conn->query("INSERT INTO report_flags(report_id, user_id, reason, flag_date) VALUES($id, $user_id, '$reason', NOW()) ON DUPLICATE KEY UPDATE reason = '$reason', flag_date = NOW()");
    }
}




//store a new credibility vote


if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['vote'])) {
    $report_id = intval($_POST['report_id']);
    $vote = $_POST['vote'];
    $sql = "INSERT INTO report_credibility (report_id, user_id, credibility_value) VALUES ('$report_id', '$user_id', '$vote')
        ON DUPLICATE KEY UPDATE credibility_value='$vote'";
    $conn->query($sql);
}



//store a new comment


if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['comment_text'])) {
    $report_id = intval($_POST['report_id']);
    $comment_text = $_POST['comment_text'];
    $sql = "INSERT INTO comments (report_id, user_id, comment_text, comment_date) VALUES('$report_id', '$user_id', '$comment_text', NOW())";
    $conn->query($sql);
}



//load reports


$sql = "SELECT * FROM reports WHERE visibility = 'visible' AND report_status = 'approved'";
// Apply category filter
if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);
    $sql .= " AND report_id IN (SELECT report_id from report_category WHERE category_id = $category_id) ";
}



// Apply location filter


if (isset($_GET['location_id']) && !empty($_GET['location_id'])) {
    $location_id = intval($_GET['location_id']);
    $sql .= " AND location_id = $location_id";
}
$sql .= " ORDER BY submission_date DESC";
$result = $conn->query($sql);



//save report



if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit_report'])) {
    $category_id = intval($_POST['category_id']);
    $location_id = intval($_POST['location_id']);
    $description = $conn->real_escape_string($_POST['description']);
    $sql = "INSERT INTO reports(user_id, location_id, description, submission_date) VALUES('$user_id', '$location_id', '$description', NOW())";
    if ($conn->query($sql)) {
        $report_id = $conn->insert_id;
        foreach ($_POST['category_id'] as $category_id) {
            $cat_id = intval($category_id);
            $conn->query("INSERT INTO report_category(report_id, category_id) VALUES($report_id,$cat_id)");
        }
        $_SESSION['success'] = "Report submitted";
        header("Location: view_reports.php");
        exit();
    } else {
        $_SESSION['error'] = $conn->error;
        header("Location: view_reports.php");
        exit();
    }
}


//report submitted/ didnt



//load locations for dropdown
$location_sql = "SELECT * FROM locations ORDER BY district, area";
$location_result = $conn->query($location_sql);



//load crime categories for dropdown
$category_sql = "SELECT * FROM crime_categories ORDER BY category_name";
$category_result = $conn->query($category_sql);
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
</head>

<body bgcolor="#f4f6f9">



    <!--welcome logout-->

    <center>
        <h1>Welcome <?php echo $_SESSION['name']; ?></h1>
        <a href="logout.php">Logout</a>
    </center>
    <hr>




    <!--submit a report-->


    <h2>Submit a Crime Report</h2>
    <div style="border: 1px solid black; padding:15px; margin-bottom:20px;">
        <?php


        //report submitted/ didnt


        if (isset($_SESSION['success'])) {
            echo "<h2 style='color:green;'>" . $_SESSION['success'] . "</h2>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<h2 style='color:red;'>Error: " . $_SESSION['error'] . "</h2>";
            unset($_SESSION['error']);
        } ?>

        <form method="POST">
            <input type="hidden" name="submit_report" value="1">

            Crime category: <br>
            <div style="border:1.5px solid #ccc; padding:10px; width:250px;">
                <?php while ($cri = $category_result->fetch_assoc()) { ?>
                    <input type="checkbox" name="category_id[]" value="<?php echo $cri['category_id']; ?>">
                    <?php echo $cri['category_name']; ?><br>
                <?php } ?>
            </div>
            <br>
            location: <br>
            <select name="location_id" required>
                <option value="">Select location</option>
                <?php while ($loc = $location_result->fetch_assoc()) {
                ?>
                    <option value="<?php echo $loc['location_id']; ?>"> <?php echo $loc['district'] . ", " . $loc['area'] . " (" . $loc['division'] . ") "; ?> </option>
                <?php } ?>
            </select><br><br>
            Description: <br>
            <textarea name="description" placeholder="Describe the crime..." required></textarea><br><br>
            <input type="submit" value="Submit report">
        </form>
    </div>
    <hr>



    <!--filteration-->


    <h3>Filter reports: </h3> <br>
    <form method="GET" action="view_reports.php">
        Crime category:
        <select name="category_id">
            <option value="">All categories</option>
            <?php
            $category_result->data_seek(0);
            while ($cri = $category_result->fetch_assoc()) { ?>
                <option value=" <?php echo $cri['category_id']; ?> ">
                    <?php if (isset($_GET['category_id']) && $_GET['category_id'] == $cri['category_id']) echo "Selected"; ?>
                    <?php echo $cri['category_name']; ?>
                </option>
            <?php } ?>
        </select>
        Location :
        <select name="location_id">
            <option value="">All locations</option>
            <?php
            $location_result->data_seek(0);
            while ($loc = $location_result->fetch_assoc()) { ?>
                <option value=" <?php echo $loc['location_id']; ?> ">
                    <?php if (isset($_GET['location_id']) && $_GET['location_id'] == $loc['location_id']) echo "Selected"; ?>
                    <?php echo $loc['district'] . ", " . $loc['area']; ?>
                </option>
            <?php } ?>
        </select>
        <input type="submit" value="Fliter">
    </form>
    <hr>



    <!--view all reports-->


    <h1>All Crime Reports</h1>
    <?php
    while ($row = $result->fetch_assoc()) {



        //load credibility count



        $report_id = intval($row['report_id']);
        $vote_sql = "SELECT credibility_value, COUNT(*) AS count FROM report_credibility WHERE 
            report_id = $report_id GROUP BY credibility_value";
        $vote_result = $conn->query($vote_sql);
        $valid_count = 0;
        $invalid_count = 0;
        while ($v = $vote_result->fetch_assoc()) {
            if ($v['credibility_value'] === 'valid')
                $valid_count = $v['count'];
            if ($v['credibility_value'] === 'invalid')
                $invalid_count = $v['count'];
        } ?>





        <!--view box of reports-->



        <div style="border: 1px solid black; padding:15px; margin-bottom:20px;">
            <h3> Report ID: <?php echo $row['report_id']; ?></h3>
            <p>User:
                <?php $user_sql = "SELECT users.first_name AS first_name, users.user_id AS user_id FROM users JOIN reports ON users.user_id=reports.user_id where report_id=$report_id";
                $first_conn = $conn->query($user_sql);
                $first = $first_conn->fetch_assoc();
                echo $first['first_name'] . $first['user_id']; ?> </p>
            <p>Category:
                <?php
                $category_sql = "SELECT category_name FROM crime_categories WHERE category_id IN (SELECT category_id FROM report_category WHERE report_id=$report_id)";
                $cat_conn = $conn->query($category_sql);
                while ($cat_name = $cat_conn->fetch_assoc()) {
                    echo $cat_name['category_name'] . " ,";
                }
                ?>
            </p>
            <p>
                Location:
                <?php $location_sql = "SELECT locations.district, locations.division, locations.area FROM locations JOIN reports ON locations.location_id=reports.location_id WHERE report_id=$report_id";
                $loc_conn = $conn->query($location_sql);
                $loc_name = $loc_conn->fetch_assoc();
                echo $loc_name['area'] . ", " . $loc_name['division'] . ", " . $loc_name['district'] . ".";
                ?> </p>
            <p><b>Description: </b> <?php echo $row['description']; ?></p>
            <p><b>Date: </b> <?php echo $row['submission_date']; ?></p>
            <p><b>Status: <?php echo $row['police_status'];  ?> </b></p>

            <!--law enforcement pannel-->


            <p><?php if ($_SESSION['role'] === 'police') { ?>
            <form method="POST">
                <input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
                <select name="status">
                    <option value="" disabled selected>Select a status</option>
                    <option value="investigating">Investigating</option>
                    <option value="verified">Verified</option>
                    <option value="resolved">Resolved</option>
                    <option value="false">false</option>
                </select>
                <input type="submit" name="update_status" value="Update status">
            </form>
        <?php } ?> </p>



        <!--show & form credibility count-->

        <form action="view_reports.php" style="margin-bottom:10px;" method="POST">
            <input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
            <button type="submit" name="vote" value="valid"> Valid - <?php echo $valid_count; ?></button>
            <button type="submit" name="vote" value="invalid"> Invalid - <?php echo $invalid_count; ?></button>
        </form>
        <hr>






        <!--report flagging-->



        <?php if ($_SESSION['role'] === 'police') {
            $flag_check = $conn->query("SELECT * FROM report_flags WHERE report_id = $report_id AND user_id= $user_id");
            if ($flag_check->num_rows == 0) { ?>
                Flag the Report:
                <form method="POST" style="margin-top:5px;">
                    <input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
                    <textarea name="reason" placeholder="Reason.." required></textarea><br>
                    <p><button type="submit" name="flag_report"> Flag for admin </button></p>
                </form>
                <hr>
        <?php } else {
                echo "<p> <b>Already Flagged</b></p>";
            }
        } ?>



        <!--update or delete report-->



        <?php if ($user_id == $row['user_id']) { ?>
            <h3>Edit Report: </h3>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
                <p><textarea name="new_description"><?php echo htmlspecialchars($row['description']); ?></textarea></p>
                <input type="submit" name="update" value="Update">
                <input type="submit" name="delete" value="Delete">
            </form>
        <?php } ?>

        <hr>



        <!--show & load comments-->


        <h2>Comments</h2>
        <?php
        $comments_sql = "SELECT c.*, u.first_name FROM comments as c JOIN users as u ON c.user_id=u.user_id WHERE report_id=$report_id ORDER BY comment_date DESC";
        $comments_result = $conn->query($comments_sql);
        while ($comment = $comments_result->fetch_assoc()) { ?>
            <p> <b> <?php echo $comment['first_name'] . $comment['user_id'] . ":"; ?></b>
                <?php echo $comment['comment_text']; ?> <br>
                <small> <?php echo $comment['comment_date']; ?></small>
            </p>
        <?php } ?>





        <!--insert Comments-->



        <hr>
        <form action="view_reports.php" method="POST">
            <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
            <textarea name="comment_text" placeholder="Write a comment.." required></textarea><br><br>
            <input type="submit" value="Add comment">
        </form>

        </div>
    <?php } ?>
</body>

</html>