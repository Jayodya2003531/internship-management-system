<?php
include '../../includes/db_connect.php';
session_start();

function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function is_valid_password($password) {
    // Minimum 6 characters, at least one letter and one number
    return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/', $password);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profile_id = $_POST['profileid'];
    $profile_name = trim($_POST['profilename']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $avatar = $_FILES['avatar']['name'];
    $avatar_temp = $_FILES['avatar']['tmp_name'];
    $previous = $_POST['previous'];
    $current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $exp = explode(".", $avatar);
    $end = end($exp);
    $name = $profile_name . "-" . time() . "." . $end;
    $path = "upload_avatar/" . $name;

    // Fetch current user data
    $user_query = mysqli_query($conn, "SELECT * FROM interns WHERE intern_id = $profile_id");
    $user = mysqli_fetch_assoc($user_query);

    // Validate email
    if (!is_valid_email($email)) {
        echo "<script>alert('Invalid email format.');window.history.back();</script>";
        exit();
    }

    // Check if email is already taken by another user
    $email_check = mysqli_query($conn, "SELECT intern_id FROM interns WHERE email='$email' AND intern_id!=$profile_id");
    if (mysqli_num_rows($email_check) > 0) {
        echo "<script>alert('Email already exists.');window.history.back();</script>";
        exit();
    }

    // If email or password is changed, verify current password
    $email_changed = ($email !== $user['email']);
    $password_changed = (!empty($password));
    if ($email_changed || $password_changed) {
        if (empty($current_password)) {
            echo "<script>alert('Current password required.');window.history.back();</script>";
            exit();
        }
        // Verify current password (assuming password is hashed)
        if (!password_verify($current_password, $user['password'])) {
            echo "<script>alert('Current password is incorrect.');window.history.back();</script>";
            exit();
        }
    }

    // Validate new password if changed
    if ($password_changed && !is_valid_password($password)) {
        echo "<script>alert('Password must be at least 6 characters and contain letters and numbers.');window.history.back();</script>";
        exit();
    }

    // Hash password if changed, else keep old
    $new_password = $password_changed ? password_hash($password, PASSWORD_DEFAULT) : $user['password'];

    // Prepare query
    if ($avatar != "") {
        $query = "UPDATE `interns` SET `name`='$profile_name',`avatar`='$path', `email`='$email', `password`='$new_password'
        WHERE `intern_id`=$profile_id";
    } else {
        $query = "UPDATE `interns` SET `name`='$profile_name', `email`='$email', `password`='$new_password'
        WHERE `intern_id`=$profile_id";
    }
    $query_run = mysqli_query($conn, $query);
    if ($query_run) {
        if ($avatar != "") {
            move_uploaded_file($avatar_temp, "../../$path");
            if ($previous && file_exists("../../" . $previous)) {
                unlink("../../" . $previous);
            }
            $_SESSION['avatar'] = $path;
        }
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $profile_name;
        $_SESSION['password'] = $new_password;
        echo "<script>alert('User account updated!');window.location='../index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Data not saved');window.history.back();</script>";
        exit();
    }
}
?>