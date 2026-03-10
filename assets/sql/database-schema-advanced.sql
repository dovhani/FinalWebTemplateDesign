-- =============================================
-- SCHOOL CALENDAR & EVENTS DATABASE SCHEMA
-- =============================================

-- Clean setup
DROP DATABASE IF EXISTS school_calendar_events;
CREATE DATABASE school_calendar_events 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;
USE school_calendar_events;

-- =============================================
-- CORE TABLES (in dependency order)
-- =============================================

-- 1. Users table (created first for foreign key references)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'teacher', 'parent', 'student') DEFAULT 'parent',
    is_active BOOLEAN DEFAULT TRUE,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_role (role),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB;

-- 2. Event categories (reference table)
CREATE TABLE event_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    slug VARCHAR(60) UNIQUE NOT NULL,
    description VARCHAR(255),
    color_code VARCHAR(7) DEFAULT '#3788d8',
    icon_class VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_slug (slug),
    INDEX idx_sort_order (sort_order)
) ENGINE=InnoDB;

-- 3. Event locations (reference table)
CREATE TABLE event_locations (
    location_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    building VARCHAR(100),
    room_number VARCHAR(20),
    capacity INT,
    amenities TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_building (building),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB;

-- 4. Calendar events (main table)
CREATE TABLE calendar_events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(300) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    short_description VARCHAR(500),
    event_date DATE NOT NULL,
    start_time TIME,
    end_time TIME,
    category ENUM('academic', 'sports', 'cultural', 'parent', 'holiday', 'exam', 'other') DEFAULT 'academic',
    event_type ENUM('school_event', 'public_holiday', 'exam_test', 'parent_meeting', 'sports_event', 'cultural_event', 'workshop', 'other') DEFAULT 'school_event',
    location VARCHAR(255),
    room_number VARCHAR(20),
    max_participants INT,
    current_participants INT DEFAULT 0,
    
    -- Organizer info
    organizer_name VARCHAR(100) NOT NULL,
    organizer_email VARCHAR(100) NOT NULL,
    organizer_phone VARCHAR(20),
    organizer_role VARCHAR(50),
    
    -- Status and approval
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    approval_notes TEXT,
    approved_by INT,
    approved_at DATETIME,
    
    -- Metadata
    featured_image VARCHAR(255),
    color_code VARCHAR(7) DEFAULT '#3788d8',
    is_featured BOOLEAN DEFAULT FALSE,
    requires_rsvp BOOLEAN DEFAULT FALSE,
    rsvp_deadline DATETIME,
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    
    -- Indexes for performance
    INDEX idx_event_date (event_date),
    INDEX idx_category (category),
    INDEX idx_event_type (event_type),
    INDEX idx_status (status),
    INDEX idx_slug (slug),
    INDEX idx_is_featured (is_featured),
    INDEX idx_created_by (created_by),
    INDEX idx_approved_by (approved_by),
    INDEX idx_rsvp_deadline (rsvp_deadline),
    
    -- Composite index for common queries
    INDEX idx_date_status (event_date, status),
    
    -- Foreign keys
    FOREIGN KEY (created_by) 
        REFERENCES users(user_id) 
        ON DELETE SET NULL,
    FOREIGN KEY (approved_by) 
        REFERENCES users(user_id) 
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- 5. Important dates table (non-event dates)
CREATE TABLE important_dates (
    date_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATE NOT NULL,
    end_date DATE,
    academic_year YEAR NOT NULL,
    applicable_grades VARCHAR(100),
    date_type ENUM('holiday', 'exam_period', 'break', 'registration', 'deadline', 'other') DEFAULT 'holiday',
    recurring_yearly BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_start_date (start_date),
    INDEX idx_end_date (end_date),
    INDEX idx_academic_year (academic_year),
    INDEX idx_date_type (date_type),
    INDEX idx_recurring_yearly (recurring_yearly)
) ENGINE=InnoDB;

-- 6. Event registrations (depends on events and users)
CREATE TABLE event_registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    user_id INT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    student_grade VARCHAR(20),
    num_attendees INT DEFAULT 1,
    special_requirements TEXT,
    status ENUM('pending', 'confirmed', 'cancelled', 'attended') DEFAULT 'pending',
    checkin_time DATETIME,
    checkin_by INT,
    
    -- Metadata
    registration_source ENUM('website', 'email', 'phone', 'in_person') DEFAULT 'website',
    notes TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Constraints and indexes
    UNIQUE KEY unique_event_email (event_id, email),
    INDEX idx_event_id (event_id),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_email (email),
    INDEX idx_created_at (created_at),
    
    -- Foreign keys
    FOREIGN KEY (event_id) 
        REFERENCES calendar_events(event_id) 
        ON DELETE CASCADE,
    FOREIGN KEY (user_id) 
        REFERENCES users(user_id) 
        ON DELETE SET NULL,
    FOREIGN KEY (checkin_by) 
        REFERENCES users(user_id) 
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- 7. Event reminders (depends on events and users)
CREATE TABLE event_reminders (
    reminder_id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    user_id INT,
    email VARCHAR(100) NOT NULL,
    reminder_time DATETIME NOT NULL,
    reminder_sent BOOLEAN DEFAULT FALSE,
    sent_at DATETIME,
    reminder_type ENUM('email', 'sms', 'both') DEFAULT 'email',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_event_id (event_id),
    INDEX idx_user_id (user_id),
    INDEX idx_email (email),
    INDEX idx_reminder_time (reminder_time),
    INDEX idx_reminder_sent (reminder_sent),
    
    FOREIGN KEY (event_id) 
        REFERENCES calendar_events(event_id) 
        ON DELETE CASCADE,
    FOREIGN KEY (user_id) 
        REFERENCES users(user_id) 
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- 8. System settings (independent)
CREATE TABLE system_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json', 'array') DEFAULT 'string',
    category VARCHAR(50),
    description TEXT,
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_setting_key (setting_key),
    INDEX idx_category (category),
    INDEX idx_is_public (is_public)
) ENGINE=InnoDB;

-- 9. Audit log (depends on users)
CREATE TABLE audit_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action_type VARCHAR(50) NOT NULL,
    table_name VARCHAR(50) NOT NULL,
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_action_type (action_type),
    INDEX idx_table_name (table_name),
    INDEX idx_record_id (record_id),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at),
    
    -- Composite indexes for common queries
    INDEX idx_table_record (table_name, record_id),
    INDEX idx_user_action (user_id, action_type),
    
    FOREIGN KEY (user_id) 
        REFERENCES users(user_id) 
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- =============================================
-- INITIAL DATA POPULATION
-- =============================================

