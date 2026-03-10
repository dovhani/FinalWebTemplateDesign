<?php
// footer.php - Shared footer function

function renderFooter() {
    echo '<footer>';
    echo '<div class="footer-content">';
    
    // School Info
    echo '<div class="footer-section">';
    echo '<div class="footer-logo">Learning Academy</div>';
    echo '<p class="footer-description">Providing quality education since 1995. Preparing students for a bright future.</p>';
    echo '<div class="social-links">';
    echo '<a href="#" class="social-link facebook">Facebook</a>';
    echo '<a href="#" class="social-link twitter">Twitter</a>';
    echo '<a href="#" class="social-link instagram">Instagram</a>';
    echo '</div>';
    echo '</div>';
    
    // Quick Links
    echo '<div class="footer-section">';
    echo '<h3 class="footer-title">Quick Links</h3>';
    echo '<ul class="footer-links">';
    echo '<li><a href="index.php">Home</a></li>';
    echo '<li><a href="about.php">About Us</a></li>';
    echo '<li><a href="our-school.php">Our School</a></li>';
    echo '<li><a href="enrolment.php">Enrolment</a></li>';
    echo '<li><a href="contact.php">Contact Us</a></li>';
    echo '</ul>';
    echo '</div>';
    
    // Contact Info
    echo '<div class="footer-section">';
    echo '<h3 class="footer-title">Contact Info</h3>';
    echo '<div class="contact-info">';
    echo '<div class="contact-item">';
    echo '<i class="contact-icon">📍</i>';
    echo '<span>123 Education Street, Academic City, AC 12345</span>';
    echo '</div>';
    echo '<div class="contact-item">';
    echo '<i class="contact-icon">📞</i>';
    echo '<span>(012) 345-6789</span>';
    echo '</div>';
    echo '<div class="contact-item">';
    echo '<i class="contact-icon">✉️</i>';
    echo '<span>info@learningacademy.edu</span>';
    echo '</div>';
    echo '<div class="contact-item">';
    echo '<i class="contact-icon">🕒</i>';
    echo '<span>Mon-Fri: 7:30 AM - 4:00 PM</span>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    // Newsletter
    echo '<div class="footer-section">';
    echo '<h3 class="footer-title">School Newsletter</h3>';
    echo '<p>Subscribe to receive updates about school events and news.</p>';
    echo '<form class="newsletter-form">';
    echo '<input type="email" placeholder="Your email address" required>';
    echo '<button type="submit">Subscribe</button>';
    echo '</form>';
    echo '</div>';
    
    echo '</div>';
    
    // Footer Bottom
    echo '<div class="footer-bottom">';
    echo '<p>&copy; ' . date('Y') . ' Learning Academy. All rights reserved.</p>';
    echo '<div class="footer-links-bottom">';
    echo '<a href="#">Privacy Policy</a>';
    echo '<a href="#">Terms of Service</a>';
    echo '<a href="#">Sitemap</a>';
    echo '</div>';
    echo '</div>';
    echo '</footer>';
}
?>