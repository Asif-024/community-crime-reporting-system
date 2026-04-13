<?php
include "db_connect.php";

//save user to the db
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $NID = $_POST['NID'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    //if theres an empty field
    $errors = [];
    if (empty($first)) $errors[] = "First name is required";
    if (empty($NID)) $errors[] = "NID is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($phone)) $errors[] = "Phone is required";
    if (empty($password)) $errors[] = "Password is required";
    if ($role === "police") {
        if (empty($_POST['badge_no'])) $errors[] = "Badge number is required";
        if (empty($_POST['rank'])) $errors[] = "Rank is required";
        if (empty($_POST['station_id'])) $errors[] = "Station ID is required";
    }
    if (!empty($errors)) {
        foreach ($errors as $e) {
            echo "<p style='color:red;'>$e</p>";
        }
    } else {
        $hash_pass = password_hash($password, PASSWORD_DEFAULT);
        $user_sql = "INSERT INTO users(first_name, last_name, email, phone, NID, password, role)
                     VALUES ('$first', '$last', '$email', '$phone', '$NID', '$hash_pass', '$role')";
        if ($conn->query($user_sql) === TRUE) {
            $user_id = $conn->insert_id;


            //if police
            if ($role === "police") {
                $badge_no = $_POST['badge_no'];
                $rank = $_POST['rank'];
                $station_id = $_POST['station_id'];
                $police_sql = "INSERT INTO police(user_id, badge_no, rank, station_id)
                               VALUES ('$user_id', '$badge_no', '$rank', '$station_id')";
                $conn->query($police_sql);
            }
            echo "<center><p style='color:green;'>Registered Successfully<br><a href='login.php'>Login</a></p></center>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body bgcolor="#f4f6f9">
    <center>
        <div style="border: 1px solid black; padding:15px; margin-bottom:20px; width:30%;">
            <h1> User Resigtration</h1>
            <form action="register.php" method="POST">
                First Name: <br>
                <input type="text" name="first_name" required><br><br>
                Last Name: <br>
                <input type="text" name="last_name"><br><br>
                NID: <br>
                <input type="number" name="NID" required><br><br>
                Email: <br>
                <input type="email" name="email" required><br><br>
                Phone: <br>
                <input type="text" name="phone" required><br><br>
                Role: <br>
                <select name="role" onchange="policeFields(this.value)">
                    <option value="user">User</option>
                    <option value="police">Police</option>
                </select><br><br>


                <!--IF POLICE-->


                <div id="police_fields" style="display:none;">
                    Badge no: <br>
                    <input type="text" name="badge_no"> <br><br>
                    Rank: <br>
                    <input type="text" name="rank"> <br><br>
                    Station ID: <br>
                    <input type="text" name="station_id"><br><br>
                </div>


                <!--JAVASCRIPT FUNCTION-->


                <script>
                    function policeFields(role) {

                        document.getElementById("police_fields").style.display = (role === "police") ? "block" : "none";
                    }
                </script>
              Password: <br>
                <input type="password" name="password" required><br><br>
                <input type="submit" value="Register">
            </form>
    </center>
    </div>
</body>

</html>