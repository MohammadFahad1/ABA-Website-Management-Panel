<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['login'] !== true){
    header("Location: upload-notice.php");
    exit;
}
require_once "./db.php";

$msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $msg = $db->upload_to_gallery($_POST, $_FILES);
}

include_once('includes/header.php');
?>

<div class="dashboard-card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-header-title">
            <h2><i class="fa-solid fa-camera"></i> Upload New Gallery Photo</h2>
            <p class="card-header-subtitle">Add campus, event, and activity photos to the website media gallery.</p>
        </div>
        <a href="gallery_image_list.php" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-photo-film"></i> View Gallery Records
        </a>
    </div>

    <?php if(!empty($msg)): ?>
        <div class="alert <?php echo strpos($msg, 'text-danger') !== false ? 'alert-danger' : 'alert-success'; ?>">
            <i class="fa-solid <?php echo strpos($msg, 'text-danger') !== false ? 'fa-triangle-exclamation' : 'fa-circle-check'; ?>"></i>
            <div><?php echo strip_tags($msg); ?></div>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label class="form-label">Photo Title / Caption <span style="color: var(--rose);">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="Enter title or event name for this image..." required>
        </div>

        <div class="form-group">
            <label class="form-label">Select Image File (JPG, JPEG) <span style="color: var(--rose);">*</span></label>
            <div class="file-upload-wrapper">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <p>Click or drag JPG photo here to upload</p>
                <span>Maximum file size: 5 MB</span>
                <input type="file" name="file" accept=".jpg,.jpeg" required onchange="previewGalleryFileName(this)">
            </div>
            <div id="galleryFileName" style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--primary); font-weight: 600;"></div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <i class="fa-solid fa-upload"></i> Upload to Photo Gallery
            </button>
            <a href="gallery_image_list.php" class="btn btn-secondary">Manage Gallery</a>
        </div>
    </form>
</div>

<script>
    function previewGalleryFileName(input) {
        var el = document.getElementById('galleryFileName');
        if(input.files && input.files[0]) {
            el.innerHTML = '<i class="fa-solid fa-image"></i> Selected: ' + input.files[0].name;
        } else {
            el.innerHTML = '';
        }
    }
</script>

<?php include_once('includes/footer.php'); ?>