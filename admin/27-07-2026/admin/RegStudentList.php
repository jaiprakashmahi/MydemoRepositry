<?php
include 'conn.php';
$query = "SELECT * FROM onlinestudents ORDER BY id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>All Registered Students - Welcome To Sharnay Institute Dashboard</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="DexignLab">
	<meta name="robots" content="">
	<meta name="keywords" content="">
	<meta name="description" content="">
	<meta property="og:title" content="">
	<meta property="og:description" content="">
	<meta property="og:image" content="social-image.html">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/png" href="images/favicon.png">
	<link rel="stylesheet" href="vendor/chartist/css/chartist.min.css">
	<link href="vendor/wow-master/css/libs/animate.css" rel="stylesheet">
	<link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<link href="vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/admin-standard.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

	<!-- Add Bootstrap CSS for modal -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<style>
		.clickable-date {
			cursor: pointer;
			color: #0066cc;
			text-decoration: underline;
			transition: all 0.3s ease;
		}

		.clickable-date:hover {
			color: #004499;
			text-decoration: none;
			font-weight: bold;
		}

		.clickable-student-code {
			cursor: pointer;
			color: #0066cc;
			text-decoration: underline;
			transition: all 0.3s ease;
			font-weight: 500;
		}

		.clickable-student-code:hover {
			color: #004499;
			text-decoration: none;
			font-weight: bold;
			background: #f0f7ff;
			padding: 2px 8px;
			border-radius: 4px;
		}

		.clickable-student-code i,
		.clickable-date i {
			font-size: 12px;
			opacity: 0.7;
			margin-left: 5px;
		}

		.clickable-student-code:hover i,
		.clickable-date:hover i {
			opacity: 1;
		}

		.modal-content {
			border-radius: 10px;
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
		}

		.modal-header {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			border-radius: 10px 10px 0 0;
		}

		.modal-header .btn-close {
			color: white;
			filter: brightness(0) invert(1);
		}

		.update-date-btn,
		.update-student-code-btn {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			border: none;
			padding: 10px 30px;
			font-weight: 600;
			transition: all 0.3s ease;
		}

		.update-date-btn:hover,
		.update-student-code-btn:hover {
			transform: translateY(-2px);
			box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
		}

		.toast-container {
			position: fixed;
			top: 20px;
			right: 20px;
			z-index: 9999;
		}

		.toast {
			min-width: 300px;
		}

		.code-display-box {
			background: #f8f9fa;
			border: 2px dashed #667eea;
			border-radius: 8px;
			padding: 12px 15px;
			font-family: 'Courier New', monospace;
			font-size: 18px;
			font-weight: bold;
			color: #333;
			letter-spacing: 1px;
		}

		.code-display-box i {
			color: #667eea;
			margin-right: 10px;
		}

		.current-date-display {
			background: #f8f9fa;
			padding: 12px 15px;
			border-radius: 8px;
			border-left: 4px solid #667eea;
			font-size: 16px;
		}

		.current-date-display i {
			color: #667eea;
			margin-right: 10px;
		}
	</style>
</head>

