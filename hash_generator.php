<!DOCTYPE html>
<html>
<head>
    <title>Password Hash Generator</title>
</head>
<body>
    <form id="hash_generator" action="" method="post">
        <input type="text" name="password" placeholder="Enter password" id="password"><br>
        <?php 
        if ($_POST) {
            $password = $_POST['password'];
            $password = password_hash($password, PASSWORD_DEFAULT);

            echo "<input type='text' value='$password' readonly><br>";
        } else {
            echo "<input type='text' placeholder='Password Hash' readonly><br>";
        }
        ?>
        <button type="submit">Generate Hash</button>
    </form>
</body>
</html>
