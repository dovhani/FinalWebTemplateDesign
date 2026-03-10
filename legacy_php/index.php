<!-- Include navigation -->
    <?php include 'includes/navigation.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Learning Academy</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <?php renderNavigation('home'); ?>
    
    <div class="hero">
        <h1>Welcome to Learning Academy</h1>
        <p>Excellence in Education Since 1995. Nurturing Young Minds for a Brighter Future.</p>
        <div class="hero-buttons">
            <a href="enrolment.php" class="btn">Apply Now</a>
            <a href="about.php" class="btn btn-secondary">Learn More</a>
        </div>
    </div>
    
    <div class="container">
        <!-- Welcome Section -->
        <div class="content-box welcome-section">
            <h2>Welcome to Our School Community</h2>
            <p>At Learning Academy, we provide a nurturing environment where students can grow academically, socially, and emotionally. Our dedicated staff and comprehensive curriculum ensure every child reaches their full potential.</p>
            
            <div class="cards-grid">
                <div class="card">
                    <div class="card-img">📚</div>
                    <div class="card-content">
                        <h3>Academic Excellence</h3>
                        <p>Our curriculum is designed to challenge and inspire students at all levels.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-img">🎨</div>
                    <div class="card-content">
                        <h3>Arts & Sports</h3>
                        <p>Comprehensive programs to develop well-rounded individuals.</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-img">🤝</div>
                    <div class="card-content">
                        <h3>Community Focus</h3>
                        <p>Strong partnerships with parents and the local community.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stats Section -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">1,200+</div>
                    <div class="stat-label">Students Enrolled</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">85</div>
                    <div class="stat-label">Qualified Teachers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Pass Rate</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">27</div>
                    <div class="stat-label">Years of Excellence</div>
                </div>
            </div>
        </div>
        
        <!-- Highlights -->
        <div class="content-box highlights">
            <h2>School Highlights</h2>
            <div class="highlight-cards">
                <div class="highlight-card">
                    <h3>📅 Upcoming Events</h3>
                    <p>Check our calendar for parent-teacher meetings, sports days, and cultural events.</p>
                    <a href="calendar-events.php" class="btn">View Calendar</a>
                </div>
                
                <div class="highlight-card">
                    <h3>🏆 Recent Achievements</h3>
                    <p>Our students excel in academics, sports, and cultural activities at district level.</p>
                    <a href="gallery/achievements.php" class="btn">View Achievements</a>
                </div>
                
                <div class="highlight-card">
                    <h3>📝 Enrolment Open</h3>
                    <p>Applications for the 2024 academic year are now open. Limited spaces available.</p>
                    <a href="enrolment.php" class="btn">Apply Now</a>
                </div>
            </div>
        </div>
        
        <!-- Latest News -->
        <div class="content-box news-section">
            <h2>Latest News & Announcements</h2>
            <div class="news-grid">
                <div class="news-item">
                    <div class="news-date">Dec 15, 2023</div>
                    <div class="news-content">
                        <h3>Annual Prize Giving Ceremony</h3>
                        <p>Congratulations to all participants in our annual inter-house sports competition.</p>
                        <a href="calendar-events.php" class="btn btn-secondary">Read More</a>
                    </div>
                </div>
                
                <div class="news-item">
                    <div class="news-date">Dec 10, 2023</div>
                    <div class="news-content">
                        <h3>Holiday Break Schedule</h3>
                        <p>School will close on December 15th and reopen on January 8th, 2024.</p>
                        <a href="calendar-events.php" class="btn btn-secondary">Read More</a>
                    </div>
                </div>
                
                <div class="news-item">
                    <div class="news-date">Dec 5, 2023</div>
                    <div class="news-content">
                        <h3>New Library Resources</h3>
                        <p>Our library has been upgraded with new books and digital learning resources.</p>
                        <a href="about.php" class="btn btn-secondary">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <!-- Include footer -->
   <?php include 'includes/footer.php'; ?>    
    <script src="js/main.js"></script>
</body>
</html>