<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form My MyGuest</title>
</head>
<body>
    <form action="insertdata.php" method="post">
        <label for="fname">First Name:*</label><br>
        <input type="text" name="fname" required> <br>
        <label for="lname">Last Name:*</label><br>
        <input type="text" name="lname" required> <br>
        <label for="email">Email:</label><br>
        <input type="text" name="email"> <br>
        <span>* required</span><br>
        <br><input type="submit" value="SAVE">
    </form>
    
</body>
</html>