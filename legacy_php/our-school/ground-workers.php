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
    <title>Ground Workers - Learning Academy</title>
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/our-school.css">
</head>
<body>
    <?php renderNavigation('our-school'); ?>
    <?php renderSchoolSubNav(); ?>
    
    <div class="page-header">
        <h1>Grounds & Maintenance Team</h1>
        <p>Meet the team that keeps our school grounds beautiful, safe, and well-maintained.</p>
    </div>
    
    <div class="container">
        <div class="content-box">
            <h2>Maintenance Team Members</h2>
            <div class="worker-profiles">
                <div class="worker-card">
                    <span class="worker-icon">🔧</span>
                    <h3>John Peterson</h3>
                    <p><strong>Head of Maintenance</strong></p>
                    <p>Licensed Electrician & Plumber</p>
                </div>
                
                <div class="worker-card">
                    <span class="worker-icon">🌿</span>
                    <h3>Robert Green</h3>
                    <p><strong>Head Gardener</strong></p>
                    <p>Diploma in Horticulture</p>
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