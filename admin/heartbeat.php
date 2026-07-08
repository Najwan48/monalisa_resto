<?php
session_name('monalisa_admin');
session_start();
$_SESSION['last_activity'] = time();
header('Content-Type: application/json');
echo json_encode(['ok' => true]);
