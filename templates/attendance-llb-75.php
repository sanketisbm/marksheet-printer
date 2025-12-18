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
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>" style="padding-left: 2.5cm !important;padding-right: 2.5cm !important;font-family: 'Times New Roman', Times, serif !important;">
        <div style="margin-top:5.2cm; display: flex; flex-direction:column;">
            <p style="font-size: 14pt;font-weight: 400;width:16cm;text-align:right;">
                Date: <?= htmlspecialchars($info['print_date'] ?? date('d-m-Y')) ?> </p>

            <p class="text-center" style="margin-top: 2.5cm;font-size: 16pt;text-decoration: underline;width:16cm">
                TO WHOM IT MAY CONCERN
            </p>

            <div style="display:flex;flex-direction:column;margin-top:2cm;width:16cm">
                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;">This is to certify that
                    <b><?= htmlspecialchars($info['student_name'] ?? '-') ?></b> S/o / D/o / W/o
                    <b><?= htmlspecialchars($info['fathers_husbands_name'] ?? '-') ?></b> bearing enrolment no.
                    <?= htmlspecialchars($info['enrollment_no'] ?? '-') ?> has been declared passed in
                    <b><?= htmlspecialchars($info['program'] ?? '-') ?></b> examination of
                    <?= htmlspecialchars($info['passing_year'] ?? '-') ?>.
                </p>

                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;">
                    He/She has been attending the class and his/her attendance was more than 75%.
                </p>
            </div>

            <div style="width:16cm; margin-top:4.3cm;">
                <p style="font-size: 14pt;line-height: 1.25;">ISBM University</p>
                <p style="font-size: 14pt;line-height: 1.25;">India</p>
            </div>
        </div>
    </div>
<?php endforeach; ?>