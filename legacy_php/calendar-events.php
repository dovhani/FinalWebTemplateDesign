<?php
// calendar-events.php - Calendar & Events Page
include 'includes/navigation.php';

// DATABASE CONNECTION
require_once 'includes/database.php';

// Get current month and year or from URL parameters
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Validate month and year
if ($month < 1 || $month > 12) $month = date('n');
if ($year < 2020 || $year > 2030) $year = date('Y');

// Get month name
$monthName = date('F', mktime(0, 0, 0, $month, 1, $year));

// Calculate previous and next months
$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear = $year - 1;
}

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear = $year + 1;
}

// Get today's date
$today = date('Y-m-d');
$todayDay = date('j');

// Get events for the current month
$events = [];
$eventsByDay = [];

try {
    $startDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
    $endDate = date('Y-m-t', strtotime($startDate));
    
    $events = Database::fetchAll("
        SELECT event_id, title, description, event_date, 
               TIME_FORMAT(start_time, '%h:%i %p') as start_time,
               TIME_FORMAT(end_time, '%h:%i %p') as end_time,
               category, event_type, location
        FROM calendar_events 
        WHERE event_date BETWEEN ? AND ?
        AND is_approved = TRUE
        ORDER BY event_date, start_time
    ", [$startDate, $endDate]);
    
    // Group events by day
    foreach ($events as $event) {
        $day = date('j', strtotime($event['event_date']));
        $eventsByDay[$day][] = $event;
    }
} catch (Exception $e) {
    $error = "Error loading events: " . $e->getMessage();
}

// Get upcoming events (next 30 days)
$upcomingEvents = [];
try {
    $upcomingEvents = Database::fetchAll("
        SELECT event_id, title, description, event_date, 
               TIME_FORMAT(start_time, '%h:%i %p') as start_time,
               TIME_FORMAT(end_time, '%h:%i %p') as end_time,
               category, event_type, location
        FROM calendar_events 
        WHERE event_date >= CURDATE()
        AND is_approved = TRUE
        ORDER BY event_date ASC
        LIMIT 6
    ");
} catch (Exception $e) {
    // Silent error for upcoming events
}

// Get important dates for current year
$importantDates = [];
try {
    $importantDates = Database::fetchAll("
        SELECT event_title, start_date, end_date, applicable_grades, description
        FROM important_dates 
        WHERE academic_year = ?
        ORDER BY start_date
    ", [$year]);
} catch (Exception $e) {
    // Silent error for important dates
}

// Generate calendar
$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date('t', $firstDayOfMonth);
$firstDayOfWeek = date('w', $firstDayOfMonth);

// Start the calendar on Sunday
$calendar = [];
$dayCount = 0;

// Previous month's days
$prevMonthDays = date('t', mktime(0, 0, 0, $prevMonth, 1, $prevYear));
for ($i = $firstDayOfWeek - 1; $i >= 0; $i--) {
    $calendar[] = [
        'day' => $prevMonthDays - $i,
        'month' => 'prev',
        'isToday' => false,
        'hasEvent' => false
    ];
    $dayCount++;
}

// Current month's days
for ($day = 1; $day <= $daysInMonth; $day++) {
    $dateStr = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($day, 2, '0', STR_PAD_LEFT);
    $isToday = ($dateStr === $today);
    $hasEvent = isset($eventsByDay[$day]);
    
    $calendar[] = [
        'day' => $day,
        'month' => 'current',
        'isToday' => $isToday,
        'hasEvent' => $hasEvent,
        'date' => $dateStr
    ];
    $dayCount++;
}

// Next month's days
$nextDay = 1;
while ($dayCount % 7 != 0) {
    $calendar[] = [
        'day' => $nextDay,
        'month' => 'next',
        'isToday' => false,
        'hasEvent' => false
    ];
    $dayCount++;
    $nextDay++;
}

// Helper function to get event color
function getEventColor($eventType) {
    $colors = [
        'school_event' => '#1a5f7a',
        'holiday' => '#28a745',
        'exam' => '#dc3545',
        'parent_meeting' => '#ffc107',
        'other' => '#6c757d'
    ];
    return $colors[$eventType] ?? '#6c757d';
}

// Get category icon
function getCategoryIcon($category) {
    $icons = [
        'academic' => '📚',
        'sports' => '⚽',
        'cultural' => '🎭',
        'parent' => '👨‍👩‍👧‍👦',
        'other' => '📅'
    ];
    return $icons[$category] ?? '📅';
}

// Get database statistics
$dbStats = Database::getStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar & Events - Learning Academy</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/calendar-events.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php renderNavigation('calendar-events'); ?>
    
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-calendar-alt"></i> School Calendar & Events</h1>
            <p>Stay updated with our school activities, important dates, and upcoming events.</p>
            
            <!-- Quick Stats -->
            <div class="quick-stats">
                <div class="stat-item">
                    <span class="stat-number"><?= $dbStats['events']['total_events'] ?? 0 ?></span>
                    <span class="stat-label">Total Events</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= $dbStats['events']['upcoming_events'] ?? 0 ?></span>
                    <span class="stat-label">Upcoming</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= $dbStats['important_dates']['total_important_dates'] ?? 0 ?></span>
                    <span class="stat-label">Important Dates</span>
                </div>
            </div>
        </div>
        
        <!-- Year/Month Selector -->
        <div class="year-selector">
            <form method="GET" action="" class="month-year-form">
                <div class="form-group">
                    <label for="monthSelect"><i class="fas fa-calendar"></i> Month:</label>
                    <select name="month" id="monthSelect" onchange="this.form.submit()">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="yearSelect"><i class="fas fa-calendar-year"></i> Year:</label>
                    <select name="year" id="yearSelect" onchange="this.form.submit()">
                        <?php for ($y = 2022; $y <= 2026; $y++): ?>
                            <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> Go
                    </button>
                    <a href="?month=<?= date('n') ?>&year=<?= date('Y') ?>" class="btn btn-secondary">
                        <i class="fas fa-calendar-day"></i> Today
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="container">
        <!-- Calendar Section -->
        <div class="content-box calendar-section">
            <div class="calendar-header">
                <h2><i class="fas fa-calendar"></i> <?= $monthName . ' ' . $year ?></h2>
                <div class="calendar-nav">
                    <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>" class="btn btn-secondary">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                    <a href="?month=<?= date('n') ?>&year=<?= date('Y') ?>" class="btn btn-primary">
                        <i class="fas fa-calendar-day"></i> Today
                    </a>
                    <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>" class="btn btn-secondary">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="calendar-grid">
                <div class="calendar-weekdays">
                    <div><i class="fas fa-sun"></i> Sun</div>
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div><i class="fas fa-satellite"></i> Sat</div>
                </div>
                
                <div class="calendar-days">
                    <?php foreach ($calendar as $index => $dayData): ?>
                        <?php
                        $class = 'calendar-day';
                        if ($dayData['month'] !== 'current') {
                            $class .= ' other-month';
                        }
                        if ($dayData['isToday']) {
                            $class .= ' active';
                        }
                        if ($dayData['hasEvent']) {
                            $class .= ' event';
                        }
                        ?>
                        <div class="<?= $class ?>" data-date="<?= $dayData['date'] ?? '' ?>">
                            <div class="day-number"><?= $dayData['day'] ?></div>
                            <?php if ($dayData['hasEvent'] && isset($eventsByDay[$dayData['day']])): ?>
                                <div class="event-indicator">
                                    <?php foreach ($eventsByDay[$dayData['day']] as $event): ?>
                                        <div class="event-dot" 
                                             style="background-color: <?= getEventColor($event['event_type']) ?>"
                                             data-tooltip="<?= htmlspecialchars($event['title']) . ' - ' . $event['start_time'] ?>"></div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="event-tooltip">
                                    <?php foreach ($eventsByDay[$dayData['day']] as $event): ?>
                                        <div class="tooltip-item">
                                            <span class="dot" style="background-color: <?= getEventColor($event['event_type']) ?>"></span>
                                            <span class="title"><?= htmlspecialchars($event['title']) ?></span>
                                            <span class="time"><?= $event['start_time'] ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <!-- Upcoming Events Section -->
        <div class="content-box events-section">
            <div class="section-header">
                <h2><i class="fas fa-clock"></i> Upcoming Events</h2>
                <a href="all-events.php" class="btn-link">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="events-filter">
                <button class="filter-btn active" onclick="filterEvents('all')">
                    <i class="fas fa-border-all"></i> All
                </button>
                <button class="filter-btn" onclick="filterEvents('academic')">
                    <i class="fas fa-graduation-cap"></i> Academic
                </button>
                <button class="filter-btn" onclick="filterEvents('sports')">
                    <i class="fas fa-running"></i> Sports
                </button>
                <button class="filter-btn" onclick="filterEvents('cultural')">
                    <i class="fas fa-theater-masks"></i> Cultural
                </button>
                <button class="filter-btn" onclick="filterEvents('parent')">
                    <i class="fas fa-users"></i> Parent
                </button>
            </div>
            
            <div class="events-grid" id="eventsGrid">
                <?php if (!empty($upcomingEvents)): ?>
                    <?php foreach ($upcomingEvents as $event): ?>
                        <div class="event-card" data-category="<?= $event['category'] ?>">
                            <div class="event-date-badge">
                                <div class="date-day"><?= date('d', strtotime($event['event_date'])) ?></div>
                                <div class="date-month"><?= strtoupper(date('M', strtotime($event['event_date']))) ?></div>
                                <div class="date-year"><?= date('Y', strtotime($event['event_date'])) ?></div>
                            </div>
                            <div class="event-content">
                                <div class="event-header">
                                    <span class="event-category <?= $event['category'] ?>">
                                        <?= getCategoryIcon($event['category']) ?> <?= ucfirst($event['category']) ?>
                                    </span>
                                    <span class="event-type" style="color: <?= getEventColor($event['event_type']) ?>">
                                        ● <?= ucfirst(str_replace('_', ' ', $event['event_type'])) ?>
                                    </span>
                                </div>
                                <h3><?= htmlspecialchars($event['title']) ?></h3>
                                <div class="event-details">
                                    <div class="detail-item">
                                        <i class="fas fa-clock"></i>
                                        <span><?= $event['start_time'] ?>
                                        <?php if ($event['end_time']): ?>
                                            - <?= $event['end_time'] ?>
                                        <?php endif; ?>
                                        </span>
                                    </div>
                                    <?php if ($event['location']): ?>
                                        <div class="detail-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?= htmlspecialchars($event['location']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <p class="event-description"><?= htmlspecialchars(substr($event['description'], 0, 120)) ?>...</p>
                                <div class="event-actions">
                                    <a href="event-details.php?id=<?= $event['event_id'] ?>" class="btn btn-primary">
                                        <i class="fas fa-info-circle"></i> Details
                                    </a>
                                    <button class="btn btn-secondary add-to-calendar-btn" 
                                            data-event='<?= htmlspecialchars(json_encode($event), ENT_QUOTES, 'UTF-8') ?>'>
                                        <i class="fas fa-calendar-plus"></i> Add
                                    </button>
                                    <button class="btn btn-outline reminder-btn" data-event-id="<?= $event['event_id'] ?>">
                                        <i class="fas fa-bell"></i> Remind
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-events">
                        <i class="fas fa-calendar-times"></i>
                        <h3>No Upcoming Events</h3>
                        <p>Check back later for scheduled events or submit your own!</p>
                        <a href="#eventForm" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Submit Event
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Important Dates Section -->
        <div class="content-box important-dates-section">
            <div class="section-header">
                <h2><i class="fas fa-star"></i> Important Dates for <?= $year ?></h2>
            </div>
            
            <?php if (!empty($importantDates)): ?>
                <div class="dates-timeline">
                    <?php foreach ($importantDates as $date): ?>
                        <div class="timeline-item">
                            <div class="timeline-date">
                                <div class="date-range">
                                    <span class="start-date"><?= date('M d', strtotime($date['start_date'])) ?></span>
                                    <?php if ($date['end_date']): ?>
                                        <span class="date-separator">→</span>
                                        <span class="end-date"><?= date('M d', strtotime($date['end_date'])) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="date-year"><?= date('Y', strtotime($date['start_date'])) ?></div>
                            </div>
                            <div class="timeline-content">
                                <h4><?= htmlspecialchars($date['event_title']) ?></h4>
                                <p><?= htmlspecialchars($date['description']) ?></p>
                                <?php if ($date['applicable_grades']): ?>
                                    <div class="applicable-grades">
                                        <i class="fas fa-users"></i> Grades: <?= $date['applicable_grades'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-dates">
                    <i class="fas fa-calendar-plus"></i>
                    <p>No important dates scheduled for <?= $year ?>. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Download Calendar Section -->
        <div class="content-box download-section">
            <h2><i class="fas fa-download"></i> Download School Calendar</h2>
            <p>Get offline access to the <?= $year ?> academic calendar.</p>
            <div class="download-options">
                <div class="download-card">
                    <div class="download-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h3>PDF Version</h3>
                    <p>Printable calendar with all events</p>
                    <a href="download-calendar.php?year=<?= $year ?>&format=pdf&lang=en" class="btn btn-primary">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                </div>
                
                <div class="download-card">
                    <div class="download-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Digital Calendar</h3>
                    <p>Sync with your digital calendar</p>
                    <a href="download-calendar.php?year=<?= $year ?>&format=ics" class="btn btn-secondary">
                        <i class="fas fa-calendar-plus"></i> Add to Calendar
                    </a>
                </div>
                
                <div class="download-card">
                    <div class="download-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile App</h3>
                    <p>Get notifications on your phone</p>
                    <button class="btn btn-success" onclick="showMobileOptions()">
                        <i class="fas fa-qrcode"></i> Get App
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Event Submission Section -->
        <div class="content-box" id="eventForm">
            <div class="section-header">
                <h2><i class="fas fa-plus-circle"></i> Submit an Event</h2>
                <span class="form-info">All submissions require approval</span>
            </div>
            
            <form class="event-submission-form" action="submit-event.php" method="POST" id="eventSubmissionForm">
                <div class="form-steps">
                    <div class="step active" data-step="1">1. Event Details</div>
                    <div class="step" data-step="2">2. Timing</div>
                    <div class="step" data-step="3">3. Contact Info</div>
                </div>
                
                <!-- Step 1: Event Details -->
                <div class="form-step active" id="step1">
                    <div class="form-group">
                        <label for="event-title"><i class="fas fa-heading"></i> Event Title *</label>
                        <input type="text" id="event-title" name="title" placeholder="e.g., Science Fair 2024" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="event-category"><i class="fas fa-tag"></i> Category *</label>
                            <select id="event-category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="academic">Academic</option>
                                <option value="sports">Sports</option>
                                <option value="cultural">Cultural</option>
                                <option value="parent">Parent Event</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="event-type"><i class="fas fa-calendar-check"></i> Event Type *</label>
                            <select id="event-type" name="event_type" required>
                                <option value="">Select Type</option>
                                <option value="school_event">School Event</option>
                                <option value="holiday">Public Holiday</option>
                                <option value="exam">Exam/Test</option>
                                <option value="parent_meeting">Parent Meeting</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="event-description"><i class="fas fa-align-left"></i> Description *</label>
                        <textarea id="event-description" name="description" 
                                  placeholder="Describe the event, including objectives, activities, and any special requirements..." 
                                  rows="4" required></textarea>
                        <div class="char-count">0/500 characters</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="event-location"><i class="fas fa-map-marker-alt"></i> Location</label>
                        <input type="text" id="event-location" name="location" placeholder="e.g., School Auditorium, Room 101">
                    </div>
                    
                    <button type="button" class="btn btn-primary next-step" data-next="2">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
                
                <!-- Step 2: Timing -->
                <div class="form-step" id="step2">
                    <div class="form-group">
                        <label for="event-date"><i class="fas fa-calendar-day"></i> Event Date *</label>
                        <input type="date" id="event-date" name="event_date" required 
                               min="<?= date('Y-m-d') ?>">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="start-time"><i class="fas fa-clock"></i> Start Time</label>
                            <input type="time" id="start-time" name="start_time">
                        </div>
                        <div class="form-group">
                            <label for="end-time"><i class="fas fa-clock"></i> End Time</label>
                            <input type="time" id="end-time" name="end_time">
                        </div>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" id="recurring-event" name="is_recurring">
                        <label for="recurring-event">This is a recurring event</label>
                    </div>
                    
                    <div id="recurring-options" style="display: none;">
                        <div class="form-group">
                            <label>Repeat</label>
                            <select name="recurrence_pattern">
                                <option value="weekly">Weekly</option>
                                <option value="biweekly">Bi-weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="recurrence_end_date">
                        </div>
                    </div>
                    
                    <div class="step-buttons">
                        <button type="button" class="btn btn-outline prev-step" data-prev="1">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn btn-primary next-step" data-next="3">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 3: Contact Info -->
                <div class="form-step" id="step3">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="organizer-name"><i class="fas fa-user"></i> Organizer Name *</label>
                            <input type="text" id="organizer-name" name="organizer_name" required>
                        </div>
                        <div class="form-group">
                            <label for="organizer-email"><i class="fas fa-envelope"></i> Email *</label>
                            <input type="email" id="organizer-email" name="organizer_email" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="organizer-phone"><i class="fas fa-phone"></i> Phone Number</label>
                        <input type="tel" id="organizer-phone" name="organizer_phone">
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" id="notify-me" name="notify_me" checked>
                        <label for="notify-me">Notify me when this event is approved</label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the <a href="terms.php" target="_blank">terms and conditions</a> *</label>
                    </div>
                    
                    <div class="step-buttons">
                        <button type="button" class="btn btn-outline prev-step" data-prev="2">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Submit Event
                        </button>
                    </div>
                </div>
            </form>
            
            <div class="form-info-box">
                <div class="info-item">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Approval Process:</strong> Events are reviewed within 2-3 business days
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-bell"></i>
                    <div>
                        <strong>Notifications:</strong> You'll receive email updates about your submission
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats Widget -->
        <div class="content-box stats-widget">
            <h2><i class="fas fa-chart-bar"></i> Calendar Statistics</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon academic">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?= count(array_filter($upcomingEvents, fn($e) => $e['category'] === 'academic')) ?></div>
                        <div class="stat-label">Academic Events</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon sports">
                        <i class="fas fa-running"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?= count(array_filter($upcomingEvents, fn($e) => $e['category'] === 'sports')) ?></div>
                        <div class="stat-label">Sports Events</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon holidays">
                        <i class="fas fa-umbrella-beach"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?= count(array_filter($upcomingEvents, fn($e) => $e['event_type'] === 'holiday')) ?></div>
                        <div class="stat-label">Holidays</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon upcoming">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number"><?= count($upcomingEvents) ?></div>
                        <div class="stat-label">Upcoming This Month</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile App QR Code Modal -->
        <div id="mobileAppModal" class="modal">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <h2><i class="fas fa-mobile-alt"></i> Get Our Mobile App</h2>
                <div class="modal-body">
                    <div class="qr-code">
                        <!-- Placeholder for QR code -->
                        <div class="qr-placeholder">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <p>Scan to download</p>
                    </div>
                    <div class="app-links">
                        <a href="#" class="app-link">
                            <i class="fab fa-google-play"></i> Google Play
                        </a>
                        <a href="#" class="app-link">
                            <i class="fab fa-app-store"></i> App Store
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php 
        include 'includes/footer.php';
        renderFooter(); 
    ?>
    
    <script src="js/main.js"></script>
    <script src="js/calendar-events.js"></script>
</body>
</html>