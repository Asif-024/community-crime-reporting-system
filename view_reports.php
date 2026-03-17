<?php
include "db_connect.php";
$sql = "SELECT * FROM reports ORDER BY submission_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title>View Reports</title>
</head>

<body>
    <h1>All Crime Reports</h1>

    <?php
    while ($row = $result->fetch_assoc()) { ?>
        <div style="border: 1px solid black; padding: 15px; margin-bottom:20 px;">
            <h3> Report ID: <?php echo $row['report_id']; ?></h3>
            <p><b>Description: </b> <?php echo $row['description']; ?></p>
            <p><b>Date: </b> <?php echo $row['submission_date']; ?></p>
            <hr>
            <h4>Comments</h4>
            <?php
            $report_id = $row['report_id'];
            $comments_sql = "SELECT c.*, u.first_name FROM comments as c JOIN users as u ON c.user_id=u.user_id WHERE report_id='$report_id' ORDER BY comment_date DESC";
            $comments_result = $conn->query($comments_sql);
            while ($comment = $comments_result->fetch_assoc()) { ?>
                <p> <b> <?php echo $comment['first_name'] . $comment['user_id'] . ":"; ?></b>
                    <?php echo $comment['comment_text']; ?> <br>
                    <small> <?php echo $comment['commnet_date']; ?></small>
                </p>
            <?php } ?>
            <hr>
            <form action="view_reports.php" method="POST">
                <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                <textarea name="comment_text" placeholder="Write a comment.." required></textarea><br><br>
                <input type="submit" value="Add comment">
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $report_id = $_POST['report_id'];
                $comment_text = $_POST['comment_text'];
                $sql = "INSERT INTO comments (report_id, user_id, comment_text, comment_date)
                    VALUES ('$report_id',1, '$comment_text', NOW() )";
                $conn->query($sql);
            }
            ?>
        </div>
    <?php } ?>
</body>

</html>