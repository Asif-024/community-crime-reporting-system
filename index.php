<!DOCTYPE html>
<html>
<head>
    <title>Crime reporing system</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Crime Reporting</h1>
    <h2>Submit a crime report</h2>
     <form action="submit_report.php" method="POST">
     Crime Type: <br>
     <input type="text" name="crime_type"><br><br>
     Location: <br>
    <input type="text" name="location"><br><br>
    Description: <br>
    <textarea name="description"></textarea><br><br>
    <input type="submit" value="Submit Report"> 
</form>

</body>

</html>