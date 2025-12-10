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
        <div style="margin-top:5.8cm; display: flex; flex-direction:column;">
            <table style="width:17cm;">
                <tr>
                    <td style="border:none !important;padding: 0 !important;font-size:12pt;font-weight:400;text-align: left !important;">S. No:
                        <?= htmlspecialchars($info['doc_no'] ?? $sl_no) ?></td>
                    <td style="border:none !important;padding: 0 !important;font-size:12pt;font-weight:400;text-align: right !important;"> Date:
                        <?= date('d-m-Y') ?> </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="2"
                        style="border:none !important;padding-top: 4cm;font-size: 14pt;font-weight: bold;text-decoration: underline;">
                        TO WHOM SO EVER IT MAY CONCERN</td>
                </tr>
                <tr>
                    <td colspan="2" style="border:none !important;font-size: 14pt;line-height: 1.15;padding-top:1.7cm;text-align: justify !important;text-indent:1.27cm;">
                        This is to certify that <?= htmlspecialchars($info['student_name'] ?? '-') ?>
                        S/o / D/o / W/o <?= htmlspecialchars($info['father_name'] ?? '-') ?> bearing
                        enrolment no. <?= htmlspecialchars($info['enrollment_no'] ?? '-') ?> was
                        a regular student and has completed <?= htmlspecialchars($initials2 ?? 'his / her') ?> LLB course in the year
                        <?= htmlspecialchars($info['exam_session'] ?? '-') ?> with
                        <?= htmlspecialchars($resultData['cDivision'] ?? $info['cDivision'] ?? '-') ?> division.</td>
                </tr>
                <tr>
                    <td colspan="2" style="border:none !important;font-size: 14pt;line-height: 1.15;padding-top:26pt;text-align: justify !important;">
                        School of Law, ISBM University was established in the year 2017. The courses (LLB and BBA LLB) are approved
                        by the Bar Council of India New Delhi. The school of Law, ISBM University follows all norms, rule and
                        regulations prescribed by the Bar Council of India.</td>
                </tr>
            </table>

            <table style="width:17cm; margin-top:3cm;">
                <tr>
                    <td style="border:none !important;"></td>
                    <td style="width:3cm; text-align:center;border:none !important;font-size:12pt;font-weight:400;">School of Law</td>
                </tr>
                <tr>
                    <td style="border:none !important;"></td>
                    <td style="width:3cm; text-align:center;border:none !important;font-size:12pt;font-weight:400;">ISBM University</td>
                </tr>
            </table>

        </div>
    </div>

<?php endforeach; ?>