<?php
if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    echo "<script>window.location = 'upload-notice.php';</script>";
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand-icon">
            <i class="fa-solid fa-graduation-cap"></i>
        </div>
        <div class="sidebar-brand-text">
            <h2>ABA Panel</h2>
            <p>Admin Console</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main Management</div>

        <a href="notices.php" class="nav-item <?php echo $current_page === 'notices.php' ? 'active' : ''; ?>">
            <i class="fa-solid fa-bullhorn"></i>
            <span>All Notices</span>
        </a>

        <a href="upload-notice.php" class="nav-item <?php echo $current_page === 'upload-notice.php' ? 'active' : ''; ?>">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <span>Upload Notice</span>
        </a>

        <a href="front-carousel.php" class="nav-item <?php echo ($current_page === 'front-carousel.php' || $current_page === 'upload-slide.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-images"></i>
            <span>Front Slider</span>
        </a>

        <div class="nav-section-label">Media & Staff</div>

        <a href="gallery_image_upload.php" class="nav-item <?php echo $current_page === 'gallery_image_upload.php' ? 'active' : ''; ?>">
            <i class="fa-solid fa-camera"></i>
            <span>Upload Gallery Image</span>
        </a>

        <a href="gallery_image_list.php" class="nav-item <?php echo $current_page === 'gallery_image_list.php' ? 'active' : ''; ?>">
            <i class="fa-solid fa-photo-film"></i>
            <span>Gallery Records</span>
        </a>

        <a href="teacher_staff_list.php" class="nav-item <?php echo ($current_page === 'teacher_staff_list.php' || $current_page === 'edit_teacher_staff.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-address-book"></i>
            <span>Teacher & Staff Directory</span>
        </a>

        <a href="add_teacher_staff.php" class="nav-item <?php echo $current_page === 'add_teacher_staff.php' ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-plus"></i>
            <span>Add Teacher/Staff</span>
        </a>

        <div class="nav-section-label">System</div>

        <a href="https://ahmedbawanyacademy.edu.bd/" target="_blank" class="nav-item">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
            <span>Back to Website</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-profile-widget">
            <div class="avatar">FM</div>
            <div class="user-info">
                <div class="user-name">Fahad Munshi</div>
                <div class="user-role">
                    <span class="status-dot"></span>
                    <span>Administrator</span>
                </div>
            </div>
        </div>
        <a href="?logout" class="nav-item nav-item-logout" onclick="return confirm('Are you sure you want to log out?');">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout System</span>
        </a>
    </div>
</aside>