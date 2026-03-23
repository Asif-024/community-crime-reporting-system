<?php
include "db_connect.php";

//save report
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $category_id =intval ($_POST['category_id']);
    $location_id = intval ($_POST['location_id']);
    $description = $conn->real_escape_string($_POST['description']);
    $sql = "INSERT INTO reports(user_id,category_id, location_id, description, submission_date,status) VALUES('$user_id','$category_id', '$location_id', '$description', NOW(),'pending')";
    if ($conn->query($sql)) {
        $message = " <h2 style='color:green;'> Crime report submitted successfully</h2>";
    } else {
        $message = " <h2 style='color:red'>Error: " . $conn->error . "</h2> ";
    }
}


//load locations for dropdown
$location_sql = "SELECT * FROM locations ORDER BY district, area";
$location_result = $conn->query($location_sql);


//load crime categories for dropdown
$category_sql = "SELECT * FROM crime_categories ORDER BY category_name";
$category_result = $conn->query($category_sql);
?>


<!--report submission form-->
<!DOCTYPE html>
<html>
<body>
    <h2>Submit a Crime Report</h2>
    <?php if (isset($message)) echo $message; ?>
    <form action="submit_report.php" method="POST">
        <input type="hidden" name="submit_report" value="1">
        Crime category: <br>
        <select name="category_id" required>
            <option value="">Select crime category</option>
            <?php while ($cri = $category_result->fetch_assoc()) {
            ?>
                <option value="<?php echo $cri['category_id']; ?>"> <?php echo $cri['category_name']; ?></option>

            <?php } ?>
        </select><br><br>


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
</body>

</html>