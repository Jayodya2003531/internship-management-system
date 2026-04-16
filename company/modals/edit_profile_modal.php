<!--======== PROFILE EDIT Modal========= -->
<?php
include '../includes/variances.php';
?>

<div class="modal fade" id="profileeditModal<?php echo $login_id ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.3);">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Edit My Profile </h5>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="editProfileForm" action="db/edit_profile_db.php" method="POST" enctype="multipart/form-data">

                <div class="modal-body">
                    <input type="hidden" name="profileid" value="<?php echo $login_id ?>">
                    <input type="hidden" name="previous" value="<?php echo $login_avatar ?>">
                    <input type="hidden" id="originalEmail" value="<?php echo $login_email ?>">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="profilename" value="<?php echo $login_name ?>" class="form-control"
                            placeholder="Enter Name">
                    </div>
                    <div class="mb-3">
                        <label>Profile Picture</label><br>
                        <img src="<?php echo "../" . $login_avatar ?>" class="avatar" />
                        <br>
                        <input type="file" accept="image/*" name="avatar" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Email address</label>
                        <input type="email" id="emailInput" name="email" value="<?php echo $login_email ?>" class="form-control"
                            placeholder="xyz@goodjob.com">
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" id="passwordInput" name="password" value=""
                            class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="updateBtn" class="btn btn-primary">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Current Password Modal -->
<div class="modal fade" id="currentPasswordModal" tabindex="-1" aria-labelledby="currentPasswordLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="currentPasswordForm">
        <div class="modal-header">
          <h5 class="modal-title" id="currentPasswordLabel">Confirm Current Password</h5>
          <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="currentPasswordInput">Current Password</label>
            <input type="password" id="currentPasswordInput" name="current_password" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Confirm</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('updateBtn').addEventListener('click', function(e) {
    const originalEmail = document.getElementById('originalEmail').value;
    const emailInput = document.getElementById('emailInput').value;
    const passwordInput = document.getElementById('passwordInput').value;

    // Check if email or password changed
    if (emailInput !== originalEmail || passwordInput.length > 0) {
        // Hide profile edit modal before showing current password modal
        var profileEditModal = bootstrap.Modal.getInstance(document.getElementById('profileeditModal<?php echo $login_id ?>'));
        if (profileEditModal) {
            profileEditModal.hide();
        }
        // Show current password modal
        var currentPasswordModal = new bootstrap.Modal(document.getElementById('currentPasswordModal'));
        currentPasswordModal.show();
    } else {
        // Submit form directly
        document.getElementById('editProfileForm').submit();
    }
});

// Handle current password modal submit
document.getElementById('currentPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Add current password to main form and submit
    var currentPassword = document.getElementById('currentPasswordInput').value;
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'current_password';
    input.value = currentPassword;
    document.getElementById('editProfileForm').appendChild(input);
    document.getElementById('editProfileForm').submit();
});
</script>