<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Management System</title>

    <link rel="shortcut icon" type="image/png" href="images/favicon.png">
    <link href="vendor/wow-master/css/libs/animate.css" rel="stylesheet">
    <link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/admin-standard.css" rel="stylesheet">

    <style>
        .drop-zone {
            border: 2px dashed #007bff;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background-color: #f8f9fa;
            min-height: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .drop-zone:hover {
            background-color: #e9ecef;
            border-color: #0056b3;
        }

        .drop-zone input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .preview-image {
            max-width: 120px;
            max-height: 120px;
            margin: 10px auto;
            display: block;
            border-radius: 5px;
            object-fit: cover;
        }

        .required:after {
            content: " *";
            color: red;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            margin: 0 5px;
            border-radius: 5px;
            cursor: pointer;
            min-width: 120px;
        }

        .step.active {
            background: #007bff;
            color: white;
        }

        .tab-pane {
            padding: 15px 0;
        }

        .modal-xl {
            max-width: 1200px;
        }

        .member-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-buttons .btn {
            padding: 5px 10px;
            font-size: 12px;
        }

        .card-header {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table th {
            background-color: #667eea;
            color: white;
            border: none;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .select2-container {
            width: 100% !important;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        /* Zonal Manager chip styling */
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
            background-color: #667eea;
            border-color: #667eea;
            color: #fff;
            padding: 2px 8px;
            border-radius: 4px;
            margin: 2px;
            font-size: 12px;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
            margin-right: 5px;
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            padding-right: 5px;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #ffcccc;
            background: transparent;
        }
    </style>
</head>

<body>
    <div name="main-wrapper">
        <?php include 'menu.php'; ?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0 text-white"><i class="fas fa-users me-2"></i>Member Management System</h4>
                                    <button type="button" class="btn btn-light" id="createMemberBtn">
                                        <i class="fas fa-plus-circle me-1"></i> Create New Member
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 text-white">All Members</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered" id="membersTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Photo</th>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Member Type</th>
                                                <th>Working Area</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT mi.*, mt.type_name, mwa.state_id, mwa.district_ids, mwa.block_ids
                                                     FROM member_information mi
                                                     LEFT JOIN member_types mt ON mi.member_type_id = mt.id
                                                     LEFT JOIN member_working_area mwa ON mi.id = mwa.member_id
                                                     ORDER BY mi.created_at DESC";
                                            $result = mysqli_query($conn, $query);

                                            if (mysqli_num_rows($result) > 0) {
                                                $counter = 1;
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $working_area = '';

                                                    if ($row['state_id']) {
                                                        $state_query = "SELECT name FROM states WHERE id = {$row['state_id']}";
                                                        $state_result = mysqli_query($conn, $state_query);
                                                        if ($state_result && mysqli_num_rows($state_result) > 0) {
                                                            $state = mysqli_fetch_assoc($state_result);
                                                            $working_area .= '<strong>State:</strong> ' . $state['name'];
                                                        }
                                                    }

                                                    if ($row['district_ids']) {
                                                        $district_ids = explode(',', $row['district_ids']);
                                                        $district_names = [];
                                                        foreach ($district_ids as $district_id) {
                                                            if (!empty($district_id)) {
                                                                $district_query = "SELECT name FROM districts WHERE id = " . intval($district_id);
                                                                $district_result = mysqli_query($conn, $district_query);
                                                                if ($district_result && mysqli_num_rows($district_result) > 0) {
                                                                    $district = mysqli_fetch_assoc($district_result);
                                                                    $district_names[] = $district['name'];
                                                                }
                                                            }
                                                        }
                                                        if (!empty($district_names)) {
                                                            $working_area .= '<br><strong>Districts:</strong> ' . implode(', ', $district_names);
                                                        }
                                                    }

                                                    if ($row['block_ids']) {
                                                        $block_ids = explode(',', $row['block_ids']);
                                                        $block_names = [];
                                                        foreach ($block_ids as $block_id) {
                                                            if (!empty($block_id)) {
                                                                $block_query = "SELECT name FROM blocks WHERE id = " . intval($block_id);
                                                                $block_result = mysqli_query($conn, $block_query);
                                                                if ($block_result && mysqli_num_rows($block_result) > 0) {
                                                                    $block = mysqli_fetch_assoc($block_result);
                                                                    $block_names[] = $block['name'];
                                                                }
                                                            }
                                                        }
                                                        if (!empty($block_names)) {
                                                            $working_area .= '<br><strong>Blocks:</strong> ' . implode(', ', $block_names);
                                                        }
                                                    }

                                                    $status_class = $row['member_status'] == 'Active' ? 'bg-success' : 'bg-danger';
                                                    $status_text = $row['member_status'] == 'Active' ? 'Active' : 'Inactive';
                                                    $photo_path = !empty($row['passport_photo']) ? $row['passport_photo'] : '';

                                                    echo "<tr>";
                                                    echo "<td>{$counter}</td>";
                                                    echo "<td>";
                                                    if (!empty($photo_path) && file_exists('../' . $photo_path)) {
                                                        echo "<img src='../{$photo_path}' class='member-photo' alt='Member Photo' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"flex\";'>";
                                                        echo "<div class='member-photo bg-secondary d-flex align-items-center justify-content-center text-white' style='display:none;'><i class='fas fa-user'></i></div>";
                                                    } else {
                                                        echo "<div class='member-photo bg-secondary d-flex align-items-center justify-content-center text-white'><i class='fas fa-user'></i></div>";
                                                    }
                                                    echo "</td>";
                                                    echo "<td><strong>{$row['applicant_name']}</strong><br><small class='text-muted'>{$row['father_name']}</small></td>";
                                                    echo "<td>{$row['mobile_number']}</td>";
                                                    echo "<td>{$row['email_id']}</td>";
                                                    echo "<td><span class='badge bg-info'>{$row['type_name']}</span></td>";
                                                    echo "<td><small>{$working_area}</small></td>";
                                                    echo "<td><span class='badge {$status_class}'>{$status_text}</span></td>";
                                                    echo "<td>
                                                            <div class='action-buttons'>
                                                                <button class='btn btn-sm btn-info view-member' data-id='{$row['id']}' title='View'><i class='fas fa-eye'></i></button>
                                                                <button class='btn btn-sm btn-warning edit-member' data-id='{$row['id']}' title='Edit'><i class='fas fa-edit'></i></button>
                                                                <button class='btn btn-sm btn-danger delete-member' data-id='{$row['id']}' title='Delete'><i class='fas fa-trash'></i></button>
                                                            </div>
                                                          </td>";
                                                    echo "</tr>";
                                                    $counter++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='9' class='text-center text-muted py-4'>No members found. Create your first member!</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>

    <!-- Create Member Modal -->
    <div class="modal fade" id="createMemberModal" tabindex="-1" aria-labelledby="createMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="memberForm" action="insert_member.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createMemberModalLabel"><i class="fas fa-user-plus me-2"></i>Application Form</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="step-indicator mb-4">
                            <div class="step active" data-step="1">1. Personal Info</div>
                            <div class="step" data-step="2">2. Address</div>
                            <div class="step" data-step="3">3. Education & Bank</div>
                            <div class="step" data-step="4">4. Working Area</div>
                            <div class="step" data-step="5">5. Photo & Payment</div>
                        </div>

                        <div class="tab-content">
                            <!-- Step 1 -->
                            <div class="tab-pane fade show active" id="step1">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Applicant Name</label>
                                        <input type="text" class="form-control" name="applicant_name" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Father's Name</label>
                                        <input type="text" class="form-control" name="father_name" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Mother's Name</label>
                                        <input type="text" class="form-control" name="mother_name">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label required">Mobile Number</label>
                                        <input type="tel" class="form-control" name="mobile_number" id="mobile_number" maxlength="10" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label required">Email ID</label>
                                        <input type="email" class="form-control" name="email_id" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">UID No (Aadhar)</label>
                                        <input type="text" class="form-control" name="uid_no" id="uid_no" maxlength="12">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">PAN No</label>
                                        <input type="text" class="form-control" name="pan_no" id="pan_no" maxlength="10">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label required">Date of Birth</label>
                                        <input type="date" class="form-control" name="date_of_birth" required max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label required">Post Applied</label>
                                        <select class="form-control" name="post_applied" id="post_applied" required>
                                            <option value="">Select Post</option>
                                            <option value="State Nodal">State Nodal</option>
                                            <option value="Zonal Manager">Zonal Manager</option>
                                            <option value="District Coordinator">District Coordinator</option>
                                            <option value="Block Coordinator">Block Coordinator</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Member Type</label>
                                        <select class="form-control" name="member_type_id" id="member_type_id" required>
                                            <option value="">Select Member Type</option>
                                            <?php
                                            $query = "SELECT * FROM member_types ORDER BY level";
                                            $result = mysqli_query($conn, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='{$row['id']}'>{$row['type_name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="tab-pane fade" id="step2">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label required">Full Address</label>
                                        <textarea class="form-control" name="full_address" rows="3" required></textarea>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label required">At/Village</label>
                                        <input type="text" class="form-control" name="at_village" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Via</label>
                                        <input type="text" class="form-control" name="via">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label required">Block/Mandal</label>
                                        <input type="text" class="form-control" name="block" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Police Station</label>
                                        <input type="text" class="form-control" name="police_station">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">District</label>
                                        <input type="text" class="form-control" name="district" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Pincode</label>
                                        <input type="text" class="form-control" name="pincode" id="pincode" maxlength="6" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">State</label>
                                        <select class="form-control" name="state" required>
                                            <option value="">Select State</option>
                                            <?php
                                            $query = "SELECT * FROM states ORDER BY name";
                                            $result = mysqli_query($conn, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='{$row['name']}'>{$row['name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="tab-pane fade" id="step3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Educational Qualification</label>
                                        <select class="form-control" name="educational_qualification" required>
                                            <option value="">Select Qualification</option>
                                            <option value="10th">10th Pass</option>
                                            <option value="12th">12th Pass</option>
                                            <option value="Graduate">Graduate</option>
                                            <option value="Post Graduate">Post Graduate</option>
                                            <option value="Diploma">Diploma</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Extra Qualification</label>
                                        <input type="text" class="form-control" name="extra_qualification">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Experience</label>
                                        <textarea class="form-control" name="experience" rows="3"></textarea>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Bank Name</label>
                                        <input type="text" class="form-control" name="bank_name" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Branch Name</label>
                                        <input type="text" class="form-control" name="branch_name" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">IFSC Code</label>
                                        <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Account Number</label>
                                        <input type="text" class="form-control" name="account_number" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Account Type</label>
                                        <select class="form-control" name="account_type" required>
                                            <option value="">Select Type</option>
                                            <option value="Savings">Savings</option>
                                            <option value="Current">Current</option>
                                            <option value="Salary">Salary</option>
                                            <option value="Fixed Deposit">Fixed Deposit</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: WORKING AREA -->
                            <div class="tab-pane fade" id="step4">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <h6 class="text-primary mb-3"><i class="fas fa-map-marker-alt me-2"></i>Working Area Selection</h6>
                                        <p class="text-muted">Select areas based on post applied</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">State <span class="text-danger">*</span></label>
                                        <select class="form-control" id="working_state">
                                            <option value="">Select State</option>
                                            <?php
                                            $query = "SELECT * FROM states ORDER BY name";
                                            $result = mysqli_query($conn, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">District</label>
                                        <select class="form-control" id="working_district"></select>
                                        <small class="text-muted" id="districtHelp"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Block</label>
                                        <select class="form-control" id="working_block"></select>
                                        <small class="text-muted" id="blockHelp"></small>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <strong><i class="fas fa-info-circle me-2"></i>Selection Rules:</strong><br>
                                            • <strong>State Nodal:</strong> Select 1 State only<br>
                                            • <strong>Zonal Manager:</strong> Select up to 5 Districts<br>
                                            • <strong>District Coordinator:</strong> Select 1 District only<br>
                                            • <strong>Block Coordinator:</strong> Select 1 District & up to 5 Blocks
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="tab-pane fade" id="step5">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Passport Size Photo</label>
                                        <div class="drop-zone" id="dropZone">
                                            <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                            <p class="mb-1">Drag & drop photo here</p>
                                            <p class="text-muted small">or click to browse</p>
                                            <input type="file" id="passport_photo" name="passport_photo" accept="image/*" required>
                                            <div id="photoPreview" class="mt-3"></div>
                                        </div>
                                        <small class="text-muted">Max size: 2MB (JPG, JPEG, PNG, GIF)</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Registration Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" class="form-control" name="reg_amount" value="3500" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Payment Mode</label>
                                        <select class="form-control" name="payment_mode" required>
                                            <option value="">Select Mode</option>
                                            <option value="Online">Online</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Payment Status</label>
                                        <select class="form-control" name="payment_status" required>
                                            <option value="">Select Status</option>
                                            <option value="Paid">Paid</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Partial">Partial</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label required">Member Status</label>
                                        <select class="form-control" name="member_status" required>
                                            <option value="Active">Active</option>
                                            <option value="Inactive" selected>Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Username</label>
                                        <input type="text" class="form-control" name="username" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" required minlength="6" id="passwordField">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(event)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="working_state_id" id="working_state_id">
                        <input type="hidden" name="working_district_ids" id="working_district_ids">
                        <input type="hidden" name="working_block_ids" id="working_block_ids">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-info" id="prevBtn">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                        <button type="submit" class="btn btn-success" id="submitBtn" style="display:none;">Submit Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Member Modal -->
    <div class="modal fade" id="viewMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-user me-2"></i>Member Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="viewMemberContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div class="modal fade" id="editMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="editMemberForm" action="ajax/update_member.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Member</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="editMemberContent"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Update Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* =========================================================
       GLOBAL
       ========================================================= */
        var currentStep = 1;
        var totalSteps = 5;

        /* =========================================================
           DOC READY
           ========================================================= */
        $(document).ready(function() {

            // ============================================
            // MODAL OPEN / CLOSE
            // ============================================
            $(document).on('click', '#createMemberBtn', function() {
                $('#createMemberModal').modal('show');
            });

            $('#createMemberModal').on('shown.bs.modal', function() {
                initWorkingAreaSelects();
                applyPostRules();
                showStep(1);
            });

            $('#createMemberModal').on('hidden.bs.modal', function() {
                // Reset form on close
                $('#memberForm')[0].reset();
                destroyIfSelect2('#working_state');
                destroyIfSelect2('#working_district');
                destroyIfSelect2('#working_block');
            });

            // ============================================
            // POST APPLIED CHANGE
            // ============================================
            $(document).on('change', '#post_applied', function() {
                var post = $(this).val();

                var map = {
                    'State Nodal': '1',
                    'Zonal Manager': '2',
                    'District Coordinator': '3',
                    'Block Coordinator': '4'
                };
                $('#member_type_id').val(map[post] || '').trigger('change');

                // Reset district/block (but keep state)
                resetDistrictSelect();
                resetBlockSelect();

                // Apply new rules
                applyPostRules();

                // If state already selected, reload districts
                var stateId = $('#working_state').val();
                if (stateId) {
                    loadDistricts(stateId);
                }
            });

            // ============================================
            // STATE CHANGE
            // ============================================
            $(document).on('change', '#working_state', function() {
                var stateId = $(this).val();
                resetBlockSelect();

                if (!stateId) {
                    resetDistrictSelect();
                    return;
                }
                loadDistricts(stateId);
            });

            // ============================================
            // DISTRICT CHANGE (for Block Coordinator)
            // ============================================
            $(document).on('change', '#working_district', function() {
                var post = $('#post_applied').val();

                if (post !== 'Block Coordinator') {
                    resetBlockSelect();
                    return;
                }

                var val = $(this).val();
                var ids = !val ? [] : (Array.isArray(val) ? val : [val]);

                if (ids.length === 0) {
                    resetBlockSelect();
                    return;
                }

                if (ids.length > 1) {
                    Swal.fire('Warning', 'Block Coordinator can only select ONE district', 'warning');
                    $(this).val([ids[0]]).trigger('change.select2');
                    return;
                }

                loadBlocks(ids[0]);
            });

            // ============================================
            // STEP NAVIGATION
            // ============================================
            $(document).on('click', '.step', function() {
                var step = parseInt($(this).data('step'));
                if (step < currentStep) {
                    showStep(step);
                } else if (validateStep(currentStep)) {
                    showStep(step);
                }
            });

            $(document).on('click', '#nextBtn', function() {
                if (validateStep(currentStep) && currentStep < totalSteps) {
                    showStep(currentStep + 1);
                }
            });

            $(document).on('click', '#prevBtn', function() {
                if (currentStep > 1) showStep(currentStep - 1);
            });

            // ============================================
            // FORM SUBMIT
            // ============================================
            $(document).on('submit', '#memberForm', function(e) {
                e.preventDefault();

                var stateVal = $('#working_state').val();
                var distVal = $('#working_district').val();
                var blockVal = $('#working_block').val();

                $('#working_state_id').val(stateVal || '');
                $('#working_district_ids').val(!distVal ? '' : (Array.isArray(distVal) ? distVal.join(',') : distVal));
                $('#working_block_ids').val(!blockVal ? '' : (Array.isArray(blockVal) ? blockVal.join(',') : blockVal));

                for (var i = 1; i <= totalSteps; i++) {
                    if (!validateStep(i)) {
                        showStep(i);
                        Swal.fire('Error', 'Please fill all required fields correctly.', 'error');
                        return false;
                    }
                }

                var formData = new FormData(this);

                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false,
                    didOpen: function() {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false
                    })
                    .done(function(response) {
                        Swal.close();
                        try {
                            var res = JSON.parse(response);
                            if (res.status === 'success') {
                                Swal.fire('Success!', res.message, 'success').then(function() {
                                    $('#createMemberModal').modal('hide');
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        } catch (e) {
                            Swal.fire('Error', 'Invalid server response', 'error');
                        }
                    })
                    .fail(function(xhr) {
                        Swal.close();
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'Server error. Check console.', 'error');
                    });
            });

            // ============================================
            // PHOTO UPLOAD
            // ============================================
            $(document).on('change', '#passport_photo', function(e) {
                var preview = document.getElementById('photoPreview');
                if (!e.target.files.length) return;
                var file = e.target.files[0];

                if (!file.type.startsWith('image/')) {
                    Swal.fire('Error', 'Only images allowed', 'error');
                    this.value = '';
                    preview.innerHTML = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire('Error', 'Max size 2MB', 'error');
                    this.value = '';
                    preview.innerHTML = '';
                    return;
                }

                var reader = new FileReader();
                reader.onload = function(ev) {
                    preview.innerHTML = '<img src="' + ev.target.result + '" class="preview-image"><p class="text-success mt-2">' + file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)</p>';
                };
                reader.readAsDataURL(file);
            });
        });

        /* =========================================================
           SELECT2 SAFE DESTROY
           ========================================================= */
        function destroyIfSelect2(selector) {
            var $el = $(selector);
            if ($el.hasClass('select2-hidden-accessible')) {
                try {
                    $el.select2('destroy');
                } catch (e) {}
            }
        }

        /* =========================================================
           INIT WORKING AREA (on modal show)
           ========================================================= */
        function initWorkingAreaSelects() {
            // STATE — always enabled, single select
            destroyIfSelect2('#working_state');
            $('#working_state').val(null).prop('disabled', false).prop('multiple', false);
            $('#working_state').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select State',
                dropdownParent: $('#createMemberModal'),
                allowClear: true
            });

            // DISTRICT — disabled, single select
            destroyIfSelect2('#working_district');
            $('#working_district').empty().val(null).prop('disabled', true).prop('multiple', false);
            $('#working_district').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select District',
                dropdownParent: $('#createMemberModal'),
                allowClear: true
            });

            // BLOCK — disabled, single select
            destroyIfSelect2('#working_block');
            $('#working_block').empty().val(null).prop('disabled', true).prop('multiple', false);
            $('#working_block').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select Block',
                dropdownParent: $('#createMemberModal'),
                allowClear: true
            });
        }

        /* =========================================================
           APPLY POST RULES — state is NEVER touched
           ========================================================= */
        function applyPostRules() {
            var post = $('#post_applied').val();

            // STATE always enabled
            $('#working_state').prop('disabled', false);

            if (post === 'State Nodal') {
                $('#working_district').prop('disabled', true);
                $('#working_block').prop('disabled', true);
                $('#districtHelp').text('State Nodal works at state level only');
                $('#blockHelp').text('Not applicable');
            } else if (post === 'Zonal Manager') {
                $('#working_district').prop('disabled', false);
                $('#working_block').prop('disabled', true);
                $('#districtHelp').text('Select 1 to 5 districts');
                $('#blockHelp').text('Not required');
            } else if (post === 'District Coordinator') {
                $('#working_district').prop('disabled', false);
                $('#working_block').prop('disabled', true);
                $('#districtHelp').text('Select exactly 1 district');
                $('#blockHelp').text('Not required');
            } else if (post === 'Block Coordinator') {
                $('#working_district').prop('disabled', false);
                $('#working_block').prop('disabled', true);
                $('#districtHelp').text('Select exactly 1 district');
                $('#blockHelp').text('Select 1 to 5 blocks from that district');
            } else {
                $('#working_district').prop('disabled', true);
                $('#working_block').prop('disabled', true);
                $('#districtHelp').text('Please select post applied first');
                $('#blockHelp').text('');
            }
        }

        /* =========================================================
           RESET DISTRICT (disable + clear + single-select)
           ========================================================= */
        function resetDistrictSelect() {
            destroyIfSelect2('#working_district');
            $('#working_district')
                .empty()
                .val(null)
                .prop('multiple', false)
                .prop('disabled', true);
            $('#working_district').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select District',
                dropdownParent: $('#createMemberModal'),
                allowClear: true
            });
        }

        /* =========================================================
           RESET BLOCK
           ========================================================= */
        function resetBlockSelect() {
            destroyIfSelect2('#working_block');
            $('#working_block')
                .empty()
                .val(null)
                .prop('multiple', false)
                .prop('disabled', true);
            $('#working_block').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select Block',
                dropdownParent: $('#createMemberModal'),
                allowClear: true
            });
        }

        /* =========================================================
           LOAD DISTRICTS — use Select2's AJAX data adapter
           ========================================================= */
        function loadDistricts(stateId) {
            var post = $('#post_applied').val();
            var allowMultiple = (post === 'Zonal Manager');

            console.log('[LOAD] Districts for state', stateId, '| multiple:', allowMultiple);

            // Fully rebuild district select fresh
            destroyIfSelect2('#working_district');
            $('#working_district')
                .empty()
                .val(null)
                .prop('multiple', allowMultiple)
                .prop('disabled', false);

            $('#working_district').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: allowMultiple ? 'Select District(s)' : 'Select District',
                dropdownParent: $('#createMemberModal'),
                multiple: allowMultiple,
                maximumSelectionLength: allowMultiple ? 5 : 1,
                allowClear: true,
                closeOnSelect: !allowMultiple,
                // === AJAX data adapter ===
                ajax: {
                    url: 'ajax/fetch_districts.php',
                    type: 'POST',
                    dataType: 'json',
                    delay: 0,
                    data: function(params) {
                        return {
                            state_id: stateId
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: false
                },
                minimumResultsForSearch: 0
            });

            // Manually trigger the AJAX load
            setTimeout(function() {
                $('#working_district').select2('open');
                setTimeout(function() {
                    $('#working_district').select2('close');
                }, 100);
            }, 50);
        }

        /* =========================================================
           LOAD BLOCKS — AJAX data adapter
           ========================================================= */
        function loadBlocks(districtId) {
            console.log('[LOAD] Blocks for district', districtId);

            destroyIfSelect2('#working_block');
            $('#working_block')
                .empty()
                .val(null)
                .prop('multiple', true)
                .prop('disabled', false);

            $('#working_block').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select Block(s)',
                dropdownParent: $('#createMemberModal'),
                multiple: true,
                maximumSelectionLength: 5,
                allowClear: true,
                ajax: {
                    url: 'ajax/fetch_blocks.php',
                    type: 'POST',
                    dataType: 'json',
                    delay: 0,
                    data: function(params) {
                        return {
                            district_id: districtId
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: false
                },
                minimumResultsForSearch: 0
            });
        }

        /* =========================================================
           STEP NAV
           ========================================================= */
        function showStep(step) {
            currentStep = step;
            $('.step').removeClass('active');
            $('.step[data-step="' + step + '"]').addClass('active');
            $('.tab-pane').removeClass('show active');
            $('#step' + step).addClass('show active');
            $('#prevBtn').toggle(step > 1);
            $('#nextBtn').toggle(step < totalSteps);
            $('#submitBtn').toggle(step === totalSteps);

            if (step === 4) {
                setTimeout(function() {
                    applyPostRules();
                    // Refresh Select2 containers now that tab is visible
                    if ($('#working_state').hasClass('select2-hidden-accessible')) {
                        $('#working_state').select2('close');
                    }
                    if ($('#working_district').hasClass('select2-hidden-accessible')) {
                        $('#working_district').select2('close');
                    }
                }, 150);
            }
        }

        /* =========================================================
           VALIDATION
           ========================================================= */
        function validateStep(step) {
            var valid = true;
            $('#step' + step + ' .form-control, #step' + step + ' .form-select').removeClass('is-invalid');

            $('#step' + step + ' [required]').each(function() {
                var $el = $(this);
                if ($el.prop('disabled')) return;
                var v = $el.val();
                if (!v || (Array.isArray(v) && v.length === 0)) {
                    $el.addClass('is-invalid');
                    valid = false;
                }
            });

            if (step === 1) {
                var phone = $('input[name="mobile_number"]').val();
                if (phone && !/^[0-9]{10}$/.test(phone)) {
                    Swal.fire('Error', 'Mobile must be 10 digits', 'error');
                    valid = false;
                }
                var uid = $('input[name="uid_no"]').val();
                if (uid && !/^[0-9]{12}$/.test(uid)) {
                    Swal.fire('Error', 'Aadhar must be 12 digits', 'error');
                    valid = false;
                }
                var pan = $('input[name="pan_no"]').val();
                if (pan && !/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(pan)) {
                    Swal.fire('Error', 'PAN must be ABCDE1234F format', 'error');
                    valid = false;
                }
                var email = $('input[name="email_id"]').val();
                if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    Swal.fire('Error', 'Invalid email', 'error');
                    valid = false;
                }
            }
            if (step === 2) {
                var pin = $('input[name="pincode"]').val();
                if (pin && !/^[0-9]{6}$/.test(pin)) {
                    Swal.fire('Error', 'Pincode must be 6 digits', 'error');
                    valid = false;
                }
            }
            if (step === 3) {
                var ifsc = $('input[name="ifsc_code"]').val();
                if (ifsc && !/^[A-Z]{4}0[A-Z0-9]{6}$/.test(ifsc)) {
                    Swal.fire('Error', 'Invalid IFSC', 'error');
                    valid = false;
                }
            }
            if (step === 4) {
                var post = $('#post_applied').val();
                if (!post) {
                    Swal.fire('Error', 'Please select Post Applied on Step 1', 'error');
                    return false;
                }

                var stateVal = $('#working_state').val();
                var distVal = $('#working_district').val();
                var blockVal = $('#working_block').val();

                var distArr = !distVal ? [] : (Array.isArray(distVal) ? distVal : [distVal]);
                var blockArr = !blockVal ? [] : (Array.isArray(blockVal) ? blockVal : [blockVal]);

                var err = '';
                if (!stateVal) {
                    err = 'Please select a state';
                } else if (post === 'Zonal Manager') {
                    if (distArr.length < 1 || distArr.length > 5) err = 'Select 1 to 5 districts';
                } else if (post === 'District Coordinator') {
                    if (distArr.length !== 1) err = 'Select exactly 1 district';
                } else if (post === 'Block Coordinator') {
                    if (distArr.length !== 1) err = 'Select exactly 1 district';
                    else if (blockArr.length < 1 || blockArr.length > 5) err = 'Select 1 to 5 blocks';
                }

                if (err) {
                    Swal.fire('Error', err, 'error');
                    valid = false;
                }
            }
            return valid;
        }

        function togglePassword(event) {
            var field = document.getElementById('passwordField');
            if (!field) return;
            field.type = field.type === 'password' ? 'text' : 'password';
            var icon = event.currentTarget.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        }
    </script>
</body>

</html>