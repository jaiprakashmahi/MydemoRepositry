<?php

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
if (
    !isset($_POST['student_id']) || !isset($_POST['student_code']) ||
    empty($_POST['student_id']) || empty($_POST['student_code'])
) {
    $response['message'] = 'Missing required parameters.';
    echo json_encode($response);
    exit;
}

// Sanitize input
$studentId = intval($_POST['student_id']);
$studentCode = trim($_POST['student_code']);

// Validate student code length
if (strlen($studentCode) > 50) {
    $response['message'] = 'Student code must be less than 50 characters.';
    echo json_encode($response);
    exit;
}

// Additional validation: Check for allowed characters (alphanumeric, hyphens, underscores, dots)
if (!preg_match('/^[a-zA-Z0-9\-_\.\s]+$/', $studentCode)) {
    $response['message'] = 'Student code contains invalid characters.';
    echo json_encode($response);
    exit;
}

// Begin transaction
$conn->begin_transaction();

try {
    // First, check if student exists
    $checkQuery = "SELECT id, student_code FROM onlinestudents WHERE id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('i', $studentId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        throw new Exception('Student not found.');
    }

    $studentData = $checkResult->fetch_assoc();
    $currentCode = $studentData['student_code'];
    $checkStmt->close();

    // Check if the new code is the same as the current code
    if ($currentCode === $studentCode) {
        $response['message'] = 'No changes made. Student code is already up to date.';
        echo json_encode($response);
        exit;
    }

    // Check if student code already exists (to prevent duplicates)
    $duplicateQuery = "SELECT id FROM onlinestudents WHERE student_code = ? AND id != ?";
    $duplicateStmt = $conn->prepare($duplicateQuery);
    $duplicateStmt->bind_param('si', $studentCode, $studentId);
    $duplicateStmt->execute();
    $duplicateResult = $duplicateStmt->get_result();

    if ($duplicateResult->num_rows > 0) {
        throw new Exception('Student code already exists. Please use a unique code.');
    }
    $duplicateStmt->close();

    // Update the student code
    $updateQuery = "UPDATE onlinestudents SET student_code = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('si', $studentCode, $studentId);

    if (!$updateStmt->execute()) {
        throw new Exception('Database error: ' . $updateStmt->error);
    }

    // Check if any rows were affected
    if ($updateStmt->affected_rows === 0) {
        throw new Exception('No changes were made to the student code.');
    }

    $updateStmt->close();

    // Commit transaction
    $conn->commit();

    $response['success'] = true;
    $response['message'] = 'Student code updated successfully!';
    $response['updated_code'] = $studentCode;
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
