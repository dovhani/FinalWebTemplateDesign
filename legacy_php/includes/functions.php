<?php
// includes/functions.php

// =============================================
// HELPER FUNCTIONS
// =============================================

/**
 * Sanitize input data
 */
function sanitize($data, $type = 'string') {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    
    $data = trim($data);
    
    switch ($type) {
        case 'email':
            $data = filter_var($data, FILTER_SANITIZE_EMAIL);
            break;
            
        case 'int':
            $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
            break;
            
        case 'float':
            $data = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            break;
            
        case 'url':
            $data = filter_var($data, FILTER_SANITIZE_URL);
            break;
            
        case 'html':
            $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            break;
            
        default:
            $data = filter_var($data, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            break;
    }
    
    return $data;
}

/**
 * Generate a slug from a string
 */
function generateSlug($string) {
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    $slug = strtolower(trim($slug, '-'));
    $slug = preg_replace('/-+/', '-', $slug);
    return $slug;
}

/**
 * Format date for display
 */
function formatDate($date, $format = null) {
    if (!$format) {
        $format = Config::get('app.date_format', 'F j, Y');
    }
    
    if (!$date instanceof DateTime) {
        $date = new DateTime($date);
    }
    
    return $date->format($format);
}

/**
 * Format time for display
 */
function formatTime($time, $format = 'g:i A') {
    if (empty($time)) {
        return '';
    }
    
    $dateTime = DateTime::createFromFormat('H:i:s', $time);
    if (!$dateTime) {
        $dateTime = DateTime::createFromFormat('H:i', $time);
    }
    
    return $dateTime ? $dateTime->format($format) : $time;
}

/**
 * Get event color based on category or type
 */
function getEventColor($category, $type = null) {
    $categories = Config::get('events.categories');
    
    if (isset($categories[$category])) {
        return $categories[$category]['color'];
    }
    
    // Default colors based on event type
    $typeColors = [
        'school_event' => '#3788d8',
        'public_holiday' => '#dc3545',
        'exam_test' => '#6f42c1',
        'parent_meeting' => '#ffc107',
        'sports_event' => '#28a745',
        'cultural_event' => '#ff6b35',
        'workshop' => '#20c997',
        'other' => '#6c757d'
    ];
    
    return $typeColors[$type] ?? '#3788d8';
}

/**
 * Get event icon based on category
 */
function getEventIcon($category) {
    $categories = Config::get('events.categories');
    
    if (isset($categories[$category])) {
        return $categories[$category]['icon'];
    }
    
    return 'fa-calendar';
}

/**
 * Get category name from slug
 */
function getCategoryName($slug) {
    $categories = Config::get('events.categories');
    
    if (isset($categories[$slug])) {
        return $categories[$slug]['name'];
    }
    
    return ucfirst(str_replace(['-', '_'], ' ', $slug));
}

/**
 * Calculate event duration
 */
function getEventDuration($startTime, $endTime) {
    if (empty($startTime) || empty($endTime)) {
        return '';
    }
    
    $start = new DateTime($startTime);
    $end = new DateTime($endTime);
    $interval = $start->diff($end);
    
    $hours = $interval->h;
    $minutes = $interval->i;
    
    if ($hours > 0 && $minutes > 0) {
        return "{$hours}h {$minutes}m";
    } elseif ($hours > 0) {
        return "{$hours}h";
    } else {
        return "{$minutes}m";
    }
}

/**
 * Check if event is happening today
 */
function isEventToday($eventDate) {
    $today = new DateTime('today');
    $event = new DateTime($eventDate);
    return $today->format('Y-m-d') === $event->format('Y-m-d');
}

/**
 * Check if event is upcoming
 */
function isEventUpcoming($eventDate) {
    $today = new DateTime('today');
    $event = new DateTime($eventDate);
    return $event >= $today;
}

/**
 * Get days until event
 */
function getDaysUntilEvent($eventDate) {
    $today = new DateTime('today');
    $event = new DateTime($eventDate);
    $interval = $today->diff($event);
    return (int)$interval->format('%r%a');
}

/**
 * Generate a unique file name
 */
function generateFileName($originalName, $prefix = '') {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $filename = uniqid($prefix) . '.' . $extension;
    return $filename;
}

/**
 * Validate email address
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (basic)
 */
function isValidPhone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return strlen($phone) >= 10 && strlen($phone) <= 15;
}

/**
 * Truncate text with ellipsis
 */
function truncateText($text, $length = 100, $ellipsis = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    $truncated = substr($text, 0, $length);
    $lastSpace = strrpos($truncated, ' ');
    
    if ($lastSpace !== false) {
        $truncated = substr($truncated, 0, $lastSpace);
    }
    
    return $truncated . $ellipsis;
}

/**
 * Get month name from number
 */
function getMonthName($monthNumber) {
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
    
    return $months[$monthNumber] ?? '';
}

/**
 * Get abbreviated month name
 */
function getShortMonthName($monthNumber) {
    $monthName = getMonthName($monthNumber);
    return substr($monthName, 0, 3);
}

/**
 * Generate calendar days array for a month
 */
