<?php
session_start();
include "db_connect.php";


//fetch the user
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users where email='$email'";
    $result = $conn->query($sql);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {

            //if the user is banned
            if ($user['account_status'] === 'banned') {
                die("Your account is banned");
            }

            //if the user is not varified 
            if ($user['varification_status' !== 'approved']) {
                die("Account is not approved");
            }

            $message = "<p style='color:green;'>Login Successful</p>";
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['name'] = $row['first_name'];
            header("Location : view_reports.php");
            exit();
        } else {
            $message = "<p style='color:red;'>Invalid password</p>";
        }
    } else {
        $message = "<p style='color:red;'>Invalid credentials</p>";
    }
}
?>


<!DOCTYPE html>
<html>

<body>
    <h1>User login</h1>
    <?php
    if (isset($message)) {
        echo $message;
    }
    ?>
    <form action="login.php" method="POST">
        Email: <br>
        <input type="text" name="email" required><br><br>
        Password: <br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>

</html>