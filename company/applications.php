<?php
include '../includes/db_connect.php';
include '../includes/update_pg_stat.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("location:../login/index.php");
    exit();
}

include '../includes/variances.php';

// Handle scheduling interviews
if (isset($_POST['schedule_interviews']) && isset($_POST['selected_applications'])) {
    $selected_apps = $_POST['selected_applications'];
    foreach ($selected_apps as $app_id) {
        mysqli_query($conn, "UPDATE applications SET status = 'Interview Scheduled' WHERE application_id = $app_id");
    }
    header("location: interview_schedule.php");
    exit();
}

// Fetch applications
$query = "SELECT
    applications.application_id,
    applications.applicant_name,
    applications.applicant_email,
    applications.phone,
    applications.application_date,
    applications.cv_path,
    applications.cover_letter_path,
    applications.status,
    applications.position_applied,
    pg_type_list.pg_type,
    applications.experience_years,
    applications.education_level
    FROM applications 
    LEFT JOIN pg_type_list ON applications.pg_type_id = pg_type_list.pg_type_id
    WHERE applications.status IN ('Pending', 'Under Review', 'Shortlisted')
    ORDER BY applications.application_date DESC";

$sql = mysqli_query($conn, $query);
if (!$sql) {
    die("SQL Error: " . mysqli_error($conn));
}

$application_count = 0; // Initialize counter
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
        .application-row { transition: background-color 0.2s; }
        .application-row:hover { background-color: rgba(0, 123, 255, 0.1); }
        .selected-row { background-color: rgba(40, 167, 69, 0.1); }
        .schedule-btn { position: fixed; bottom: 30px; right: 30px; z-index: 1000; padding: 15px 25px; font-size: 16px; border-radius: 50px; box-shadow: 0 4px 12px rgba(0,0,0,0.3); }
        .document-link { color: #0066cc; text-decoration: none; }
        .document-link:hover { text-decoration: underline; }
        .status-badge { font-size: 0.85em; }
        .application-checkbox { transform: scale(1.2); }
        .counter-badge { position: absolute; top: -8px; right: -8px; min-width: 20px; height: 20px; border-radius: 50%; background: #dc3545; color: white; font-size: 12px; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
<?php
include 'includes/side_menu.php';
include '../style.css'; 
?>
<section class="home-section">
    <div class="home-content">
        <i class='bx bx-file-blank'></i>
        <a href="./applications.php" class="text">Applications Management</a>
    </div>
    <div class="mainContent">
        <form method="POST" id="applicationsForm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Intern Applications</h4>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="selectAll()">
                        <i class="bi bi-check-all"></i> Select All
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="clearSelection()">
                        <i class="bi bi-x-circle"></i> Clear Selection
                    </button>
                </div>
            </div>
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th><input type="checkbox" class="form-check-input application-checkbox" id="selectAllCheckbox" onchange="toggleAll(this)"></th>
                        <th>Applicant</th>
                        <th>Position</th>
                        <th>Program Type</th>
                        <th>Experience</th>
                        <th>Education</th>
                        <th>Application Date</th>
                        <th>Documents</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($sql)) { $application_count++; ?>
                    <tr class="application-row" data-app-id="<?php echo $row['application_id']; ?>">
                        <td><input type="checkbox" class="form-check-input application-checkbox app-checkbox" name="selected_applications[]" value="<?php echo $row['application_id']; ?>" onchange="updateSelection()"></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['applicant_name']); ?></strong><br>
                            <small class="text-muted">
                                <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($row['applicant_email']); ?><br>
                                <i class="bi bi-telephone"></i> <?php echo htmlspecialchars($row['phone']); ?>
                            </small>
                        </td>
                        <td><span class="badge bg-info"><?php echo htmlspecialchars($row['position_applied']); ?></span></td>
                        <td><?php echo htmlspecialchars($row['pg_type'] ?? 'Not specified'); ?></td>
                        <td><span class="badge bg-secondary"><?php echo $row['experience_years']; ?> years</span></td>
                        <td><?php echo htmlspecialchars($row['education_level']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['application_date'])); ?></td>
                        <td>
                            <?php if ($row['cv_path']): ?><a href="<?php echo '../'.$row['cv_path']; ?>" target="_blank" class="document-link"><i class="bi bi-file-earmark-pdf"></i> CV</a><br><?php endif; ?>
                            <?php if ($row['cover_letter_path']): ?><a href="<?php echo '../'.$row['cover_letter_path']; ?>" target="_blank" class="document-link"><i class="bi bi-file-earmark-text"></i> Cover Letter</a><?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $status_class = match($row['status']) {
                                'Pending' => 'bg-warning text-dark',
                                'Under Review' => 'bg-primary',
                                'Shortlisted' => 'bg-success',
                                'Interview Scheduled' => 'bg-info',
                                'Rejected' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewApplicationModal<?php echo $row['application_id']; ?>"><i class="bi bi-eye"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-success" onclick="updateApplicationStatus(<?php echo $row['application_id']; ?>,'Shortlisted')"><i class="bi bi-check-circle"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="updateApplicationStatus(<?php echo $row['application_id']; ?>,'Rejected')"><i class="bi bi-x-circle"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if ($application_count === 0): ?>
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d;"></i>
                            <p class="mt-2 text-muted">No applications found</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="submit" name="schedule_interviews" class="btn btn-success schedule-btn" id="scheduleBtn" disabled>
                <i class="bi bi-calendar-plus"></i> Schedule Interviews
                <span class="counter-badge" id="selectionCounter" style="display: none;">0</span>
            </button>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
let selectedCount = 0;
function updateSelection(){
    const checkboxes = document.querySelectorAll('.app-checkbox:checked');
    selectedCount = checkboxes.length;
    const scheduleBtn = document.getElementById('scheduleBtn');
    const counter = document.getElementById('selectionCounter');
    if(selectedCount>0){
        scheduleBtn.disabled=false;
        counter.style.display='flex';
        counter.textContent=selectedCount;
    }else{
        scheduleBtn.disabled=true;
        counter.style.display='none';
    }
}
function toggleAll(selectAllCheckbox){
    const checkboxes=document.querySelectorAll('.app-checkbox');
    checkboxes.forEach(cb=>cb.checked=selectAllCheckbox.checked);
    updateSelection();
}
function selectAll(){document.querySelectorAll('.app-checkbox').forEach(cb=>cb.checked=true);updateSelection();}
function clearSelection(){document.querySelectorAll('.app-checkbox').forEach(cb=>cb.checked=false);updateSelection();}
function updateApplicationStatus(appId,status){
    if(confirm(`Are you sure you want to mark this application as ${status}?`)){
        const formData=new FormData();
        formData.append('application_id',appId);
        formData.append('new_status',status);
        fetch('update_application_status.php',{method:'POST',body:formData})
        .then(r=>r.json()).then(data=>{
            if(data.success){location.reload();}else{alert('Error updating status');}
        }).catch(e=>{console.error(e);alert('Error updating status');});
    }
}
</script>
</body>
</html>
