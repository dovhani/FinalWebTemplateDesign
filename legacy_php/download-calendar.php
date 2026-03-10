<?php
// download-calendar.php
require_once 'includes/database.php';

$year = $_GET['year'] ?? date('Y');
$format = $_GET['format'] ?? 'pdf';
$lang = $_GET['lang'] ?? 'en';

// Get events for the year
$startDate = "$year-01-01";
$endDate = "$year-12-31";

$events = Database::fetchAll("
    SELECT title, description, event_date, 
           TIME_FORMAT(start_time, '%h:%i %p') as start_time,
           TIME_FORMAT(end_time, '%h:%i %p') as end_time,
           category, event_type, location
    FROM calendar_events 
    WHERE event_date BETWEEN ? AND ?
    AND is_approved = TRUE
    ORDER BY event_date, start_time
", [$startDate, $endDate]);

$importantDates = Database::fetchAll("
    SELECT event_title, start_date, end_date, applicable_grades, description
    FROM important_dates 
    WHERE academic_year = ?
    ORDER BY start_date
", [$year]);

// For ICS format
if ($format === 'ics') {
    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename="school_calendar_' . $year . '.ics"');
    
    echo "BEGIN:VCALENDAR\r\n";
    echo "VERSION:2.0\r\n";
    echo "PRODID:-//Learning Academy//School Calendar//EN\r\n";
    echo "CALSCALE:GREGORIAN\r\n";
    echo "METHOD:PUBLISH\r\n";
    
    foreach ($events as $event) {
        $startDate = $event['event_date'] . ($event['start_time'] ? 'T' . date('His', strtotime($event['start_time'])) : '');
        $endDate = $event['event_date'] . ($event['end_time'] ? 'T' . date('His', strtotime($event['end_time'])) : 'T235959');
        
        echo "BEGIN:VEVENT\r\n";
        echo "UID:" . uniqid() . "@learningacademy.edu\r\n";
        echo "DTSTAMP:" . date('Ymd\THis\Z') . "\r\n";
        echo "DTSTART:" . str_replace(['-', ':'], '', $startDate) . "\r\n";
        echo "DTEND:" . str_replace(['-', ':'], '', $endDate) . "\r\n";
        echo "SUMMARY:" . $event['title'] . "\r\n";
        echo "DESCRIPTION:" . strip_tags($event['description']) . "\r\n";
        if ($event['location']) {
            echo "LOCATION:" . $event['location'] . "\r\n";
        }
        echo "END:VEVENT\r\n";
    }
    
    echo "END:VCALENDAR\r\n";
    exit;
}

// For PDF format (simplified - in production use a proper PDF library)
elseif ($format === 'pdf') {
    header('Content-Type: text/html; charset=utf-8');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>School Calendar <?= $year ?></title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { color: #333; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .event-date { font-weight: bold; }
            .category { color: #666; font-size: 0.9em; }
        </style>
    </head>
    <body>
        <h1>Learning Academy - School Calendar <?= $year ?></h1>
        
        <h2>Important Dates</h2>
        <ul>
            <?php foreach ($importantDates as $date): ?>
                <li>
                    <strong><?= htmlspecialchars($date['event_title']) ?></strong>
                    (<?= date('M d', strtotime($date['start_date'])) ?>
                    <?= $date['end_date'] ? ' - ' . date('M d', strtotime($date['end_date'])) : '' ?>)
                    - <?= htmlspecialchars($date['description']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <h2>Events Calendar</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Event</th>
                <th>Time</th>
                <th>Category</th>
                <th>Location</th>
            </tr>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td class="event-date"><?= date('M d, Y', strtotime($event['event_date'])) ?></td>
                    <td>
                        <strong><?= htmlspecialchars($event['title']) ?></strong><br>
                        <span class="category"><?= ucfirst($event['category']) ?></span>
                    </td>
                    <td>
                        <?php if ($event['start_time']): ?>
                            <?= $event['start_time'] ?>
                            <?php if ($event['end_time']): ?>
                                - <?= $event['end_time'] ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td><?= ucfirst(str_replace('_', ' ', $event['event_type'])) ?></td>
                    <td><?= htmlspecialchars($event['location'] ?? 'TBD') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <p style="margin-top: 30px; color: #666; font-size: 0.9em;">
            Generated on <?= date('F j, Y') ?> | Learning Academy Calendar System
        </p>
        
        <script>
            window.onload = function() {
                window.print();
            }
        </script>
    </body>
    </html>
    <?php
    exit;
}
?>