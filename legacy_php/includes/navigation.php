<?php
// navigation.php - FOR PROJECT IN PhpFinalWebTemplate FOLDER

// Main navigation function
function renderNavigation($currentPage) {
    $base_path = '/'; // Add trailing slash
    
    $pages = [
        'home' => ['title' => 'Home', 'file' => 'index.php'],
        'about' => ['title' => 'About Us', 'file' => 'about.php'],
        'our-school' => ['title' => 'Our School', 'file' => 'our-school.php'],
        'calendar-events' => ['title' => 'Calendar & Events', 'file' => 'calendar-events.php'],
        'gallery' => ['title' => 'Gallery', 'file' => 'gallery.php'],
        'enrolment' => ['title' => 'Enrolment', 'file' => 'enrolment.php'],
        'contact' => ['title' => 'Contact Us', 'file' => 'contact.php']
    ];
    
    echo '<nav class="main-nav">';
    echo '<div class="nav-container">';
    echo '<div class="logo">';

    echo '<a href="/index.php" class="logo-link">';
    echo '<img src="/assets/images/logo5.png" alt="Learning Academy Logo" class="logo-image">';
    echo '<div class="logo-text">';
    echo '<h1>Tshikhuthula</h1>';
    echo '<p>Secondary School</p>';
    echo '</div>';
    echo '</a>';
    echo '</div>';
    echo '<ul class="nav-menu">';
    
    foreach ($pages as $key => $page) {
        $activeClass = ($currentPage === $key) ? 'active' : '';
        echo "<li><a href='/{$page['file']}' class='{$activeClass}'>{$page['title']}</a></li>";
    }
    
    echo '</ul>';
    echo '<div class="hamburger" onclick="toggleMenu()">';
    echo '<span></span><span></span><span></span>';
    echo '</div>';
    echo '</div>';
    echo '</nav>';
}

// School sub-navigation function
function renderSchoolSubNav() {
    $base_path = '/';
    
    $subPages = [
        'staff.php' => 'Staff Directory',
        'sgb.php' => 'School Governing Body',
        'food-handlers.php' => 'Food Handlers',
        'ground-workers.php' => 'Ground Workers',
        'security.php' => 'Security'
    ];
    
    echo '<div class="sub-nav">';
    echo '<div class="sub-nav-container">';
    echo '<ul class="sub-nav-menu">';
    
    foreach ($subPages as $file => $title) {        
        echo "<li><a href='/our-school/{$file}'>{$title}</a></li>";
    }
    
    echo '</ul>';
    echo '</div>';
    echo '</div>';
}

// Gallery sub-navigation function
function renderGallerySubNav() {
    $base_path = '/';
    
    $subPages = [
        'achievements.php' => 'Achievements',
        'students-gallery.php' => 'Students Gallery'
    ];
    
    echo '<div class="sub-nav gallery-sub-nav">';
    echo '<div class="sub-nav-container">';
    echo '<ul class="sub-nav-menu">';
    
    foreach ($subPages as $file => $title) {        
        echo "<li><a href='/gallery/{$file}'>{$title}</a></li>";
    }
    
    echo '</ul>';
    echo '</div>';
    echo '</div>';
}
?>

