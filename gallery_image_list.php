<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['login'] !== true){
    header("Location: upload-notice.php");
    exit;
}
require_once "./db.php";

$msg = "";

// Handle Delete Request via PDO
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    try {
        // Fetch image path
        $stmt = $db->pdo->prepare("SELECT image_url FROM website_gallery WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($image) {
            $imagePath = $image['image_url'];
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }

            $delStmt = $db->pdo->prepare("DELETE FROM website_gallery WHERE id = :id");
            $delStmt->execute([':id' => $id]);
            $msg = "<p class='text-success'>Gallery image deleted successfully!</p>";
        }
    } catch (PDOException $e) {
        $msg = "<p class='text-danger'>Error deleting image: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Fetch records
$galleryImages = $db->get_all_gallery_images();
$totalImages = count($galleryImages);

include_once('includes/header.php');
?>

<div class="dashboard-card">
    <div class="card-header">
        <div class="card-header-title">
            <h2><i class="fa-solid fa-photo-film"></i> Photo Gallery Directory</h2>
            <p class="card-header-subtitle">Manage uploaded website gallery photos and captions (Total: <?php echo $totalImages; ?> photos).</p>
        </div>
        <a href="gallery_image_upload.php" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Upload New Photo
        </a>
    </div>

    <?php if(!empty($msg)): ?>
        <div class="alert <?php echo strpos($msg, 'text-danger') !== false ? 'alert-danger' : 'alert-success'; ?>">
            <i class="fa-solid <?php echo strpos($msg, 'text-danger') !== false ? 'fa-triangle-exclamation' : 'fa-circle-check'; ?>"></i>
            <div><?php echo strip_tags($msg); ?></div>
        </div>
    <?php endif; ?>

    <?php if ($totalImages > 0): ?>
        <div class="gallery-grid">
            <?php foreach ($galleryImages as $row): ?>
                <div class="gallery-card">
                    <div class="gallery-img-wrapper">
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" onerror="this.src='https://via.placeholder.com/300x200?text=No+Image';">
                        <span class="badge badge-info" style="position: absolute; top: 10px; right: 10px; font-weight: 800;">
                            #<?php echo $row['id']; ?>
                        </span>
                    </div>
                    <div class="gallery-card-body">
                        <h3 class="gallery-card-title" title="<?php echo htmlspecialchars($row['title']); ?>">
                            <?php echo htmlspecialchars($row['title']); ?>
                        </h3>
                        <div class="gallery-card-actions">
                            <a href="<?php echo htmlspecialchars($row['image_url']); ?>" target="_blank" class="btn btn-secondary btn-sm" style="padding: 0.35rem 0.65rem; font-size: 0.775rem;">
                                <i class="fa-solid fa-expand"></i> View
                            </a>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this gallery photo?');" style="padding: 0.35rem 0.65rem; font-size: 0.775rem;">
                                <i class="fa-solid fa-trash-can"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
            <i class="fa-solid fa-images" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
            <h3>No Gallery Images Found</h3>
            <p>Upload photos to display them in the portal gallery.</p>
            <a href="gallery_image_upload.php" class="btn btn-primary" style="margin-top: 1rem;">
                <i class="fa-solid fa-upload"></i> Upload First Photo
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include_once('includes/footer.php'); ?>
