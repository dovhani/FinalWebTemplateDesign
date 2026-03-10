<?php
// all-events.php - All Events Page
include 'includes/navigation.php';

// DATABASE CONNECTION
require_once 'includes/database.php';

// Get pagination parameters
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 20; // Events per page
$offset = ($page - 1) * $limit;

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$event_type = isset($_GET['event_type']) ? $_GET['event_type'] : '';
$month = isset($_GET['month']) ? (int)$_GET['month'] : '';
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'event_date_asc';

// Build query with filters
$where = ['is_approved = TRUE'];
$params = [];

// Category filter
if (!empty($category) && $category !== 'all') {
    $where[] = 'category = ?';
    $params[] = $category;
}

// Event type filter
if (!empty($event_type) && $event_type !== 'all') {
    $where[] = 'event_type = ?';
    $params[] = $event_type;
}

// Month filter
if (!empty($month) && $month >= 1 && $month <= 12) {
    $where[] = 'MONTH(event_date) = ?';
    $params[] = $month;
}

// Year filter
if (!empty($year)) {
    $where[] = 'YEAR(event_date) = ?';
    $params[] = $year;
}

// Search filter
if (!empty($search)) {
    $where[] = '(title LIKE ? OR description LIKE ? OR location LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Future events only (default)
$where[] = 'event_date >= CURDATE()';

$where_clause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Build sort order
$sort_map = [
    'event_date_asc' => 'event_date ASC, start_time ASC',
    'event_date_desc' => 'event_date DESC, start_time DESC',
    'title_asc' => 'title ASC',
    'title_desc' => 'title DESC',
    'category_asc' => 'category ASC, event_date ASC',
];
$order_by = $sort_map[$sort] ?? 'event_date ASC, start_time ASC';

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM calendar_events $where_clause";
$count_result = Database::fetchOne($count_sql, $params);
$total_events = $count_result['total'] ?? 0;
$total_pages = ceil($total_events / $limit);

// Get events with pagination
$sql = "
    SELECT event_id, title, description, event_date, 
           TIME_FORMAT(start_time, '%h:%i %p') as start_time,
           TIME_FORMAT(end_time, '%h:%i %p') as end_time,
           category, event_type, location,
           DAY(event_date) as day,
           MONTH(event_date) as month_num,
           YEAR(event_date) as year_num
    FROM calendar_events 
    $where_clause
    ORDER BY $order_by
    LIMIT ? OFFSET ?
";

$params[] = $limit;
$params[] = $offset;

$events = Database::fetchAll($sql, $params);

// Get unique categories and event types for filters
$categories = Database::fetchAll("
    SELECT DISTINCT category, COUNT(*) as count 
    FROM calendar_events 
    WHERE is_approved = TRUE AND event_date >= CURDATE()
    GROUP BY category 
    ORDER BY category
");

$event_types = Database::fetchAll("
    SELECT DISTINCT event_type, COUNT(*) as count 
    FROM calendar_events 
    WHERE is_approved = TRUE AND event_date >= CURDATE()
    GROUP BY event_type 
    ORDER BY event_type
");

// Get months with events
$months = Database::fetchAll("
    SELECT DISTINCT MONTH(event_date) as month_num, 
           MONTHNAME(event_date) as month_name,
           COUNT(*) as count
    FROM calendar_events 
    WHERE is_approved = TRUE AND event_date >= CURDATE()
    GROUP BY MONTH(event_date), MONTHNAME(event_date)
    ORDER BY month_num
");

// Get years with events
$years = Database::fetchAll("
    SELECT DISTINCT YEAR(event_date) as year, COUNT(*) as count
    FROM calendar_events 
    WHERE is_approved = TRUE
    GROUP BY YEAR(event_date)
    ORDER BY year DESC
");

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

// Helper function to get category icon
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
    <title>All Events - Learning Academy</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/all-events.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php renderNavigation('calendar-events'); ?>
    
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-calendar-week"></i> All School Events</h1>
            <p>Browse and filter through all upcoming and past school events.</p>
        </div>
    </div>
    
    <div class="container">
        <!-- Filters Sidebar -->
        <div class="filters-sidebar">
            <div class="filters-header">
                <h3><i class="fas fa-filter"></i> Filters</h3>
                <button class="clear-filters" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear All
                </button>
            </div>
            
            <!-- Search -->
            <div class="filter-section">
                <h4><i class="fas fa-search"></i> Search</h4>
                <form method="GET" action="" class="search-form">
                    <div class="search-box">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                               placeholder="Search events...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
            
            <!-- Category Filter -->
            <div class="filter-section">
                <h4><i class="fas fa-tag"></i> Category</h4>
                <div class="filter-options">
                    <a href="?<?= buildQueryString(['category' => 'all', 'page' => 1]) ?>" 
                       class="filter-option <?= empty($category) || $category === 'all' ? 'active' : '' ?>">
                        All Categories
                        <span class="filter-count"><?= $total_events ?></span>
                    </a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="?<?= buildQueryString(['category' => $cat['category'], 'page' => 1]) ?>" 
                           class="filter-option <?= $category === $cat['category'] ? 'active' : '' ?>">
                            <?= getCategoryIcon($cat['category']) ?> <?= ucfirst($cat['category']) ?>
                            <span class="filter-count"><?= $cat['count'] ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Event Type Filter -->
            <div class="filter-section">
                <h4><i class="fas fa-calendar-check"></i> Event Type</h4>
                <div class="filter-options">
                    <a href="?<?= buildQueryString(['event_type' => 'all', 'page' => 1]) ?>" 
                       class="filter-option <?= empty($event_type) || $event_type === 'all' ? 'active' : '' ?>">
                        All Types
                        <span class="filter-count"><?= $total_events ?></span>
                    </a>
                    <?php foreach ($event_types as $type): ?>
                        <a href="?<?= buildQueryString(['event_type' => $type['event_type'], 'page' => 1]) ?>" 
                           class="filter-option <?= $event_type === $type['event_type'] ? 'active' : '' ?>">
                            <span class="type-dot" style="background-color: <?= getEventColor($type['event_type']) ?>"></span>
                            <?= ucfirst(str_replace('_', ' ', $type['event_type'])) ?>
                            <span class="filter-count"><?= $type['count'] ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Month Filter -->
            <div class="filter-section">
                <h4><i class="fas fa-calendar-alt"></i> Month</h4>
                <div class="filter-options">
                    <a href="?<?= buildQueryString(['month' => '', 'page' => 1]) ?>" 
                       class="filter-option <?= empty($month) ? 'active' : '' ?>">
                        All Months
                        <span class="filter-count"><?= $total_events ?></span>
                    </a>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <?php 
                        $month_name = date('F', mktime(0, 0, 0, $m, 1));
                        $month_count = 0;
                        foreach ($months as $month_data) {
                            if ($month_data['month_num'] == $m) {
                                $month_count = $month_data['count'];
                                break;
                            }
                        }
                        ?>
                        <a href="?<?= buildQueryString(['month' => $m, 'page' => 1]) ?>" 
                           class="filter-option <?= $month == $m ? 'active' : '' ?>">
                            <?= substr($month_name, 0, 3) ?>
                            <span class="filter-count"><?= $month_count ?></span>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
            
            <!-- Year Filter -->
            <div class="filter-section">
                <h4><i class="fas fa-calendar-year"></i> Year</h4>
                <div class="filter-options">
                    <a href="?<?= buildQueryString(['year' => date('Y'), 'page' => 1]) ?>" 
                       class="filter-option <?= empty($year) || $year == date('Y') ? 'active' : '' ?>">
                        Current Year (<?= date('Y') ?>)
                    </a>
                    <?php foreach ($years as $y): ?>
                        <a href="?<?= buildQueryString(['year' => $y['year'], 'page' => 1]) ?>" 
                           class="filter-option <?= $year == $y['year'] ? 'active' : '' ?>">
                            <?= $y['year'] ?>
                            <span class="filter-count"><?= $y['count'] ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Show Past Events -->
            <div class="filter-section">
                <h4><i class="fas fa-history"></i> Include Past Events</h4>
                <div class="filter-options">
                    <a href="?<?= buildQueryString(['past' => 'no', 'page' => 1]) ?>" class="filter-option active">
                        <i class="fas fa-toggle-off"></i> Hide Past Events
                    </a>
                    <a href="?past=yes" class="filter-option">
                        <i class="fas fa-toggle-on"></i> Show Past Events
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Results Header -->
            <div class="results-header">
                <div class="results-info">
                    <h2>
                        <?php if ($search): ?>
                            Search Results for "<?= htmlspecialchars($search) ?>"
                        <?php elseif ($category && $category !== 'all'): ?>
                            <?= ucfirst($category) ?> Events
                        <?php else: ?>
                            All Events
                        <?php endif; ?>
                    </h2>
                    <p class="results-count">
                        <?= $total_events ?> event<?= $total_events != 1 ? 's' : '' ?> found
                        <?php if ($month): ?>
                            in <?= date('F', mktime(0, 0, 0, $month, 1)) ?>
                        <?php endif; ?>
                    </p>
                </div>
                
                <div class="results-controls">
                    <div class="sort-control">
                        <label for="sortSelect"><i class="fas fa-sort"></i> Sort by:</label>
                        <select id="sortSelect" onchange="sortEvents(this.value)">
                            <option value="event_date_asc" <?= $sort == 'event_date_asc' ? 'selected' : '' ?>>Date (Oldest First)</option>
                            <option value="event_date_desc" <?= $sort == 'event_date_desc' ? 'selected' : '' ?>>Date (Newest First)</option>
                            <option value="title_asc" <?= $sort == 'title_asc' ? 'selected' : '' ?>>Title (A-Z)</option>
                            <option value="title_desc" <?= $sort == 'title_desc' ? 'selected' : '' ?>>Title (Z-A)</option>
                            <option value="category_asc" <?= $sort == 'category_asc' ? 'selected' : '' ?>>Category</option>
                        </select>
                    </div>
                    
                    <div class="view-controls">
                        <button class="view-btn active" data-view="list" title="List View">
                            <i class="fas fa-list"></i>
                        </button>
                        <button class="view-btn" data-view="grid" title="Grid View">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button class="view-btn" data-view="calendar" title="Calendar View">
                            <i class="fas fa-calendar"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Events List -->
            <div class="events-container" id="eventsContainer">
                <?php if (!empty($events)): ?>
                    <div class="events-list" id="eventsList">
                        <?php foreach ($events as $event): ?>
                            <div class="event-list-item">
                                <div class="event-date">
                                    <div class="date-day"><?= $event['day'] ?></div>
                                    <div class="date-month"><?= date('M', strtotime($event['event_date'])) ?></div>
                                    <div class="date-year"><?= $event['year_num'] ?></div>
                                </div>
                                
                                <div class="event-content">
                                    <div class="event-header">
                                        <h3>
                                            <a href="event-details.php?id=<?= $event['event_id'] ?>">
                                                <?= htmlspecialchars($event['title']) ?>
                                            </a>
                                        </h3>
                                        <div class="event-tags">
                                            <span class="event-category <?= $event['category'] ?>">
                                                <?= getCategoryIcon($event['category']) ?> <?= ucfirst($event['category']) ?>
                                            </span>
                                            <span class="event-type" style="color: <?= getEventColor($event['event_type']) ?>">
                                                ● <?= ucfirst(str_replace('_', ' ', $event['event_type'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                    
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
                                    
                                    <p class="event-description">
                                        <?= htmlspecialchars(substr($event['description'], 0, 200)) ?>
                                        <?php if (strlen($event['description']) > 200): ?>...<?php endif; ?>
                                    </p>
                                    
                                    <div class="event-actions">
                                        <a href="event-details.php?id=<?= $event['event_id'] ?>" class="btn btn-primary">
                                            <i class="fas fa-info-circle"></i> View Details
                                        </a>
                                        <button class="btn btn-outline add-to-calendar-btn" 
                                                data-event='<?= htmlspecialchars(json_encode($event), ENT_QUOTES, 'UTF-8') ?>'>
                                            <i class="fas fa-calendar-plus"></i> Add to Calendar
                                        </button>
                                        <a href="#" class="btn-link share-btn" data-event-id="<?= $event['event_id'] ?>">
                                            <i class="fas fa-share-alt"></i> Share
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?<?= buildQueryString(['page' => 1]) ?>" class="page-link first">
                                    <i class="fas fa-angle-double-left"></i> First
                                </a>
                                <a href="?<?= buildQueryString(['page' => $page - 1]) ?>" class="page-link prev">
                                    <i class="fas fa-angle-left"></i> Previous
                                </a>
                            <?php endif; ?>
                            
                            <div class="page-numbers">
                                <?php
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);
                                
                                if ($start_page > 1) {
                                    echo '<span class="page-dots">...</span>';
                                }
                                
                                for ($i = $start_page; $i <= $end_page; $i++):
                                    ?>
                                    <a href="?<?= buildQueryString(['page' => $i]) ?>" 
                                       class="page-number <?= $i == $page ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                if ($end_page < $total_pages) {
                                    echo '<span class="page-dots">...</span>';
                                }
                                ?>
                            </div>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?<?= buildQueryString(['page' => $page + 1]) ?>" class="page-link next">
                                    Next <i class="fas fa-angle-right"></i>
                                </a>
                                <a href="?<?= buildQueryString(['page' => $total_pages]) ?>" class="page-link last">
                                    Last <i class="fas fa-angle-double-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="no-events-found">
                        <div class="no-events-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h3>No Events Found</h3>
                        <p>There are no events matching your criteria. Try adjusting your filters or check back later.</p>
                        <div class="suggestions">
                            <p>Suggestions:</p>
                            <ul>
                                <li>Clear your filters</li>
                                <li>Try a different search term</li>
                                <li>Check all categories</li>
                                <li>Browse past events</li>
                            </ul>
                        </div>
                        <a href="all-events.php" class="btn btn-primary">
                            <i class="fas fa-redo"></i> Clear Filters
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Quick Stats -->
            <div class="quick-stats-box">
                <h3><i class="fas fa-chart-pie"></i> Event Statistics</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value"><?= $total_events ?></div>
                        <div class="stat-label">Total Events</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= count($categories) ?></div>
                        <div class="stat-label">Categories</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= count($months) ?></div>
                        <div class="stat-label">Months with Events</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= count($years) ?></div>
                        <div class="stat-label">Years Covered</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Export Modal -->
    <div id="exportModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2><i class="fas fa-download"></i> Export Events</h2>
            <div class="modal-body">
                <div class="export-options">
                    <div class="export-option">
                        <div class="export-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <h4>Export as PDF</h4>
                        <p>Generate a printable PDF of selected events</p>
                        <button class="btn btn-primary export-btn" data-format="pdf">
                            <i class="fas fa-download"></i> Download PDF
                        </button>
                    </div>
                    
                    <div class="export-option">
                        <div class="export-icon">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <h4>Export as Excel</h4>
                        <p>Download events data in Excel format</p>
                        <button class="btn btn-success export-btn" data-format="excel">
                            <i class="fas fa-download"></i> Download Excel
                        </button>
                    </div>
                    
                    <div class="export-option">
                        <div class="export-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4>Export as Calendar</h4>
                        <p>Add events to your digital calendar</p>
                        <button class="btn btn-secondary export-btn" data-format="ics">
                            <i class="fas fa-calendar-plus"></i> Download ICS
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Share Modal -->
    <div id="shareModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2><i class="fas fa-share-alt"></i> Share Event</h2>
            <div class="modal-body">
                <div class="share-options">
                    <a href="#" class="share-option" data-platform="facebook">
                        <i class="fab fa-facebook"></i>
                        <span>Facebook</span>
                    </a>
                    <a href="#" class="share-option" data-platform="twitter">
                        <i class="fab fa-twitter"></i>
                        <span>Twitter</span>
                    </a>
                    <a href="#" class="share-option" data-platform="whatsapp">
                        <i class="fab fa-whatsapp"></i>
                        <span>WhatsApp</span>
                    </a>
                    <a href="#" class="share-option" data-platform="email">
                        <i class="fas fa-envelope"></i>
                        <span>Email</span>
                    </a>
                    <a href="#" class="share-option" data-platform="link">
                        <i class="fas fa-link"></i>
                        <span>Copy Link</span>
                    </a>
                </div>
                
                <div class="share-link">
                    <input type="text" id="shareUrl" readonly value="">
                    <button class="btn btn-outline" onclick="copyShareUrl()">
                        <i class="fas fa-copy"></i> Copy
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
    <script src="js/all-events.js"></script>
</body>
</html>

<?php
// Helper function to build query string
function buildQueryString($updates = []) {
    $params = $_GET;
    foreach ($updates as $key => $value) {
        if ($value === '') {
            unset($params[$key]);
        } else {
            $params[$key] = $value;
        }
    }
    return http_build_query($params);
}
?>