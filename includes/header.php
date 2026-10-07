<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['login']) && $_SESSION['login'] === true;
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isLoggedIn ? "Ahmed Bawany Academy - Admin Panel" : "Admin Login - Ahmed Bawany Academy"; ?></title>
    
    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    
    <!-- Custom Dashboard Design System CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body class="<?php echo !$isLoggedIn && $currentPage === 'upload-notice.php' ? 'login-body' : ''; ?>">

<?php if ($isLoggedIn): ?>
<div class="app-layout">
    <!-- Sidebar Mobile Backdrop Overlay -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar Navigation -->
    <?php include __DIR__ . "/../menu.php"; ?>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="page-title-area">
                    <h1>
                        <?php 
                            switch($currentPage) {
                                case 'notices.php': echo "Notice Management"; break;
                                case 'upload-notice.php': echo "Upload Notice"; break;
                                case 'front-carousel.php': echo "Front Carousel Slider"; break;
                                case 'upload-slide.php': echo "Edit Carousel Slide"; break;
                                case 'gallery_image_upload.php': echo "Upload Gallery Image"; break;
                                case 'gallery_image_list.php': echo "Gallery Image Records"; break;
                                case 'teacher_staff_list.php': echo "Teacher & Staff Directory"; break;
                                case 'add_teacher_staff.php': echo "Add Teacher & Staff"; break;
                                case 'edit_teacher_staff.php': echo "Edit Teacher & Staff"; break;
                                default: echo "Admin Dashboard"; break;
                            }
                        ?>
                    </h1>
                    <p>Ahmed Bawany Academy School & College Management Panel</p>
                </div>
            </div>

            <div class="top-navbar-actions">
                <a href="https://ahmedbawanyacademy.edu.bd/" target="_blank" class="btn-external-site">
                    <i class="fa-solid fa-globe"></i>
                    <span>Main Website</span>
                </a>
            </div>
        </header>

        <!-- Main Content Container -->
        <main class="main-content">
<?php endif; ?>