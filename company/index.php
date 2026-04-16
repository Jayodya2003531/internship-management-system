<?php
include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("location:../login/index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $deadline = mysqli_real_escape_string($conn, $_POST['application_deadline']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "INSERT INTO internship_vacancies (title, location, description, application_deadline, status, created_at) 
              VALUES ('$title', '$location', '$description', '$deadline', '$status', NOW())";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Vacancy posted successfully!";
        header("Location: vacancies.php"); // Redirect back to vacancies list
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Internship Vacancy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css"> <!-- ✅ your custom CSS -->
</head>
<body>

<?php include 'includes/side_menu.php'; ?> <!-- ✅ your sidebar -->

<section class="home-section">
    <div class="home-content">
        <i class="bi bi-plus-circle"></i>
        <span class="text">Post Internship Vacancy</span>
    </div>

    <div class="container mt-4">
        <div class="card shadow-lg vacancy-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Create New Internship Vacancy</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Job Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Application Deadline</label>
                        <input type="date" name="application_deadline" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Open">Open</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="vacancies.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Post Vacancy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
