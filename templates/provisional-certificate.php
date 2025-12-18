<?php
global $conn;

if (empty($data) || !is_array($data)) {
    return;
}
?>

<?php foreach ($data as $info):
    $sl_no = random_int(10000000, 99999999);
?>

    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>" style="padding-left: 2.8cm !important;padding-right: 2.8cm !important;font-family: 'Times New Roman', Times, serif !important;">
        <div style="margin-top:4.2cm; display: flex; flex-direction:column;">
            <table style="width: 15.4cm;">
                <tr>
                    <td style="border:none !important;font-size:12pt;font-weight:400;text-align: left !important;">S. No:
                        <?= htmlspecialchars($info['doc_no'] ?? $sl_no) ?></td>
                    <td style="border:none !important;font-size:12pt;font-weight:400;text-align: right !important;"> Date:
                        <?= date('d-m-Y') ?> </td>
                </tr>
            </table>

            <p style="margin-top: 1.4cm;font-size: 12pt;width: 15.4cm">
                Enrollment No:- <?= htmlspecialchars($info['enrollment_no'] ?? '-') ?>
            </p>

            <p class="text-center"
                style="margin-top: 2.2cm;font-size: 18pt;font-weight: bold;text-decoration: underline;width: 15.4cm">
                PROVISIONAL CERTIFICATE
            </p>

            <p style="font-size: 16pt;line-height:  calc(2 * 1em + 5pt);margin-top:1.7cm;width: 15.4cm;word-spacing: 5px;text-indent:1.27cm;">It has been certified that Mr. / Mrs. /
                Ms. <b><?= htmlspecialchars($info['student_name'] ?? '-') ?></b> S/o /
                D/o / W/o. <?= htmlspecialchars($info['fathers_husbands_name'] ?? '-') ?> is qualified to
                <?php
                $program = $info['program'] ?? '-';

                // Check if program starts with "Diploma" (case-insensitive)
                if (stripos($program, 'Diploma') === 0) {
                    echo "receive the " . htmlspecialchars($program) . ", having";
                } else {
                    echo "receive the Degree of " . htmlspecialchars($program) . ", having";
                }
                ?>
                successfully passed the examination in the year
                <?= htmlspecialchars($info['passing_year'] ?? '-') ?> and
                has been placed in <?= htmlspecialchars($info['cDivision'] ?? '-') ?> Division.</p>

            <p style="text-align:right;font-size:14pt;font-weight:400;margin-top:4.8cm;width: 15.4cm;">
                Controller of Examinations
            </p>
        </div>
    </div>

<?php endforeach; ?>