-- Default admin user (password: Admin@123)
INSERT INTO users (username, email, password_hash, full_name, role) VALUES
('admin', 'admin@school.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin'),
('teacher1', 'teacher1@school.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', 'teacher'),
('parent1', 'parent1@school.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', 'parent');

-- Default event categories
INSERT INTO event_categories (name, slug, description, color_code, icon_class, sort_order) VALUES
('Academic', 'academic', 'Academic events, classes, lectures', '#1a5f7a', 'fas fa-graduation-cap', 1),
('Sports', 'sports', 'Sports events and tournaments', '#28a745', 'fas fa-running', 2),
('Cultural', 'cultural', 'Cultural events, festivals, arts', '#ff6b35', 'fas fa-theater-masks', 3),
('Parent Events', 'parent-events', 'Parent-teacher meetings and events', '#ffc107', 'fas fa-users', 4),
('Holidays', 'holidays', 'School holidays and breaks', '#dc3545', 'fas fa-umbrella-beach', 5),
('Exams', 'exams', 'Exams and tests', '#6f42c1', 'fas fa-file-alt', 6),
('Workshops', 'workshops', 'Workshops and training sessions', '#20c997', 'fas fa-tools', 7);

-- Default locations
INSERT INTO event_locations (name, building, room_number, capacity) VALUES
('Main Auditorium', 'Main Building', 'A-101', 500),
('School Gym', 'Sports Complex', 'G-01', 300),
('Science Lab', 'Science Building', 'S-201', 30),
('Computer Lab', 'Technology Building', 'T-101', 40),
('Library Hall', 'Library Building', 'L-01', 100),
('Football Field', 'Sports Complex', NULL, 1000),
('Classroom 101', 'Main Building', '101', 40);