<body>

	<!-- Toast Notification Container -->
	<div class="toast-container">
		<div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header bg-success text-white">
				<strong class="me-auto">Success</strong>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
			</div>
			<div class="toast-body">
				Date updated successfully!
			</div>
		</div>
		<div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header bg-danger text-white">
				<strong class="me-auto">Error</strong>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
			</div>
			<div class="toast-body">
				Failed to update. Please try again.
			</div>
		</div>
	</div>

	<!-- Update Date Modal -->
	<div class="modal fade" id="updateDateModal" tabindex="-1" aria-labelledby="updateDateModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="updateDateModalLabel">
						<i class="fas fa-calendar-alt me-2"></i>Update Apply Date
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="updateDateForm">
						<input type="hidden" id="student_id" name="student_id">

						<div class="mb-4">
							<label class="form-label fw-bold">
								<i class="fas fa-clock text-primary me-1"></i>Current Apply Date
							</label>
							<div class="current-date-display">
								<i class="fas fa-calendar-check"></i>
								<span id="current_date_text" class="fw-bold">Loading...</span>
							</div>
							<small class="text-muted mt-1 d-block">
								<i class="fas fa-info-circle"></i> This is the current date in the database
							</small>
						</div>

						<div class="mb-3">
							<label for="apply_date" class="form-label fw-bold">
								<i class="fas fa-edit text-success me-1"></i>Select New Date
							</label>
							<input type="datetime-local" class="form-control form-control-lg" id="apply_date" name="apply_date" required>
							<small class="form-text text-muted mt-2 d-block">
								<i class="fas fa-info-circle"></i> Choose the new apply date and time
							</small>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
						<i class="fas fa-times me-1"></i>Cancel
					</button>
					<button type="button" class="btn btn-primary update-date-btn" onclick="submitDateUpdate()">
						<i class="fas fa-save me-1"></i>Update Date
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Update Student Code Modal -->
	<div class="modal fade" id="updateStudentCodeModal" tabindex="-1" aria-labelledby="updateStudentCodeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="updateStudentCodeModalLabel">
						<i class="fas fa-id-badge me-2"></i>Update Student Code
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="updateStudentCodeForm">
						<input type="hidden" id="student_id_code" name="student_id">

						<div class="mb-4">
							<label class="form-label fw-bold">
								<i class="fas fa-id-card text-primary me-1"></i>Current Student Code
							</label>
							<div class="code-display-box">
								<i class="fas fa-tag"></i>
								<span id="current_student_code_text" class="fw-bold">Loading...</span>
							</div>
							<small class="text-muted mt-1 d-block">
								<i class="fas fa-info-circle"></i> This is the current student code in the database
							</small>
						</div>

						<div class="mb-3">
							<label for="student_code" class="form-label fw-bold">
								<i class="fas fa-edit text-success me-1"></i>Enter New Student Code
							</label>
							<input type="text" class="form-control form-control-lg" id="student_code" name="student_code"
								placeholder="Enter new student code (e.g., STU-2026-001)" required>
							<small class="form-text text-muted mt-2 d-block">
								<i class="fas fa-info-circle"></i> Enter a unique student code (max 50 characters)
							</small>
						</div>

						<div class="mb-3">
							<label class="form-label fw-bold">
								<i class="fas fa-user text-secondary me-1"></i>Student Name
							</label>
							<p class="form-control-static bg-light p-2 rounded">
								<i class="fas fa-user me-2"></i>
								<span id="student_name_text"></span>
							</p>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
						<i class="fas fa-times me-1"></i>Cancel
					</button>
					<button type="button" class="btn btn-primary update-student-code-btn" onclick="submitStudentCodeUpdate()">
						<i class="fas fa-save me-1"></i>Update Code
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Your existing HTML content -->
	<div id="preloader">
		<div class="loader"></div>
	</div>
	<div id="main-wrapper">
		<?php include 'menu.php'; ?>
		<div class="content-body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xl-12">
						<div class="row">
							<div class="col-xl-12">
								<div class="page-title flex-wrap">
									<div class="input-group search-area mb-md-0 mb-3">
										<input type="text" class="form-control" placeholder="Search here...">
										<span class="input-group-text"><a href="javascript:void(0)">
												<svg width="15" height="15" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M17.5605 15.4395L13.7527 11.6317C14.5395 10.446 15 9.02625 15 7.5C15 3.3645 11.6355 0 7.5 0C3.3645 0 0 3.3645 0 7.5C0 11.6355 3.3645 15 7.5 15C9.02625 15 10.446 14.5395 11.6317 13.7527L15.4395 17.5605C16.0245 18.1462 16.9755 18.1462 17.5605 17.5605C18.1462 16.9747 18.1462 16.0252 17.5605 15.4395V15.4395ZM2.25 7.5C2.25 4.605 4.605 2.25 7.5 2.25C10.395 2.25 12.75 4.605 12.75 7.5C12.75 10.395 10.395 12.75 7.5 12.75C4.605 12.75 2.25 10.395 2.25 7.5V7.5Z" fill="#01A3FF" />
												</svg>
											</a></span>
									</div>
									<div>
										<button type="button" class="btn btn-primary">
											<a href="add-student.php" style="color:white;text-decoration:none;"> + New Student </a>
										</button>
									</div>
								</div>
							</div>
							<div class="col-xl-12 wow fadeInUp" data-wow-delay="1.5s">
								<div class="table-responsive full-data">
									<table class="table-responsive-lg table display dataTablesCard student-tab dataTable no-footer" id="example-student">
										<thead>
											<tr>
												<th><input type="checkbox" class="form-check-input" id="checkAll" required=""></th>
												<th>Student Code</th>
												<th>Name</th>
												<th>Course</th>
												<th>Duration</th>
												<th>Price</th>
												<th>Contact</th>
												<th>Study Center</th>
												<th>Photo</th>
												<th>Id Card</th>
												<th>Apply Date</th>
												<th>Reg. Amount</th>
												<th>Reg. Pay Mode</th>
												<th>Reg. Pay Status</th>
												<th>Pay. Approve</th>
												<th class="text-end">Status</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if ($result->num_rows > 0):
												while ($row = $result->fetch_assoc()):
											?>
													<tr>
														<td>
															<div class="checkbox me-0 align-self-center">
																<div class="custom-control custom-checkbox">
																	<input type="checkbox" class="form-check-input" name="selected[]" value="<?= $row['id']; ?>">
																	<label class="custom-control-label" for="check10"></label>
																</div>
															</div>
														</td>
														<td>
															<span class="clickable-student-code"
																onclick="openStudentCodeModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['student_code']) ?>', '<?= htmlspecialchars($row['name']) ?>')">
																<?= htmlspecialchars($row['student_code']) ?>
																<i class="fas fa-edit"></i>
															</span>
														</td>
														<td><?= htmlspecialchars($row['name']) ?></td>
														<td><?= htmlspecialchars($row['course_name']) ?></td>
														<td><?= htmlspecialchars($row['duration']) ?></td>
														<td><?= htmlspecialchars($row['price']) ?></td>
														<td><?= htmlspecialchars($row['mobile']) ?></td>
														<td><?= htmlspecialchars($row['study_center']) ?></td>
														<td>
															<?php
															$photo = $row['photo'] ?? '';
															if (!empty($photo) && file_exists("../uploads/students/photos/" . $photo)) {
															?>
																<img src="../uploads/students/photos/<?= htmlspecialchars($photo) ?>" alt="Photo" width="80" height="80">
															<?php
															} elseif (!empty($photo) && file_exists("../admin/uploads/students/photos/" . $photo)) {
															?>
																<img src="../admin/uploads/students/photos/<?= htmlspecialchars($photo) ?>" alt="Photo" width="80" height="80">
															<?php
															} else {
																echo "No Photo";
															}
															?>
														</td>
														<td>
															<?php
															$idProof = $row['id_proof'] ?? '';
															if (!empty($idProof) && file_exists("../uploads/students/id_proofs/" . $idProof)) {
																$filePath = "../uploads/students/id_proofs/" . $idProof;
															} elseif (!empty($idProof) && file_exists("../admin/uploads/students/id_proofs/" . $idProof)) {
																$filePath = "../admin/uploads/students/id_proofs/" . $idProof;
															} else {
																$filePath = '';
															}
															if ($filePath) {
																echo '<a href="' . htmlspecialchars($filePath) . '" target="_blank" style="color:black;">View</a>';
															} else {
																echo 'No ID';
															}
															?>
														</td>
														<td>
															<span class="clickable-date" onclick="openDateModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['created_at']) ?>')">
																<?= htmlspecialchars(date('d-m-Y H:i:s', strtotime($row['created_at']))) ?>
																<i class="fas fa-edit ms-1"></i>
															</span>
														</td>
														<td><?= htmlspecialchars($row['reg_amount']) ?></td>
														<td><?= htmlspecialchars($row['payment_mode']) ?></td>
														<td><?= htmlspecialchars($row['payment_status']) ?></td>
														<td>
															<a href="Approve_RegPaymentStatus.php?id=<?= $row['id'] ?>" onclick="return confirm('Approve this payment?')">
																<button style='background:green;color:white;'>Approve</button>
															</a>
														</td>
														<td><?php echo $row['approved'] ? 'Approved' : 'Pending'; ?></td>
													</tr>
												<?php
												endwhile;
											else:
												?>
												<tr>
													<td colspan="16" style="text-align: center;">No students found.</td>
												</tr>
											<?php endif; ?>
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

	<!-- Required vendors -->
	<script src="vendor/global/global.min.js"></script>
	<script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
	<script src="js/plugins-init/datatables.init.js"></script>
	<script src="vendor/wow-master/dist/wow.min.js"></script>
	<script src="js/custom.min.js"></script>
	<script src="js/dlabnav-init.js"></script>
	<script src="js/admin-standard.js"></script>
	<script src="js/demo.js"></script>
	<script src="js/styleSwitcher.js"></script>

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		// ==================== MODAL INITIALIZATION ====================
		// Use jQuery to ensure modals work properly with existing libraries
		$(document).ready(function() {
			// Initialize modals using jQuery
			window.dateModal = new bootstrap.Modal(document.getElementById('updateDateModal'));
			window.codeModal = new bootstrap.Modal(document.getElementById('updateStudentCodeModal'));

			// Debug: Log to confirm initialization
			console.log('Modals initialized successfully');
		});

		// ==================== DATE MODAL FUNCTIONS ====================

		function openDateModal(studentId, currentDate) {
			try {
				console.log('Opening date modal for student:', studentId);

				// Set the student ID
				document.getElementById('student_id').value = studentId;

				// Format the current date for display
				let displayDate = currentDate ? new Date(currentDate) : new Date();
				let formattedDate = displayDate.toLocaleString('en-US', {
					year: 'numeric',
					month: 'long',
					day: 'numeric',
					hour: '2-digit',
					minute: '2-digit',
					second: '2-digit',
					hour12: true
				});
				document.getElementById('current_date_text').textContent = formattedDate;

				// Set the input field
				let dateInput = document.getElementById('apply_date');
				if (currentDate) {
					let date = new Date(currentDate);
					let year = date.getFullYear();
					let month = String(date.getMonth() + 1).padStart(2, '0');
					let day = String(date.getDate()).padStart(2, '0');
					let hours = String(date.getHours()).padStart(2, '0');
					let minutes = String(date.getMinutes()).padStart(2, '0');
					dateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
				} else {
					let now = new Date();
					let year = now.getFullYear();
					let month = String(now.getMonth() + 1).padStart(2, '0');
					let day = String(now.getDate()).padStart(2, '0');
					let hours = String(now.getHours()).padStart(2, '0');
					let minutes = String(now.getMinutes()).padStart(2, '0');
					dateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
				}

				// Show modal using Bootstrap
				window.dateModal.show();

			} catch (error) {
				console.error('Error opening date modal:', error);
				alert('Error opening date modal. Please try again.');
			}
		}

		// Function to submit the date update
		function submitDateUpdate() {
			try {
				const studentId = document.getElementById('student_id').value;
				const applyDate = document.getElementById('apply_date').value;

				if (!applyDate) {
					alert('Please select a date.');
					return;
				}

				// Show loading state
				const submitBtn = document.querySelector('.update-date-btn');
				const originalText = submitBtn.innerHTML;
				submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
				submitBtn.disabled = true;

				// Prepare data for AJAX request
				const formData = new FormData();
				formData.append('student_id', studentId);
				formData.append('apply_date', applyDate);
				formData.append('action', 'update_date');

				// Send AJAX request
				fetch('update_apply_date.php', {
						method: 'POST',
						body: formData
					})
					.then(response => response.json())
					.then(data => {
						// Reset button state
						submitBtn.innerHTML = originalText;
						submitBtn.disabled = false;

						if (data.success) {
							showToast('success', data.message || 'Date updated successfully!');
							window.dateModal.hide();
							setTimeout(() => {
								location.reload();
							}, 1500);
						} else {
							showToast('error', data.message || 'Failed to update date.');
						}
					})
					.catch(error => {
						console.error('Error:', error);
						submitBtn.innerHTML = originalText;
						submitBtn.disabled = false;
						showToast('error', 'Network error. Please try again.');
					});
			} catch (error) {
				console.error('Error in submitDateUpdate:', error);
				alert('An error occurred. Please try again.');
			}
		}

		// ==================== STUDENT CODE MODAL FUNCTIONS ====================

		function openStudentCodeModal(studentId, currentCode, studentName) {
			try {
				console.log('Opening student code modal for student:', studentId);

				// Set the student ID
				document.getElementById('student_id_code').value = studentId;

				// Display current student code
				document.getElementById('current_student_code_text').textContent = currentCode || 'No code set';

				// Display student name
				document.getElementById('student_name_text').textContent = studentName || 'Unknown Student';

				// Set the input field
				const codeInput = document.getElementById('student_code');
				codeInput.value = currentCode || '';

				// Focus and select after a small delay
				setTimeout(function() {
					codeInput.focus();
					codeInput.select();
				}, 300);

				// Show modal using Bootstrap
				window.codeModal.show();

			} catch (error) {
				console.error('Error opening student code modal:', error);
				alert('Error opening student code modal. Please try again.');
			}
		}

		// Function to submit the student code update
		function submitStudentCodeUpdate() {
			try {
				const studentId = document.getElementById('student_id_code').value;
				const studentCode = document.getElementById('student_code').value.trim();

				// Validate input
				if (!studentCode) {
					alert('Please enter a student code.');
					document.getElementById('student_code').focus();
					return;
				}

				if (studentCode.length > 50) {
					alert('Student code must be less than 50 characters.');
					document.getElementById('student_code').focus();
					return;
				}

				// Show loading state
				const submitBtn = document.querySelector('.update-student-code-btn');
				const originalText = submitBtn.innerHTML;
				submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
				submitBtn.disabled = true;

				// Prepare data for AJAX request
				const formData = new FormData();
				formData.append('student_id', studentId);
				formData.append('student_code', studentCode);
				formData.append('action', 'update_student_code');

				// Send AJAX request
				fetch('update_student_code.php', {
						method: 'POST',
						body: formData
					})
					.then(response => response.json())
					.then(data => {
						// Reset button state
						submitBtn.innerHTML = originalText;
						submitBtn.disabled = false;

						if (data.success) {
							showToast('success', data.message || 'Student Code updated successfully!');
							window.codeModal.hide();
							setTimeout(() => {
								location.reload();
							}, 1500);
						} else {
							showToast('error', data.message || 'Failed to update student code.');
						}
					})
					.catch(error => {
						console.error('Error:', error);
						submitBtn.innerHTML = originalText;
						submitBtn.disabled = false;
						showToast('error', 'Network error. Please try again.');
					});
			} catch (error) {
				console.error('Error in submitStudentCodeUpdate:', error);
				alert('An error occurred. Please try again.');
			}
		}

		// ==================== TOAST NOTIFICATION FUNCTIONS ====================

		function showToast(type, message) {
			try {
				const toastElement = type === 'success' ?
					document.getElementById('successToast') :
					document.getElementById('errorToast');

				const toastBody = toastElement.querySelector('.toast-body');
				toastBody.textContent = message;

				const bsToast = new bootstrap.Toast(toastElement);
				bsToast.show();
			} catch (error) {
				console.error('Error showing toast:', error);
				alert(message);
			}
		}
	</script>

</body>

</html>