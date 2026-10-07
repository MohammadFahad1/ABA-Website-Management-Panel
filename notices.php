<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['login'] !== true){
    header("Location: upload-notice.php");
    exit;
}
require_once "db.php";

$msg = "";
if(isset($_POST['submit'])){
    $msg = $db->upload_notice($_POST, $_FILES);
}
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $msg = $db->delete_notice($_GET['delete']);
}

include_once('includes/header.php');

// Fetch all notices
$notices = $db->notices("", false);
$totalNotices = count($notices);
$activeCount = 0;
$inactiveCount = 0;
foreach($notices as $n) {
    if($n['status'] == 1) $activeCount++;
    else $inactiveCount++;
}
?>

<!-- Quick Stats Row -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-bullhorn"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo $totalNotices; ?></div>
            <div class="stat-label">Total Notices</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon emerald">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo $activeCount; ?></div>
            <div class="stat-label">Active Notices</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon rose">
            <i class="fa-solid fa-circle-xmark"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo $inactiveCount; ?></div>
            <div class="stat-label">Inactive Notices</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon sky">
            <i class="fa-solid fa-folder-open"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value">PDF & Media</div>
            <div class="stat-label">Attached Files</div>
        </div>
    </div>
</div>

<?php if(!empty($msg)): ?>
    <div class="alert <?php echo strpos($msg, 'text-danger') !== false ? 'alert-danger' : 'alert-success'; ?>">
        <i class="fa-solid <?php echo strpos($msg, 'text-danger') !== false ? 'fa-triangle-exclamation' : 'fa-circle-check'; ?>"></i>
        <div><?php echo strip_tags($msg); ?></div>
    </div>
<?php endif; ?>

<!-- Main Table Card -->
<div class="dashboard-card">
    <div class="card-header">
        <div class="card-header-title">
            <h2><i class="fa-solid fa-list-check"></i> Published Notices Directory</h2>
            <p class="card-header-subtitle">Manage, activate/inactivate, or delete posted official notices.</p>
        </div>
        <a href="upload-notice.php" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> New Notice
        </a>
    </div>

    <div class="table-responsive">
        <table id="noticeTable" class="dataTable display">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 12%">Date</th>
                    <th style="width: 25%">Title</th>
                    <th style="width: 33%">Description</th>
                    <th style="width: 12%">Attachments</th>
                    <th style="width: 13%">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if($totalNotices > 0){
                    $i = 0;
                    foreach($notices as $notice){ $i++;
            ?>
            <tr>
                <td><strong><?php echo $i; ?></strong></td>
                <td>
                    <span style="font-weight: 600; color: #475569;">
                        <i class="fa-regular fa-calendar-days" style="color: #64748b; margin-right: 4px;"></i>
                        <?php echo date("d M Y", strtotime($notice['date'])); ?>
                    </span>
                </td>
                <td>
                    <div style="font-weight: 700; color: #0f172a; margin-bottom: 2px;">
                        <?php echo htmlspecialchars($notice['title']); ?>
                    </div>
                </td>
                <td>
                    <div style="color: #475569; font-size: 0.85rem; max-height: 80px; overflow-y: auto;">
                        <?php echo $notice['description']; ?>
                    </div>
                </td>
                <td>
                    <?php
                        $files = $db->file($notice['id']);
                        if(count($files) > 0){
                            $x = 0;
                            foreach($files as $file){ $x++;
                                $ext = strtolower(pathinfo($file['files'], PATHINFO_EXTENSION));
                                $icon = 'fa-file';
                                if($ext === 'pdf') $icon = 'fa-file-pdf';
                                else if(in_array($ext, ['jpg','jpeg','png'])) $icon = 'fa-file-image';
                                else if(in_array($ext, ['doc','docx'])) $icon = 'fa-file-word';
                    ?>
                                <a href="<?php echo htmlspecialchars($file['files']); ?>" class="file-pill" target="_blank" title="View File <?php echo $x; ?>">
                                    <i class="fa-solid <?php echo $icon; ?>"></i> File <?php echo $x; ?>
                                </a>
                    <?php          
                            }
                        } else {
                            echo '<span style="color: #94a3b8; font-size: 0.8rem;">No files</span>';
                        }
                    ?>
                </td>
                <td>
                    <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                        <a href="notice-status.php?nid=<?php echo $notice['id']; ?>" class="badge <?php echo $notice['status'] == 1 ? 'badge-active' : 'badge-inactive'; ?>" title="Click to toggle status">
                            <i class="fa-solid <?php echo $notice['status'] == 1 ? 'fa-check' : 'fa-xmark'; ?>"></i>
                            <?php echo $notice['status'] == 1 ? 'Active' : 'Inactive'; ?>
                        </a>
                        
                        <?php if($notice['status'] != 1){ ?>
                            <a href="?delete=<?php echo $notice['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete();" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;">
                                <i class="fa-solid fa-trash-can"></i> Delete
                            </a>
                        <?php } ?>
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
    function confirmDelete(){
        return confirm("Are you sure you want to permanently delete this notice record?");
    }

    $(document).ready(function () {
        $('#noticeTable').DataTable({
            "pageLength": 10,
            "order": [[0, "asc"]],
            "language": {
                "search": "<i class='fa-solid fa-magnifying-glass'></i> Search records:"
            }
        });
    });
</script>

<?php include_once('includes/footer.php'); ?>