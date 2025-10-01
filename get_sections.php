<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    require 'config.php';

    if (!isset($_GET['id_centre']) || !filter_var($_GET['id_centre'], FILTER_VALIDATE_INT)) {
        echo json_encode(['error' => 'Invalid or missing centre ID.']);
        exit;
    }

    $id_centre = $_GET['id_centre'];

    $stmt = $pdo->prepare("SELECT id, designation FROM section WHERE id_centre = ? ORDER BY designation");
    $stmt->execute([$id_centre]);
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($sections);

} catch (PDOException $e) {
    // Log the detailed error to a file for server-side debugging
    $log_message = "[" . date('Y-m-d H:i:s') . "] " . "Database Error in get_sections.php: " . $e->getMessage() . "\n";
    error_log($log_message, 3, __DIR__ . '/error.log');

    // Send a generic but informative error back to the client
    http_response_code(500);
    echo json_encode(['error' => 'A database error occurred. Please check server logs.']);

} catch (Exception $e) {
    // Catch any other unexpected errors
    $log_message = "[" . date('Y-m-d H:i:s') . "] " . "General Error in get_sections.php: " . $e->getMessage() . "\n";
    error_log($log_message, 3, __DIR__ . '/error.log');

    http_response_code(500);
    echo json_encode(['error' => 'An unexpected error occurred.']);
}
?>
