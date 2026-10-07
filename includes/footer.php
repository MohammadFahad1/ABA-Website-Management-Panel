<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['login']) && $_SESSION['login'] === true;
?>

<?php if ($isLoggedIn): ?>
        </main>

        <!-- Application Footer -->
        <footer class="app-footer">
            <p>&copy; <?php echo date("Y"); ?> <strong>Ahmed Bawany Academy School & College</strong>. System designed & developed by <a href="https://www.linkedin.com/in/fahad4bd/" target="_blank">Md. Fahad Munshi</a>.</p>
        </footer>
    </div> <!-- .main-wrapper -->
</div> <!-- .app-layout -->
<?php endif; ?>

<!-- JavaScript Libraries -->
<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/nicEdit.js"></script>

<script type="text/javascript">
    // Initialize Rich Text Editor on all textareas if nicEditors is loaded
    if (typeof bkLib !== 'undefined') {
        bkLib.onDomLoaded(function() { 
            nicEditors.allTextAreas(); 
        });
    }

    // Sidebar Mobile Toggle & Backdrop Script
    $(document).ready(function() {
        function toggleSidebar() {
            $('#sidebar').toggleClass('show');
            $('#sidebarBackdrop').toggleClass('show');
        }

        function closeSidebar() {
            $('#sidebar').removeClass('show');
            $('#sidebarBackdrop').removeClass('show');
        }

        $('#mobileToggle').on('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });

        $('#sidebarBackdrop').on('click', function() {
            closeSidebar();
        });

        $('.sidebar-nav a').on('click', function() {
            if ($(window).width() <= 992) {
                closeSidebar();
            }
        });
    });
</script>
</body>
</html>