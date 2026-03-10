<?php
// event-details.php
include 'includes/navigation.php';
require_once 'includes/database.php';

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($event_id <= 0) {
    header('Location: calendar-events.php');
    exit;
}

// Get event details
$event = Database::fetchOne("
    SELECT event_id, title, description, event_date, 
           TIME_FORMAT(start_time, '%h:%i %p') as start_time,
           TIME_FORMAT(end_time, '%h:%i %p') as end_time,
           category, event_type, location,
           organizer_name, organizer_email,
           created_at, is_approved
    FROM calendar_events 
    WHERE event_id = ? AND is_approved = TRUE
", [$event_id]);

if (!$event) {
    header('Location: calendar-events.php');
    exit;
}

// Get similar events (same category)
$similar_events = Database::fetchAll("
    SELECT event_id, title, event_date, 
           TIME_FORMAT(start_time, '%h:%i %p') as start_time
    FROM calendar_events 
    WHERE category = ? 
    AND event_id != ? 
    AND event_date >= CURDATE()
    AND is_approved = TRUE
    ORDER BY event_date
    LIMIT 3
", [$event['category'], $event_id]);

// Helper functions
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['title']) ?> - Learning Academy</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/event-details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php renderNavigation('calendar-events'); ?>
    
    <div class="page-header">
        <div class="header-content">
            <nav class="breadcrumb">
                <a href="calendar-events.php"><i class="fas fa-calendar-alt"></i> Calendar</a>
                <i class="fas fa-chevron-right"></i>
                <a href="all-events.php">All Events</a>
                <i class="fas fa-chevron-right"></i>
                <span>Event Details</span>
            </nav>
            <h1><?= htmlspecialchars($event['title']) ?></h1>
        </div>
    </div>
    
    <div class="container">
        <!-- Main Event Details -->
        <div class="event-details-container">
            <div class="event-main">
                <div class="event-header">
                    <div class="event-date-badge">
                        <div class="date-day"><?= date('d', strtotime($event['event_date'])) ?></div>
                        <div class="date-month"><?= strtoupper(date('M', strtotime($event['event_date']))) ?></div>
                        <div class="date-year"><?= date('Y', strtotime($event['event_date'])) ?></div>
                    </div>
                    
                    <div class="event-info">
                        <div class="event-tags">
                            <span class="event-category <?= $event['category'] ?>">
                                <?= getCategoryIcon($event['category']) ?> <?= ucfirst($event['category']) ?>
                            </span>
                            <span class="event-type" style="color: <?= getEventColor($event['event_type']) ?>">
                                ● <?= ucfirst(str_replace('_', ' ', $event['event_type'])) ?>
                            </span>
                        </div>
                        
                        <div class="event-meta">
                            <?php if ($event['start_time']): ?>
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <div class="meta-label">Time</div>
                                        <div class="meta-value">
                                            <?= $event['start_time'] ?>
                                            <?php if ($event['end_time']): ?>
                                                - <?= $event['end_time'] ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($event['location']): ?>
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <div class="meta-label">Location</div>
                                        <div class="meta-value"><?= htmlspecialchars($event['location']) ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($event['organizer_name']): ?>
                                <div class="meta-item">
                                    <i class="fas fa-user"></i>
                                    <div>
                                        <div class="meta-label">Organizer</div>
                                        <div class="meta-value"><?= htmlspecialchars($event['organizer_name']) ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="event-content">
                    <div class="event-description">
                        <h3>Event Description</h3>
                        <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                    </div>
                    
                    <div class="event-actions">
                        <button class="btn btn-primary add-to-calendar-btn" 
                                data-event='<?= htmlspecialchars(json_encode($event), ENT_QUOTES, 'UTF-8') ?>'>
                            <i class="fas fa-calendar-plus"></i> Add to Calendar
                        </button>
                        
                        <button class="btn btn-secondary" onclick="setReminder(<?= $event['event_id'] ?>)">
                            <i class="fas fa-bell"></i> Set Reminder
                        </button>
                        
                        <button class="btn btn-outline" onclick="shareEvent()">
                            <i class="fas fa-share-alt"></i> Share Event
                        </button>
                        
                        <a href="calendar-events.php" class="btn btn-link">
                            <i class="fas fa-arrow-left"></i> Back to Calendar
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Event Actions Sidebar -->
            <div class="event-sidebar">
                <div class="sidebar-section">
                    <h3><i class="fas fa-calendar-day"></i> Event Date</h3>
                    <div class="event-date-display">
                        <div class="date-full"><?= date('l, F j, Y', strtotime($event['event_date'])) ?></div>
                        <?php if ($event['start_time']): ?>
                            <div class="time-full">
                                <?= $event['start_time'] ?>
                                <?php if ($event['end_time']): ?>
                                    - <?= $event['end_time'] ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="sidebar-section">
                    <h3><i class="fas fa-info-circle"></i> Quick Info</h3>
                    <div class="quick-info">
                        <div class="info-item">
                            <span class="info-label">Category:</span>
                            <span class="info-value"><?= ucfirst($event['category']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Type:</span>
                            <span class="info-value"><?= ucfirst(str_replace('_', ' ', $event['event_type'])) ?></span>
                        </div>
                        <?php if ($event['organizer_name']): ?>
                            <div class="info-item">
                                <span class="info-label">Organizer:</span>
                                <span class="info-value"><?= htmlspecialchars($event['organizer_name']) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <span class="info-label">Posted:</span>
                            <span class="info-value"><?= date('M j, Y', strtotime($event['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-section">
                    <h3><i class="fas fa-download"></i> Export</h3>
                    <div class="export-options">
                        <button class="export-btn" onclick="exportEvent('pdf')">
                            <i class="fas fa-file-pdf"></i> Save as PDF
                        </button>
                        <button class="export-btn" onclick="exportEvent('ics')">
                            <i class="fas fa-calendar-alt"></i> Add to Calendar
                        </button>
                    </div>
                </div>
                
                <?php if (!empty($similar_events)): ?>
                    <div class="sidebar-section">
                        <h3><i class="fas fa-calendar-star"></i> Similar Events</h3>
                        <div class="similar-events">
                            <?php foreach ($similar_events as $similar): ?>
                                <a href="event-details.php?id=<?= $similar['event_id'] ?>" class="similar-event">
                                    <div class="similar-date">
                                        <?= date('M j', strtotime($similar['event_date'])) ?>
                                    </div>
                                    <div class="similar-title"><?= htmlspecialchars($similar['title']) ?></div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="event-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><i class="fas fa-calendar-check"></i> Need to Update?</h3>
                    <p>If you need to update event details or report an issue, please contact us.</p>
                    <a href="contact.php" class="btn btn-outline">Contact Organizer</a>
                </div>
                
                <div class="footer-section">
                    <h3><i class="fas fa-share-square"></i> Share This Event</h3>
                    <div class="social-share">
                        <button class="social-btn facebook" onclick="shareOnFacebook()">
                            <i class="fab fa-facebook"></i>
                        </button>
                        <button class="social-btn twitter" onclick="shareOnTwitter()">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button class="social-btn whatsapp" onclick="shareOnWhatsApp()">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                        <button class="social-btn email" onclick="shareByEmail()">
                            <i class="fas fa-envelope"></i>
                        </button>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3><i class="fas fa-print"></i> Print Event</h3>
                    <p>Get a printable version of this event for reference.</p>
                    <button class="btn btn-secondary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Details
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <?php 
        include 'includes/footer.php';
        renderFooter(); 
    ?>
    
    <script src="js/main.js"></script>
    <script>
        // Add to calendar
        document.querySelector('.add-to-calendar-btn').addEventListener('click', function() {
            const eventData = JSON.parse(this.dataset.event);
            addToCalendar(eventData);
        });
        
        function addToCalendar(eventData) {
            const startDate = new Date(eventData.event_date + 'T' + (eventData.start_time || '00:00'));
            const endDate = eventData.end_time ? 
                new Date(eventData.event_date + 'T' + eventData.end_time) : 
                new Date(startDate.getTime() + 3600000);
            
            const formatDate = (date) => {
                return date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
            };
            
            const icsContent = `BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
BEGIN:VEVENT
DTSTART:${formatDate(startDate)}
DTEND:${formatDate(endDate)}
SUMMARY:${eventData.title}
DESCRIPTION:${eventData.description}
LOCATION:${eventData.location || 'Not specified'}
STATUS:CONFIRMED
END:VEVENT
END:VCALENDAR`;
            
            const blob = new Blob([icsContent], { type: 'text/calendar' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${eventData.title.replace(/\s+/g, '_')}.ics`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            showToast('Event added to your calendar!', 'success');
        }
        
        // Set reminder
        function setReminder(eventId) {
            if ('Notification' in window && Notification.permission === 'granted') {
                // Schedule notification
                showToast('Reminder set for this event!', 'success');
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        showToast('Reminder permission granted! Please set the reminder again.', 'success');
                    }
                });
            }
        }
        
        // Share event
        function shareEvent() {
            const eventUrl = window.location.href;
            const eventTitle = '<?= addslashes($event['title']) ?>';
            
            if (navigator.share) {
                navigator.share({
                    title: eventTitle,
                    text: 'Check out this school event!',
                    url: eventUrl
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const shareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(eventTitle + ' - ' + eventUrl)}`;
                window.open(shareUrl, '_blank');
            }
        }
        
        // Social sharing functions
        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
        }
        
        function shareOnTwitter() {
            const text = encodeURIComponent('<?= addslashes($event['title']) ?>');
            const url = encodeURIComponent(window.location.href);
            window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
        }
        
        function shareOnWhatsApp() {
            const text = encodeURIComponent('<?= addslashes($event['title']) ?> - ' + window.location.href);
            window.open(`https://api.whatsapp.com/send?text=${text}`, '_blank');
        }
        
        function shareByEmail() {
            const subject = encodeURIComponent('School Event: <?= addslashes($event['title']) ?>');
            const body = encodeURIComponent(`Check out this event:\n\n<?= addslashes($event['title']) ?>\n\nDate: <?= date('F j, Y', strtotime($event['event_date'])) ?>\n\nDescription: <?= addslashes(substr($event['description'], 0, 200)) ?>...\n\nMore details: ${window.location.href}`);
            window.location.href = `mailto:?subject=${subject}&body=${body}`;
        }
        
        // Export functions
        function exportEvent(format) {
            const eventId = <?= $event['event_id'] ?>;
            let url = '';
            
            switch (format) {
                case 'pdf':
                    url = `export-single.php?id=${eventId}&format=pdf`;
                    break;
                case 'ics':
                    url = `export-single.php?id=${eventId}&format=ics`;
                    break;
            }
            
            if (url) {
                window.open(url, '_blank');
            }
        }
        
        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.textContent = message;
            
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                background: ${type === 'success' ? '#28a745' : '#dc3545'};
                color: white;
                border-radius: 5px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                z-index: 1000;
                animation: slideIn 0.3s ease;
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }
        
        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>