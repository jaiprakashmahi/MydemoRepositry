<?php
include 'conn.php';

if (!isset($_GET['id'])) {
    die("Teacher ID not provided.");
}
$id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT
        t.*,
        s.name AS state_name,
        d.name AS district_name,
        c.center_name
    FROM teachers t
    LEFT JOIN states s ON t.state_id = s.id
    LEFT JOIN districts d ON t.district_id = d.id
    LEFT JOIN center_details c ON t.center_id = c.id
    WHERE t.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Teacher not found.");
}
$teacher = $result->fetch_assoc();
if (!empty($teacher["photo"])) {
    $path_db_img = $teacher["photo"] ?? "";
} else {
    $path_db_img = "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Teacher ID Card</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #e8e8e8;
            padding: 20px;
        }

        .card-wrapper {
            display: flex;
            gap: 50px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        /* ID Card Base - Portrait orientation */
        .id-card,
        .id-card-back {
            width: 400px;
            height: 580px;
            border-radius: 12px;
            position: relative;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .id-card:hover,
        .id-card-back:hover {
            transform: scale(1.02);
        }

        .id-card {
            background-image: url('images/Certificate_idcard/TeacherFrontNew.jpeg');
        }

        .id-card-back {
            background-image: url('images/Certificate_idcard/Teacher-member_back.jpg');
        }

        /* ===== FRONT CARD ===== */

        /* Registration Number - Right aligned */
        .reg-number {
            position: absolute;
            top: 97px;
            right: 35px;
            font-size: 14px;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: 0.5px;
            text-align: right;
        }

        /* Teacher Name - Left aligned */
        .teacher-name {
            position: absolute;
            top: 156px;
            left: 38px;
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 220px;
            text-align: left;
        }

        /* Designation - Left aligned */
        .designation {
            position: absolute;
            top: 184px;
            left: 38px;
            font-size: 14px;
            font-weight: 600;
            color: #c0392b;
            width: 220px;
            text-align: left;
        }

        /* Blood Group - Left aligned */
        .blood-group {
            position: absolute;
            top: 216px;
            left: 38px;
            font-size: 14px;
            font-weight: 500;
            color: #1a1a1a;
            width: 220px;
            text-align: left;
        }

        /* Contact Number - Left aligned */
        .contact-number {
            position: absolute;
            top: 248px;
            left: 38px;
            font-size: 14px;
            font-weight: 500;
            color: #1a1a1a;
            width: 220px;
            text-align: left;
        }

        /* Center Name - Left aligned */
        .center-name {
            position: absolute;
            top: 278px;
            left: 38px;
            font-size: 13px;
            font-weight: 500;
            color: #1a1a1a;
            width: 220px;
            line-height: 1.3;
            text-align: left;
        }

        /* Photo */
        .photo {
            position: absolute;
            top: 139px;
            right: 35px;
            width: 108px;
            height: 135px;
            border: 3px solid #c0392b;
            border-radius: 8px;
            overflow: hidden;
            background: #f5f5f5;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            background: #e0e0e0;
            color: #999;
            font-size: 11px;
            text-align: center;
        }

        /* ===== BACK CARD - MATCHING TEACHER-MEMBER_BACK.JPG ===== */

        /* Back Card Header - Logo area */
        .back-header {
            position: absolute;
            top: 22px;
            left: 0;
            right: 0;
            text-align: center;
            width: 100%;
        }

        .back-header .institute-name {
            font-size: 18px;
            font-weight: 800;
            color: #8b1a1a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .back-header .institute-sub {
            font-size: 11px;
            color: #555;
            font-weight: 400;
            display: block;
            letter-spacing: 0.5px;
        }

        /* Blood Group - Positioned on background */
        .back-blood-group {
            position: absolute;
            top: 85px;
            left: 32px;
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            text-align: left;
        }

        .back-blood-group .label {
            font-weight: 400;
            color: #555;
        }

        /* Date of Joining - Positioned on background */
        .back-date-joining {
            position: absolute;
            top: 85px;
            right: 32px;
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            text-align: right;
        }

        .back-date-joining .label {
            font-weight: 400;
            color: #555;
        }

        /* Date of Birth - Positioned on background */
        .back-date-birth {
            position: absolute;
            top: 115px;
            left: 32px;
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            text-align: left;
        }

        .back-date-birth .label {
            font-weight: 400;
            color: #555;
        }

        /* Address Section - Positioned on background */
        .address-section {
            position: absolute;
            top: 155px;
            left: 32px;
            font-size: 14px;
            font-weight: 500;
            color: #1a1a1a;
            text-align: left;
            line-height: 1.8;
        }

        .address-section .address-label {
            font-weight: 700;
            color: #2c3e50;
        }

        .address-section .address-line {
            padding-left: 5px;
        }

        /* Mobile Section - Positioned on background */
        .back-mobile {
            position: absolute;
            top: 370px;
            left: 32px;
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            text-align: left;
        }

        .back-mobile .label {
            font-weight: 400;
            color: #555;
        }

        /* Email Section - Positioned on background */
        .back-email {
            position: absolute;
            top: 398px;
            left: 32px;
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            text-align: left;
        }

        .back-email .label {
            font-weight: 400;
            color: #555;
        }

        /* Footer - Device info matching background */
        .device-footer {
            position: absolute;
            bottom: 22px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            color: #999;
            font-weight: 400;
            letter-spacing: 0.3px;
        }

        /* Director Signature - Bottom Right */
        .director-signature {
            position: absolute;
            bottom: 55px;
            right: 32px;
            font-size: 13px;
            font-weight: 700;
            color: #2c3e50;
            text-align: right;
            letter-spacing: 0.5px;
        }

        .director-signature span {
            display: block;
            font-size: 10px;
            font-weight: 400;
            color: #888;
            letter-spacing: 0.3px;
        }

        /* ===== BUTTON STYLES ===== */
        .print-btn {
            margin-top: 40px;
        }

        .print-btn button {
            padding: 14px 45px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(135deg, #8b1a1a, #c0392b);
            color: white;
            border: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(192, 57, 43, 0.4);
            letter-spacing: 0.5px;
        }

        .print-btn button:hover {
            background: linear-gradient(135deg, #6b1414, #a93226);
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(192, 57, 43, 0.5);
        }

        .print-btn button:active {
            transform: translateY(0);
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background: white !important;
                margin: 10px !important;
                padding: 0 !important;
            }

            .card-wrapper {
                gap: 30px !important;
            }

            .id-card,
            .id-card-back {
                box-shadow: none !important;
                border: 2px solid #333 !important;
                border-radius: 8px !important;
                transform: none !important;
                page-break-inside: avoid;
                width: 400px !important;
                height: 580px !important;
            }

            .id-card {
                background-image: url('images/Certificate_idcard/TeacherFrontNew.jpeg') !important;
            }

            .id-card-back {
                background-image: url('images/Certificate_idcard/Teacher-member_back.jpg') !important;
            }

            .id-card:hover,
            .id-card-back:hover {
                transform: none !important;
            }

            .print-btn {
                display: none !important;
            }

            .id-card,
            .id-card-back {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        /* ===== RESPONSIVE ===== */
        @media screen and (max-width: 500px) {

            .id-card,
            .id-card-back {
                width: 85vw;
                height: 123vw;
                min-height: 450px;
                max-height: 580px;
            }

            /* Front card responsive */
            .reg-number {
                font-size: 11px;
                top: 70px;
                right: 25px;
            }

            .teacher-name {
                font-size: 13px;
                top: 115px;
                left: 25px;
                width: 160px;
            }

            .designation {
                font-size: 11px;
                top: 137px;
                left: 25px;
                width: 160px;
            }

            .blood-group {
                font-size: 11px;
                top: 160px;
                left: 25px;
                width: 160px;
            }

            .contact-number {
                font-size: 11px;
                top: 183px;
                left: 25px;
                width: 160px;
            }

            .center-name {
                font-size: 10px;
                top: 205px;
                left: 25px;
                width: 160px;
            }

            .photo {
                width: 75px;
                height: 95px;
                top: 103px;
                right: 25px;
                border-width: 2px;
            }

            /* Back card responsive */
            .back-header .institute-name {
                font-size: 14px;
            }

            .back-header .institute-sub {
                font-size: 9px;
            }

            .back-blood-group {
                font-size: 11px;
                top: 65px;
                left: 20px;
            }

            .back-date-joining {
                font-size: 11px;
                top: 65px;
                right: 20px;
            }

            .back-date-birth {
                font-size: 11px;
                top: 88px;
                left: 20px;
            }

            .address-section {
                font-size: 11px;
                top: 120px;
                left: 20px;
                line-height: 1.6;
            }

            .back-mobile {
                font-size: 11px;
                top: 290px;
                left: 20px;
            }

            .back-email {
                font-size: 11px;
                top: 314px;
                left: 20px;
            }

            .device-footer {
                font-size: 9px;
                bottom: 15px;
            }

            .director-signature {
                font-size: 11px;
                bottom: 45px;
                right: 20px;
            }

            .director-signature span {
                font-size: 9px;
            }

            .card-wrapper {
                gap: 25px;
            }

            .print-btn button {
                padding: 10px 30px;
                font-size: 15px;
            }
        }

        @media screen and (max-width: 380px) {

            .id-card,
            .id-card-back {
                width: 90vw;
                height: 130vw;
                min-height: 380px;
            }

            .reg-number {
                font-size: 9px;
                top: 55px;
                right: 18px;
            }

            .teacher-name {
                font-size: 11px;
                top: 90px;
                left: 18px;
                width: 130px;
            }

            .designation {
                font-size: 10px;
                top: 110px;
                left: 18px;
                width: 130px;
            }

            .blood-group {
                font-size: 10px;
                top: 130px;
                left: 18px;
                width: 130px;
            }

            .contact-number {
                font-size: 10px;
                top: 150px;
                left: 18px;
                width: 130px;
            }

            .center-name {
                font-size: 9px;
                top: 168px;
                left: 18px;
                width: 130px;
            }

            .photo {
                width: 60px;
                height: 75px;
                top: 80px;
                right: 18px;
                border-width: 2px;
            }

            .back-header .institute-name {
                font-size: 12px;
            }

            .back-header .institute-sub {
                font-size: 8px;
            }

            .back-blood-group {
                font-size: 10px;
                top: 55px;
                left: 15px;
            }

            .back-date-joining {
                font-size: 10px;
                top: 55px;
                right: 15px;
            }

            .back-date-birth {
                font-size: 10px;
                top: 75px;
                left: 15px;
            }

            .address-section {
                font-size: 10px;
                top: 100px;
                left: 15px;
                line-height: 1.5;
            }

            .back-mobile {
                font-size: 10px;
                top: 240px;
                left: 15px;
            }

            .back-email {
                font-size: 10px;
                top: 260px;
                left: 15px;
            }

            .device-footer {
                font-size: 8px;
                bottom: 12px;
            }

            .director-signature {
                font-size: 10px;
                bottom: 38px;
                right: 15px;
            }

            .director-signature span {
                font-size: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="card-wrapper">
        <!-- ===== FRONT CARD ===== -->
        <div class="id-card">
            <!-- Registration Number -->
            <div class="reg-number">
                <?= htmlspecialchars($teacher['teacher_code'] ?? 'N/A') ?>
            </div>

            <!-- Teacher Name -->
            <div class="teacher-name">
                <?= htmlspecialchars($teacher['name'] ?? 'N/A') ?>
            </div>

            <!-- Designation -->
            <div class="designation">
                <?= htmlspecialchars($teacher['designation'] ?? 'Teacher') ?>
            </div>

            <!-- Blood Group -->
            <div class="blood-group">
                <?= htmlspecialchars($teacher['blood_group'] ?? 'N/A') ?>
            </div>

            <!-- Contact Number -->
            <div class="contact-number">
                <?= htmlspecialchars($teacher['mobile'] ?? 'N/A') ?>
            </div>

            <!-- Center Name -->
            <div class="center-name">
                <?= htmlspecialchars(mb_strimwidth($teacher['center_name'] ?? 'N/A', 0, 30, '..')) ?>
            </div>

            <!-- Photo -->
            <div class="photo">
                <?php if (!empty($path_db_img) && file_exists("uploads/" . $path_db_img)): ?>
                    <img src="uploads/<?= htmlspecialchars($path_db_img) ?>" alt="Teacher Photo">
                <?php else: ?>
                    <div class="photo-placeholder">No Photo</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ===== BACK CARD - USING TEACHER-MEMBER_BACK.JPG ===== -->
        <div class="id-card-back">
            <!-- Header with Logo -->
            <div class="back-header">
                <div class="institute-name">SHARNAY INSTITUTE</div>
                <span class="institute-sub">of Education &amp; Technology Pvt. Ltd</span>
            </div>

            <!-- Blood Group -->
            <div class="back-blood-group">
                <span class="label">Blood Group :</span>
                <?= !empty($teacher['blood_group']) ? htmlspecialchars($teacher['blood_group']) : 'N/A' ?>
            </div>

            <!-- Date of Joining -->
            <div class="back-date-joining">
                <span class="label">Date of Joining :</span><br>
                <?= !empty($teacher['date_of_joining']) ? htmlspecialchars($teacher['date_of_joining']) : 'N/A' ?>
            </div>

            <!-- Date of Birth -->
            <div class="back-date-birth">
                <span class="label">Date of Birth :</span>
                <?= !empty($teacher['dob']) ? htmlspecialchars($teacher['dob']) : 'N/A' ?>
            </div>

            <!-- Address Section -->
            <div class="address-section">
                <div class="address-label">Address :</div>
                <div class="address-line"><?= !empty($teacher['address']) ? htmlspecialchars($teacher['address']) : 'N/A' ?></div>
                <?php if (!empty($teacher['ward_no'])): ?>
                    <div class="address-line">Ward No.-<?= htmlspecialchars($teacher['ward_no']) ?></div>
                <?php endif; ?>
                <?php if (!empty($teacher['post_office'])): ?>
                    <div class="address-line">P.o. <?= htmlspecialchars($teacher['post_office']) ?></div>
                <?php endif; ?>
                <?php if (!empty($teacher['police_station'])): ?>
                    <div class="address-line">P.s. <?= htmlspecialchars($teacher['police_station']) ?></div>
                <?php endif; ?>
                <?php if (!empty($teacher['district_name'])): ?>
                    <div class="address-line">Dist. <?= htmlspecialchars($teacher['district_name']) ?></div>
                <?php endif; ?>
                <?php if (!empty($teacher['pin_code'])): ?>
                    <div class="address-line">Pin Code. <?= htmlspecialchars($teacher['pin_code']) ?></div>
                <?php endif; ?>
            </div>

            <!-- Mobile -->
            <div class="back-mobile">
                <span class="label">Mobile No :</span>
                <?= htmlspecialchars($teacher['mobile'] ?? 'N/A') ?>
            </div>

            <!-- Email -->
            <div class="back-email">
                <span class="label">Email Id :</span>
                <?= htmlspecialchars($teacher['email'] ?? 'N/A') ?>
            </div>

            <!-- Director Signature -->
            <div class="director-signature">
                DIRECTOR
                <span>Sharnay Institute</span>
            </div>

            <!-- Footer - Device Info -->
            <div class="device-footer">
                Galaxy M15 5G
            </div>
        </div>
    </div>

    <div class="print-btn">
        <button onclick="window.print()">🖨️ Print ID Card</button>
    </div>
</body>

</html>