function generateCalendarDays($year, $month) {
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date('t', $firstDay);
    $firstDayOfWeek = date('w', $firstDay);
    
    $calendar = [];
    
    // Previous month's days
    $prevMonthDays = date('t', mktime(0, 0, 0, $month - 1, 1, $year));
    for ($i = $firstDayOfWeek - 1; $i >= 0; $i--) {
        $calendar[] = [
            'day' => $prevMonthDays - $i,
            'month' => 'prev',
            'date' => date('Y-m-d', mktime(0, 0, 0, $month - 1, $prevMonthDays - $i, $year)),
            'isToday' => false,
            'hasEvents' => false
        ];
    }
    
    // Current month's days
    $today = date('Y-m-d');
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
        $calendar[] = [
            'day' => $day,
            'month' => 'current',
            'date' => $date,
            'isToday' => ($date === $today),
            'hasEvents' => false
        ];
    }
    
    // Next month's days
    $nextDay = 1;
    while (count($calendar) % 7 !== 0) {
        $calendar[] = [
            'day' => $nextDay,
            'month' => 'next',
            'date' => date('Y-m-d', mktime(0, 0, 0, $month + 1, $nextDay, $year)),
            'isToday' => false,
            'hasEvents' => false
        ];
        $nextDay++;
    }
    
    return $calendar;
}

/**
 * Redirect to a URL
 */
function redirect($url, $permanent = false) {
    if ($permanent) {
        header('HTTP/1.1 301 Moved Permanently');
    }
    
    header('Location: ' . $url);
    exit;
}

/**
 * Get current URL
 */
function currentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Get base URL
 */
function baseUrl() {
    return Config::get('app.url');
}

/**
 * Get client IP address
 */
function getClientIP() {
    $ip = '';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    return $ip;
}

/**
 * Generate pagination array
 */
function generatePagination($currentPage, $totalPages, $maxLinks = 5) {
    $pagination = [
        'current' => $currentPage,
        'total' => $totalPages,
        'pages' => [],
        'hasPrevious' => $currentPage > 1,
        'hasNext' => $currentPage < $totalPages,
        'previous' => max(1, $currentPage - 1),
        'next' => min($totalPages, $currentPage + 1)
    ];
    
    $start = max(1, $currentPage - floor($maxLinks / 2));
    $end = min($totalPages, $start + $maxLinks - 1);
    
    if ($end - $start + 1 < $maxLinks) {
        $start = max(1, $end - $maxLinks + 1);
    }
    
    for ($i = $start; $i <= $end; $i++) {
        $pagination['pages'][] = [
            'number' => $i,
            'isCurrent' => $i == $currentPage
        ];
    }
    
    return $pagination;
}

/**
 * Send JSON response
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Get POST data with sanitization
 */
function getPostData() {
    $data = [];
    
    foreach ($_POST as $key => $value) {
        $data[$key] = sanitize($value);
    }
    
    return $data;
}

/**
 * Get GET data with sanitization
 */
function getGetData() {
    $data = [];
    
    foreach ($_GET as $key => $value) {
        $data[$key] = sanitize($value);
    }
    
    return $data;
}

/**
 * Check if request is AJAX
 */
function isAjaxRequest() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Get file extension
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Check if file type is allowed
 */
function isAllowedFileType($filename, $allowedTypes = null) {
    if ($allowedTypes === null) {
        $allowedTypes = Config::get('security.allowed_upload_types');
    }
    
    $extension = getFileExtension($filename);
    return in_array($extension, $allowedTypes);
}

/**
 * Check file size
 */
function isValidFileSize($fileSize) {
    $maxSize = Config::get('security.max_upload_size');
    return $fileSize <= $maxSize;
}

/**
 * Create directory if it doesn't exist
 */
function createDirectory($path) {
    if (!is_dir($path)) {
        return mkdir($path, 0755, true);
    }
    return true;
}

/**
 * Delete directory and its contents
 */
function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return false;
    }
    
    $files = array_diff(scandir($dir), ['.', '..']);
    
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }
    
    return rmdir($dir);
}

/**
 * Format file size
 */
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, 2) . ' ' . $units[$pow];
}

/**
 * Get browser information
 */
function getBrowserInfo() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    $browser = 'Unknown';
    $platform = 'Unknown';
    
    // Platform
    if (preg_match('/linux/i', $userAgent)) {
        $platform = 'Linux';
    } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
        $platform = 'Mac';
    } elseif (preg_match('/windows|win32/i', $userAgent)) {
        $platform = 'Windows';
    }
    
    // Browser
    if (preg_match('/MSIE/i', $userAgent) && !preg_match('/Opera/i', $userAgent)) {
        $browser = 'Internet Explorer';
    } elseif (preg_match('/Firefox/i', $userAgent)) {
        $browser = 'Mozilla Firefox';
    } elseif (preg_match('/Chrome/i', $userAgent)) {
        $browser = 'Google Chrome';
    } elseif (preg_match('/Safari/i', $userAgent)) {
        $browser = 'Apple Safari';
    } elseif (preg_match('/Opera/i', $userAgent)) {
        $browser = 'Opera';
    } elseif (preg_match('/Netscape/i', $userAgent)) {
        $browser = 'Netscape';
    }
    
    return [
        'browser' => $browser,
        'platform' => $platform,
        'user_agent' => $userAgent
    ];
}
?>