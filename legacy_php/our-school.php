<?php
    // our-school.php - Our School Page
    include 'includes/navigation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our School - Learning Academy</title>
    <!-- ✅ CORRECTED: Use absolute paths starting with / -->
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/our-school.css">
</head>
<body>
    <?php renderNavigation('our-school'); ?>
    
    <div class="page-header">
        <h1>Our School</h1>
        <p>Discover our school community, facilities, and dedicated staff members.</p>
    </div>
    
    <?php renderSchoolSubNav(); ?>
    
    <div class="container">
        <!-- School Introduction -->
        <div class="content-box school-intro">
            <h2>Welcome to Learning Academy</h2>
            <p>Established in 1995, Learning Academy has been providing quality education to students from Grade R to Grade 12. Our school is committed to academic excellence, character development, and holistic education.</p>
            
            <div class="cards-grid">
                <div class="card">
                    <div class="card-img">🎓</div>
                    <div class="card-content">
                        <h3>Our Mission</h3>
                        <p>To provide a nurturing environment that fosters academic excellence, moral integrity, and social responsibility.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-img">🌟</div>
                    <div class="card-content">
                        <h3>Our Vision</h3>
                        <p>To be a leading educational institution that develops innovative thinkers and responsible citizens.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-img">💡</div>
                    <div class="card-content">
                        <h3>Our Values</h3>
                        <p>Excellence, Integrity, Respect, Diversity, and Community Service.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- School Departments -->
        <div class="content-box department-cards">
            <h2>School Departments</h2>
            <div class="cards-grid">
                <div class="card department-card">
                    <div class="card-content">
                        <h3>Foundation Phase</h3>
                        <p>Grades R-3 focusing on literacy, numeracy, and life skills development.</p>
                        <a href="#" class="btn btn-secondary">Learn More</a>
                    </div>
                </div>
                
                <div class="card department-card">
                    <div class="card-content">
                        <h3>Intermediate Phase</h3>
                        <p>Grades 4-6 with specialized subjects and skill development.</p>
                        <a href="#" class="btn btn-secondary">Learn More</a>
                    </div>
                </div>
                
                <div class="card department-card">
                    <div class="card-content">
                        <h3>Senior Phase</h3>
                        <p>Grades 7-9 preparing students for subject choices in high school.</p>
                        <a href="#" class="btn btn-secondary">Learn More</a>
                    </div>
                </div>
                
                <div class="card department-card">
                    <div class="card-content">
                        <h3>FET Phase</h3>
                        <p>Grades 10-12 with academic and vocational streams.</p>
                        <a href="#" class="btn btn-secondary">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Staff Preview -->
        <div class="staff-preview">
            <h2>Meet Our Leadership Team</h2>
            <div class="staff-grid-preview">
                <div class="staff-member-preview">
                    <div class="staff-avatar">JS</div>
                    <h3>Jane Smith</h3>
                    <p>Principal</p>
                    <p>M.Ed Educational Leadership</p>
                    <!-- ✅ CORRECTED: Use absolute path -->
                    <a href="/our-school/staff.php" class="btn btn-secondary">View Profile</a>
                </div>
                
                <div class="staff-member-preview">
                    <div class="staff-avatar">RD</div>
                    <h3>Robert Davis</h3>
                    <p>Deputy Principal</p>
                    <p>B.Ed Honours</p>
                    <!-- ✅ CORRECTED: Use absolute path -->
                    <a href="/our-school/staff.php" class="btn btn-secondary">View Profile</a>
                </div>
                
                <div class="staff-member-preview">
                    <div class="staff-avatar">MW</div>
                    <h3>Mary Wilson</h3>
                    <p>Head of Academics</p>
                    <p>M.Sc Mathematics</p>
                    <!-- ✅ CORRECTED: Use absolute path -->
                    <a href="/our-school/staff.php" class="btn btn-secondary">View Profile</a>
                </div>
            </div>
            <div style="text-align: center; margin-top: 30px;">
                <!-- ✅ CORRECTED: Use absolute path -->
                <a href="/our-school/staff.php" class="btn">View All Staff Members</a>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="content-box quick-links-section">
            <h2>School Personnel</h2>
            <div class="quick-links-grid">
                <!-- ✅ CORRECTED: All links use absolute paths -->
                <a href="/our-school/staff.php" class="quick-link-item">
                    <span class="quick-link-icon">👩‍🏫</span>
                    <h3>Teaching Staff</h3>
                    <p>Meet our qualified educators</p>
                </a>
                
                <a href="/our-school/sgb.php" class="quick-link-item">
                    <span class="quick-link-icon">🤝</span>
                    <h3>SGB Members</h3>
                    <p>School Governing Body</p>
                </a>
                
                <a href="/our-school/food-handlers.php" class="quick-link-item">
                    <span class="quick-link-icon">🍎</span>
                    <h3>Food Handlers</h3>
                    <p>Nutrition & Food Services</p>
                </a>
                
                <a href="/our-school/ground-workers.php" class="quick-link-item">
                    <span class="quick-link-icon">🌳</span>
                    <h3>Ground Workers</h3>
                    <p>Maintenance & Facilities</p>
                </a>
                
                <a href="/our-school/security.php" class="quick-link-item">
                    <span class="quick-link-icon">👮</span>
                    <h3>Security Team</h3>
                    <p>Safety & Security</p>
                </a>
            </div>
        </div>
    </div>
    
    <?php 
        include 'includes/footer.php';
        renderFooter(); 
    ?>
    
    <!-- ✅ CORRECTED: Use absolute path for JS -->
    <script src="/js/main.js"></script>
</body>
</html>