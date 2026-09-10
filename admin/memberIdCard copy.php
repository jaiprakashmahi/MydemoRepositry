<?php
include 'conn.php';

if (!isset($_GET['id'])) {
    die("Member ID not provided.");
}
$id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT mi.*, mt.type_name AS member_type_name FROM member_information mi
    LEFT JOIN member_types mt ON mi.member_type_id = mt.id 
    WHERE mi.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();



if ($result->num_rows == 0) {
    die("Member not found.");
}
$member = $result->fetch_assoc(); // ✅ Important fix
//print_r($member);
if (!empty($member["passport_photo"])) {
    $path_db_img = $member["passport_photo"] ?? "";
} else {
    $path_db_img = "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Member ID Card</title>
    <style>
        /* body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 30px;
            margin: 50px;
            flex-wrap: wrap;
        } */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 30px;
        }

        .card-wrapper {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .id-card,
        .id-card-back {
            width: 350px;
            height: 220px;
            border: 2px solid #333;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            padding: 20px;
            position: relative;
            box-sizing: border-box;
            color: #000;
        }

        .id-card {
            background-image: url('images/Certificate_idcard/MemberIdCardFront.jpg');
        }

        .id-card-back {
            background-image: url('images/Certificate_idcard/TeacherIdCardBack.jpg');
        }

        .info {
            position: absolute;
            top: 90px;
            left: 118px;
            font-size: 11px;
            line-height: 1.4;
        }

        .info p {
            margin: 1px 0;
        }

        .reg {
            position: absolute;
            top: 38px;
            left: 185px;
            font-size: 13px;
            font-weight: bold;
        }

        .photo {
            position: absolute;
            top: 95px;
            right: 11px;
            width: 61px;
            height: 75px;
            border: 2px solid #e66969ff;
            border-radius: 5px;
            overflow: hidden;
            background: #ddd;
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .address-section {
            position: absolute;
            top: 60px;
            left: 20px;
            font-size: 14px;
            line-height: 1.4;
            width: 90%;
        }

        .address-section p {
            margin: 2px 0;
        }

        .address-title {
            font-weight: bold;
            background-color: #801336;
            color: white;
            padding: 2px 6px;
            display: inline-block;
            margin-bottom: 4px;
        }

        .print-btn {
            margin-top: 240px;
            margin-left: -400px;
        }

        .print-btn button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .id-card {
                background-image: url('images/Certificate_idcard/MemberIdCardFront.jpg') !important;
            }

            .id-card-back {
                background-image: url('images/Certificate_idcard/TeacherIdCardBack.jpg') !important;
            }

            button {
                display: none;
            }

            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="card-wrapper">
        <div class="card-wrapper">
            <!-- Front Card -->
            <div class="id-card">
                <!-- Registration Number -->
                <div class="reg">
                    <p><?= htmlspecialchars($member['unicode'] ?? 'N/A') ?></p>
                </div>
                <!-- Front Information - Only values, labels are on the image -->
                <div class="info" style="margin-top: 18px;">
                    <p><?= htmlspecialchars($member['applicant_name'] ?? 'N/A') ?></p>
                    <p><?= htmlspecialchars($member['member_type_name'] ?? 'Member') ?></p>
                    <p><?= htmlspecialchars($member['blood_group'] ?? 'B+') ?></p>
                    <p><?= htmlspecialchars($member['mobile_number'] ?? 'N/A') ?></p>
                    <p>
                        <?= htmlspecialchars(
                            mb_strimwidth(
                                'State: ' . ($member['state'] ?? 'N/A') .
                                    ' | District: ' . ($member['district'] ?? 'N/A') .
                                    ' | Block: ' . ($member['block'] ?? 'N/A'),
                                0,
                                55,
                                '..'
                            )
                        ) ?>
                    </p>
                </div>

                <!-- Photo -->
                <div class="photo">
                    <?php if (!empty($path_db_img) && file_exists("../" . $path_db_img)): ?>
                        <img src="../<?= htmlspecialchars($path_db_img) ?>" alt="Member Photo">
                    <?php else: ?>
                        <img src="https://www.gravatar.com/avatar/?d=mp&s=100" alt="No Photo">
                    <?php endif; ?>
                </div>
            </div>

            <div class="id-card-back">
                <div class="info" style="margin-top: -59px; font-size: 13px;">
                    <p><?= !empty($member['father_name']) ? htmlspecialchars($member['father_name']) : 'N/A' ?></p>
                    <p><?= !empty($member['mother_name']) ? htmlspecialchars($member['mother_name']) : 'N/A' ?></p>
                    <p><?= !empty($member['full_address']) ? htmlspecialchars($member['full_address']) : 'N/A' ?> </p>
                    <p><?= !empty($member['block']) ? htmlspecialchars($member['block']) : 'N/A' ?></p>
                    <p><?= !empty($member['block']) ? htmlspecialchars($member['block']) : 'N/A' ?></p>
                    <p><?= !empty($member['district']) ? htmlspecialchars($member['district']) : 'N/A' ?></p>
                    <p><?= !empty($member['state']) ? htmlspecialchars($member['state']) : 'N/A' ?></p>
                    <p><?= !empty($member['pin_code']) ? htmlspecialchars($member['pin_code']) : 'N/A' ?></p>
                </div>
            </div>

        </div>
</body>

<div class="print-btn">
    <button onclick="window.print()">Print ID Card</button>
</div>

</html>