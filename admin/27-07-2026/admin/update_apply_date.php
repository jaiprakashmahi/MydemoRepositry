<?php

/**
 * update_apply_date.php
 * Handles AJAX requests to update the apply date for a student
 * Created: 2026-07-27
 * Developer: Senior PHP Developer
 */

// Include database connection
include 'conn.php';

// Set header for JSON response
header('Content-Type: application/json');

// Initialize response array
$response = [
    'success' => false,
    'message' => 'Invalid request.'
];

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode($response);
    exit;
}

// Validate required fields
if (!isset($_POST['student_id']) || !isset($_POST['apply_date']) || empty($_POST['student_id']) || empty($_POST['apply_date'])) {
    $response['message'] = 'Missing required parameters.';
    echo json_encode($response);
    exit;
}

// Sanitize input
$studentId = intval($_POST['student_id']);
$applyDate = trim($_POST['apply_date']);

// Validate date format (YYYY-MM-DDTHH:MM)
if (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $applyDate)) {
    $response['message'] = 'Invalid date format.';
    echo json_encode($response);
    exit;
}

// Convert to datetime format for database (Y-m-d H:i:s)
$dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $applyDate);
if (!$dateTime) {
    $response['message'] = 'Invalid date/time.';
    echo json_encode($response);
    exit;
}
$formattedDate = $dateTime->format('Y-m-d H:i:s');

// Begin transaction
$conn->begin_transaction();

try {
    // First, check if student exists
    $checkQuery = "SELECT id FROM onlinestudents WHERE id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('i', $studentId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        throw new Exception('Student not found.');
    }
    $checkStmt->close();

    // Update the apply date
    $updateQuery = "UPDATE onlinestudents SET created_at = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('si', $formattedDate, $studentId);

    if (!$updateStmt->execute()) {
        throw new Exception('Database error: ' . $updateStmt->error);
    }

    // Check if any rows were affected
    if ($updateStmt->affected_rows === 0) {
        throw new Exception('No changes were made. The date may already be up to date.');
    }

    $updateStmt->close();

    // Commit transaction
    $conn->commit();

    $response['success'] = true;
    $response['message'] = 'Apply date updated successfully!';
    $response['updated_date'] = $formattedDate;
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $response['success'] = false;
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Close connection
$conn->close();

// Return JSON response
echo json_encode($response);
exit;
