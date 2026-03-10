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
    <title>SGB - Learning Academy</title>
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/our-school.css">
</head>
<body>
    <?php renderNavigation('our-school'); ?>
    <?php renderSchoolSubNav(); ?>
    
    <div class="page-header">
        <h1>School Governing Body</h1>
        <p>Meet our elected representatives who help guide our school's vision and policies.</p>
    </div>
    
    <div class="container">
        <div class="content-box">
            <h2>SGB Members 2023-2025</h2>
            <div class="staff-grid">
                <div class="staff-card">
                    <div class="staff-header">
                        <h3>Mr. David Johnson</h3>
                        <p>SGB Chairperson</p>
                    </div>
                    <div class="staff-body">
                        <p class="staff-position">Parent Representative</p>
                        <p>Email: sgb.chair@learningacademy.edu</p>
                    </div>
                </div>
                
                <div class="staff-card">
                    <div class="staff-header">
                        <h3>Ms. Sarah Williams</h3>
                        <p>SGB Secretary</p>
                    </div>
                    <div class="staff-body">
                        <p class="staff-position">Teacher Representative</p>
                        <p>Email: sgb.secretary@learningacademy.edu</p>
                    </div>
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