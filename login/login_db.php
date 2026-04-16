<?php
include '../includes/db_connect.php';
session_start();
if (!$conn) {
    die("Unable to connect");
}
if ($_POST) {
    $email = $_POST["email"];
    $pass = $_POST["password"];
    //Making sure that SQL Injection doesn't work
    $email = mysqli_real_escape_string($conn, $email); //test or 1=1
    $sql = "SELECT * FROM `interns` WHERE `email` = '$email'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    if (!password_verify($pass, $row['password'])) {
        header("Location: index.php?error=Invalid email or password");
exit();

    }

    if ($row['type'] == "2") {
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row['name'];
        $_SESSION['avatar'] = $row['avatar'];
        $_SESSION['password'] = $row['password'];
        $_SESSION['profile_id'] = $row['intern_id'];
        header("location:../intern/index.php");
    } elseif ($row['type'] == "1") {
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row['name'];
        $_SESSION['avatar'] = $row['avatar'];
        $_SESSION['password'] = $row['password'];
        $_SESSION['profile_id'] = $row['intern_id'];
        header("location:../company/index.php");
    } else {
        header("Location: index.php?error=Invalid email or password");
	exit();

    }
}