-- System settings
INSERT INTO system_settings (setting_key, setting_value, setting_type, category, description) VALUES
('school_name', 'Learning Academy', 'string', 'general', 'Name of the school'),
('calendar_year_start', '2024', 'number', 'calendar', 'Start year for academic calendar'),
('calendar_year_end', '2025', 'number', 'calendar', 'End year for academic calendar'),
('event_approval_required', '1', 'boolean', 'events', 'Whether events require admin approval'),
('default_event_color', '#3788d8', 'string', 'events', 'Default color for events'),
('max_events_per_day', '5', 'number', 'events', 'Maximum events allowed per day'),
('rsvp_enabled', '1', 'boolean', 'events', 'Enable RSVP for events'),
('email_notifications', '1', 'boolean', 'notifications', 'Enable email notifications'),
('timezone', 'America/New_York', 'string', 'general', 'System timezone'),
('date_format', 'F j, Y', 'string', 'general', 'Date display format'),
('time_format', 'g:i A', 'string', 'general', 'Time display format'),
('items_per_page', '10', 'number', 'general', 'Default items per page'),
('maintenance_mode', '0', 'boolean', 'system', 'Maintenance mode status');

-- Sample calendar events
INSERT INTO calendar_events (
    title, slug, description, short_description, event_date, 
    start_time, end_time, category, event_type, location, 
    organizer_name, organizer_email, status, color_code, 
    is_featured, requires_rsvp, created_by, created_at
) VALUES
('Science Fair 2024', 'science-fair-2024', 
 'Annual science exhibition showcasing innovative student projects from all grades. All parents and students are welcome to attend.',
 'Annual science exhibition with student projects',
 '2024-03-15', '09:00:00', '15:00:00', 'academic', 'school_event', 
 'Main Auditorium', 'Dr. Sarah Johnson', 'sarah@school.edu', 
 'approved', '#1a5f7a', TRUE, TRUE, 1, NOW() - INTERVAL 10 DAY),

('Basketball Tournament Finals', 'basketball-tournament-finals-2024', 
 'Championship match between top teams. Come support your school!',
 'Basketball championship finals',
 '2024-03-18', '14:00:00', '17:00:00', 'sports', 'sports_event', 
 'School Gym', 'Coach Michael Brown', 'coach@school.edu', 
 'approved', '#28a745', TRUE, FALSE, 1, NOW() - INTERVAL 8 DAY),

('Parent-Teacher Conference', 'parent-teacher-conference-march-2024', 
 'Quarterly meeting to discuss student progress. Please bring report cards.',
 'Meet with teachers to discuss student progress',
 '2024-03-22', '16:00:00', '19:00:00', 'parent', 'parent_meeting', 
 'Various Classrooms', 'Principal Davis', 'principal@school.edu', 
 'approved', '#ffc107', FALSE, TRUE, 1, NOW() - INTERVAL 5 DAY),

('Spring Break', 'spring-break-2024', 
 'School closed for spring break. No classes scheduled during this period.',
 'School break - no classes',
 '2024-04-01', NULL, NULL, 'holiday', 'public_holiday', 
 'School-wide', 'Administration', 'admin@school.edu', 
 'approved', '#dc3545', FALSE, FALSE, 1, NOW() - INTERVAL 3 DAY),

('Mid-term Examinations', 'mid-term-exams-2024', 
 'Mid-term exams for all grades. Study materials available on the portal.',
 'Mid-term exams week',
 '2024-04-10', '08:30:00', '14:30:00', 'exam', 'exam_test', 
 'All Classrooms', 'Academic Department', 'academic@school.edu', 
 'approved', '#6f42c1', TRUE, FALSE, 1, NOW() - INTERVAL 1 DAY),

('Art Exhibition', 'art-exhibition-2024', 
 'Showcase of student artwork from the semester.',
 'Student art showcase',
 '2024-04-25', '10:00:00', '16:00:00', 'cultural', 'cultural_event', 
 'Library Hall', 'Ms. Emily Chen', 'echen@school.edu', 
 'pending', '#ff6b35', FALSE, TRUE, 2, NOW());

