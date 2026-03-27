<?php
// config.php - Configuration for all environments

// Environment detection
function getEnvironment() {
    // Check if localhost (multiple methods for compatibility)
    $host = $_SERVER['HTTP_HOST'];
    
    // Check common localhost indicators
    $is_localhost = (
        $host == 'localhost' || 
        $host == '127.0.0.1' ||
        substr($host, -6) == '.local' ||
        substr($host, -10) == '.localhost' ||
        (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '127.0.0.1') ||
        (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == '127.0.0.1')
    );
    
    return $is_localhost ? 'development' : 'production';
}

// Set environment
define('ENVIRONMENT', getEnvironment());

// Error reporting based on environment
if (ENVIRONMENT === 'development') {
    // Show all errors locally
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    
    // Create logs directory if it doesn't exist
    $log_dir = __DIR__ . '/logs';
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    ini_set('error_log', $log_dir . '/error.log');
} else {
    // Hide errors on live server
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    
    $log_dir = __DIR__ . '/logs';
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    ini_set('error_log', $log_dir . '/error.log');
}

// Base URL function (compatible with all servers)
function getBaseUrl() {
    // Determine protocol
    if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
        (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }
    
    // Get host (with fallback)
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    
    // Get script directory
    $script_dir = '';
    if (isset($_SERVER['SCRIPT_NAME'])) {
        $script_dir = dirname($_SERVER['SCRIPT_NAME']);
        // Remove trailing slash if present
        $script_dir = rtrim($script_dir, '/');
        // Add slash if not empty
        if ($script_dir !== '') {
            $script_dir .= '/';
        }
    }
    
    return $protocol . $host . '/' . $script_dir;
}

// Define base URL constant
define('BASE_URL', getBaseUrl());

// Asset helper function
function asset($path) {
    return BASE_URL . ltrim($path, '/');
}

// Database configuration (example)
if (ENVIRONMENT === 'development') {
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'school_website');
} else {
    // Production settings - update these when deploying
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', 'school_username');
    define('DB_PASSWORD', 'StrongPassword123!');
    define('DB_NAME', 'school_website');
}

// Site settings
define('SITE_NAME', 'Lotsha Primary School');
define('SITE_EMAIL', 'info@tshikhuthula.edu');
define('SITE_PHONE', '+27 123 456 7890');

// Timezone for South Africa
date_default_timezone_set('Africa/Johannesburg');

// Debug function (only in development)
function debug($data) {
    if (ENVIRONMENT === 'development') {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
?>