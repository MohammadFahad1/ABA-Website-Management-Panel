<?php
require_once "./db.php";
session_start();  

$msg = "";
if(isset($_POST['submit'])){
    $msg = $db->upload_notice($_POST, $_FILES);
}

if(isset($_POST['login'])){
    $msg = $db->login($_POST['password']);
}

include_once('includes/header.php');
$isLoggedIn = isset($_SESSION['login']) && $_SESSION['login'] === true;
?>

<?php if($isLoggedIn): ?>
<div class="dashboard-card" style="max-width: 850px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-header-title">
            <h2><i class="fa-solid fa-cloud-arrow-up"></i> Publish New Website Notice</h2>
            <p class="card-header-subtitle">Create and attach official notice documents to display on the school portal.</p>
        </div>
        <a href="notices.php" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> All Notices
        </a>
    </div>

    <?php if(!empty($msg)): ?>
        <div class="alert <?php echo strpos($msg, 'text-danger') !== false ? 'alert-danger' : 'alert-success'; ?>">
            <i class="fa-solid <?php echo strpos($msg, 'text-danger') !== false ? 'fa-triangle-exclamation' : 'fa-circle-check'; ?>"></i>
            <div><?php echo strip_tags($msg); ?></div>
        </div>
    <?php endif; ?>

    <form action="upload-notice.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label class="form-label">Notice Title <span style="color: var(--rose);">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="Enter clear descriptive notice title..." required>
        </div>

        <div class="form-group">
            <label class="form-label">Publishing Date <span style="color: var(--rose);">*</span></label>
            <input type="date" name="date" class="form-control" value="<?php echo date("Y-m-d"); ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Notice Description & Details</label>
            <textarea name="description" rows="6" class="form-control" placeholder="Enter detailed information regarding this notice..."></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Attachment Files (PDF, JPG, PNG, DOCX, XLSX)</label>
            <div class="file-upload-wrapper">
                <i class="fa-solid fa-file-circle-plus"></i>
                <p>Click or drag files here to attach</p>
                <span>Supports multiple files up to 50 MB total</span>
                <input type="file" name="file[]" multiple onchange="updateFileNames(this)">
            </div>
            <div id="fileList" style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--primary); font-weight: 600;"></div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" name="submit" class="btn btn-primary" style="flex: 1;">
                <i class="fa-solid fa-paper-plane"></i> Publish Notice
            </button>
            <a href="notices.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    function updateFileNames(input) {
        var fileList = document.getElementById('fileList');
        if (input.files && input.files.length > 0) {
            var names = [];
            for (var i = 0; i < input.files.length; i++) {
                names.push('<i class="fa-solid fa-paperclip"></i> ' + input.files[i].name);
            }
            fileList.innerHTML = 'Selected: ' + names.join(', ');
        } else {
            fileList.innerHTML = '';
        }
    }
</script>

<?php else: ?>
<!-- Centered High-End Login Interface -->
<div class="login-card">
    <div class="login-header">
        <div class="login-logo">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h2>Ahmed Bawany Academy</h2>
        <p>Admin Portal Authentication</p>
    </div>

    <?php if(!empty($msg)): ?>
        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
            <i class="fa-solid fa-lock"></i>
            <div>Authentication Failed. Please check password.</div>
        </div>
    <?php endif; ?>

    <form action="upload-notice.php" method="post">
        <div class="form-group" style="position: relative;">
            <label class="form-label" style="font-weight: 700;">Security Password</label>
            <div style="position: relative;">
                <input type="password" name="password" class="form-control" placeholder="Enter admin password..." style="padding-left: 2.75rem;" required autofocus>
                <i class="fa-solid fa-key" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
            </div>
        </div>

        <button type="submit" name="login" class="btn btn-primary" style="width: 100%; margin-top: 1rem; padding: 0.85rem;">
            <i class="fa-solid fa-right-to-bracket"></i> Authenticate & Enter Dashboard
        </button>
    </form>

    <div style="text-align: center; margin-top: 1.75rem; font-size: 0.775rem; color: #64748b;">
        <i class="fa-solid fa-circle-info"></i> System Access restricted to authorized personnel only.
    </div>
</div>
<?php endif; ?>

<?php include_once('includes/footer.php'); ?>