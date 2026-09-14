<?php

/**
 * fetch_blocks.php
 * Returns JSON for Select2 AJAX adapter.
 * Located in /ajax/ folder.
 */

include '../conn.php';

error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

$district_id = isset($_POST['district_id']) ? intval($_POST['district_id']) : 0;

if ($district_id <= 0) {
    echo json_encode(['results' => []]);
    exit;
}

if (!$conn) {
    echo json_encode(['results' => []]);
    exit;
}

$query = "SELECT id, name FROM blocks WHERE district_id = ? ORDER BY name ASC";
$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    echo json_encode(['results' => []]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $district_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$results = [];
while ($row = mysqli_fetch_assoc($result)) {
    $results[] = [
        'id'   => (int)$row['id'],
        'text' => $row['name']
    ];
}

echo json_encode(['results' => $results]);
mysqli_stmt_close($stmt);
exit;
