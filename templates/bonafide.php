<?php
global $conn;

if (empty($data) || !is_array($data)) {
    return;
}
?>

<?php foreach ($data as $info):
    $envArray = str_split($info['enrollment_no']); // split into characters
    $startYr = $envArray[0] . $envArray[1]; ?>
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>" style="padding-left: 2.5cm !important;padding-right: 2.5cm !important;font-family: 'Times New Roman', Times, serif !important;">
        <div style="margin-top:5cm; display: flex; flex-direction:column;">
            <p style="font-size: 14pt;font-weight: 400;width:16cm;text-align:right;">
                Date: <?= htmlspecialchars($info['print_date'] ?? date('d-m-Y')) ?> </p>

            <p class="text-center" style="margin-top: 2.5cm;font-size: 16pt;text-decoration: underline;width:16cm">
                Bonafide Certificate
            </p>

            <div style="display:flex;flex-direction:column;margin-top:2cm;width:16cm">
                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;">
                    This is to certify that
                    <b><?= htmlspecialchars($info['student_name'] ?? '-') ?></b>
                    S/o / D/o / W/o
                    <b><?= htmlspecialchars($info['fathers_husbands_name'] ?? '-') ?></b> bearing
                    enrolment no. <?= htmlspecialchars($info['enrollment_no'] ?? '-') ?> is
                    a student of <b><?= htmlspecialchars($info['program'] ?? '-') ?></b> in the
                    batch of
                    <?= htmlspecialchars("20" . $startYr . "-" . $info['passing_year'] ?? '-') ?>.
                </p>
                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.5;">
                    He / She is a Bonafide student of ISBM University.
                </p>
                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.5;">
                    He / She is reliable, sincere, hardworking and bears a good moral character.
                </p>
            </div>

            <div style="width:16cm; margin-top:4cm;">
                <p style="font-size: 14pt;line-height: 1.25;">ISBM University</p>
                <p style="font-size: 14pt;line-height: 1.25;">India</p>
            </div>
        </div>
    </div>
<?php endforeach; ?>