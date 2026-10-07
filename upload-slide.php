<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['login'] !== true){
    header("Location: upload-notice.php");
    exit;
}
if(!isset($_GET['slide_change']) || empty($_GET['slide_change'])){
    header("Location: front-carousel.php");
    exit;
}
require_once "db.php";

$msg = "";
if(isset($_POST['change'])){
    $msg = $db->slide_change($_POST, $_FILES);
}

include_once('includes/header.php');

$slideNoInt = intval($_GET['slide_change']);
$carousel = $db->front_carousel();
$currentTitle = isset($carousel[0]['slide'.$slideNoInt.'_title']) ? $carousel[0]['slide'.$slideNoInt.'_title'] : '';
$currentDesc = isset($carousel[0]['slide'.$slideNoInt.'_desc']) ? $carousel[0]['slide'.$slideNoInt.'_desc'] : '';
$currentImg = isset($carousel[0]['slide_'.$slideNoInt]) ? $carousel[0]['slide_'.$slideNoInt] : '';
?>

<div class="dashboard-card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <div class="card-header-title">
            <h2><i class="fa-solid fa-pen-to-square"></i> Edit Carousel Slide <?php echo htmlspecialchars($_GET['slide_change']); ?></h2>
            <p class="card-header-subtitle">Update slide image, banner title, and caption text.</p>
        </div>
        <a href="front-carousel.php" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Slider
        </a>
    </div>

    <?php if(!empty($msg)): ?>
        <div class="alert <?php echo strpos($msg, 'text-danger') !== false ? 'alert-danger' : 'alert-success'; ?>">
            <i class="fa-solid <?php echo strpos($msg, 'text-danger') !== false ? 'fa-triangle-exclamation' : 'fa-circle-check'; ?>"></i>
            <div><?php echo strip_tags($msg); ?></div>
        </div>
    <?php endif; ?>

    <?php if(!empty($currentImg)): ?>
        <div style="margin-bottom: 1.5rem; text-align: center;">
            <label class="form-label" style="text-align: left;">Current Slide Image Preview</label>
            <div style="width: 100%; height: 220px; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color); background: #f1f5f9;">
                <img src="<?php echo htmlspecialchars($currentImg); ?>" alt="Current Slide" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="slide" value="<?php echo htmlspecialchars($_GET['slide_change']); ?>">

        <div class="form-group">
            <label class="form-label">Replace Slide Image (JPG, JPEG only)</label>
            <div class="file-upload-wrapper">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <p>Click or drag new banner image here</p>
                <span>Recommended resolution: 1920 x 700 px (Max 5 MB)</span>
                <input type="file" name="file" accept=".jpg,.jpeg" onchange="previewImageName(this)">
            </div>
            <div id="slideFileName" style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--primary); font-weight: 600;"></div>
        </div>

        <div class="form-group">
            <label class="form-label">Slide Banner Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter headline title for slide..." value="<?php echo htmlspecialchars($currentTitle); ?>">
        </div>

        <div class="form-group">
            <label class="form-label">Slide Subtitle / Description</label>
            <input type="text" name="description" class="form-control" placeholder="Enter short supporting description..." value="<?php echo htmlspecialchars($currentDesc); ?>">
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" name="change" class="btn btn-primary" style="flex: 1;">
                <i class="fa-solid fa-floppy-disk"></i> Save Slide Changes
            </button>
            <a href="front-carousel.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    function previewImageName(input) {
        var el = document.getElementById('slideFileName');
        if(input.files && input.files[0]) {
            el.innerHTML = '<i class="fa-solid fa-image"></i> Selected: ' + input.files[0].name;
        } else {
            el.innerHTML = '';
        }
    }
</script>

<?php include_once('includes/footer.php'); ?>