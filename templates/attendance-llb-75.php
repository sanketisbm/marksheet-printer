<?php
global $conn;

if (empty($data) || !is_array($data)) {
    return;
}
?>

<?php foreach ($data as $info):
    $envArray = str_split($info['enrollment_no']); // split into characters
    $env = $envArray[2] . $envArray[3];
    if ($env === "01" || $env === "11") {
        $initials = "He";
        $initials2 = "his";
        $prefix_eng = "S/o";
    } elseif ($env === "02" || $env === "12") {
        $initials = "She";
        $initials2 = "her";
        $prefix_eng = "D/o";
    }
?>
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>">
        <div style="margin-top:5.2cm; display: flex; flex-direction:column;">
            <p style="font-size: 14pt;font-weight: 400;width:17cm;text-align:right;">
                Date: <?= htmlspecialchars($info['print_date'] ?? date('d-m-Y')) ?> </p>

            <p class="text-center"
                style="margin-top: 2.5cm;font-size: 16pt;font-weight: bold;text-decoration: underline;width:17cm">
                TO WHOM IT MAY CONCERN
            </p>

            <div style="display:flex;flex-direction:column;margin-top:2cm;width:17cm">
                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;">This is to certify that
                    <b><?= htmlspecialchars($info['student_name'] ?? '-') ?></b> <?= htmlspecialchars($info['prefix_eng'] ?? $prefix_eng) ?>
                    <b><?= htmlspecialchars($info['fathers_husbands_name'] ?? '-') ?></b> bearing enrolment no.
                    <?= htmlspecialchars($info['enrollment_no'] ?? '-') ?> has been declared passed in
                    <b><?= htmlspecialchars($info['program'] ?? '-') ?></b> examination of
                    <?= htmlspecialchars($info['exam_session'] ?? '-') ?>.
                </p>

                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;">
                    <?= htmlspecialchars($initials ?? 'He/She') ?> has been attending the class and
                    <?= htmlspecialchars($initials2 ?? 'his/her') ?> attendance was more than 75%.
                </p>

            </div>

            <div style="width:17cm; margin-top:4.3cm;">
                <p style="font-size: 14pt;line-height: 1.25;">ISBM University</p>
                <p style="font-size: 14pt;line-height: 1.25;">India</p>
            </div>
        </div>
    </div>
<?php endforeach; ?>