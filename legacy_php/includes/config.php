<?php
// includes/config.php

// =============================================
// APPLICATION CONFIGURATION
// =============================================

class Config {
    private static $config = [];
    
    public static function load() {
        // Load database configuration first
        require_once __DIR__ . '/database.php';
        
        // Application settings
        self::$config = [
            'app' => [
                'name' => 'Learning Academy Calendar',
                'version' => '2.0.0',
                'env' => 'development', // development, staging, production
                'debug' => true,
                'timezone' => 'America/New_York',
                'url' => 'http://localhost/PhpFinalWebTemplate',
                'asset_url' => 'http://localhost/PhpFinalWebTemplate',
                'admin_email' => 'admin@school.edu',
                'support_email' => 'support@school.edu'
            ],
            
            'database' => [
                'host' => 'localhost',
                'port' => 3306,
                'database' => 'school_calendar_events',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8mb4'
            ],
            
            'session' => [
                'name' => 'school_calendar_session',
                'lifetime' => 7200, // 2 hours
                'path' => '/',
                'domain' => '',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ],
            
            'security' => [
                'csrf_protection' => true,
                'xss_protection' => true,
                'password_hash_algo' => PASSWORD_BCRYPT,
                'password_hash_options' => ['cost' => 12],
                'allowed_upload_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
                'max_upload_size' => 5242880 // 5MB
            ],
            
            'mail' => [
                'driver' => 'smtp', // smtp, sendmail, mail
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'username' => '',
                'password' => '',
                'encryption' => 'tls',
                'from_address' => 'noreply@school.edu',
                'from_name' => 'School Calendar System'
            ],
            
            'calendar' => [
                'default_view' => 'month', // month, week, day, list
                'first_day_of_week' => 0, // 0 = Sunday, 1 = Monday
                'min_time' => '06:00:00',
                'max_time' => '22:00:00',
                'slot_duration' => '00:30:00',
                'snap_duration' => '00:15:00',
                'event_limit' => 5,
                'event_limit_text' => 'more'
            ],
            
            'events' => [
                'approval_required' => true,
                'default_status' => 'pending',
                'max_events_per_day' => 10,
                'rsvp_enabled' => true,
                'reminders_enabled' => true,
                'categories' => [
                    'academic' => ['name' => 'Academic', 'color' => '#1a5f7a', 'icon' => 'fa-graduation-cap'],
                    'sports' => ['name' => 'Sports', 'color' => '#28a745', 'icon' => 'fa-running'],
                    'cultural' => ['name' => 'Cultural', 'color' => '#ff6b35', 'icon' => 'fa-theater-masks'],
                    'parent' => ['name' => 'Parent Events', 'color' => '#ffc107', 'icon' => 'fa-users'],
                    'holiday' => ['name' => 'Holidays', 'color' => '#dc3545', 'icon' => 'fa-umbrella-beach'],
                    'exam' => ['name' => 'Exams', 'color' => '#6f42c1', 'icon' => 'fa-file-alt'],
                    'other' => ['name' => 'Other', 'color' => '#6c757d', 'icon' => 'fa-calendar']
                ]
            ],
            
            'pagination' => [
                'per_page' => 20,
                'max_links' => 5
            ],
            
            'cache' => [
                'enabled' => false,
                'driver' => 'file', // file, redis, memcached
                'lifetime' => 3600, // 1 hour
                'path' => __DIR__ . '/../storage/cache'
            ]
        ];
        
        // Set timezone
        date_default_timezone_set(self::get('app.timezone'));
        
        // Start session with custom settings
        self::startSession();
        
        // Initialize error handling
        self::initErrorHandling();
    }
    
