<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['login'] !== true){
    header("Location: upload-notice.php");
    exit;
}
if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: teacher_staff_list.php");
    exit;
}
require_once "db.php";

$id = intval($_GET['id']);
$msg = "";

if(isset($_POST['submit'])){
    $msg = $db->update_teacher_staff($id, $_POST, $_FILES);
}

$teacher = $db->get_teacher_staff_by_id($id);
if(!$teacher){
    header("Location: teacher_staff_list.php");
    exit;
}

include_once('includes/header.php');
?>

<div class="dashboard-card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-header-title">
            <h2><i class="fa-solid fa-user-pen"></i> Edit Teacher & Staff Details</h2>
            <p class="card-header-subtitle">Update record information for <?php echo htmlspecialchars($teacher['name']); ?>.</p>
        </div>
        <a href="teacher_staff_list.php" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Directory
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
                <input type="text" name="name" class="form-control" placeholder="Enter teacher/staff full name..." value="<?php echo htmlspecialchars($teacher['name']); ?>" required>
            </div>

            <div class="form-group" style="grid-column: span 4;">
                <label class="form-label">Serial Order (SL)</label>
                <input type="number" name="sl" class="form-control" placeholder="e.g. 1, 2, 3..." value="<?php echo htmlspecialchars($teacher['sl']); ?>">
            </div>

            <div class="form-group" style="grid-column: span 6;">
                <label class="form-label">Designation <span style="color: var(--rose);">*</span></label>
                <input type="text" name="designation" class="form-control" placeholder="e.g. Senior Lecturer, Asst. Teacher..." value="<?php echo htmlspecialchars($teacher['designation']); ?>" required>
            </div>

            <div class="form-group" style="grid-column: span 6;">
                <label class="form-label">Mobile Number</label>
                <input type="text" name="mobile" class="form-control" placeholder="e.g. +880 1700 000000" value="<?php echo htmlspecialchars($teacher['mobile']); ?>">
            </div>

            <div class="form-group" style="grid-column: span 6;">
                <label class="form-label">Shift</label>
                <select name="shift" class="form-control">
                    <option value="">Select Shift...</option>
                    <option value="1" <?php echo $teacher['shift'] == '1' || strtolower($teacher['shift']) == 'morning' ? 'selected' : ''; ?>>Morning Shift</option>
                    <option value="2" <?php echo $teacher['shift'] == '2' || strtolower($teacher['shift']) == 'day' ? 'selected' : ''; ?>>Day Shift</option>
                </select>
            </div>

            <div class="form-group" style="grid-column: span 6;">
                <label class="form-label">Section / Category</label>
                <select name="section" class="form-control">
                    <option value="">Select Section...</option>
                    <option value="shift-incharge-morning" <?php echo $teacher['section'] === 'shift-incharge-morning' ? 'selected' : ''; ?>>Shift Incharge (Morning)</option>
                    <option value="shift-incharge-day" <?php echo $teacher['section'] === 'shift-incharge-day' ? 'selected' : ''; ?>>Shift Incharge (Day)</option>
                    <option value="college-teacher" <?php echo $teacher['section'] === 'college-teacher' ? 'selected' : ''; ?>>College Teachers</option>
                    <option value="asst-teacher-morning" <?php echo $teacher['section'] === 'asst-teacher-morning' ? 'selected' : ''; ?>>Asst. Teacher (Morning)</option>
                    <option value="asst-teacher-day" <?php echo $teacher['section'] === 'asst-teacher-day' ? 'selected' : ''; ?>>Asst. Teacher (Day)</option>
                    <option value="third-staff" <?php echo $teacher['section'] === 'third-staff' ? 'selected' : ''; ?>>3rd Class Staff</option>
                    <option value="fourth-staff" <?php echo $teacher['section'] === 'fourth-staff' ? 'selected' : ''; ?>>4th Class Staff</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" name="submit" class="btn btn-primary" style="flex: 1;">
                <i class="fa-solid fa-floppy-disk"></i> Save Teacher Changes
            </button>
            <a href="teacher_staff_list.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include_once('includes/footer.php'); ?>
