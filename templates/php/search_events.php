<?php
session_start();
require_once 'db_connection.php';

header('Content-Type: application/json');

function sendJsonResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

if (!isset($_GET['query'])) {
    sendJsonResponse(false, 'Search query is required');
}

$searchQuery = '%' . $_GET['query'] . '%';

try {
    $query = "SELECT e.*, c.name as category_name 
              FROM events e 
              JOIN categories c ON e.category_id = c.category_id 
              WHERE e.title LIKE ? AND e.status = 'approved'
              ORDER BY e.event_date ASC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$searchQuery]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the events data
    $formattedEvents = array_map(function($event) {
        return [
            'event_id' => $event['event_id'],
            'title' => $event['title'],
            'description' => $event['description'],
            'category' => $event['category_name'],
            'event_date' => $event['event_date'],
            'venue' => $event['venue'],
            'ticket_price' => $event['ticket_price'],
            'available_tickets' => $event['available_tickets'],
            'image_url' => $event['image_url'],
            'google_maps_link' => $event['google_maps_link']
        ];
    }, $events);
    
    sendJsonResponse(true, 'Events found', $formattedEvents);
    
} catch (Exception $e) {
    error_log("Error in search_events.php: " . $e->getMessage());
    sendJsonResponse(false, 'Error searching events');
}
?> 