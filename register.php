<?php 
include "db_connect.php";
if($_SERVER['REQUEST_METHOD']=='POST'){
$first = $_POST['first_name'];
$last= $_POST['last_name'];
$NID= $_POST['NID'];
$email= $_POST['email'];
$phone = $_POST['phone'];
$password= $_POST['password'];
$hash_pass= password_hash($password, PASSWORD_DEFAULT);
$sql= "INSERT INTO users(first_name, last_name, email, phone,NID, password)
VALUES ('$first', '$last', '$email', '$phone', '$NID', '$hash_pass')";
if($conn -> query($sql) === TRUE)
    {
        echo " <p style = 'color: green;'> Registered Successfully</p> ";
        
    }
    else 
        {
            echo " <p style='color:red;'>Error: ". $conn->error . " </p> ";
        }
}
?>
<!DOCTYPE html> 
<html>
    <body>
    <h1> User Resigtration</h1>
    <form action="register.php" method="POST">
    First Name: <br>
    <input type="text" name="first_name" required><br><br>
    Last Name: <br>
    <input type="text" name="last_name" ><br><br>
    NID: <br>
    <input type="number" name="NID" required><br><br>
    Email: <br>
    <input type="email" name="email" required><br><br>
    Phone: <br>
    <input type="number" name="phone" required><br><br>
    Password: <br>
    <input type="password" name="password" required><br><br>
    <input type="submit" value="Register">
    </form>    
    </body>
</html>