-- Important dates
INSERT INTO important_dates (title, description, start_date, end_date, academic_year, applicable_grades, date_type, recurring_yearly) VALUES
('Academic Year Start', 'First day of new academic year', '2024-09-03', NULL, 2024, 'All Grades', 'other', TRUE),
('Winter Break', 'Winter holidays - school closed', '2024-12-23', '2025-01-03', 2024, 'All Grades', 'break', TRUE),
('Final Exams', 'End of year examinations', '2025-05-20', '2025-06-05', 2024, '9-12', 'exam_period', TRUE),
('Graduation Day', 'Graduation ceremony for seniors', '2025-06-15', NULL, 2024, '12th Grade', 'other', TRUE),
('Summer Vacation', 'Summer break begins', '2025-06-20', '2025-08-30', 2024, 'All Grades', 'break', TRUE),
('Teacher Professional Day', 'No classes - teacher training', '2024-10-11', NULL, 2024, 'All Grades', 'holiday', TRUE),
('Registration Deadline', 'Last day for fall registration', '2024-08-15', NULL, 2024, 'All Grades', 'deadline', TRUE);

-- Sample registrations
INSERT INTO event_registrations (event_id, user_id, full_name, email, phone, student_grade, num_attendees, status) VALUES
(1, 3, 'John Doe', 'parent1@school.edu', '555-0101', '10th', 2, 'confirmed'),
(1, NULL, 'Sarah Wilson', 'sarah.wilson@email.com', '555-0102', '9th', 1, 'pending'),
(3, NULL, 'Robert Chen', 'r.chen@email.com', '555-0103', '11th', 1, 'confirmed');

-- Sample reminders
INSERT INTO event_reminders (event_id, user_id, email, reminder_time, reminder_type) VALUES
(1, 3, 'parent1@school.edu', '2024-03-14 18:00:00', 'email'),
(1, NULL, 'sarah.wilson@email.com', '2024-03-14 09:00:00', 'email'),
(3, NULL, 'r.chen@email.com', '2024-03-21 12:00:00', 'email');

-- =============================================
-- VIEWS
-- =============================================

-- Upcoming events (next 30 days)
CREATE VIEW vw_upcoming_events AS
SELECT 
    event_id,
    title,
    slug,
    short_description,
    event_date,
    start_time,
    end_time,
    category,
    event_type,
    location,
    organizer_name,
    color_code,
    is_featured,
    requires_rsvp,
    status,
    DATEDIFF(event_date, CURDATE()) as days_until,
    CASE 
        WHEN event_date = CURDATE() THEN 'Today'
        WHEN event_date = CURDATE() + INTERVAL 1 DAY THEN 'Tomorrow'
        WHEN DATEDIFF(event_date, CURDATE()) <= 7 THEN 'This Week'
        WHEN DATEDIFF(event_date, CURDATE()) <= 30 THEN 'This Month'
        ELSE 'Upcoming'
    END as timeframe
FROM calendar_events
WHERE status = 'approved'
    AND event_date >= CURDATE()
    AND event_date <= CURDATE() + INTERVAL 30 DAY
ORDER BY event_date, start_time;

-- Today's events
CREATE VIEW vw_todays_events AS
SELECT 
    event_id,
    title,
    short_description,
    start_time,
    end_time,
    category,
    location,
    room_number,
    color_code
FROM calendar_events
WHERE status = 'approved'
    AND event_date = CURDATE()
    AND start_time IS NOT NULL
ORDER BY start_time;

-- Event statistics
CREATE VIEW vw_event_statistics AS
SELECT 
    -- Counts
    COUNT(*) as total_events,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_events,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_events,
    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_events,
    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_events,
    
    -- Features
    SUM(CASE WHEN is_featured = TRUE THEN 1 ELSE 0 END) as featured_events,
    SUM(CASE WHEN requires_rsvp = TRUE THEN 1 ELSE 0 END) as rsvp_events,
    
    -- Date ranges
    MIN(event_date) as earliest_event,
    MAX(event_date) as latest_event,
    
    -- Current stats
    SUM(CASE WHEN event_date >= CURDATE() AND status = 'approved' THEN 1 ELSE 0 END) as upcoming_events,
    SUM(CASE WHEN event_date < CURDATE() THEN 1 ELSE 0 END) as past_events,
    
    -- Categories
    COUNT(DISTINCT category) as categories_count,
    
    -- Registration stats
    (SELECT COUNT(*) FROM event_registrations WHERE status = 'confirmed') as confirmed_registrations,
    (SELECT COUNT(DISTINCT event_id) FROM event_registrations) as events_with_registrations
