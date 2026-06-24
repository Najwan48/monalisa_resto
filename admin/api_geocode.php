<?php
require_once 'includes/db.php';
header('Content-Type: application/json');
header('Cache-Control: no-store');

$q = $_GET['q'] ?? '';
if ($q === '') {
    echo json_encode(['lat' => null, 'lng' => null]);
    exit;
}

if (filter_var($q, FILTER_VALIDATE_URL)) {
    $opts = ['http' => [
        'header' => "User-Agent: MonalisaResto/1.0\r\n",
        'timeout' => 5,
        'follow_location' => false
    ]];
    $headers = @get_headers($q, true, stream_context_create($opts));
    $redirect_url = is_array($headers['Location']) ? end($headers['Location']) : ($headers['Location'] ?? '');
    $place_name = null;
    if (preg_match('/\/place\/([^\/]+)/', $redirect_url, $pm)) {
        $place_name = urldecode(str_replace('+', ' ', $pm[1]));
    }
    if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $redirect_url, $m)) {
        echo json_encode(['lat' => $m[1], 'lng' => $m[2], 'name' => $place_name]);
        exit;
    }
    if (preg_match('/\/place\/([^\/]+)/', $q, $pm)) {
        $place_name = urldecode(str_replace('+', ' ', $pm[1]));
    }
    if ($place_name) {
        $geo_url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' . urlencode($place_name);
        $geo_opts = ['http' => ['header' => "User-Agent: MonalisaResto/1.0\r\n", 'timeout' => 5]];
        $geo_raw = @file_get_contents($geo_url, false, stream_context_create($geo_opts));
        if ($geo_raw) {
            $geo_data = json_decode($geo_raw, true);
            if (!empty($geo_data[0]['lat']) && !empty($geo_data[0]['lon'])) {
                echo json_encode(['lat' => $geo_data[0]['lat'], 'lng' => $geo_data[0]['lon'], 'name' => $place_name]);
                exit;
            }
        }
        echo json_encode(['lat' => null, 'lng' => null, 'name' => $place_name]);
        exit;
    }
}

$geo_url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' . urlencode($q);
$geo_opts = ['http' => ['header' => "User-Agent: MonalisaResto/1.0\r\n", 'timeout' => 5]];
$geo_raw = @file_get_contents($geo_url, false, stream_context_create($geo_opts));
if ($geo_raw) {
    $geo_data = json_decode($geo_raw, true);
    if (!empty($geo_data[0]['lat']) && !empty($geo_data[0]['lon'])) {
        echo json_encode(['lat' => $geo_data[0]['lat'], 'lng' => $geo_data[0]['lon']]);
        exit;
    }
}

echo json_encode(['lat' => null, 'lng' => null]);
