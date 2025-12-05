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
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>">
        <div style="margin-top:5.2cm; display: flex; flex-direction:column;">
            <p style="font-size: 14pt;font-weight: 400;width:17cm;text-align:right;">
                Date: <?php echo date('d-m-Y'); ?> </p>

            <p class="text-center"
                style="margin-top: 3cm;font-size: 16pt;font-weight: bold;text-decoration: underline;width:17cm">
                TO WHOM IT MAY CONCERN
            </p>

            <div style="display:flex;flex-direction:column;margin-top:2cm;width:17cm">
                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;">This is to certify that
                    <b><?= htmlspecialchars($resultData['student_name'] ?? $info['student_name'] ?? '-') ?></b> S/o / D/o W/o
                    <b><?= htmlspecialchars($resultData['father_name'] ?? $info['father_name'] ?? '-') ?></b> bearing enrolment no.
                    <?= htmlspecialchars($resultData['enrollment_no'] ?? $info['enrollment_no'] ?? '-') ?> has been declared
                    passed in <b><?= htmlspecialchars($resultData['program_print_name'] ?? $info['program'] ?? '-') ?></b> examination of
                    <?= htmlspecialchars(explode("-", $resultData['exam_session'])[1] ?? $info['exam_session'] ?? '-') ?>.
                </p>

                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;">
                    <?= htmlspecialchars($initials ?? 'He/She') ?> has been attending the class and <?= htmlspecialchars($initials2 ?? 'his/her') ?> attendance was satisfactory.
                </p>

            </div>

            <div style="width:17cm; margin-top:4cm;">
                 <p style="font-size: 14pt;line-height: 1.25;">ISBM University</p>
                <p style="font-size: 14pt;line-height: 1.25;">India</p>
            </div>
        </div>
    </div>

<?php endforeach; ?>