FROM calendar_events;

-- Monthly event calendar
CREATE VIEW vw_monthly_event_calendar AS
SELECT 
    YEAR(event_date) as year,
    MONTH(event_date) as month,
    MONTHNAME(event_date) as month_name,
    COUNT(*) as total_events,
    COUNT(DISTINCT category) as unique_categories,
    GROUP_CONCAT(DISTINCT category ORDER BY category) as categories_list,
    MIN(event_date) as first_event_date,
    MAX(event_date) as last_event_date
FROM calendar_events
WHERE status = 'approved'
GROUP BY YEAR(event_date), MONTH(event_date), MONTHNAME(event_date)
ORDER BY year DESC, month DESC;

-- Event registration summary
CREATE VIEW vw_event_registration_summary AS
SELECT 
    ce.event_id,
    ce.title,
    ce.event_date,
    ce.max_participants,
    ce.current_participants,
    COUNT(er.registration_id) as total_registrations,
    SUM(CASE WHEN er.status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_registrations,
    SUM(CASE WHEN er.status = 'pending' THEN 1 ELSE 0 END) as pending_registrations,
    SUM(CASE WHEN er.status = 'attended' THEN er.num_attendees ELSE 0 END) as total_attended
FROM calendar_events ce
LEFT JOIN event_registrations er ON ce.event_id = er.event_id
WHERE ce.requires_rsvp = TRUE
GROUP BY ce.event_id, ce.title, ce.event_date, ce.max_participants, ce.current_participants
ORDER BY ce.event_date DESC;

-- =============================================
-- STORED PROCEDURES
-- =============================================

-- Procedure to approve an event
DELIMITER $$
CREATE PROCEDURE sp_approve_event(
    IN p_event_id INT,
    IN p_approved_by INT,
    IN p_notes TEXT
)
BEGIN
    DECLARE v_old_status VARCHAR(20);
    
    -- Get current status for audit
    SELECT status INTO v_old_status 
    FROM calendar_events 
    WHERE event_id = p_event_id;
    
    -- Update event
    UPDATE calendar_events
    SET status = 'approved',
        approved_by = p_approved_by,
        approved_at = NOW(),
        approval_notes = p_notes,
        updated_at = NOW()
    WHERE event_id = p_event_id
        AND status = 'pending';
    
    -- Log the change
    INSERT INTO audit_log (
        user_id,
        action_type,
        table_name,
        record_id,
        old_values,
        new_values
    ) VALUES (
        p_approved_by,
        'EVENT_APPROVED',
        'calendar_events',
        p_event_id,
        JSON_OBJECT('status', v_old_status),
        JSON_OBJECT('status', 'approved', 'approved_by', p_approved_by)
    );
    
    SELECT ROW_COUNT() as rows_affected;
END$$
DELIMITER ;

-- Procedure to get events by date range with filters
DELIMITER $$
CREATE PROCEDURE sp_get_events_by_date_range(
    IN p_start_date DATE,
    IN p_end_date DATE,
    IN p_status VARCHAR(20),
    IN p_category VARCHAR(20),
    IN p_event_type VARCHAR(20),
    IN p_limit INT,
    IN p_offset INT
)
BEGIN
    SELECT 
        ce.event_id,
        ce.title,
        ce.slug,
        ce.short_description,
        ce.event_date,
        ce.start_time,
        ce.end_time,
        ce.category,
        ce.event_type,
        ce.location,
        ce.room_number,
        ce.color_code,
        ce.organizer_name,
        ce.organizer_email,
        ce.is_featured,
        ce.requires_rsvp,
        ce.status,
        -- Count registrations if needed
        (SELECT COUNT(*) FROM event_registrations er 
         WHERE er.event_id = ce.event_id AND er.status = 'confirmed') as confirmed_rsvps
    FROM calendar_events ce
    WHERE ce.event_date BETWEEN p_start_date AND p_end_date
        AND (p_status IS NULL OR ce.status = p_status)
        AND (p_category IS NULL OR ce.category = p_category)
        AND (p_event_type IS NULL OR ce.event_type = p_event_type)
    ORDER BY 
        CASE WHEN ce.is_featured = TRUE THEN 0 ELSE 1 END,
        ce.event_date,
        ce.start_time
    LIMIT p_limit OFFSET p_offset;
    
    -- Also return total count for pagination
    SELECT COUNT(*) as total_count
    FROM calendar_events ce
    WHERE ce.event_date BETWEEN p_start_date AND p_end_date
        AND (p_status IS NULL OR ce.status = p_status)
        AND (p_category IS NULL OR ce.category = p_category)
        AND (p_event_type IS NULL OR ce.event_type = p_event_type);
END$$
DELIMITER ;

-- Procedure to register for an event
DELIMITER $$
CREATE PROCEDURE sp_register_for_event(
    IN p_event_id INT,
    IN p_user_id INT,
    IN p_full_name VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_phone VARCHAR(20),
    IN p_student_grade VARCHAR(20),
    IN p_num_attendees INT,
    IN p_special_requirements TEXT,
    IN p_registration_source VARCHAR(20)
)
BEGIN
    DECLARE v_event_exists BOOLEAN;
    DECLARE v_max_participants INT;
    DECLARE v_current_participants INT;
    DECLARE v_requires_rsvp BOOLEAN;
    
    -- Check if event exists and get details
    SELECT 
        EXISTS(SELECT 1 FROM calendar_events WHERE event_id = p_event_id AND status = 'approved'),
        max_participants,
        current_participants,
        requires_rsvp
    INTO 
        v_event_exists,
        v_max_participants,
        v_current_participants,
        v_requires_rsvp
    FROM calendar_events 
    WHERE event_id = p_event_id;
    
    IF NOT v_event_exists THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Event not found or not approved';
    END IF;
    
    IF v_requires_rsvp = FALSE THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Event does not require RSVP';
    END IF;
    
    IF v_max_participants IS NOT NULL AND 
       (v_current_participants + p_num_attendees) > v_max_participants THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Event is at full capacity';
    END IF;
    
    -- Insert registration
    INSERT INTO event_registrations (
        event_id,
        user_id,
        full_name,
        email,
        phone,
        student_grade,
        num_attendees,
        special_requirements,
        registration_source,
        status
    ) VALUES (
        p_event_id,
        p_user_id,
        p_full_name,
        p_email,
        p_phone,
        p_student_grade,
        p_num_attendees,
        p_special_requirements,
        p_registration_source,
        'pending'
    )
    ON DUPLICATE KEY UPDATE
        num_attendees = VALUES(num_attendees),
        special_requirements = VALUES(special_requirements),
        updated_at = NOW();
    
    -- Update participant count
    UPDATE calendar_events
    SET current_participants = current_participants + p_num_attendees,
        updated_at = NOW()
    WHERE event_id = p_event_id;
    
    SELECT LAST_INSERT_ID() as registration_id;
END$$
DELIMITER ;

-- Procedure to send event reminders
DELIMITER $$
CREATE PROCEDURE sp_send_event_reminders()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_reminder_id INT;
    DECLARE v_event_id INT;
    DECLARE v_email VARCHAR(100);
    DECLARE v_event_title VARCHAR(255);
    DECLARE v_event_date DATE;
    DECLARE v_start_time TIME;
    
    DECLARE reminder_cursor CURSOR FOR
        SELECT 
            er.reminder_id,
            er.event_id,
            er.email,
            ce.title,
            ce.event_date,
            ce.start_time
        FROM event_reminders er
        JOIN calendar_events ce ON er.event_id = ce.event_id
        WHERE er.reminder_sent = FALSE
            AND er.reminder_time <= NOW()
            AND ce.status = 'approved';
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN reminder_cursor;
    
    reminder_loop: LOOP
        FETCH reminder_cursor INTO 
            v_reminder_id, v_event_id, v_email, v_event_title, v_event_date, v_start_time;
        
        IF done THEN
            LEAVE reminder_loop;
        END IF;
        
        -- Here you would typically send the actual email
        -- For now, just mark as sent
        UPDATE event_reminders
        SET reminder_sent = TRUE,
            sent_at = NOW()
        WHERE reminder_id = v_reminder_id;
        
        -- Log the reminder sent
        INSERT INTO audit_log (
            action_type,
            table_name,
            record_id,
            new_values
        ) VALUES (
            'REMINDER_SENT',
            'event_reminders',
            v_reminder_id,
            JSON_OBJECT('email', v_email, 'event_id', v_event_id)
        );
    END LOOP;
    
    CLOSE reminder_cursor;
    
    SELECT CONCAT('Processed ', ROW_COUNT(), ' reminders') as result;
END$$
DELIMITER ;

-- =============================================
-- FUNCTIONS
-- =============================================

-- Function to check if event is full
DELIMITER $$
CREATE FUNCTION fn_is_event_full(p_event_id INT) 
RETURNS BOOLEAN
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_max_participants INT;
    DECLARE v_current_participants INT;
    
    SELECT max_participants, current_participants
    INTO v_max_participants, v_current_participants
    FROM calendar_events
    WHERE event_id = p_event_id;
    
    IF v_max_participants IS NULL THEN
        RETURN FALSE;
    END IF;
    
    RETURN v_current_participants >= v_max_participants;
END$$
DELIMITER ;

-- Function to get days until event
DELIMITER $$
CREATE FUNCTION fn_days_until_event(p_event_id INT) 
RETURNS INT
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_event_date DATE;
    
    SELECT event_date INTO v_event_date
    FROM calendar_events
    WHERE event_id = p_event_id;
    
    RETURN DATEDIFF(v_event_date, CURDATE());
END$$
DELIMITER ;

-- =============================================
-- TRIGGERS
-- =============================================

-- Auto-generate slug before event insert
DELIMITER $$
CREATE TRIGGER trg_before_event_insert
BEFORE INSERT ON calendar_events
FOR EACH ROW
BEGIN
    DECLARE base_slug VARCHAR(300);
    DECLARE counter INT DEFAULT 0;
    DECLARE final_slug VARCHAR(300);
    
    IF NEW.slug IS NULL OR NEW.slug = '' THEN
        -- Create base slug from title and year
        SET base_slug = LOWER(
            REPLACE(
                REPLACE(
                    REPLACE(
                        REGEXP_REPLACE(NEW.title, '[^a-zA-Z0-9 ]', ''), 
                        ' ', '-'
                    ),
                    '--', '-'
                ),
                '---', '-'
            )
        );
        
        SET base_slug = CONCAT(base_slug, '-', YEAR(NEW.event_date));
        SET final_slug = base_slug;
        
        -- Check for duplicates and add counter if needed
        WHILE EXISTS(SELECT 1 FROM calendar_events WHERE slug = final_slug) DO
            SET counter = counter + 1;
            SET final_slug = CONCAT(base_slug, '-', counter);
        END WHILE;
        
        SET NEW.slug = final_slug;
    END IF;
    
    -- Set default color based on category if not provided
    IF NEW.color_code IS NULL OR NEW.color_code = '' THEN
        CASE NEW.category
            WHEN 'academic' THEN SET NEW.color_code = '#1a5f7a';
            WHEN 'sports' THEN SET NEW.color_code = '#28a745';
            WHEN 'cultural' THEN SET NEW.color_code = '#ff6b35';
            WHEN 'parent' THEN SET NEW.color_code = '#ffc107';
            WHEN 'holiday' THEN SET NEW.color_code = '#dc3545';
            WHEN 'exam' THEN SET NEW.color_code = '#6f42c1';
            ELSE SET NEW.color_code = '#3788d8';
        END CASE;
    END IF;
END$$
DELIMITER ;

-- Log event status changes
DELIMITER $$
CREATE TRIGGER trg_after_event_status_update
AFTER UPDATE ON calendar_events
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO audit_log (
            user_id,
            action_type,
            table_name,
            record_id,
            old_values,
            new_values
        ) VALUES (
            NEW.approved_by,
            'EVENT_STATUS_CHANGE',
            'calendar_events',
            NEW.event_id,
            JSON_OBJECT('status', OLD.status),
            JSON_OBJECT('status', NEW.status, 'approved_by', NEW.approved_by)
        );
    END IF;
END$$
DELIMITER ;

-- Update participant count when registration status changes
DELIMITER $$
CREATE TRIGGER trg_after_registration_update
AFTER UPDATE ON event_registrations
FOR EACH ROW
BEGIN
    DECLARE participant_change INT;
    
    -- Calculate change in participants
    IF OLD.status != NEW.status THEN
        IF NEW.status = 'confirmed' AND OLD.status != 'confirmed' THEN
            SET participant_change = NEW.num_attendees;
        ELSEIF OLD.status = 'confirmed' AND NEW.status != 'confirmed' THEN
            SET participant_change = -OLD.num_attendees;
        ELSE
            SET participant_change = 0;
        END IF;
        
        IF participant_change != 0 THEN
            UPDATE calendar_events
            SET current_participants = current_participants + participant_change,
                updated_at = NOW()
            WHERE event_id = NEW.event_id;
        END IF;
    END IF;
END$$
DELIMITER ;
-- =============================================
-- FINAL SETUP (CORRECTED - Without emoji issues)
-- =============================================

-- Create indexes for performance
CREATE INDEX idx_events_date_status_category 
    ON calendar_events(event_date, status, category);

CREATE INDEX idx_events_featured_date 
    ON calendar_events(is_featured, event_date);

CREATE INDEX idx_registrations_event_status 
    ON event_registrations(event_id, status);

CREATE INDEX idx_important_dates_year_type 
    ON important_dates(academic_year, date_type);

-- Create a user for the application
CREATE USER IF NOT EXISTS 'calendar_app'@'localhost' IDENTIFIED BY 'SecurePass123!';
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE 
    ON school_calendar_events.* 
    TO 'calendar_app'@'localhost';

-- Create a read-only user for reporting
CREATE USER IF NOT EXISTS 'calendar_report'@'localhost' IDENTIFIED BY 'ReadOnlyPass123!';
GRANT SELECT 
    ON school_calendar_events.* 
    TO 'calendar_report'@'localhost';

-- Grant SELECT on individual views
GRANT SELECT ON school_calendar_events.vw_upcoming_events TO 'calendar_report'@'localhost';
GRANT SELECT ON school_calendar_events.vw_todays_events TO 'calendar_report'@'localhost';
GRANT SELECT ON school_calendar_events.vw_event_statistics TO 'calendar_report'@'localhost';
GRANT SELECT ON school_calendar_events.vw_monthly_event_calendar TO 'calendar_report'@'localhost';
GRANT SELECT ON school_calendar_events.vw_event_registration_summary TO 'calendar_report'@'localhost';

-- Flush privileges to apply changes
FLUSH PRIVILEGES;

-- Final success message (without emojis to avoid collation issues)
SELECT 'Database schema created successfully!' as message;
SELECT CONCAT('Database: ', DATABASE()) as info;
SELECT 'Users created: admin, teacher1, parent1' as users;

-- Get counts without collation issues
SET @event_count = (SELECT COUNT(*) FROM calendar_events);
SET @location_count = (SELECT COUNT(*) FROM event_locations);
SET @user_count = (SELECT COUNT(*) FROM users);

SELECT CONCAT('Sample events: ', @event_count) as events;
SELECT CONCAT('Locations: ', @location_count) as locations;
SELECT CONCAT('Total users: ', @user_count) as total_users;

-- Display summary table
SELECT 
    'Summary' as item,
    'Count' as value
UNION ALL
SELECT 
    'Calendar Events',
    CAST(@event_count AS CHAR)
UNION ALL
SELECT 
    'Important Dates',
    CAST((SELECT COUNT(*) FROM important_dates) AS CHAR)
UNION ALL
SELECT 
    'Event Locations',
    CAST(@location_count AS CHAR)
UNION ALL
SELECT 
    'Users',
    CAST(@user_count AS CHAR)
UNION ALL
SELECT 
    'Event Categories',
    CAST((SELECT COUNT(*) FROM event_categories) AS CHAR);

