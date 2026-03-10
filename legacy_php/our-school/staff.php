<?php
// our-school/staff.php - FIXED VERSION WITH ROOT PATHS

// Define base path - THIS IS THE KEY FIX
$base_path = '/'; // Change to your actual folder name

// Include navigation from root
include '../includes/navigation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Directory - Learning Academy</title>
    
    <!-- FIXED: Use absolute paths for CSS -->
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/our-school.css">
    
    <style>
        /* Page-specific styles */
        .staff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .staff-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .staff-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .staff-header {
            background: linear-gradient(135deg, #1a5f7a, #2c3e50);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .staff-body {
            padding: 20px;
        }
        
        .staff-position {
            color: #ff6b35;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .staff-qualifications {
            color: #495057;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <!-- Navigation will now work from any folder -->
    <?php renderNavigation('our-school'); ?>
    
    <!-- Sub-navigation -->
    <?php renderSchoolSubNav(); ?>
    
    <div class="page-header">
        <h1>Staff Directory</h1>
        <p>Meet our dedicated team of educators and support staff.</p>
    </div>
    
    <div class="container">
        <!-- Leadership Team -->
        <div class="content-box">
            <h2>Leadership Team</h2>
            <div class="staff-grid">
                <div class="staff-card">
                    <div class="staff-header">
                        <h3>Jane Smith</h3>
                        <p>Principal</p>
                    </div>
                    <div class="staff-body">
                        <p class="staff-position">Principal & Academic Head</p>
                        <p class="staff-qualifications">M.Ed Educational Leadership, B.Ed, 15 years experience</p>
                        <p>Email: principal@learningacademy.edu</p>
                        <p>Phone: Ext. 101</p>
                        <p>Office: Administration Building, Room 101</p>
                    </div>
                </div>
                
                <div class="staff-card">
                    <div class="staff-header">
                        <h3>Robert Davis</h3>
                        <p>Deputy Principal</p>
                    </div>
                    <div class="staff-body">
                        <p class="staff-position">Deputy Principal & Discipline Head</p>
                        <p class="staff-qualifications">B.Ed Honours, 12 years experience</p>
                        <p>Email: deputy@learningacademy.edu</p>
                        <p>Phone: Ext. 102</p>
                        <p>Office: Administration Building, Room 102</p>
                    </div>
                </div>
                
                <div class="staff-card">
                    <div class="staff-header">
                        <h3>Mary Wilson</h3>
                        <p>Head of Academics</p>
                    </div>
                    <div class="staff-body">
                        <p class="staff-position">Head of Academics & Curriculum</p>
                        <p class="staff-qualifications">M.Sc Mathematics, B.Ed, 10 years experience</p>
                        <p>Email: academics@learningacademy.edu</p>
                        <p>Phone: Ext. 103</p>
                        <p>Office: Academic Block, Room 201</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Links to go back -->
        <div class="content-box" style="text-align: center; background-color: #f8f9fa;">
            <h3>Navigation Help</h3>
            <p>Use the main navigation above or these quick links:</p>
            <div style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
                <a href="<?php echo $base_path; ?>/index.php" class="btn">🏠 Home Page</a>
                <a href="<?php echo $base_path; ?>/our-school.php" class="btn btn-secondary">← Back to Our School</a>
                <a href="<?php echo $base_path; ?>/contact.php" class="btn">📞 Contact Us</a>
            </div>
        </div>
    </div>
    
    <!-- Footer - FIXED: Removed duplicate footer code -->
    <?php 
        // Include and render footer correctly
        include '../includes/footer.php';
        renderFooter(); 
    ?>
    
    <!-- FIXED: Use absolute path for JavaScript -->
  <script src="/js/main.js"></script>
    <script>
        // Additional JavaScript for this page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Staff page loaded from: <?php echo $base_path; ?>');
            
            // Test if navigation works
            const navLinks = document.querySelectorAll('.nav-menu a');
            navLinks.forEach(link => {
                console.log('Link found:', link.href);
            });
        });
    </script>
</body>
</html>