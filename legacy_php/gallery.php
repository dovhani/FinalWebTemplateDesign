<?php
    // our-school.php - Our School Page
    include 'includes/navigation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Learning Academy</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/gallery.css">
</head>
<body>
    <?php renderNavigation('gallery'); ?>
    
    <div class="page-header">
        <h1>School Gallery</h1>
        <p>Explore our collection of memories, achievements, and student life moments.</p>
    </div>
    
    <?php renderGallerySubNav(); ?>
    
    <div class="container">
        <!-- Gallery Categories -->
        <div class="content-box gallery-categories">
            <h2>Gallery Categories</h2>
            <div class="category-grid">
                <a href="gallery/achievements.php" class="category-card">
                    <div class="category-image">🏆</div>
                    <div class="category-content">
                        <h3>Achievements</h3>
                        <p>Celebrate our students' academic, sports, and cultural accomplishments.</p>
                    </div>
                </a>
                
                <a href="gallery/students-gallery.php" class="category-card">
                    <div class="category-image">📸</div>
                    <div class="category-content">
                        <h3>Students Gallery</h3>
                        <p>Capture moments from classroom activities, events, and daily school life.</p>
                    </div>
                </a>
                
                <a href="#" class="category-card">
                    <div class="category-image">🎓</div>
                    <div class="category-content">
                        <h3>Graduation</h3>
                        <p>Memorable moments from our annual graduation ceremonies.</p>
                    </div>
                </a>
                
                <a href="#" class="category-card">
                    <div class="category-image">⚽</div>
                    <div class="category-content">
                        <h3>Sports Events</h3>
                        <p>Action shots from inter-house competitions and sports days.</p>
                    </div>
                </a>
                
                <a href="#" class="category-card">
                    <div class="category-image">🎭</div>
                    <div class="category-content">
                        <h3>Cultural Activities</h3>
                        <p>Photos from drama productions, music concerts, and art exhibitions.</p>
                    </div>
                </a>
                
                <a href="#" class="category-card">
                    <div class="category-image">🔬</div>
                    <div class="category-content">
                        <h3>Science & Innovation</h3>
                        <p>Showcasing our science fairs, robotics competitions, and projects.</p>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Photo Gallery -->
        <div class="content-box photo-gallery">
            <h2>Recent Photos</h2>
            
            <div class="gallery-filter">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="sports">Sports</button>
                <button class="filter-btn" data-filter="academic">Academic</button>
                <button class="filter-btn" data-filter="events">Events</button>
                <button class="filter-btn" data-filter="cultural">Cultural</button>
            </div>
            
            <div class="gallery-grid">
                <div class="gallery-item" data-category="academic">
                    <div class="item-img">Science Fair 2023</div>
                </div>
                
                <div class="gallery-item" data-category="sports">
                    <div class="item-img">Athletics Day</div>
                </div>
                
                <div class="gallery-item" data-category="cultural">
                    <div class="item-img">Drama Production</div>
                </div>
                
                <div class="gallery-item" data-category="academic">
                    <div class="item-img">Math Olympiad</div>
                </div>
                
                <div class="gallery-item" data-category="events">
                    <div class="item-img">Prize Giving</div>
                </div>
                
                <div class="gallery-item" data-category="sports">
                    <div class="item-img">Swimming Gala</div>
                </div>
                
                <div class="gallery-item" data-category="cultural">
                    <div class="item-img">Art Exhibition</div>
                </div>
                
                <div class="gallery-item" data-category="academic">
                    <div class="item-img">Library Week</div>
                </div>
                
                <div class="gallery-item" data-category="events">
                    <div class="item-img">Heritage Day</div>
                </div>
                
                <div class="gallery-item" data-category="sports">
                    <div class="item-img">Soccer Finals</div>
                </div>
                
                <div class="gallery-item" data-category="cultural">
                    <div class="item-img">Music Concert</div>
                </div>
                
                <div class="gallery-item" data-category="academic">
                    <div class="item-img">Robotics Club</div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="#" class="btn">View More Photos</a>
            </div>
        </div>
        
        <!-- Video Gallery -->
        <div class="content-box">
            <h2>School Videos</h2>
            <p>Watch highlights from our school events and activities.</p>
            
            <div class="video-grid">
                <div class="video-card">
                    <div class="video-placeholder">
                        <div class="play-icon">▶️</div>
                        <div class="video-title">2023 Graduation Ceremony</div>
                    </div>
                    <div class="video-info">
                        <h3>Class of 2023 Graduation</h3>
                        <p>Full ceremony recording of our Grade 12 graduation.</p>
                    </div>
                </div>
                
                <div class="video-card">
                    <div class="video-placeholder">
                        <div class="play-icon">▶️</div>
                        <div class="video-title">Annual Sports Day</div>
                    </div>
                    <div class="video-info">
                        <h3>Sports Day 2023 Highlights</h3>
                        <p>Highlights from our inter-house athletics competition.</p>
                    </div>
                </div>
                
                <div class="video-card">
                    <div class="video-placeholder">
                        <div class="play-icon">▶️</div>
                        <div class="video-title">Cultural Festival</div>
                    </div>
                    <div class="video-info">
                        <h3>Cultural Festival Performances</h3>
                        <p>Performances from our annual cultural festival.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Photo Submission -->
        <div class="content-box">
            <h2>Submit Your Photos</h2>
            <p>Share your photos from school events with our community. Selected photos will be featured in our gallery.</p>
            
            <div class="submission-form">
                <div class="form-group">
                    <label for="photo-title">Photo Title</label>
                    <input type="text" id="photo-title" placeholder="e.g., Science Fair 2023">
                </div>
                
                <div class="form-group">
                    <label for="photo-category">Category</label>
                    <select id="photo-category">
                        <option value="sports">Sports</option>
                        <option value="academic">Academic</option>
                        <option value="cultural">Cultural</option>
                        <option value="events">Events</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="photo-description">Description</label>
                    <textarea id="photo-description" placeholder="Describe the photo, event, and people in it..." rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="photo-upload">Upload Photo</label>
                    <div class="file-upload">
                        <input type="file" id="photo-upload" accept="image/*">
                        <p>Click to upload or drag and drop</p>
                        <p>Maximum file size: 5MB</p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="submitter-name">Your Name</label>
                    <input type="text" id="submitter-name" placeholder="Your name">
                </div>
                
                <div class="form-group">
                    <label for="submitter-email">Your Email</label>
                    <input type="email" id="submitter-email" placeholder="Your email address">
                </div>
                
                <button type="submit" class="btn">Submit Photo</button>
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