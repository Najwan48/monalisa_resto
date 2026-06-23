<?php
require_once 'includes/db.php';

try {
    if ($pdo) {
        echo "Database connection successful!\n";
        // Simple query test
        $stmt = $pdo->query('SELECT 1');
        if ($stmt) {
            echo "Query test successful!\n";
        }
    } else {
        echo "PDO object is null.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
