<?php
// templates/transcripts.php

global $conn;

// Ensure we have the enrollment number
$enroll = isset($enrollment_no) ? $enrollment_no : null;
if (!$enroll) {
    // Nothing to render
    return;
}

// Fetch all result rows for this enrollment
$enrollEscaped = mysqli_real_escape_string($conn, $enroll);
$templateSql = "SELECT * FROM results 
                WHERE enrollment_no = '{$enrollEscaped}'
                ORDER BY CAST(SUBSTRING_INDEX(period, ' ', -1) AS UNSIGNED) ASC";
$templateResult = mysqli_query($conn, $templateSql);

if (!$templateResult || mysqli_num_rows($templateResult) == 0) {
    // No results for this enrollment, nothing to print
    return;
}

// Build student info + per-period data
$student_info   = [];
$resultsByPeriod = [];

// Overall totals per enrollment
$grand_total     = 0;
$grand_total_obt = 0;

while ($resultData = mysqli_fetch_assoc($templateResult)) {

    // Build student info ONCE
    if (empty($student_info)) {
        $passing_year = "";

        if (!empty($resultData['exam_session'])) {
            $parts        = explode("-", $resultData['exam_session']);
            $passing_year = $parts[1] ?? "";
        }

        $student_info = [
            'student_name'   => $resultData['student_name'] ?? '',
            'enrollment_no'  => $resultData['enrollment_no'] ?? '',
            'father_name'    => $resultData['father_name'] ?? '',
            'program'        => $resultData['program_print_name'] ?? '',
            'specialization' => in_array($resultData['program_print_name'], [
                "Masters in Business Administration",
                "Executive Masters In Business Administration"
            ])
                ? ($resultData['stream'] ?? '-')
                : '-',
            'exam_session'   => $resultData['exam_session'] ?? '',
            'passing_year'   => $passing_year ?: '-',
        ];
    }

    $period = $resultData['period'];

    if (!isset($resultsByPeriod[$period])) {
        $resultsByPeriod[$period] = [
            'subjects' => [],
            'totals'   => []
        ];
    }

    // Push subjects for this period
    for ($i = 1; $i <= 13; $i++) {
        if (!empty($resultData['sub' . $i . '_code'])) {
            $resultsByPeriod[$period]['subjects'][] = [
                'sub_code'          => $resultData['sub' . $i . '_code'],
                'sub_name'          => $resultData['sub' . $i . '_name'],
                'sub_ext_max'       => $resultData['sub' . $i . '_ext_max'],
                'sub_ext_obt'       => $resultData['sub' . $i . '_ext_obt'],
                'sub_int_max'       => $resultData['sub' . $i . '_int_max'],
                'sub_int_obt'       => $resultData['sub' . $i . '_int_obt'],
                'sub_total_obt'     => $resultData['sub' . $i . '_total_obt'],
                'sub_result_remark' => $resultData['sub' . $i . '_result_Remark'],
            ];
        }
    }

    // Per-period totals
    $resultsByPeriod[$period]['totals'] = [
        'Ext_max_total'     => $resultData['Ext_max_total'],
        'Ext_max_obt_total' => $resultData['Ext_max_obt_total'],
        'int_max_total'     => $resultData['int_max_total'],
        'int_max_obt_total' => $resultData['int_max_obt_total'],
        'total_obt'         => $resultData['total_obt']
    ];

    // Accumulate grand totals for this enrollment
    $grand_total     += (float)$resultData['Ext_max_total'] + (float)$resultData['int_max_total'];
    $grand_total_obt += (float)$resultData['total_obt'];
    $student_info['division']         = $resultData['cDivision'] ?? ''; // or compute based on % if you want
}

// After processing all result rows for this enrollment
$percentage = $grand_total > 0 ? ($grand_total_obt / $grand_total) * 100 : 0;

// Append totals to student_info
$student_info['grand_total']      = $grand_total;
$student_info['grand_total_obt']  = $grand_total_obt;
$student_info['percentage']       = $percentage;
$student_info['doi']              = date('d/m/Y');

