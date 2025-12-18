<?php
global $conn;

if (empty($data) || !is_array($data)) {
    return;
}
?>

<?php foreach ($data as $info): ?>
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>" style="font-family: 'Times New Roman', Times, serif !important;">
        <div style="margin-top:5.2cm; display: flex; flex-direction:column;">
            <p style="font-size: 14pt;font-weight: 400;width:17cm;text-align:right;">
                Date: <?= htmlspecialchars($info['print_date'] ?? date('d-m-Y')) ?> </p>

            <p class="text-center" style="margin-top: 2cm;font-size: 16pt;text-decoration: underline;width:17cm">
                Medium of Instruction
            </p>

            <div style="display:flex;flex-direction:column;margin-top:1.5cm;width:17cm">
                <p style="font-size: 13pt;margin-bottom: 10pt;line-height: 1.25;text-align: justify !important;">
                    This is to certify that <?= htmlspecialchars($info['student_name'] ?? '-') ?>, was
                    a student of <?= htmlspecialchars($info['program'] ?? '-') ?>, bearing the
                    enrolment number <?= htmlspecialchars($info['enrollment_no'] ?? '-') ?> has
                    completed the course successfully.
                </p>
                <p style="font-size: 13pt;line-height: 1.25;">
                    The medium of instruction under which he / she carried out the course was English.
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