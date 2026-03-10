<?php
    // our-school.php - Our School Page
    include 'includes/navigation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Learning Academy</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/about.css">
</head>
<body>
    <?php renderNavigation('about'); ?>
    
    <div class="page-header">
        <h1>About Our School</h1>
        <p>Discover our rich history, educational philosophy, and commitment to excellence.</p>
    </div>
    
    <div class="container">
        <!-- School History -->
        <div class="content-box">
            <h2>Our History</h2>
            <p>Founded in 1995, Learning Academy began as a small community school with just 50 students and 5 teachers. Over the past 27 years, we have grown into a leading educational institution serving over 1,200 students from Grade R to Grade 12.</p>
            
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-year">1995</div>
                    <div class="timeline-content">
                        <h3>School Founded</h3>
                        <p>Learning Academy opens its doors with 50 students in temporary facilities.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">2000</div>
                    <div class="timeline-content">
                        <h3>First Permanent Building</h3>
                        <p>Construction completed on our main academic building.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">2005</div>
                    <div class="timeline-content">
                        <h3>Expansion to High School</h3>
                        <p>Added Grades 10-12, becoming a complete primary and secondary school.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">2015</div>
                    <div class="timeline-content">
                        <h3>20th Anniversary</h3>
                        <p>Celebrated 20 years of educational excellence with new science labs.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">2020</div>
                    <div class="timeline-content">
                        <h3>Digital Transformation</h3>
                        <p>Implemented smart classrooms and digital learning platforms.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">2023</div>
                    <div class="timeline-content">
                        <h3>Current Excellence</h3>
                        <p>Recognized as a top-performing school in the district with 98% pass rate.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mission & Vision -->
        <div class="content-box">
            <h2>Our Mission & Vision</h2>
            
            <div class="mission-vision-grid">
                <div class="mission-card">
                    <div class="mission-icon">🎯</div>
                    <h3>Our Mission</h3>
                    <p>To provide a nurturing, inclusive, and stimulating learning environment that empowers students to achieve academic excellence, develop strong character, and become responsible global citizens.</p>
                </div>
                
                <div class="vision-card">
                    <div class="vision-icon">🌟</div>
                    <h3>Our Vision</h3>
                    <p>To be a premier educational institution recognized for developing innovative thinkers, compassionate leaders, and lifelong learners who contribute positively to society.</p>
                </div>
            </div>
        </div>
        
        <!-- Core Values -->
        <div class="content-box">
            <h2>Our Core Values</h2>
            
            <div class="values-grid">
                <div class="value-item">
                    <div class="value-icon">📚</div>
                    <h3>Excellence</h3>
                    <p>Striving for the highest standards in academics, character, and service.</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">🤝</div>
                    <h3>Integrity</h3>
                    <p>Acting with honesty, fairness, and ethical behavior in all we do.</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">🌍</div>
                    <h3>Respect</h3>
                    <p>Valuing diversity and treating everyone with dignity and consideration.</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">💡</div>
                    <h3>Innovation</h3>
                    <p>Embracing creativity and new approaches to teaching and learning.</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">❤️</div>
                    <h3>Compassion</h3>
                    <p>Caring for others and making a positive difference in our community.</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">👨‍👩‍👧‍👦</div>
                    <h3>Community</h3>
                    <p>Building strong partnerships between school, families, and community.</p>
                </div>
            </div>
        </div>
        
        <!-- School Facilities -->
        <div class="content-box">
            <h2>Our Facilities</h2>
            
            <div class="facilities-grid">
                <div class="facility-card">
                    <div class="facility-img">🏫</div>
                    <div class="facility-content">
                        <h3>Modern Classrooms</h3>
                        <p>50+ smart classrooms equipped with interactive whiteboards and digital learning tools.</p>
                    </div>
                </div>
                
                <div class="facility-card">
                    <div class="facility-img">🔬</div>
                    <div class="facility-content">
                        <h3>Science Labs</h3>
                        <p>Fully equipped physics, chemistry, and biology laboratories for hands-on learning.</p>
                    </div>
                </div>
                
                <div class="facility-card">
                    <div class="facility-img">📚</div>
                    <div class="facility-content">
                        <h3>Library & Media Center</h3>
                        <p>Extensive collection of books, e-books, and digital research resources.</p>
                    </div>
                </div>
                
                <div class="facility-card">
                    <div class="facility-img">🎭</div>
                    <div class="facility-content">
                        <h3>Arts Center</h3>
                        <p>Dedicated spaces for music, drama, and visual arts with professional equipment.</p>
                    </div>
                </div>
                
                <div class="facility-card">
                    <div class="facility-img">⚽</div>
                    <div class="facility-content">
                        <h3>Sports Facilities</h3>
                        <p>Soccer field, basketball courts, swimming pool, and indoor gymnasium.</p>
                    </div>
                </div>
                
                <div class="facility-card">
                    <div class="facility-img">💻</div>
                    <div class="facility-content">
                        <h3>Computer Labs</h3>
                        <p>State-of-the-art computer labs with high-speed internet and latest software.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Accreditation -->
        <div class="content-box">
            <h2>Accreditation & Recognition</h2>
            
            <div class="accreditation-grid">
                <div class="accreditation-item">
                    <h3>🏆 Department of Education</h3>
                    <p>Fully accredited by the Department of Basic Education</p>
                </div>
                
                <div class="accreditation-item">
                    <h3>⭐ Quality Assurance</h3>
                    <p>Consistently rated as a high-performing school in annual evaluations</p>
                </div>
                
                <div class="accreditation-item">
                    <h3>🎓 Umalusi Accredited</h3>
                    <p>Registered to issue the National Senior Certificate (NSC)</p>
                </div>
                
                <div class="accreditation-item">
                    <h3>🌍 International Standards</h3>
                    <p>Curriculum aligned with international educational standards</p>
                </div>
            </div>
        </div>
    </div>
    
<?php 
  include 'includes/footer.php';
  renderFooter(); 
?>   
    <script src="js/main.js"></script>
</body>
</html>