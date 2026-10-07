<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['login'] !== true){
    header("Location: upload-notice.php");
    exit;
}
require_once "db.php";

$msg = "";
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $msg = $db->delete_teacher_staff($_GET['delete']);
}

// Get filter values from GET
$selectedShift = isset($_GET['shift']) ? trim($_GET['shift']) : '';
$selectedSection = isset($_GET['section']) ? trim($_GET['section']) : '';

// Fetch all staff and filtered staff
$allTeachers = $db->get_all_teachers_staff('', '');
$teachers = $db->get_all_teachers_staff($selectedShift, $selectedSection);

// Calculate metrics
$totalStaff = count($allTeachers);
$morningCount = 0;
$dayCount = 0;
foreach($allTeachers as $t) {
    if($t['shift'] == '1' || strtolower($t['shift']) == 'morning') $morningCount++;
    else if($t['shift'] == '2' || strtolower($t['shift']) == 'day') $dayCount++;
}

// Helper arrays for human readable labels
$sectionsMap = [
    'shift-incharge-morning' => 'Shift Incharge (Morning)',
    'shift-incharge-day' => 'Shift Incharge (Day)',
    'college-teacher' => 'College Teachers',
    'asst-teacher-morning' => 'Asst. Teacher (Morning)',
    'asst-teacher-day' => 'Asst. Teacher (Day)',
    'third-staff' => '3rd Class Staff',
    'fourth-staff' => '4th Class Staff'
];

$shiftsMap = [
    '1' => 'Morning Shift',
    '2' => 'Day Shift'
];

include_once('includes/header.php');
?>

<!-- Quick Metrics Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo $totalStaff; ?></div>
            <div class="stat-label">Total Faculty & Staff</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon sky">
            <i class="fa-solid fa-sun"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo $morningCount; ?></div>
            <div class="stat-label">Morning Shift</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber">
            <i class="fa-solid fa-cloud-sun"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo $dayCount; ?></div>
            <div class="stat-label">Day Shift</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon emerald">
            <i class="fa-solid fa-filter"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo count($teachers); ?></div>
            <div class="stat-label">Matching Records</div>
        </div>
    </div>
</div>

<?php if(!empty($msg)): ?>
    <div class="alert <?php echo strpos($msg, 'text-danger') !== false ? 'alert-danger' : 'alert-success'; ?>">
        <i class="fa-solid <?php echo strpos($msg, 'text-danger') !== false ? 'fa-triangle-exclamation' : 'fa-circle-check'; ?>"></i>
        <div><?php echo strip_tags($msg); ?></div>
    </div>
<?php endif; ?>