    public static function get($key = null, $default = null) {
        if ($key === null) {
            return self::$config;
        }
        
        $keys = explode('.', $key);
        $value = self::$config;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default;
            }
        }
        
        return $value;
    }
    
    public static function set($key, $value) {
        $keys = explode('.', $key);
        $config = &self::$config;
        
        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }
        
        $config = $value;
    }
    
    private static function startSession() {
        $sessionConfig = self::get('session');
        
        session_name($sessionConfig['name']);
        session_set_cookie_params([
            'lifetime' => $sessionConfig['lifetime'],
            'path' => $sessionConfig['path'],
            'domain' => $sessionConfig['domain'],
            'secure' => $sessionConfig['secure'],
            'httponly' => $sessionConfig['httponly'],
            'samesite' => $sessionConfig['samesite']
        ]);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    private static function initErrorHandling() {
        $env = self::get('app.env');
        $debug = self::get('app.debug');
        
        if ($env === 'production' && !$debug) {
            error_reporting(0);
            ini_set('display_errors', '0');
            ini_set('display_startup_errors', '0');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
        }
        
        // Custom error handler
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            if (!(error_reporting() & $errno)) {
                return false;
            }
            
            $errorTypes = [
                E_ERROR => 'Error',
                E_WARNING => 'Warning',
                E_PARSE => 'Parse Error',
                E_NOTICE => 'Notice',
                E_CORE_ERROR => 'Core Error',
                E_CORE_WARNING => 'Core Warning',
                E_COMPILE_ERROR => 'Compile Error',
                E_COMPILE_WARNING => 'Compile Warning',
                E_USER_ERROR => 'User Error',
                E_USER_WARNING => 'User Warning',
                E_USER_NOTICE => 'User Notice',
                E_STRICT => 'Strict',
                E_RECOVERABLE_ERROR => 'Recoverable Error',
                E_DEPRECATED => 'Deprecated',
                E_USER_DEPRECATED => 'User Deprecated'
            ];
            
            $errorType = isset($errorTypes[$errno]) ? $errorTypes[$errno] : 'Unknown';
            
            $logData = [
                'timestamp' => date('Y-m-d H:i:s'),
                'type' => $errorType,
                'message' => $errstr,
                'file' => $errfile,
                'line' => $errline,
                'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
            ];
            
            $logFile = __DIR__ . '/../logs/errors.log';
            $logDir = dirname($logFile);
            
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
            
            error_log(json_encode($logData, JSON_PRETTY_PRINT) . PHP_EOL, 3, $logFile);
            
            // Don't execute PHP internal error handler
            return true;
        });
        
        // Custom exception handler
        set_exception_handler(function($exception) {
            $logData = [
                'timestamp' => date('Y-m-d H:i:s'),
                'type' => 'Exception',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ];
            
            $logFile = __DIR__ . '/../logs/exceptions.log';
            $logDir = dirname($logFile);
            
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
            
            error_log(json_encode($logData, JSON_PRETTY_PRINT) . PHP_EOL, 3, $logFile);
            
            // Show error page
            if (Config::get('app.env') === 'production') {
                header('HTTP/1.1 500 Internal Server Error');
                include __DIR__ . '/../views/errors/500.php';
            } else {
                echo '<pre>';
                echo "Exception: " . $exception->getMessage() . "\n";
                echo "File: " . $exception->getFile() . ":" . $exception->getLine() . "\n";
                echo "Trace:\n" . $exception->getTraceAsString() . "\n";
                echo '</pre>';
            }
            
            exit;
        });
        
        // Shutdown function for fatal errors
        register_shutdown_function(function() {
            $error = error_get_last();
            
            if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
                $logData = [
                    'timestamp' => date('Y-m-d H:i:s'),
                    'type' => 'Fatal Error',
                    'message' => $error['message'],
                    'file' => $error['file'],
                    'line' => $error['line']
                ];
                
                $logFile = __DIR__ . '/../logs/fatal_errors.log';
                $logDir = dirname($logFile);
                
                if (!is_dir($logDir)) {
                    mkdir($logDir, 0755, true);
                }
                
                error_log(json_encode($logData, JSON_PRETTY_PRINT) . PHP_EOL, 3, $logFile);
                
                if (Config::get('app.env') === 'production') {
                    header('HTTP/1.1 500 Internal Server Error');
                    include __DIR__ . '/../views/errors/500.php';
                } else {
                    echo '<pre>';
                    echo "Fatal Error: " . $error['message'] . "\n";
                    echo "File: " . $error['file'] . ":" . $error['line'] . "\n";
                    echo '</pre>';
                }
            }
        });
    }
    
    public static function asset($path) {
        return self::get('app.asset_url') . '/' . ltrim($path, '/');
    }
    
    public static function url($path = '') {
        return self::get('app.url') . '/' . ltrim($path, '/');
    }
    
    public static function isSecure() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
            || $_SERVER['SERVER_PORT'] == 443
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https');
    }
    
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function verifyCSRFToken($token) {
        if (!self::get('security.csrf_protection')) {
            return true;
        }
        
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

// Initialize configuration
Config::load();
?>