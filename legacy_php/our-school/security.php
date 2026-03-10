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
    <title>Security - Learning Academy</title>
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/our-school.css">
</head>
<body>
    <?php renderNavigation('our-school'); ?>
    <?php renderSchoolSubNav(); ?>
    
    <div class="page-header">
        <h1>Security & Safety Team</h1>
        <p>Ensuring a safe and secure learning environment for all students and staff.</p>
    </div>
    
    <div class="container">
        <div class="content-box">
            <h2>Security Team Members</h2>
            <div class="worker-profiles">
                <div class="worker-card">
                    <span class="worker-icon">🛡️</span>
                    <h3>Captain Mark Johnson</h3>
                    <p><strong>Security Manager</strong></p>
                    <p>20 years law enforcement</p>
                </div>
                
                <div class="worker-card">
                    <span class="worker-icon">👮</span>
                    <h3>Officer Sarah Williams</h3>
                    <p><strong>Deputy Security Manager</strong></p>
                    <p>15 years security experience</p>
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
    
    <?php
        include '../includes/footer.php';
        renderFooter(); 
    ?>
    <script src="/js/main.js"></script>
</body>
</html>