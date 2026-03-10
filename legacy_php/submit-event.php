<?php
// submit-event.php
header('Content-Type: application/json');

require_once 'includes/database.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = ['success' => false, 'message' => ''];

try {
    // Check if request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Get form data
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;
    $category = $_POST['category'] ?? 'other';
    $event_type = $_POST['event_type'] ?? 'school_event';
    $location = trim($_POST['location'] ?? '');
    $organizer_name = trim($_POST['organizer_name'] ?? '');
    $organizer_email = trim($_POST['organizer_email'] ?? '');
    $organizer_phone = trim($_POST['organizer_phone'] ?? '');
    
    // Validate required fields
    $required = ['title', 'description', 'event_date', 'category', 'event_type', 'organizer_name', 'organizer_email'];
    foreach ($required as $field) {
        if (empty($$field)) {
            throw new Exception("Please fill in all required fields. Missing: $field");
        }
    }
    
    // Validate email
    if (!filter_var($organizer_email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Please enter a valid email address');
    }
    
    // Validate date
    $dateObj = DateTime::createFromFormat('Y-m-d', $event_date);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $event_date) {
        throw new Exception('Invalid date format');
    }
    
    // Prepare data for insertion
    $eventData = [
        'title' => $title,
        'description' => $description,
        'event_date' => $event_date,
        'category' => $category,
        'event_type' => $event_type,
        'location' => $location,
        'organizer_name' => $organizer_name,
        'organizer_email' => $organizer_email,
        'is_approved' => false, // Needs approval
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Add times if provided
    if ($start_time) {
        $eventData['start_time'] = $start_time . ':00'; // Add seconds
    }
    if ($end_time) {
        $eventData['end_time'] = $end_time . ':00'; // Add seconds
    }
    if ($organizer_phone) {
        $eventData['organizer_phone'] = $organizer_phone;
    }
    
    // Insert into database
    $eventId = Database::insert('calendar_events', $eventData);
    
    if ($eventId) {
        // Send notification email (in production)
        $notificationSent = sendNotificationEmail($eventData);
        
        $response['success'] = true;
        $response['message'] = 'Event submitted successfully! It will be reviewed by our team.';
        $response['event_id'] = $eventId;
        $response['requires_approval'] = true;
    } else {
        throw new Exception('Failed to save event to database');
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log("Event Submission Error: " . $e->getMessage());
}

echo json_encode($response);

/**
 * Send notification email
 */
function sendNotificationEmail($eventData) {
    // In production, implement actual email sending
    // For now, just log it
    $message = "New Event Submission:\n\n" . print_r($eventData, true);
    error_log("New event submission: " . $message);
    
    // Example using mail() function:
    /*
    $to = "admin@school.edu";
    $subject = "New Event Submission: " . $eventData['title'];
    $headers = "From: events@school.edu\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
    */
    
    return true;
}
?>