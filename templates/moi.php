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
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>">
        <div style="margin-top:4.8cm; display: flex; flex-direction:column;">
            <p style="font-size: 14pt;font-weight: 400;width:17cm;text-align:right;">
                Date: <?php echo date('d-m-Y'); ?> </p>

            <p class="text-center" style="margin-top: 2cm;font-size: 16pt;font-weight: bold;text-decoration: underline;width:17cm">
                Medium of Instruction
            </p>

            <div style="display:flex;flex-direction:column;margin-top:1.5cm;width:17cm">
                <p style="font-size: 13pt;margin-bottom: 10pt;line-height: 1.25;">
                    This is to certify that <?= htmlspecialchars($resultData['student_name'] ?? $info['student_name'] ?? '-') ?>, was
                    a student of <?= htmlspecialchars($resultData['program'] ?? $info['program'] ?? '-') ?>, bearing the
                    enrolment number <?= htmlspecialchars($resultData['enrollment_no'] ?? $info['enrollment_no'] ?? '-') ?> has
                    completed the course successfully.
                </p>
                <p style="font-size: 13pt;line-height: 1.25;">
                    The medium of instruction under which he carried out the course was English.
                </p>
            </div>

            <table style="width:17cm; margin-top:4cm;">
                <tr>
                    <td style="border:none !important;font-size:12pt;font-weight:400;text-align: left !important;">ISBM University</td>
                </tr>
                <tr>
                    <td style="border:none !important;font-size:12pt;font-weight:400;text-align: left !important;">India</td>
                </tr>
            </table>
        </div>
    </div>

<?php endforeach; ?>