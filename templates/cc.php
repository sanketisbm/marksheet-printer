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

    $envArray = str_split($info['enrollment_no']); // split into characters
    $env = $envArray[2] . $envArray[3];
    if ($env === "01" || $env === "11") {
        $initials = "He";
        $initials2 = "his";
    } elseif ($env === "02" || $env === "12") {
        $initials = "She";
        $initials2 = "her";
    }
?>
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>" style="padding-left: 2.5cm !important;padding-right: 2.5cm !important;">
        <div style="margin-top:5.2cm; display: flex; flex-direction:column;">
            <p style="font-size: 12pt;font-weight: 400;"> S. No.: <?php htmlspecialchars($info['doc_no'] ?? $sl_no) ?> </p>
            <p style="font-size: 12pt;font-weight: 400;margin-top:0.4cm;width:16cm;text-align:right;">
                Dated: <?= htmlspecialchars($info['print_date'] ?? date('d-m-Y')) ?> </p>

            <p class="text-center" style="margin-top: 1.3cm;font-size: 18pt;font-weight: bold;text-decoration: underline;width:16cm">
                Character Certificate
            </p>

            <table style="margin-top:1cm;width:16cm">
                <tr>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:5cm">This is to certify that</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:1.3cm">: -</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;"><?= htmlspecialchars($info['student_name'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:5cm">Father's/Husband’s name</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:1.3cm">: -</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;"><?= htmlspecialchars($info['fathers_husbands_name'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:5cm">Mother's name</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:1.3cm">: -</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;"><?= htmlspecialchars($resultData['mother_name'] ?? $info['mother_name'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:5cm">Enrollment No.</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:1.3cm">: -</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;"><?= htmlspecialchars($info['enrollment_no'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:14pt;width:5cm">Conduct</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:1.3cm">: -</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;">GOOD</td>
                </tr>
            </table>

            <div style="display:flex;flex-direction:column;margin-top:1cm;width:16cm">
                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;">has been a regular student of the University in class / course
                    <b><?= htmlspecialchars($info['program'] ?? '-') ?></b> and passed <?= htmlspecialchars($initials2 ?? 'his / her') ?>
                    examination of the above course in
                    <b><?= htmlspecialchars($info['exam_session'] ?? '-') ?></b>.
                </p>
                <p style="font-size: 14pt;line-height: 1.25;"><?= htmlspecialchars($initials ?? 'He / She') ?> is reliable, sincere, hardworking and bears a good moral character.</p>
            </div>
            <p style="text-align:right;font-size:12pt;font-weight:400;margin-top:4.3cm;width:16cm;">
                For Registrar
            </p>
        </div>
    </div>

<?php endforeach; ?>