<!-- Main Dashboard Card -->
<div class="dashboard-card">
    <div class="card-header">
        <div class="card-header-title">
            <h2><i class="fa-solid fa-id-card"></i> Faculty & Staff Directory</h2>
            <p class="card-header-subtitle">Filter, view, edit, or remove teacher and staff records by shift and section.</p>
        </div>
        <a href="add_teacher_staff.php" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-user-plus"></i> Add New Teacher/Staff
        </a>
    </div>

    <!-- Filter Form Bar -->
    <form method="GET" action="teacher_staff_list.php" style="background: #f8fafc; padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-bottom: 1.5rem;">
        <div class="form-grid" style="align-items: flex-end;">
            <div class="form-group" style="grid-column: span 5; margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.825rem;"><i class="fa-solid fa-clock"></i> Filter by Shift</label>
                <select name="shift" class="form-control" onchange="this.form.submit()">
                    <option value="">All Shifts</option>
                    <option value="1" <?php echo $selectedShift === '1' ? 'selected' : ''; ?>>Morning Shift</option>
                    <option value="2" <?php echo $selectedShift === '2' ? 'selected' : ''; ?>>Day Shift</option>
                </select>
            </div>

            <div class="form-group" style="grid-column: span 5; margin-bottom: 0;">
                <label class="form-label" style="font-size: 0.825rem;"><i class="fa-solid fa-layer-group"></i> Filter by Section / Category</label>
                <select name="section" class="form-control" onchange="this.form.submit()">
                    <option value="">All Sections & Categories</option>
                    <?php foreach($sectionsMap as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php echo $selectedSection === $key ? 'selected' : ''; ?>><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="grid-column: span 2; margin-bottom: 0;">
                <?php if(!empty($selectedShift) || !empty($selectedSection)): ?>
                    <a href="teacher_staff_list.php" class="btn btn-secondary btn-sm" style="width: 100%; height: 42px;">
                        <i class="fa-solid fa-rotate-left"></i> Reset Filter
                    </a>
                <?php else: ?>
                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; height: 42px;">
                        <i class="fa-solid fa-filter"></i> Apply Filter
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <!-- Table Directory -->
    <div class="table-responsive">
        <table id="teacherTable" class="dataTable display">
            <thead>
                <tr>
                    <th style="width: 6%">SL</th>
                    <th style="width: 24%">Name</th>
                    <th style="width: 20%">Designation</th>
                    <th style="width: 14%">Shift</th>
                    <th style="width: 18%">Section / Category</th>
                    <th style="width: 10%">Mobile</th>
                    <th style="width: 8%">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if(count($teachers) > 0){
                    foreach($teachers as $teacher){
                        $shiftLabel = isset($shiftsMap[$teacher['shift']]) ? $shiftsMap[$teacher['shift']] : ($teacher['shift'] ? $teacher['shift'] : 'N/A');
                        $sectionLabel = isset($sectionsMap[$teacher['section']]) ? $sectionsMap[$teacher['section']] : ($teacher['section'] ? $teacher['section'] : 'N/A');
            ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($teacher['sl']); ?></strong></td>
                <td>
                    <div style="font-weight: 700; color: #0f172a;">
                        <i class="fa-solid fa-user" style="color: var(--primary); margin-right: 6px;"></i>
                        <?php echo htmlspecialchars($teacher['name']); ?>
                    </div>
                </td>
                <td>
                    <span style="font-weight: 600; color: #475569;">
                        <?php echo htmlspecialchars($teacher['designation']); ?>
                    </span>
                </td>
                <td>
                    <?php if($teacher['shift'] == '1' || strtolower($teacher['shift']) == 'morning'): ?>
                        <span class="badge badge-info"><i class="fa-solid fa-sun"></i> Morning</span>
                    <?php elseif($teacher['shift'] == '2' || strtolower($teacher['shift']) == 'day'): ?>
                        <span class="badge" style="background-color: var(--amber-light); color: var(--amber-text);"><i class="fa-solid fa-cloud-sun"></i> Day</span>
                    <?php else: ?>
                        <span class="badge badge-secondary"><?php echo htmlspecialchars($shiftLabel); ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="badge badge-active" style="font-size: 0.725rem; font-weight: 600;">
                        <?php echo htmlspecialchars($sectionLabel); ?>
                    </span>
                </td>
                <td>
                    <span style="font-size: 0.85rem; color: #475569; font-weight: 600;">
                        <?php echo !empty($teacher['mobile']) ? htmlspecialchars($teacher['mobile']) : '<em style="color:#94a3b8">N/A</em>'; ?>
                    </span>
                </td>
                <td>
                    <div style="display: flex; gap: 0.35rem;">
                        <a href="edit_teacher_staff.php?id=<?php echo $teacher['id']; ?>" class="btn btn-secondary btn-sm" style="padding: 0.25rem 0.5rem; font-size: 0.775rem;" title="Edit details">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <a href="?delete=<?php echo $teacher['id']; ?>&shift=<?php echo urlencode($selectedShift); ?>&section=<?php echo urlencode($selectedSection); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this teacher/staff record?');" style="padding: 0.25rem 0.5rem; font-size: 0.775rem;" title="Delete record">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php
                    }
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#teacherTable').DataTable({
            "pageLength": 15,
            "order": [[0, "asc"]],
            "language": {
                "search": "<i class='fa-solid fa-magnifying-glass'></i> Search teachers/staff:"
            }
        });
    });
</script>

<?php include_once('includes/footer.php'); ?>
