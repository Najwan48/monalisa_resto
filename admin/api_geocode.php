<?php
header('Content-Type: application/json');
header('Cache-Control: no-store');

$q = $_GET['q'] ?? '';
if ($q === '') {
    echo json_encode(['lat' => null, 'lng' => null, 'name' => null]);
    exit;
}

$lat = null;
$lng = null;
$place_name = null;

if (filter_var($q, FILTER_VALIDATE_URL)) {
    $ch = curl_init($q);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS     => 5,
        CURLOPT_TIMEOUT       => 8,
        CURLOPT_USERAGENT     => 'Mozilla/5.0 (Linux; Android 10) AppleWebKit/537.36 Chrome/120.0.0.0 Mobile Safari/537.36',
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    curl_exec($ch);
    $final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err || !$final_url) $final_url = $q;

    if (preg_match('/\/place\/([^\/\?]+)/', $final_url, $pm)) {
        $place_name = urldecode(str_replace('+', ' ', $pm[1]));
    }

    if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $final_url, $m)) {
        $lat = $m[1];
        $lng = $m[2];
    } elseif (preg_match('/!3d(-?\d+\.?\d*)!4d(-?\d+\.?\d*)/', $final_url, $m)) {
        $lat = $m[1];
        $lng = $m[2];
    }
}

if (!$lat) {
    $geo_url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' . urlencode($q);
    $geo_opts = ['http' => ['header' => "User-Agent: MonalisaResto/1.0\r\n", 'timeout' => 5]];
    $geo_raw = @file_get_contents($geo_url, false, stream_context_create($geo_opts));
    if ($geo_raw) {
        $geo_data = json_decode($geo_raw, true);
        if (!empty($geo_data[0]['lat']) && !empty($geo_data[0]['lon'])) {
            $lat = $geo_data[0]['lat'];
            $lng = $geo_data[0]['lon'];
        }
    }
}

$address = null;
if ($lat && $lng) {
    $rev_url = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' . $lat . '&lon=' . $lng . '&zoom=18&addressdetails=1';
    $rev_opts = ['http' => ['header' => "User-Agent: MonalisaResto/1.0\r\n", 'timeout' => 5]];
    $rev_raw = @file_get_contents($rev_url, false, stream_context_create($rev_opts));
    if ($rev_raw) {
        $rev_data = json_decode($rev_raw, true);
        if (!empty($rev_data['display_name'])) {
            $address = $rev_data['display_name'];
        }
    }
}

echo json_encode(['lat' => $lat, 'lng' => $lng, 'name' => $place_name, 'address' => $address]);
