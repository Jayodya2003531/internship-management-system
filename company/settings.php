<?php
include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("location:../login/index.php");
    exit();
}

// Save settings if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
                                ON DUPLICATE KEY UPDATE setting_value=?");
        $stmt->bind_param("sss", $key, $value, $value);
        $stmt->execute();
        $stmt->close();
    }
    $success = "Settings updated successfully!";
}

// Load settings safely
$settings = [];
$sql = "SELECT * FROM settings";
$res = mysqli_query($conn, $sql);

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} else {
    die("SQL Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .content {
            margin-left: 250px; /* push body right of sidebar */
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include 'includes/side_menu.php';
	      include '../style.css';	?>

    <div class="content">
        <h3>System Settings</h3>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="post" class="mt-3">
            <div class="mb-3">
                <label class="form-label">Default Interview Venue</label>
                <input type="text" name="default_venue" 
                       value="<?php echo $settings['default_venue'] ?? 'Company HQ - Meeting Room 1'; ?>" 
                       class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Default Interview Time</label>
                <input type="time" name="default_time" 
                       value="<?php echo $settings['default_time'] ?? '10:00'; ?>" 
                       class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Buffer Days Before Interview</label>
                <input type="number" name="default_buffer_days" 
                       value="<?php echo $settings['default_buffer_days'] ?? 3; ?>" 
                       class="form-control" min="0">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="enable_email_notifications" class="form-check-input" id="enableEmail" 
                       <?php echo (!empty($settings['enable_email_notifications']) && $settings['enable_email_notifications'] == '1') ? 'checked' : ''; ?>>
                <label class="form-check-label" for="enableEmail">Enable Email Notifications</label>
            </div>
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</body>
</html>

