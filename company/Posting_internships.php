<?php
include '../includes/db_connect.php';

session_start();

if (!isset($_SESSION['email'])) {
    header("location:../login/index.php");
    exit();
}
include '../includes/variances.php';

// Fetch vacancies
$query = "SELECT * FROM internship_vacancies ORDER BY created_at DESC";
$sql = mysqli_query($conn, $query);

if (!$sql) {
    die("SQL Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Internship Vacancies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .vacancy-card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.2s; }
        .vacancy-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>
<<?php include 'includes/side_menu.php';
      include '../style.css'; ?

<section class="home-section">
    <div class="home-content">
        <i class='bi bi-briefcase'></i>
        <a href="./vacancies.php" class="text">Internship Vacancies</a>
    </div>

    <div class="container mt-4">
        <h4 class="mb-4">Available Internship Vacancies</h4>

        <div class="row">
            <?php if (mysqli_num_rows($sql) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($sql)) { ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card vacancy-card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="text-muted">
                                    <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($row['location']); ?><br>
                                    <i class="bi bi-calendar-event"></i> Deadline: <?php echo date('M d, Y', strtotime($row['application_deadline'])); ?>
                                </p>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars(substr($row['description'], 0, 120))) . "..."; ?></p>
                                <span class="badge <?php echo $row['status']=='Open' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </div>
                            <div class="card-footer text-end">
                                <a href="view_vacancy.php?id=<?php echo $row['vacancy_id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d;"></i>
                    <p class="mt-2 text-muted">No internship vacancies available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
