<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['login'] !== true){
    header("Location: upload-notice.php");
    exit;
}
require_once "db.php";

$msg = "";
if(isset($_POST['submit'])){
    $msg = $db->add_teacher_staff($_POST, $_FILES);
}

include_once('includes/header.php');
?>

<div class="dashboard-card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-header-title">
            <h2><i class="fa-solid fa-user-plus"></i> Add Teacher & Staff Member</h2>
            <p class="card-header-subtitle">Register new faculty and staff profiles into the institution directory.</p>
        </div>
        <a href="teacher_staff_list.php" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-address-book"></i> View Directory
        </a>
    </div>

    <?php if(!empty($msg)): ?>
        <div class="alert <?php echo strpos($msg, 'text-danger') !== false ? 'alert-danger' : 'alert-success'; ?>">
            <i class="fa-solid <?php echo strpos($msg, 'text-danger') !== false ? 'fa-triangle-exclamation' : 'fa-circle-check'; ?>"></i>
            <div><?php echo strip_tags($msg); ?></div>
        </div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group" style="grid-column: span 8;">
                <label class="form-label">Full Name <span style="color: var(--rose);">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Enter teacher/staff full name..." required>
            </div>

            <div class="form-group" style="grid-column: span 4;">
                <label class="form-label">Serial Order (SL)</label>
                <input type="number" name="sl" class="form-control" placeholder="e.g. 1, 2, 3..." value="1">
            </div>

            <div class="form-group" style="grid-column: span 6;">
                <label class="form-label">Designation <span style="color: var(--rose);">*</span></label>
                <input type="text" name="designation" class="form-control" placeholder="e.g. Senior Lecturer, Asst. Teacher..." required>
            </div>

            <div class="form-group" style="grid-column: span 6;">
                <label class="form-label">Mobile Number</label>
                <input type="text" name="mobile" class="form-control" placeholder="e.g. +880 1700 000000">
            </div>

            <div class="form-group" style="grid-column: span 6;">
                <label class="form-label">Shift</label>
                <select name="shift" class="form-control">
                    <option value="">Select Shift...</option>
                    <option value="1">Morning Shift</option>
                    <option value="2">Day Shift</option>
                </select>
            </div>

            <div class="form-group" style="grid-column: span 6;">
                <label class="form-label">Section / Category</label>
                <select name="section" class="form-control">
                    <option value="">Select Section...</option>
                    <option value="shift-incharge-morning">Shift Incharge (Morning)</option>
                    <option value="shift-incharge-day">Shift Incharge (Day)</option>
                    <option value="college-teacher">College Teachers</option>
                    <option value="asst-teacher-morning">Asst. Teacher (Morning)</option>
                    <option value="asst-teacher-day">Asst. Teacher (Day)</option>
                    <option value="third-staff">3rd Class Staff</option>
                    <option value="fourth-staff">4th Class Staff</option>
                </select>
            </div>

            <div class="form-group" style="grid-column: span 12;">
                <label class="form-label">Profile Photo (JPG, JPEG)</label>
                <div class="file-upload-wrapper">
                    <i class="fa-solid fa-user-circle"></i>
                    <p>Click or drag teacher photo here</p>
                    <span>Recommended format: JPG/JPEG (Max 5 MB)</span>
                    <input type="file" name="photo" accept=".jpg,.jpeg" onchange="previewPhotoName(this)">
                </div>
                <div id="photoFileName" style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--primary); font-weight: 600;"></div>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" name="submit" class="btn btn-primary" style="flex: 1;">
                <i class="fa-solid fa-user-check"></i> Add Teacher / Staff Record
            </button>
            <a href="notices.php" class="btn btn-secondary">Dashboard</a>
        </div>
    </form>
</div>

<script>
    function previewPhotoName(input) {
        var el = document.getElementById('photoFileName');
        if(input.files && input.files[0]) {
            el.innerHTML = '<i class="fa-solid fa-image"></i> Selected Photo: ' + input.files[0].name;
        } else {
            el.innerHTML = '';
        }
    }
</script>

<?php include_once('includes/footer.php'); ?>