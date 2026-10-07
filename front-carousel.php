<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['login'] !== true){
    header("Location: upload-notice.php");
    exit;
}
require_once "db.php";
include_once('includes/header.php');

$notices = $db->front_carousel();
$slideData = count($notices) > 0 ? $notices[0] : [];
?>

<div class="dashboard-card">
    <div class="card-header">
        <div class="card-header-title">
            <h2><i class="fa-solid fa-images"></i> Homepage Banner Carousel Slider</h2>
            <p class="card-header-subtitle">Manage up to 7 hero slides displayed on the main school portal homepage.</p>
        </div>
    </div>

    <div class="gallery-grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));">
        <?php for($i = 1; $i <= 7; $i++): 
            $slideImg = isset($slideData['slide_'.$i]) ? $slideData['slide_'.$i] : 'images/front-carousel-slider/0'.$i.'.jpg';
            $slideTitle = isset($slideData['slide'.$i.'_title']) ? $slideData['slide'.$i.'_title'] : '';
            $slideDesc = isset($slideData['slide'.$i.'_desc']) ? $slideData['slide'.$i.'_desc'] : '';
            $formattedSlideNo = sprintf("%02d", $i);
        ?>
            <div class="gallery-card">
                <div class="gallery-img-wrapper" style="height: 200px;">
                    <img src="<?php echo htmlspecialchars($slideImg); ?>" alt="Slide <?php echo $i; ?>" onerror="this.src='https://via.placeholder.com/600x300?text=Slide+<?php echo $i; ?>';">
                    <span class="badge badge-info" style="position: absolute; top: 12px; left: 12px; font-weight: 800;">
                        <i class="fa-solid fa-layer-group"></i> Slide <?php echo $formattedSlideNo; ?>
                    </span>
                </div>
                <div class="gallery-card-body">
                    <h3 class="gallery-card-title" style="font-size: 1.05rem; margin-bottom: 0.35rem;">
                        <?php echo !empty($slideTitle) ? htmlspecialchars($slideTitle) : '<em style="color:#94a3b8">No Title Set</em>'; ?>
                    </h3>
                    <p style="font-size: 0.825rem; color: var(--text-muted); margin-bottom: 1.25rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.4em;">
                        <?php echo !empty($slideDesc) ? htmlspecialchars($slideDesc) : 'No description specified for this banner slide.'; ?>
                    </p>
                    <a href="upload-slide.php?slide_change=<?php echo $formattedSlideNo; ?>" class="btn btn-primary btn-sm" style="width: 100%;">
                        <i class="fa-solid fa-pen-to-square"></i> Change Slide Image & Text
                    </a>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>