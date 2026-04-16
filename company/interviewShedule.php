<?php
include '../includes/db_connect.php';
include '../includes/update_pg_stat.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("location:../login/index.php");
    exit();
}
include '../includes/variances.php';

// Handle form submission (save interview details + send email)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'])) {
    $id = intval($_POST['application_id']);
    $date = $_POST['interview_date'] ?: null;
    $time = $_POST['interview_time'] ?: null;
    $venue = $_POST['interview_venue'] ?: null;

    // Update DB
    $stmt = $conn->prepare("UPDATE applications 
                            SET interview_date=?, interview_time=?, interview_venue=? 
                            WHERE application_id=?");
    $stmt->bind_param("sssi", $date, $time, $venue, $id);
    $stmt->execute();
    $stmt->close();

    // Fetch applicant details for email
    $res = mysqli_query($conn, "SELECT applicant_name, applicant_email FROM applications WHERE application_id=$id");
    $row = mysqli_fetch_assoc($res);

    if ($row) {
        include '../includes/send_mail.php';
        sendInterviewMail($row['applicant_email'], $row['applicant_name'], $date, $time, $venue);
    }

    header("Location: interview_schedule.php");
    exit();
}

// Fetch all interview scheduled applications
$sql = "SELECT 
    applications.application_id,
    applications.applicant_name,
    applications.applicant_email,
    applications.phone,
    applications.position_applied,
    pg_type_list.pg_type,
    applications.experience_years,
    applications.education_level,
    applications.application_date,
    applications.interview_date,
    applications.interview_time,
    applications.interview_venue
FROM applications
LEFT JOIN pg_type_list ON applications.pg_type_id = pg_type_list.pg_type_id
WHERE applications.status = 'Interview Scheduled'
ORDER BY applications.application_date DESC";

$result = mysqli_query($conn, $sql);
if (!$result) {
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
            margin-left: 250px; /* push content right of sidebar */
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include 'includes/side_menu.php';
		  include '../style.css';	?>

    <div class="content">
        <h3>Scheduled Interviews</h3>
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Applicant</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Program Type</th>
                    <th>Experience</th>
                    <th>Education</th>
                    <th>Application Date</th>
                    <th>Interview Date</th>
                    <th>Interview Time</th>
                    <th>Venue</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['applicant_name']); ?></td>
                            <td><?= htmlspecialchars($row['applicant_email']); ?></td>
                            <td><?= htmlspecialchars($row['phone']); ?></td>
                            <td><?= htmlspecialchars($row['position_applied']); ?></td>
                            <td><?= htmlspecialchars($row['pg_type'] ?? 'Not specified'); ?></td>
                            <td><?= $row['experience_years']; ?> years</td>
                            <td><?= htmlspecialchars($row['education_level']); ?></td>
                            <td><?= date('M d, Y', strtotime($row['application_date'])); ?></td>
                            <td><?= $row['interview_date'] ? htmlspecialchars($row['interview_date']) : '-'; ?></td>
                            <td><?= $row['interview_time'] ? htmlspecialchars($row['interview_time']) : '-'; ?></td>
                            <td><?= $row['interview_venue'] ? htmlspecialchars($row['interview_venue']) : '-'; ?></td>
                            <td>
                                <form method="post" class="d-flex flex-column gap-1">
                                    <input type="hidden" name="application_id" value="<?= $row['application_id']; ?>">
                                    <input type="date" name="interview_date" value="<?= $row['interview_date']; ?>" class="form-control" required>
                                    <input type="time" name="interview_time" value="<?= $row['interview_time']; ?>" class="form-control" required>
                                    <input type="text" name="interview_venue" value="<?= $row['interview_venue']; ?>" placeholder="Venue" class="form-control" required>
                                    <button type="submit" class="btn btn-sm btn-primary mt-2">Save & Notify</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12" class="text-center text-muted">No scheduled interviews yet</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
