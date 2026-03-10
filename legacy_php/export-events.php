<?php
// export-events.php
require_once 'includes/database.php';

$format = $_GET['format'] ?? 'pdf';
$category = $_GET['category'] ?? '';
$event_type = $_GET['event_type'] ?? '';
$month = $_GET['month'] ?? '';
$year = $_GET['year'] ?? '';
$search = $_GET['search'] ?? '';

// Build query with same filters as all-events.php
$where = ['is_approved = TRUE'];
$params = [];

if (!empty($category) && $category !== 'all') {
    $where[] = 'category = ?';
    $params[] = $category;
}

if (!empty($event_type) && $event_type !== 'all') {
    $where[] = 'event_type = ?';
    $params[] = $event_type;
}

if (!empty($month) && $month >= 1 && $month <= 12) {
    $where[] = 'MONTH(event_date) = ?';
    $params[] = $month;
}

if (!empty($year)) {
    $where[] = 'YEAR(event_date) = ?';
    $params[] = $year;
}

if (!empty($search)) {
    $where[] = '(title LIKE ? OR description LIKE ? OR location LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$where[] = 'event_date >= CURDATE()';
$where_clause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Get events
$events = Database::fetchAll("
    SELECT event_id, title, description, event_date, 
           TIME_FORMAT(start_time, '%h:%i %p') as start_time,
           TIME_FORMAT(end_time, '%h:%i %p') as end_time,
           category, event_type, location
    FROM calendar_events 
    $where_clause
    ORDER BY event_date, start_time
", $params);

// Set headers based on format
switch ($format) {
    case 'pdf':
        // For production, use a proper PDF library like TCPDF or mPDF
        // This is a simplified HTML version that can be printed as PDF
        header('Content-Type: text/html; charset=utf-8');
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>School Events Export</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #333; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
                .event { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
                .event-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
                .event-date { font-weight: bold; color: #667eea; }
                .event-title { font-size: 1.2em; font-weight: bold; color: #333; }
                .event-details { margin: 10px 0; color: #666; }
                .event-category { display: inline-block; padding: 2px 8px; background: #f0f0f0; border-radius: 3px; font-size: 0.9em; }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <h1>School Events Export</h1>
            <p class="no-print">Generated on <?= date('F j, Y, g:i a') ?> | <button onclick="window.print()">Print</button></p>
            
            <div class="filter-info">
                <?php if (!empty($category) && $category !== 'all'): ?>
                    <p><strong>Category:</strong> <?= ucfirst($category) ?></p>
                <?php endif; ?>
                <?php if (!empty($event_type) && $event_type !== 'all'): ?>
                    <p><strong>Event Type:</strong> <?= ucfirst(str_replace('_', ' ', $event_type)) ?></p>
                <?php endif; ?>
                <?php if (!empty($month)): ?>
                    <p><strong>Month:</strong> <?= date('F', mktime(0, 0, 0, $month, 1)) ?></p>
                <?php endif; ?>
                <?php if (!empty($search)): ?>
                    <p><strong>Search:</strong> "<?= htmlspecialchars($search) ?>"</p>
                <?php endif; ?>
                <p><strong>Total Events:</strong> <?= count($events) ?></p>
            </div>
            
            <?php foreach ($events as $event): ?>
                <div class="event">
                    <div class="event-header">
                        <div class="event-date"><?= date('F j, Y', strtotime($event['event_date'])) ?></div>
                        <div class="event-category"><?= ucfirst($event['category']) ?></div>
                    </div>
                    <div class="event-title"><?= htmlspecialchars($event['title']) ?></div>
                    <div class="event-details">
                        <?php if ($event['start_time']): ?>
                            <div><strong>Time:</strong> <?= $event['start_time'] ?>
                                <?php if ($event['end_time']): ?>
                                    - <?= $event['end_time'] ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($event['location']): ?>
                            <div><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></div>
                        <?php endif; ?>
                        <div><strong>Type:</strong> <?= ucfirst(str_replace('_', ' ', $event['event_type'])) ?></div>
                    </div>
                    <?php if ($event['description']): ?>
                        <div class="event-description"><?= htmlspecialchars($event['description']) ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <script>
                window.onload = function() {
                    <?php if (isset($_GET['autoprint'])): ?>
                        window.print();
                    <?php endif; ?>
                }
            </script>
        </body>
        </html>
        <?php
        break;
        
    case 'excel':
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="events_' . date('Y-m-d') . '.xls"');
        
        echo "Event Title\tDate\tStart Time\tEnd Time\tCategory\tType\tLocation\tDescription\n";
        
        foreach ($events as $event) {
            $row = [
                $event['title'],
                $event['event_date'],
                $event['start_time'] ?: '',
                $event['end_time'] ?: '',
                ucfirst($event['category']),
                ucfirst(str_replace('_', ' ', $event['event_type'])),
                $event['location'] ?: '',
                str_replace(["\t", "\r", "\n"], ' ', strip_tags($event['description']))
            ];
            echo implode("\t", $row) . "\n";
        }
        break;
        
    case 'ics':
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="school_events.ics"');
        
        echo "BEGIN:VCALENDAR\r\n";
        echo "VERSION:2.0\r\n";
        echo "PRODID:-//Learning Academy//School Events//EN\r\n";
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
            echo "CATEGORIES:" . $event['category'] . "\r\n";
            echo "END:VEVENT\r\n";
        }
        
        echo "END:VCALENDAR\r\n";
        break;
        
    default:
        echo "Invalid export format.";
        break;
}
?>