if (!function_exists('formatSemester')) {
    function formatSemester($period)
    {
        preg_match('/(\d+)/', $period, $matches);
        if (!$matches) return $period;

        $num = (int)$matches[1];

        $suffix = "th";
        if ($num % 10 == 1 && $num % 100 != 11) $suffix = "st";
        elseif ($num % 10 == 2 && $num % 100 != 12) $suffix = "nd";
        elseif ($num % 10 == 3 && $num % 100 != 13) $suffix = "rd";

        return "{$num}{$suffix} Semester";
    }
}

// Paging logic: split resultsByPeriod into pages
$maxRowsFirstPage  = 52; // with student info
$maxRowsOtherPages = 62; // without student info on later pages

$pages           = [];
$currentPage     = [];
$currentRowCount = 0;

// current page capacity (first page uses smaller limit)
$currentPageMaxRows = $maxRowsFirstPage;

foreach ($resultsByPeriod as $period => $pdata) {
    $subjects = $pdata['subjects'] ?? [];
    $totals   = $pdata['totals'] ?? [];

    // rows for this period: 1 (sem heading) + N subjects + 1 (grand total)
    $rowsInPeriod = 1 + count($subjects) + 1;

    // If adding this period would exceed the current page's limit, start a new page
    if ($currentRowCount > 0 && ($currentRowCount + $rowsInPeriod) > $currentPageMaxRows) {
        // close current page
        $pages[] = $currentPage;

        // start new page
        $currentPage      = [];
        $currentRowCount  = 0;

        // from now on, use the "other pages" capacity
        $currentPageMaxRows = $maxRowsOtherPages;
    }

    // Add this period to current page
    $currentPage[$period] = [
        'subjects' => $subjects,
        'totals'   => $totals,
    ];
    $currentRowCount += $rowsInPeriod;
}

// Push last page if it has any periods
if (!empty($currentPage)) {
    $pages[] = $currentPage;
}

$lastPageIndex = count($pages) - 1;
?>

