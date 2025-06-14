<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once 'db.php';

try {
    // Get events with registration count
    $stmt = $pdo->query("
        SELECT 
            e.*,
            COUNT(er.id) as registered_count
        FROM events e
        LEFT JOIN event_registrations er ON e.id = er.event_id
        GROUP BY e.id
        ORDER BY e.date ASC, e.time ASC
    ");
    
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format dates and times
    foreach ($events as &$event) {
        $event['date'] = date('Y-m-d', strtotime($event['date']));
        $event['time'] = date('H:i', strtotime($event['time']));
        $event['available_spots'] = $event['capacity'] - $event['registered_count'];
    }
    
    echo json_encode([
        'success' => true,
        'events' => $events
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} 