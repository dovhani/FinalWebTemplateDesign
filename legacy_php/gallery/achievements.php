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
    <title>Achievements - Learning Academy</title>
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/gallery.css">
</head>
<body>
    <?php renderNavigation('gallery'); ?>
    <?php renderGallerySubNav(); ?>
    
    <div class="page-header">
        <h1>Achievements Gallery</h1>
        <p>Celebrating our students' successes and accomplishments.</p>
    </div>
    
    <div class="container">
        <div class="content-box">
            <h2>Our Achievements</h2>
            <div class="achievement-cards">
                <div class="achievement-card">
                    <div class="achievement-year">2023</div>
                    <h3 class="achievement-title">🏆 District Science Fair Winners</h3>
                    <p>Our Grade 10 students won first place in the Regional Science Fair.</p>
                </div>
                
                <div class="achievement-card">
                    <div class="achievement-year">2023</div>
                    <h3 class="achievement-title">🎓 98% Matric Pass Rate</h3>
                    <p>Class of 2023 achieved a 98% pass rate.</p>
                </div>
            </div>
        </div>
        
        <div class="content-box" style="text-align: center;">
            <h3>Quick Navigation</h3>
            <div style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
                <a href="<?php echo $base_path; ?>/index.php" class="btn">🏠 Home</a>
                <a href="<?php echo $base_path; ?>/gallery.php" class="btn btn-secondary">← Gallery</a>
            </div>
        </div>
    </div>
    
    <!-- Include footer -->
    <?php
        include '../includes/footer.php';
        renderFooter();
    ?>
    
    <script src="/js/main.js"></script>
</body>
</html>