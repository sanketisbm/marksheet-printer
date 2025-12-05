<?php
global $conn;

if (empty($data) || !is_array($data)) {
    return;
}
?>

<?php foreach ($data as $info):
    $sl_no = random_int(10000000, 99999999);

    $templateSql    = "SELECT * FROM results WHERE enrollment_no = '{$info['enrollment_no']}' ORDER BY period DESC";
    $templateResult = mysqli_query($conn, $templateSql);

    if (!$templateResult || mysqli_num_rows($templateResult) == 0) {
        return;
    }

    $resultData = mysqli_fetch_assoc($templateResult);
?>
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>" style="padding-left: 2.5cm !important;padding-right: 2.5cm !important;">
        <div style="margin-top:4.5cm; display: flex; flex-direction:column;">
            <table style="width:16cm;">
                <tr>
                    <td style="border:none !important;font-size:12pt;font-weight:400;text-align: left !important;">S. No:
                        <?= $sl_no ?></td>
                    <td style="border:none !important;font-size:12pt;font-weight:400;text-align: right !important;"> Date:
                        <?= date('d-m-Y') ?> </td>
                </tr>
            </table>

            <p style="margin-top: 1.5cm;font-size: 12pt;width:16cm">
                Enrollment No:- <?= htmlspecialchars($resultData['enrollment_no'] ?? $info['enrollment_no'] ?? '-') ?>
            </p>

            <p class="text-center"
                style="margin-top: 2.5cm;font-size: 18pt;font-weight: bold;text-decoration: underline;width:16cm">
                PROVISIONAL CERTIFICATE
            </p>

            <p style="font-size: 16pt;line-height:  calc(2 * 1em + 5pt);margin-top:1.7cm;width:16cm;word-spacing: 5px;text-indent:1.27cm;">It has been certified that Mr. / Mrs. /
                Ms. <b><?= htmlspecialchars($resultData['student_name'] ?? $info['student_name'] ?? '-') ?></b> S/o /
                D/o / W/o. <?= htmlspecialchars($resultData['father_name'] ?? $info['father_name'] ?? '-') ?> is qualified to
                receive the Degree of <?= htmlspecialchars($resultData['program_print_name'] ?? $info['program'] ?? '-') ?>, having
                successfully passed the examination in the year
                <?= htmlspecialchars(explode("-", $resultData['exam_session'])[1] ?? $info['exam_session'] ?? '-') ?> and
                has been placed in <?= htmlspecialchars($resultData['cDivision'] ?? $info['cDivision'] ?? '-') ?> Division.</p>

            <p style="text-align:right;font-size:14pt;font-weight:400;margin-top:5.8cm;width:16cm;">
                Controller of Examinations
            </p>
        </div>
    </div>

<?php endforeach; ?>