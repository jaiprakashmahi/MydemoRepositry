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
$teacher = $result->fetch_assoc(); // ✅ Important fix
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
            background-image: url('images/Certificate_idcard/TeacherIdCardFront.jpg');
        }

        .id-card-back {
            background-image: url('images/Certificate_idcard/TeacherIdCardBack.jpg');
        }

        .info {
            position: absolute;
            top: 87px;
            left: 118px;
            font-size: 11px;
            line-height: 1.4;
        }

        .info p {
            margin: 2px 0;
        }

        .reg {
            position: absolute;
            top: 65px;
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
                background-image: url('images/Certificate_idcard/TeacherIdCardFront.jpg') !important;
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
                    <p><?= htmlspecialchars($teacher['teacher_code'] ?? 'N/A') ?></p>
                </div>

                <!-- Front Information - Only values, labels are on the image -->
                <div class="info" style="margin-top: 18px;">
                    <p><?= htmlspecialchars($teacher['name'] ?? 'N/A') ?></p>
                    <p><?= htmlspecialchars($teacher['designation'] ?? 'Teacher') ?></p>
                    <p><?= htmlspecialchars($teacher['blood_group'] ?? 'N/A') ?></p>
                    <p><?= htmlspecialchars($teacher['mobile'] ?? 'N/A') ?></p>
                    <p>
                        <?= htmlspecialchars(
                            mb_strimwidth($teacher['center_name'] ?? 'N/A', 0, 55, '..')
                        ) ?>
                    </p>
                </div>

                <!-- Photo -->
                <div class="photo">
                    <?php if (!empty($path_db_img) && file_exists("uploads/" . $path_db_img)): ?>
                        <img src="uploads/<?= htmlspecialchars($path_db_img) ?>" alt="Teacher Photo">
                    <?php else: ?>
                        <img src="https://www.gravatar.com/avatar/?d=mp&s=100" alt="No Photo">
                    <?php endif; ?>
                </div>
            </div>

            <div class="id-card-back">
                <div class="info" style="margin-top: -59px; font-size: 13px;">
                    <p><?= !empty($teacher['father_name']) ? htmlspecialchars($teacher['father_name']) : 'N/A' ?></p>
                    <p><?= !empty($teacher['mother_name']) ? htmlspecialchars($teacher['mother_name']) : 'N/A' ?></p>
                    <p><?= !empty($teacher['address']) ? htmlspecialchars($teacher['address']) : 'N/A' ?> </p>
                    <p><?= !empty($teacher['post_office']) ? htmlspecialchars($teacher['post_office']) : 'N/A' ?></p>
                    <p><?= !empty($teacher['block']) ? htmlspecialchars($teacher['block']) : 'N/A' ?></p>
                    <p><?= !empty($teacher['district_name']) ? htmlspecialchars($teacher['district_name']) : 'N/A' ?></p>
                    <p><?= !empty($teacher['state_name']) ? htmlspecialchars($teacher['state_name']) : 'N/A' ?></p>
                    <p><?= !empty($teacher['pin_code']) ? htmlspecialchars($teacher['pin_code']) : 'N/A' ?></p>
                </div>
            </div>

        </div>
</body>

<div class="print-btn">
    <button onclick="window.print()">Print ID Card</button>
</div>

</html>