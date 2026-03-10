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
    <title>Food Handlers - Learning Academy</title>
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/our-school.css">
</head>
<body>
    <?php renderNavigation('our-school'); ?>
    <?php renderSchoolSubNav(); ?>
    
    <div class="page-header">
        <h1>Food Services Team</h1>
        <p>Meet our dedicated food handlers who ensure our students receive nutritious and safe meals.</p>
    </div>
    
    <div class="container">
        <div class="content-box">
            <h2>Food Services Team</h2>
            <div class="worker-profiles">
                <div class="worker-card">
                    <span class="worker-icon">👨‍🍳</span>
                    <h3>Chef Michael Brown</h3>
                    <p><strong>Head Chef</strong></p>
                    <p>Food Safety Level 3 Certified</p>
                </div>
                
                <div class="worker-card">
                    <span class="worker-icon">👩‍🍳</span>
                    <h3>Sarah Johnson</h3>
                    <p><strong>Assistant Chef</strong></p>
                    <p>Food Safety Level 2 Certified</p>
                </div>
            </div>
        </div>
        
        <div class="content-box" style="text-align: center;">
            <h3>Quick Navigation</h3>
            <div style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
                <a href="<?php echo $base_path; ?>/index.php" class="btn">🏠 Home</a>
                <a href="<?php echo $base_path; ?>/our-school.php" class="btn btn-secondary">← Our School</a>
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