<?php

/**
 * fetch_blocks.php
 * Returns <option> HTML for blocks of a given district.
 * Located in /ajax/ folder.
 */

include '../conn.php';

error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: text/html; charset=utf-8');

$district_id = isset($_POST['district_id']) ? intval($_POST['district_id']) : 0;

// Debug log
$log = date('Y-m-d H:i:s') . " | district_id = " . $district_id . "\n";
file_put_contents(__DIR__ . '/blocks_debug.log', $log, FILE_APPEND);

if ($district_id <= 0) {
    echo '<option value="">Select Block</option>';
    exit;
}

if (!$conn) {
    file_put_contents(__DIR__ . '/blocks_debug.log', "DB connection failed\n", FILE_APPEND);
    echo '<option value="">DB connection error</option>';
    exit;
}

$query = "SELECT id, name FROM blocks WHERE district_id = ? ORDER BY name ASC";
$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    file_put_contents(
        __DIR__ . '/blocks_debug.log',
        "Prepare failed: " . mysqli_error($conn) . "\n",
        FILE_APPEND
    );
    echo '<option value="">DB prepare error</option>';
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $district_id);

if (!mysqli_stmt_execute($stmt)) {
    file_put_contents(
        __DIR__ . '/blocks_debug.log',
        "Execute failed: " . mysqli_stmt_error($stmt) . "\n",
        FILE_APPEND
    );
    echo '<option value="">Query execute error</option>';
    mysqli_stmt_close($stmt);
    exit;
}

$result = mysqli_stmt_get_result($stmt);
$count = $result ? mysqli_num_rows($result) : 0;

file_put_contents(
    __DIR__ . '/blocks_debug.log',
    "Rows found: " . $count . "\n",
    FILE_APPEND
);

if ($count > 0) {
    echo '<option value="">Select Block</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . (int)$row['id'] . '">'
            . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8')
            . '</option>';
    }
} else {
    echo '<option value="">No blocks found for this district</option>';
}

mysqli_stmt_close($stmt);
