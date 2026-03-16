<?php
include "db_connect.php";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users where email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $message = "<p style='color:green;'>Login Successful</p>";
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