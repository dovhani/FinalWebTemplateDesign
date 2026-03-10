<?php
    // our-school/sgb.php - FIXED
    $base_path = '/';
    include '../includes/navigation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Gallery - Learning Academy</title>
    
    <!-- Use ../ to go up ONE level to root -->
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/gallery.css">
</head>
<body>
    <?php renderNavigation('gallery'); ?>
    <?php renderGallerySubNav(); ?>
    
    <div class="page-header">
        <h1>Students Gallery</h1>
        <p>Capture moments from classroom activities, events, and daily school life.</p>
    </div>
    
    <div class="container">
        <div class="content-box">
            <h2>Student Photos</h2>
            <div class="gallery-grid">
                <div class="gallery-item" style="background: linear-gradient(135deg, #1a5f7a, #2c3e50);">
                    <div class="item-img">Science Class</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include footer -->
    <?php
        include '../includes/footer.php';
        renderFooter();
    ?>
    
    <!-- Use ../ to go up ONE level to root -->
    <script src="../js/main.js"></script>
</body>
</html>