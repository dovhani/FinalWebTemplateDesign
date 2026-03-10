-- Create database if not exists
CREATE DATABASE IF NOT EXISTS school_calendar_events CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_calendar_events;

-- Calendar events table
CREATE TABLE IF NOT EXISTS calendar_events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    start_time TIME,
    end_time TIME,
    category ENUM('academic', 'sports', 'cultural', 'parent', 'other') DEFAULT 'academic',
    event_type ENUM('school_event', 'holiday', 'exam', 'parent_meeting', 'other') DEFAULT 'school_event',
    location VARCHAR(255),
    is_approved BOOLEAN DEFAULT TRUE,
    organizer_name VARCHAR(100),
    organizer_email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Important dates table
CREATE TABLE IF NOT EXISTS important_dates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_title VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    academic_year YEAR NOT NULL,
    applicable_grades VARCHAR(50),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample events
INSERT INTO calendar_events (title, description, event_date, start_time, end_time, category, event_type, location) VALUES
('Science Fair', 'Annual school science exhibition showcasing student projects', CURDATE() + INTERVAL 5 DAY, '09:00:00', '15:00:00', 'academic', 'school_event', 'School Auditorium'),
('Basketball Tournament', 'Inter-school basketball championship finals', CURDATE() + INTERVAL 8 DAY, '14:00:00', '17:00:00', 'sports', 'school_event', 'School Gym'),
('Parent-Teacher Meeting', 'Quarterly parent-teacher conference', CURDATE() + INTERVAL 3 DAY, '16:00:00', '19:00:00', 'parent', 'parent_meeting', 'Classrooms'),
('Mid-term Exams', 'Mid-term examinations for all grades', CURDATE() + INTERVAL 12 DAY, '08:30:00', '14:30:00', 'academic', 'exam', 'Classrooms'),
('Music Concert', 'Annual school music concert', CURDATE() + INTERVAL 15 DAY, '18:00:00', '21:00:00', 'cultural', 'school_event', 'Auditorium');

-- Insert important dates
INSERT INTO important_dates (event_title, start_date, end_date, academic_year, applicable_grades, description) VALUES
('Spring Break', '2024-03-25', '2024-03-29', 2024, 'All Grades', 'Spring vacation'),
('Final Exams', '2024-05-20', '2024-05-31', 2024, '9-12', 'End of year examinations'),
('Graduation Day', '2024-06-15', NULL, 2024, '12th Grade', 'Graduation ceremony'),
('Summer Vacation', '2024-06-20', '2024-08-15', 2024, 'All Grades', 'Summer holidays'),
('New Academic Year', '2024-08-20', NULL, 2024, 'All Grades', 'First day of new academic year');