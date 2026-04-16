<?php
include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("location:../login/index.php");
    exit();
}

// Debugging mode (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle saving results
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['results'])) {
    foreach ($_POST['results'] as $appId => $status) {
        $stmt = $conn->prepare("INSERT INTO interview_results (application_id, result) 
                                VALUES (?, ?)
                                ON DUPLICATE KEY UPDATE result=?");
        $stmt->bind_param("iss", $appId, $status, $status);
        if (!$stmt->execute()) {
            die("Error saving result: " . $stmt->error);
        }
    }
    $success_msg = "Interview results saved successfully!";
}

// Fetch all scheduled interviews + applicant details
$query = "SELECT 
            a.application_id,
            a.applicant_name,
            a.applicant_email,
            a.phone,
            a.position_applied,
            ir.result
          FROM applications a
          INNER JOIN scheduled_interviews si ON a.application_id = si.application_id
          LEFT JOIN interview_results ir ON a.application_id = ir.application_id
          ORDER BY a.applicant_name ASC";

$sql = mysqli_query($conn, $query);
if (!$sql) {
    die("SQL Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interview Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .result-select { min-width: 150px; }
    </style>
</head>
<body>
<?php include 'includes/side_menu.php';
      include '../style.css'; ?>
 

<section class="home-section">
    <div class="home-content">
        <i class='bi bi-clipboard-check'></i>
        <a href="./interview_results.php" class="text">Interview Results</a>
    </div>
    <div class="mainContent container mt-4">
        <h4 class="mb-4">Interview Results</h4>

        <?php if (isset($success_msg)): ?>
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <form method="POST">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Applicant</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Position</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($sql) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($sql)) { ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['applicant_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['applicant_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><span class="badge bg-info"><?php echo htmlspecialchars($row['position_applied']); ?></span></td>
                            <td>
                                <select class="form-select result-select" name="results[<?php echo $row['application_id']; ?>]">
                                    <option value="Pending" <?php if ($row['result']=="Pending") echo "selected"; ?>>Pending</option>
                                    <option value="Selected" <?php if ($row['result']=="Selected") echo "selected"; ?>>Selected</option>
                                    <option value="Rejected" <?php if ($row['result']=="Rejected") echo "selected"; ?>>Rejected</option>
                                </select>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem; color: #6c757d;"></i>
                                <p class="mt-2 text-muted">No scheduled interviews found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save Results</button>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
