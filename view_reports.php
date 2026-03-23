<?php
session_start();
include "db_connect.php";
$user_id = $_SESSION['user_id'] ?? 1;


//store a new credibility vote
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['vote'])) {
    $report_id = $_POST['report_id'];
    $vote = $_POST['vote'];
    $sql = "INSERT INTO report_credibility (report_id, user_id, credibility_value) VALUES ('$report_id', '$user_id', '$vote')
        ON DUPLICATE KEY UPDATE credibility_value='$vote'";
    $conn->query($sql);
}


//store a new comment
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['comment'])) {
    $report_id = $_POST['report_id'];
    $comment_text = $_POST['comment_text'];
    $sql = "INSERT INTO comments (report_id, user_id, comment_text, comment_date) VALUES('$report_id', '$user_id', '$comment_text', NOW())";
    $conn->query($sql);
}


//load reports
$sql = "SELECT * FROM reports ORDER BY submission_date DESC";
$result = $conn->query($sql);


?>
<!DOCTYPE html>
<html>



<body>





















    <h1>All Crime Reports</h1>


    <?php
    while ($row = $result->fetch_assoc()) {



        //load credibility count
        $report_id = $row['report_id'];
        $vote_sql = "SELECT credibility_value, COUNT(*) AS count FROM report_credibility WHERE 
            report_id = '$report_id' GROUP BY credibility_value";
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
            <p><b>Description: </b> <?php echo $row['description']; ?></p>
            <p><b>Date: </b> <?php echo $row['submission_date']; ?></p>
            <p><b>Status: <?php echo $row['status'];  ?></b></p>


            <!--show & form credibility count-->
            <form action="view_reports.php" style="margin-bottom:10px;" method="POST">
                <input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
                <button type="submit" name="vote" value="valid"> Valid(<?php echo $valid_count; ?>)</button>
                <button type="submit" name="vote" value="invalid"> Invalid(<?php echo $invalid_count; ?>)</button>
            </form>


            <hr>


            <!--show & load comments-->
            <h4>Comments</h4>
            <?php
            $comments_sql = "SELECT c.*, u.first_name FROM comments as c JOIN users as u ON c.user_id=u.user_id WHERE report_id='$report_id' ORDER BY comment_date DESC";
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