<?php foreach ($pages as $pageIndex => $periodsOnPage): ?>
    <div class="transcript-container" id="<?= htmlspecialchars($enroll . '-' . ($pageIndex + 1)) ?>">
        <div class="marksheet">

            <?php if ($pageIndex === 0): ?>
                <!-- Heading on FIRST PAGE -->
                <h4 class="text-center w-100"
                    style="font-family: calibri, sans-serif !important;font-size: 12pt;font-weight: bold;width: 17cm !important;text-align: center !important;">
                    TRANSCRIPT
                </h4>

                <!-- Student Info Table ONLY ON FIRST PAGE -->
                <table class="w-100 text-start" style="margin-top:0.5cm;margin-bottom:0.5cm;width:17cm;">
                    <tr>
                        <td class="text-start border"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                            Name of Candidate
                        </td>
                        <td class="text-start border"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;">
                            <?= htmlspecialchars($student_info['student_name']) ?>
                        </td>

                        <td class="text-start border"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                            Enrollment No
                        </td>
                        <td class="text-start border"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;">
                            <?= htmlspecialchars($student_info['enrollment_no']) ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-start border"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:8pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                            Father’s/Husband’s Name
                        </td>
                        <td class="text-start border"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;">
                            <?= htmlspecialchars($student_info['father_name']) ?>
                        </td>

                        <td class="text-start border"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                            Course
                        </td>
                        <td class="text-start border"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;">
                            <?= htmlspecialchars($student_info['program']) ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-start border"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                            Passing Year
                        </td>
                        <td class="text-start border"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;">
                            <?= htmlspecialchars($student_info['passing_year']) ?>
                        </td>

                        <td class="text-start border"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                            Specialization
                        </td>
                        <td class="text-start border"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;">
                            <?= htmlspecialchars($student_info['specialization'] ?: "-") ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-start border" colspan="4"
                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                            Medium of Instruction : English
                        </td>
                    </tr>
                </table>
            <?php endif; ?>

            <!-- SUBJECT TABLE: table is on EVERY PAGE, thead ONLY on PAGE 1 -->
            <table class="table table-bordered table-marks mb-0 w-100" style="width:17cm;">
                <?php if ($pageIndex === 0): ?>
                    <thead>
                        <tr>
                            <th rowspan="2"
                                style="font-family:calibri,sans-serif !important;font-size:8pt;font-weight:bold;width:2cm;border:1px solid #111 !important;">
                                Subject Code
                            </th>
                            <th rowspan="2" class="text-start"
                                style="font-family:calibri,sans-serif !important;font-size:8pt;font-weight:bold;width:8cm;padding-left: 5px">
                                Subjects
                            </th>
                            <th colspan="2" style="font-family:calibri,sans-serif !important;font-size:8pt;font-weight:bold;">
                                External<br>Marks
                            </th>
                            <th colspan="2" style="font-family:calibri,sans-serif !important;font-size:8pt;font-weight:bold;">
                                Internal<br>Marks
                            </th>
                            <th rowspan="2" style="font-family:calibri,sans-serif !important;font-size:8pt;font-weight:bold;">
                                Total<br>Marks<br>OBTD
                            </th>
                            <th rowspan="2" style="font-family:calibri,sans-serif !important;font-size:8pt;font-weight:bold;">
                                Result/<br>Remark
                            </th>
                        </tr>
                        <tr>
                            <th style="font-family:calibri,sans-serif !important;font-size:7.5pt;font-weight:bold;">
                                MAX
                            </th>
                            <th style="font-family:calibri,sans-serif !important;font-size:7.5pt;font-weight:bold;">
                                OBTD
                            </th>
                            <th style="font-family:calibri,sans-serif !important;font-size:7.5pt;font-weight:bold;">
                                MAX
                            </th>
                            <th style="font-family:calibri,sans-serif !important;font-size:7.5pt;font-weight:bold;">
                                OBTD
                            </th>
                        </tr>
                    </thead>
                <?php endif; ?>

                <tbody>
                    <?php foreach ($periodsOnPage as $period => $pdata): ?>
                        <?php $subjects = $pdata['subjects']; ?>
                        <?php $totals   = $pdata['totals']; ?>

                        <!-- Semester / Period Heading -->
                        <tr>
                            <td colspan="8" class="text-start"
                                style="font-family:calibri,sans-serif !important;font-size:8pt;padding-left:5px;border:1px solid #111 !important;line-height: 0.35cm;">
                                <?= htmlspecialchars(formatSemester($period)) ?>
                            </td>
                        </tr>

                        <!-- Subject rows -->
                        <?php foreach ($subjects as $sub):
                            if (preg_match('/[\p{Devanagari}]/u', $sub['sub_name'])) {
                                $classValue = 'Mangal, sans-serif !important';
                            } else {
                                $classValue = 'calibri, sans-serif !important';
                            }
                        ?>
                            <tr>
                                <td class="text-start"
                                    style="font-family:calibri,sans-serif !important;font-size:8pt;padding-left:5px;border:1px solid #111 !important;line-height: 0.35cm;">
                                    <?= htmlspecialchars($sub['sub_code']) ?>
                                </td>
                                <td class="text-start"
                                    style="font-family:<?php echo  $classValue; ?>;font-size:8pt;padding-left:5px;border:1px solid #111 !important;line-height: 0.35cm;">
                                    <?= htmlspecialchars($sub['sub_name']) ?>
                                </td>

                                <td class="text-center"
                                    style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;line-height: 0.35cm;">
                                    <?= htmlspecialchars($sub['sub_ext_max']) ?>
                                </td>
                                <td class="text-center"
                                    style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;line-height: 0.35cm;">
                                    <?= htmlspecialchars($sub['sub_ext_obt']) ?>
                                </td>

                                <td class="text-center"
                                    style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;line-height: 0.35cm;">
                                    <?= htmlspecialchars($sub['sub_int_max']) ?>
                                </td>
                                <td class="text-center"
                                    style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;line-height: 0.35cm;">
                                    <?= htmlspecialchars($sub['sub_int_obt']) ?>
                                </td>

                                <td class="text-center"
                                    style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;line-height: 0.35cm;">
                                    <?= htmlspecialchars($sub['sub_total_obt']) ?>
                                </td>
                                <td class="text-start"
                                    style="font-family:calibri,sans-serif !important;font-size:8pt;padding-left:5px;border:1px solid #111 !important;line-height: 0.35cm;">
                                    <?= htmlspecialchars($sub['sub_result_remark']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Grand Total for that period -->
                        <tr>
                            <td colspan="2" class="text-center"
                                style="font-weight:bold;font-family:calibri,sans-serif !important;font-size:8pt;padding-left:5px;border:1px solid #111 !important;line-height: 0.35cm;">
                                <div>Grand Total</div>
                            </td>
                            <td class="text-center"
                                style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;line-height: 0.35cm;">
                                <?= htmlspecialchars($totals['Ext_max_total']) ?>
                            </td>
                            <td class="text-center"
                                style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;line-height: 0.35cm;">
                                <?= htmlspecialchars($totals['Ext_max_obt_total']) ?>
                            </td>
                            <td class="text-center"
                                style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;line-height: 0.35cm;">
                                <?= htmlspecialchars($totals['int_max_total']) ?>
                            </td>
                            <td class="text-center"
                                style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;line-height: 0.35cm;">
                                <?= htmlspecialchars($totals['int_max_obt_total']) ?>
                            </td>
                            <td class="text-center"
                                style="font-weight:bold;font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;line-height: 0.35cm;">
                                <?= htmlspecialchars($totals['total_obt']) ?>
                            </td>
                            <td class="text-center"
                                style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;line-height: 0.35cm;">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($pageIndex === $lastPageIndex): ?>
                <!-- FINAL SUMMARY ONLY ON LAST PAGE -->
                <table class="w-100 text-start" style="margin-top:0.1cm;">
                    <tr>
                        <td class="text-start border"
                            style="font-weight:bold;padding:0 !important;padding-top:0.1cm !important;border:none !important;width:2.5cm;font-size:10pt;">
                            Grand Total
                        </td>
                        <td class="text-start border"
                            style="font-weight:bold;padding:0 !important;padding-top:0.1cm !important;border:none !important;font-size:10pt;">
                            :
                            <?= htmlspecialchars($student_info['grand_total_obt']) ?>/<?= htmlspecialchars($student_info['grand_total']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start border"
                            style="font-weight:bold;padding:0 !important;padding-top:0.1cm !important;border:none !important;width:2.5cm;font-size:10pt;">
                            Percentage
                        </td>
                        <td class="text-start border"
                            style="font-weight:bold;padding:0 !important;padding-top:0.1cm !important;border:none !important;font-size:10pt;">
                            : <?= number_format($student_info['percentage'], 2) ?>%
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start border"
                            style="font-weight:bold;padding:0 !important;padding-top:0.1cm !important;border:none !important;width:2.5cm;font-size:10pt;">
                            Division
                        </td>
                        <td class="text-start border"
                            style="font-weight:bold;padding:0 !important;padding-top:0.1cm !important;border:none !important;font-size:10pt;">
                            : <?= htmlspecialchars($student_info['division'] ?? '') ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-start border"
                            style="font-weight:bold;padding:0 !important;padding-top:0.1cm !important;border:none !important;width:2.5cm;font-size:10pt;">
                            Date of Issue
                        </td>
                        <td class="text-start border"
                            style="font-weight:bold;padding:0 !important;padding-top:0.1cm !important;border:none !important;font-size:10pt;">
                            : <?= htmlspecialchars($student_info['doi'] ?? '') ?>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>

        </div>

        <?php if ($pageIndex === $lastPageIndex): ?>
            <p class="coe-sign text-end" style='font-size:11pt;font-family: "Times New Roman", Times, serif !important;'>
                Controller of Examination
            </p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>