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
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 30px;
            background: #f0f0f0;
        }

        .card-wrapper {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* INCREASED SIZE - Now 450px wide and 280px tall */
        .id-card,
        .id-card-back {
            width: 450px;
            height: 280px;
            border: 3px solid #333;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            padding: 25px;
            position: relative;
            box-sizing: border-box;
            color: #000;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .id-card {
            background-image: url('images/Certificate_idcard/TeacherIdCardFront.jpg');
        }

        .id-card-back {
            background-image: url('images/Certificate_idcard/TeacherIdCardBack.jpg');
        }

        /* Adjusted positions for larger card */
        .info {
            position: absolute;
            top: 100px;
            left: 140px;
            font-size: 14px;
            line-height: 1.6;
        }

        .info p {
            margin: 3px 0;
        }

        .reg {
            position: absolute;
            top: 84px;
            left: 237px;
            font-size: 16px;
            font-weight: bold;
        }

        /* Bigger photo */
        .photo {
            position: absolute;
            top: 118px;
            right: 18px;
            width: 80px;
            height: 95px;
            border: 3px solid #e66969ff;
            border-radius: 8px;
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
            top: 70px;
            left: 25px;
            font-size: 16px;
            line-height: 1.6;
            width: 90%;
        }

        .address-section p {
            margin: 3px 0;
        }

        .address-title {
            font-weight: bold;
            background-color: #801336;
            color: white;
            padding: 3px 8px;
            display: inline-block;
            margin-bottom: 6px;
            font-size: 15px;
        }

        .print-btn {
            margin-top: 30px;
        }

        .print-btn button {
            padding: 12px 30px;
            font-size: 18px;
            cursor: pointer;
            background: #801336;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .print-btn button:hover {
            background: #a01845;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background: white;
                margin: 20px;
            }

            .id-card {
                background-image: url('images/Certificate_idcard/TeacherIdCardFront.jpg') !important;
            }

            .id-card-back {
                background-image: url('images/Certificate_idcard/TeacherIdCardBack.jpg') !important;
            }

            .id-card,
            .id-card-back {
                box-shadow: none !important;
                border: 2px solid #333 !important;
            }

            button {
                display: none;
            }

            .print-btn {
                display: none;
            }

            .card-wrapper {
                gap: 20px;
            }
        }

        /* Responsive for smaller screens */
        @media screen and (max-width: 500px) {

            .id-card,
            .id-card-back {
                width: 95vw;
                height: auto;
                min-height: 280px;
                padding: 20px;
            }

            .info {
                font-size: 12px;
                left: 120px;
                top: 85px;
            }

            .photo {
                width: 60px;
                height: 75px;
                right: 15px;
                top: 100px;
            }

            .reg {
                font-size: 14px;
                left: 190px;
                top: 65px;
            }
        }
    </style>
</head>

<body>
    <div class="card-wrapper">
        <!-- Front Card -->
        <div class="id-card" style="font-size: 14px;">
            <!-- Registration Number -->
            <div class="reg">
                <p><?= htmlspecialchars($teacher['teacher_code'] ?? 'N/A') ?></p>
            </div>

            <!-- Front Information -->
            <div class="info" style="margin-top: 26px;">
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

        <!-- Back Card -->
        <div class="id-card-back">
            <div class="info" style="margin-top: -69px; font-size: 14px; padding-left: 7px;">
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

    <div class="print-btn">
        <button onclick="window.print()">🖨️ Print ID Card</button>
    </div>
</body>

</html>