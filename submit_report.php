<?php
include "db_connect.php";
$crime_type = $_POST['crime_type'];
$location = $_POST['location'];
$description = $_POST['description'];
$submission_date = date("Y-m-d H:i:s");
$sql = "INSERT INTO reports (description, submission_date) 
VALUES ('$description', '$submission_date')";
if($conn->query($sql)==true)
    {
        echo " <h2> Crime report submitted sucessfully </h2> ";
    }
    else{
        echo "Error: ". $conn->error;
    }
    ?>
