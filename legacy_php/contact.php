<?php
    // our-school.php - Our School Page
    include 'includes/navigation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="handheldfriendly" content="true">
    <meta name="mobileoptimized" content="320">
    <title>Contact Us - Learning Academy</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/contact.css">
</head>
<body>
    <?php renderNavigation('contact'); ?>
    
    <div class="page-header">
        <h1>Contact Our School</h1>
        <p>Get in touch with us. We're here to help and answer any questions you may have.</p>
    </div>
    
    <div class="container">
        <!-- Contact Info Grid -->
        <div class="content-box">
            <h2>Get in Touch</h2>
            <div class="contact-info-grid">
                <div class="contact-info-card">
                    <span class="contact-icon-large">📞</span>
                    <h3>Phone</h3>
                    <p><strong>Main Office:</strong> (012) 345-6789</p>
                    <p><strong>Fax:</strong> (012) 345-6790</p>
                    <p>Mon-Fri: 7:30 AM - 4:00 PM</p>
                </div>
                
                <div class="contact-info-card">
                    <span class="contact-icon-large">✉️</span>
                    <h3>Email</h3>
                    <p><strong>General Inquiries:</strong> info@learningacademy.edu</p>
                    <p><strong>Admissions:</strong> admissions@learningacademy.edu</p>
                    <p><strong>Support:</strong> support@learningacademy.edu</p>
                </div>
                
                <div class="contact-info-card">
                    <span class="contact-icon-large">📍</span>
                    <h3>Address</h3>
                    <p>Learning Academy</p>
                    <p>123 Education Street</p>
                    <p>Academic City, AC 12345</p>
                    <p>South Africa</p>
                </div>
                
                <div class="contact-info-card">
                    <span class="contact-icon-large">🕒</span>
                    <h3>Office Hours</h3>
                    <p><strong>Administration:</strong> 7:30 AM - 4:00 PM</p>
                    <p><strong>Reception:</strong> 7:00 AM - 5:00 PM</p>
                    <p><strong>Weekends:</strong> Closed</p>
                </div>
            </div>
        </div>
        
        <!-- Department Contacts -->
        <div class="content-box department-contacts">
            <h2>Department Contacts</h2>
            <p>Contact the specific department you need assistance from:</p>
            
            <div class="department-grid">
                <div class="department-card">
                    <h3>📚 Academics</h3>
                    <p class="department-head">Mrs. Mary Wilson</p>
                    <p>Head of Academics</p>
                    <p>Email: academics@learningacademy.edu</p>
                    <p>Ext: 201</p>
                </div>
                
                <div class="department-card">
                    <h3>💰 Finance</h3>
                    <p class="department-head">Mr. James Brown</p>
                    <p>Finance Manager</p>
                    <p>Email: finance@learningacademy.edu</p>
                    <p>Ext: 301</p>
                </div>
                
                <div class="department-card">
                    <h3>🎓 Admissions</h3>
                    <p class="department-head">Ms. Sarah Johnson</p>
                    <p>Admissions Officer</p>
                    <p>Email: admissions@learningacademy.edu</p>
                    <p>Ext: 101</p>
                </div>
                
                <div class="department-card">
                    <h3>⚽ Sports</h3>
                    <p class="department-head">Coach David Miller</p>
                    <p>Sports Director</p>
                    <p>Email: sports@learningacademy.edu</p>
                    <p>Ext: 401</p>
                </div>
                
                <div class="department-card">
                    <h3>🎭 Arts & Culture</h3>
                    <p class="department-head">Ms. Lisa Chen</p>
                    <p>Arts Coordinator</p>
                    <p>Email: arts@learningacademy.edu</p>
                    <p>Ext: 501</p>
                </div>
                
                <div class="department-card">
                    <h3>🔧 Maintenance</h3>
                    <p class="department-head">Mr. John Peterson</p>
                    <p>Maintenance Head</p>
                    <p>Email: maintenance@learningacademy.edu</p>
                    <p>Ext: 601</p>
                </div>
            </div>
        </div>
        
        <!-- Contact Form -->
        <div class="content-box contact-form-section">
            <h2>Send Us a Message</h2>
            <p>Fill out the form below and we'll get back to you as soon as possible.</p>
            
            <div class="form-tabs">
                <button class="form-tab active" data-tab="general">General Inquiry</button>
                <button class="form-tab" data-tab="admissions">Admissions</button>
                <button class="form-tab" data-tab="feedback">Feedback</button>
                <button class="form-tab" data-tab="complaint">Complaint</button>
            </div>
            
            <form class="contact-form" id="contactForm">
                <div class="form-content active" id="general-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" placeholder="Your full name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" placeholder="Your email address" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" placeholder="Your phone number">
                        </div>
                        
                        <div class="form-group">
                            <label for="student-name">Student Name (if applicable)</label>
                            <input type="text" id="student-name" placeholder="Student's name">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <input type="text" id="subject" placeholder="Subject of your inquiry" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" placeholder="Please provide details about your inquiry..." rows="6" required></textarea>
                    </div>
                </div>
                
                <div class="form-content" id="admissions-form">
                    <!-- Admissions form would have different fields -->
                    <p>For admissions inquiries, please include student's current grade and desired enrolment year.</p>
                </div>
                
                <div class="form-content" id="feedback-form">
                    <!-- Feedback form would have different fields -->
                    <p>We appreciate your feedback to help us improve our services.</p>
                </div>
                
                <div class="form-content" id="complaint-form">
                    <!-- Complaint form would have different fields -->
                    <p>Please provide detailed information about your complaint for proper investigation.</p>
                </div>
                
                <div class="form-group">
                    <label>Preferred Contact Method</label>
                    <div class="radio-group">
                        <label><input type="radio" name="contact-method" value="email" checked> Email</label>
                        <label><input type="radio" name="contact-method" value="phone"> Phone</label>
                        <label><input type="radio" name="contact-method" value="either"> Either</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="attachment">Attachment (optional)</label>
                    <input type="file" id="attachment" accept=".pdf,.doc,.docx,.jpg,.png">
                    <small>Maximum file size: 5MB</small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" required>
                        I agree to the privacy policy and terms of service *
                    </label>
                </div>
                
                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
        
        <!-- Map Section -->
        <div class="content-box map-section">
            <h2>Find Our School</h2>
            <div class="map-container">
                <div class="map-placeholder">
                    <div class="map-marker">📍</div>
                    <h3>Learning Academy Location</h3>
                    <p>123 Education Street, Academic City</p>
                    <p>Click to open in Google Maps</p>
                </div>
            </div>
            
            <div class="transportation-info">
                <div class="transport-card">
                    <h3>🚗 Driving Directions</h3>
                    <p>Take Highway N1, exit at Academic City Interchange. Follow Education Street for 2km. School is on the left.</p>
                </div>
                
                <div class="transport-card">
                    <h3>🚌 Public Transport</h3>
                    <p>Bus routes 101, 203, and 305 stop at Academic City Central. School shuttle available from station.</p>
                </div>
                
                <div class="transport-card">
                    <h3>🅿️ Parking</h3>
                    <p>Visitor parking available at Main Gate. Additional parking at Sports Complex for events.</p>
                </div>
            </div>
        </div>
        
        <!-- Emergency Contacts -->
        <div class="content-box emergency-contacts">
            <h2 class="emergency-header">🚨 Emergency Contacts</h2>
            
            <div class="emergency-grid">
                <div class="emergency-item">
                    <span class="emergency-icon">🚑</span>
                    <h3>Medical Emergency</h3>
                    <p><strong>On-campus:</strong> Ext. 911</p>
                    <p><strong>Ambulance:</strong> 10177</p>
                </div>
                
                <div class="emergency-item">
                    <span class="emergency-icon">👮</span>
                    <h3>Security Emergency</h3>
                    <p><strong>Security Office:</strong> Ext. 301</p>
                    <p><strong>Police:</strong> 10111</p>
                </div>
                
                <div class="emergency-item">
                    <span class="emergency-icon">🔥</span>
                    <h3>Fire Emergency</h3>
                    <p><strong>Fire Department:</strong> 10177</p>
                    <p><strong>School Fire Warden:</strong> Ext. 302</p>
                </div>
                
                <div class="emergency-item">
                    <span class="emergency-icon">📞</span>
                    <h3>Crisis Hotline</h3>
                    <p><strong>Childline:</strong> 08000 55555</p>
                    <p><strong>Lifeline:</strong> 0861 322 322</p>
                </div>
            </div>
        </div>
        
        <!-- Business Hours -->
        <div class="content-box business-hours">
            <h2>School Hours & Access</h2>
            
            <div class="hours-table-container">
                <table class="hours-table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>School Hours</th>
                            <th>Office Hours</th>
                            <th>Aftercare</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Monday</td>
                            <td>7:30 AM - 2:30 PM</td>
                            <td>7:30 AM - 4:00 PM</td>
                            <td>2:30 PM - 5:30 PM</td>
                        </tr>
                        <tr>
                            <td>Tuesday</td>
                            <td>7:30 AM - 2:30 PM</td>
                            <td>7:30 AM - 4:00 PM</td>
                            <td>2:30 PM - 5:30 PM</td>
                        </tr>
                        <tr>
                            <td>Wednesday</td>
                            <td>7:30 AM - 2:30 PM</td>
                            <td>7:30 AM - 4:00 PM</td>
                            <td>2:30 PM - 5:30 PM</td>
                        </tr>
                        <tr>
                            <td>Thursday</td>
                            <td>7:30 AM - 2:30 PM</td>
                            <td>7:30 AM - 4:00 PM</td>
                            <td>2:30 PM - 5:30 PM</td>
                        </tr>
                        <tr>
                            <td>Friday</td>
                            <td>7:30 AM - 2:30 PM</td>
                            <td>7:30 AM - 4:00 PM</td>
                            <td>2:30 PM - 5:30 PM</td>
                        </tr>
                        <tr>
                            <td>Saturday</td>
                            <td>Closed</td>
                            <td>Closed</td>
                            <td>Closed</td>
                        </tr>
                        <tr>
                            <td>Sunday</td>
                            <td>Closed</td>
                            <td>Closed</td>
                            <td>Closed</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="access-notes">
                <p><strong>Note:</strong> During school holidays, office hours are 8:00 AM - 1:00 PM. The school is closed on public holidays.</p>
                <p>All visitors must report to the reception desk and sign in. Photo ID may be required.</p>
            </div>
        </div>
        
        <!-- Social Media -->
        <div class="content-box social-media">
            <h2>Connect With Us</h2>
            <p>Follow us on social media for updates, news, and school events:</p>
            
            <div class="social-grid">
                <a href="#" class="social-card facebook">
                    <span class="social-icon">📘</span>
                    <h3>Facebook</h3>
                    <p>@LearningAcademyOfficial</p>
                </a>
                
                <a href="#" class="social-card twitter">
                    <span class="social-icon">🐦</span>
                    <h3>Twitter</h3>
                    <p>@LearnAcademy_SA</p>
                </a>
                
                <a href="#" class="social-card instagram">
                    <span class="social-icon">📷</span>
                    <h3>Instagram</h3>
                    <p>@learning_academy</p>
                </a>
                
                <a href="#" class="social-card youtube">
                    <span class="social-icon">📺</span>
                    <h3>YouTube</h3>
                    <p>Learning Academy Channel</p>
                </a>
                
                <a href="#" class="social-card linkedin">
                    <span class="social-icon">💼</span>
                    <h3>LinkedIn</h3>
                    <p>Learning Academy School</p>
                </a>
                
                <a href="#" class="social-card newsletter">
                    <span class="social-icon">📰</span>
                    <h3>Newsletter</h3>
                    <p>Subscribe for updates</p>
                </a>
            </div>
        </div>
    </div>
    
    <?php 
        include 'includes/footer.php';
        renderFooter(); 
    ?>    
    <script src="js/main.js"></script>
    
    <script>
        // Form Tab Switching
        const tabs = document.querySelectorAll('.form-tab');
        const forms = document.querySelectorAll('.form-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs and forms
                tabs.forEach(t => t.classList.remove('active'));
                forms.forEach(f => f.classList.remove('active'));
                
                // Add active class to clicked tab
                tab.classList.add('active');
                
                // Show corresponding form
                const tabId = tab.getAttribute('data-tab');
                const form = document.getElementById(`${tabId}-form`);
                if (form) {
                    form.classList.add('active');
                }
            });
        });
        
        // Form Submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your message! We will respond within 24 hours.');
            this.reset();
            
            // Reset to general form
            tabs.forEach(t => t.classList.remove('active'));
            forms.forEach(f => f.classList.remove('active'));
            tabs[0].classList.add('active');
            forms[0].classList.add('active');
        });
    </script>
</body>
</html>