<?php
// -----------------------------------------
// Extract Passing Year from exam_session
// -----------------------------------------
$lastExamSession = $result_records['exam_session'] ?? '';
$lastRecord = $result_records;

$student_name = $lastRecord['student_name'];
$enrollment_no = $lastRecord['enrollment_no'];
$father_name = $lastRecord['father_name'];
$program = $lastRecord['program'];
$specialization = $lastRecord['stream']; // or specialization
$exam_session = $lastRecord['exam_session'];

$passing_year = '';
if ($lastExamSession) {
    $parts = explode("-", $lastExamSession);
    $passing_year = $parts[1] ?? '';
}
?>

<?php foreach ($transcript_records as $row): ?>
    <div class="d-flex w-100 justify-content-end" style="margin-top:4.3cm;">
        <h4 style="font-size: 12pt;font-weight: bold;margin: auto;">TRANSCRIPT</h4>
    </div>

    <div class="marksheet" style="margin-top:0.6cm;">
        <table class="w-100 text-start">
            <tr>
                <td class="text-start border" style="padding-left: 5px;font-size: 9pt; border-color: #111 !important;font-weight: bold;">Name of Candidate</td>
                <td class="text-start border" style="padding-left: 5px;font-size: 9pt; border-color: #111 !important;"><?php echo $student_name ?></td>
                <td class="text-start border" style="padding-left: 5px;font-size: 9pt; border-color: #111 !important;font-weight: bold;">Enrollment No</td>
                <td class="text-start border" style="padding-left: 5px;font-size: 9pt; border-color: #111 !important;"><?php echo $enrollment_no ?></td>
            </tr>
            <tr>
                <td class="text-start border" style="padding-left: 5px;font-size: 8pt;font-weight: bold; border-color: #111 !important;">Father’s/Husband’s Name</td>
                <td class="text-start border" style="padding-left: 5px;font-size: 9pt; border-color: #111 !important;"><?php echo $father_name ?></td>
                <td class="text-start border" style="padding-left: 5px;font-size: 9pt; border-color: #111 !important;font-weight: bold;">Course</td>
                <td class="text-start border" style="padding-left: 5px;font-size: 9pt; border-color: #111 !important;"><?php echo $program ?></td>
            </tr>
            <tr>
                <td class="text-start border" style="padding-left: 5px;font-size: 9pt; border-color: #111 !important;font-weight: bold;">Passing Year</td>
                <td class="text-start border" style="padding-left: 5px;font-size: 9pt; border-color: #111 !important;"><?php echo $passing_year ?></td>
                <td class="text-start border" style="padding-left: 5px;font-size: 9pt; border-color: #111 !important;font-weight: bold;">Specialization</td>
                <td class="text-start border" style="padding-left: 5px;font-size: 9pt; border-color: #111 !important;"><?php echo $specialization ?: "-" ?></td>
            </tr>
            <tr>
                <td class="text-start border" colspan="4" style="padding-left: 5px;font-size: 9pt; border-color: #111 !important;font-weight: bold;">Medium of Instruction : English</td>
            </tr>
        </table>

        <table class="table table-bordered table-marks mb-0 w-100" style="margin-top:0.6cm;">
            <thead>
                <tr>
                    <th rowspan="2" class="border-start-1" style="font-size: 9pt;font-weight: bold;width: 2cm;">Subject Code</th>
                    <th rowspan="2" style="font-size: 9pt;font-weight: bold;width: 8cm;">Subjects</th>
                    <th colspan="2" style="font-size: 9pt;font-weight: bold;width: 2.1cm;">External<br>Marks</th>
                    <th colspan="2" style="font-size: 9pt;font-weight: bold;width: 2.1cm;">Internal<br>Marks</th>
                    <th rowspan="2" style="font-size: 9pt;font-weight: bold;width: 1.1cm;">Total Marks OBTD</th>
                    <th rowspan="2" style="font-size: 9pt;font-weight: bold;width: 1.7cm;">Result/Remark</th>
                </tr>
                <tr>
                    <th style="font-size: 8pt;font-weight: bold;">MAX</th>
                    <th style="font-size: 8pt;font-weight: bold;">OBTD</th>
                    <th style="font-size: 8pt;font-weight: bold;">MAX</th>
                    <th style="font-size: 8pt;font-weight: bold;">OBTD</th>
                </tr>
            </thead>

            <tbody>
                <?php for ($i = 1; $i < 14; $i++): ?>
                    <?php if (!empty(${'sub' . $i . '_code'})): ?>

                        <?php
                        // Handle font styling
                        $classValue = '';
                        $fontSizeValue = '';

                        $longSubs = ["HSD-MEPS801", "HSD-MEPS701", "HSD-MERBL701", "HSD-MERBL801", "HSD-MEPBL501", "HSD-MERBL501", "HSD-ETCPS601", "HSD-ETCPBL601", "HSD-ETCABL601"];
                        if (in_array(${'sub' . $i . '_code'}, $longSubs)) {
                            $fontSizeValue = 'font-size: 9pt !important';
                        }

                        $subjectName = ${'sub' . $i . '_name'};

                        if (preg_match('/[\p{Devanagari}]/u', $subjectName)) {
                            $classValue = 'font-family: Mangal, sans-serif !important; font-size: 8pt !important;';
                        } elseif (
                            $subjectName === "Philosophical & Conceptual Foundation of Research Methodology" ||
                            $subjectName === "System Development Project (System Design & Implementation)"
                        ) {
                            $classValue = 'font-family: calibri, sans-serif !important; font-size: 9pt !important;';
                        } elseif (strlen($subjectName) > 70) {
                            $classValue = 'font-family: calibri, sans-serif !important; font-size: 8pt !important;';
                        } elseif (strlen($subjectName) > 55) {
                            $classValue = 'font-family: calibri, sans-serif !important; font-size: 9.5pt !important;';
                        } else {
                            $classValue = 'font-family: calibri, sans-serif !important;';
                        }
                        ?>

                        <tr>
                            <td class="column-47 text-start ps-1 font-10pt border-start-1" style="<?php echo $fontSizeValue; ?>">
                                <?= ${'sub' . $i . '_code'} ?>
                            </td>

                            <td class="column-47 text-start ps-1 font-10pt" style="<?= $classValue ?>">
                                <?= $subjectName ?>
                            </td>

                            <td class="column-47 text-center font-10pt"><?= ${'sub' . $i . '_ext_max'} ?></td>
                            <td class="column-47 text-center font-10pt"><?= ${'sub' . $i . '_ext_obt'} ?></td>
                            <td class="column-47 text-center font-10pt"><?= ${'sub' . $i . '_int_max'} ?></td>
                            <td class="column-47 text-center font-10pt"><?= ${'sub' . $i . '_int_obt'} ?></td>
                            <td class="column-47 text-center font-10pt"><?= ${'sub' . $i . '_total_obt'} ?></td>
                            <td class="column-47 text-center font-10pt"><?= ${'sub' . $i . '_result_Remark'} ?></td>
                        </tr>

                    <?php endif; ?>
                <?php endfor; ?>
            </tbody>
        </table>

    </div>
<?php endforeach; ?>