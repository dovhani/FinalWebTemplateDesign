<?php
// includes/database.php

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'school_calendar_events');
define('DB_PORT', 3306);
define('DB_CHARSET', 'utf8mb4');

class Database {
    private static $pdo = null;
    private static $mysqli = null;
    
    /**
     * Get PDO connection (recommended)
     */
    public static function getPDO() {
        if (self::$pdo === null) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;port=%d;dbname=%s;charset=%s",
                    DB_HOST,
                    DB_PORT,
                    DB_NAME,
                    DB_CHARSET
                );
                
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                self::$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
                
            } catch (PDOException $e) {
                self::logError($e->getMessage(), 'PDO Connection');
                die('Database connection failed. Please contact administrator.');
            }
        }
        return self::$pdo;
    }
    
    /**
     * Get MySQLi connection (for legacy compatibility)
     */
    public static function getMysqli() {
        if (self::$mysqli === null) {
            self::$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
            
            if (self::$mysqli->connect_error) {
                self::logError(self::$mysqli->connect_error, 'MySQLi Connection');
                die('Database connection failed. Please contact administrator.');
            }
            
            self::$mysqli->set_charset(DB_CHARSET);
        }
        return self::$mysqli;
    }
    
    /**
     * Helper function for prepared statements
     */
    public static function query($sql, $params = []) {
        $pdo = self::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * Fetch all rows
     */
    public static function fetchAll($sql, $params = []) {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Fetch single row
     */
    public static function fetchOne($sql, $params = []) {
        $stmt = self::query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Insert data and return last insert ID
     */
    public static function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        $pdo = self::getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_values($data));
        
        return $pdo->lastInsertId();
    }
    
    /**
     * Update data
     */
    public static function update($table, $data, $where, $whereParams = []) {
        $set = [];
        $params = [];
        
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
            $params[] = $value;
        }
        
        $params = array_merge($params, $whereParams);
        $setClause = implode(', ', $set);
        $sql = "UPDATE $table SET $setClause WHERE $where";
        
        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Delete data
     */
    public static function delete($table, $where, $params = []) {
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Begin transaction
     */
    public static function beginTransaction() {
        return self::getPDO()->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public static function commit() {
        return self::getPDO()->commit();
    }
    
    /**
     * Rollback transaction
     */
    public static function rollback() {
        return self::getPDO()->rollBack();
    }
    
    /**
     * Error logging
     */
    private static function logError($error, $context = 'Database') {
        error_log("[$context] " . date('Y-m-d H:i:s') . " - " . $error);
    }
    
    /**
     * Test database connection
     */
    public static function testConnection() {
        try {
            $pdo = self::getPDO();
            $pdo->query('SELECT 1');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get database statistics
     */
    public static function getStats() {
        $stats = [];
        
        try {
            // Get event counts
            $stmt = self::query("
                SELECT 
                    COUNT(*) as total_events,
                    SUM(CASE WHEN event_date >= CURDATE() THEN 1 ELSE 0 END) as upcoming_events,
                    COUNT(DISTINCT category) as categories_count
                FROM calendar_events 
                WHERE is_approved = TRUE
            ");
            $stats['events'] = $stmt->fetch();
            
            // Get important dates count
            $stmt = self::query("
                SELECT COUNT(*) as total_important_dates
                FROM important_dates 
                WHERE academic_year = YEAR(CURDATE())
            ");
            $stats['important_dates'] = $stmt->fetch();
            
            // Get events by month
            $stmt = self::query("
                SELECT 
                    MONTH(event_date) as month,
                    COUNT(*) as event_count
                FROM calendar_events 
                WHERE YEAR(event_date) = YEAR(CURDATE())
                AND is_approved = TRUE
                GROUP BY MONTH(event_date)
                ORDER BY month
            ");
            $stats['events_by_month'] = $stmt->fetchAll();
            
        } catch (Exception $e) {
            self::logError($e->getMessage(), 'Get Stats');
        }
        
        return $stats;
    }
}

// Legacy functions for backward compatibility
function getDatabaseConnection() {
    return Database::getPDO();
}

function getPDOConnection() {
    return Database::getPDO();
}

function getMysqliConnection() {
    return Database::getMysqli();
}

function testDatabaseConnection() {
    return Database::testConnection();
}
?>