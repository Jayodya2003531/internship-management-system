<?php
include '../includes/db_connect.php';
include '../includes/update_pg_stat.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("location:../login/index.php");
    exit();
}

if (isset($_GET['status_id']) && isset($_GET['task_id']) && isset($_GET['pg_id'])) {
    $task_id = intval($_GET['task_id']);
    $status_id = intval($_GET['status_id']);
    $pg_id = intval($_GET['pg_id']);

    // Update task status
    $stmt = $conn->prepare("UPDATE task_list SET status_id = ? WHERE task_id = ?");
    $stmt->bind_param("ii", $status_id, $task_id);
    $stmt->execute();
    $stmt->close();

    // Update program progress
    updatePG($pg_id);

    // Redirect back
    header("Location: index.php");
    exit();
} else {
    echo "Missing parameters!";
}
?>
