<?php
session_start();
if (!isset($_SESSION['employee_name']) && !isset($_SESSION['session_id'])) {
    echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
    exit();
}

require 'dbFiles/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$idsList = $_GET['param'] ?? '';
$idsList = preg_replace('/[^0-9,]/', '', $idsList);

$sql = "SELECT * FROM document_requests 
        WHERE id IN ($idsList) 
        ORDER BY enrollment_no, print_flag ASC";

$transcript = mysqli_query($conn, $sql);

if (!$transcript || mysqli_num_rows($transcript) == 0) {
    http_response_code(404);
    echo 'No transcripts found for the provided IDs.';
    exit;
}

$grouped = [];

while ($row = mysqli_fetch_assoc($transcript)) {

    $enroll = $row['enrollment_no'];

    // Initialize container
    if (!isset($grouped[$enroll])) {
        $grouped[$enroll] = [
            "document_requests" => $row,
            "student_info"       => [],
            "results"            => []
        ];
    }

    // Fetch ALL RESULT ROWS
    $templateSql    = "SELECT * FROM results WHERE enrollment_no = '$enroll' ORDER BY period ASC";
    $templateResult = mysqli_query($conn, $templateSql);

    if (!$templateResult || mysqli_num_rows($templateResult) == 0) {
        continue;
    }

    // Overall totals per enrollment
    $grand_total      = 0;
    $grand_total_obt  = 0;

    while ($resultData = mysqli_fetch_assoc($templateResult)) {

        // Build student info ONCE
        if (empty($grouped[$enroll]['student_info'])) {

            $passing_year = "";

            if (!empty($resultData['exam_session'])) {
                $parts        = explode("-", $resultData['exam_session']);
                $passing_year = $parts[1] ?? "";
            }

            $grouped[$enroll]['student_info'] = [
                'student_name'   => $resultData['student_name'] ?? '',
                'enrollment_no'  => $resultData['enrollment_no'] ?? '',
                'father_name'    => $resultData['father_name'] ?? '',
                'program'        => $resultData['program'] ?? '',
                'specialization' => $resultData['stream'] ?? '',
                'exam_session'   => $resultData['exam_session'] ?? '',
                'passing_year'   => $passing_year ?: '-',
            ];
        }

        $period = $resultData['period'];

        if (!isset($grouped[$enroll]['results'][$period])) {
            $grouped[$enroll]['results'][$period] = [
                'subjects' => [],
                'totals'   => []
            ];
        }

        // Push subjects for this period
        for ($i = 1; $i <= 13; $i++) {
            if (!empty($resultData['sub' . $i . '_code'])) {
                $grouped[$enroll]['results'][$period]['subjects'][] = [
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
        $grouped[$enroll]['results'][$period]['totals'] = [
            'Ext_max_total'     => $resultData['Ext_max_total'],
            'Ext_max_obt_total' => $resultData['Ext_max_obt_total'],
            'int_max_total'     => $resultData['int_max_total'],
            'int_max_obt_total' => $resultData['int_max_obt_total'],
            'total_obt'         => $resultData['total_obt']
        ];

        // Accumulate grand totals for this enrollment
        $grand_total     += (float)$resultData['Ext_max_total'] + (float)$resultData['int_max_total'];
        $grand_total_obt += (float)$resultData['total_obt'];
    }

    // After processing all result rows for this enrollment
    if ($grand_total > 0) {
        $percentage = ($grand_total_obt / $grand_total) * 100;
    } else {
        $percentage = 0;
    }

    // Append totals to existing student_info (don’t overwrite it)
    $grouped[$enroll]['student_info']['grand_total']      = $grand_total;
    $grouped[$enroll]['student_info']['grand_total_obt']  = $grand_total_obt;
    $grouped[$enroll]['student_info']['percentage']       = $percentage;
    $grouped[$enroll]['student_info']['division']         = 'First'; // or compute based on % if you want
    $grouped[$enroll]['student_info']['doi']              = date('d/m/Y');
}

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

/**
 * PAGINATION LOGIC:
 * For each enrollment, we chunk the periods into "pages" such that:
 * (period-header-row + subjects + grand-total-row) are counted,
 * and we cap at 48 rows (first page) and 52 rows (other pages).
 */
$maxRowsFirstPage  = 48; // with student info
$maxRowsOtherPages = 52; // without student info on later pages

foreach ($grouped as $enroll => $data) {
    $resultsByPeriod = $data['results']; // period => ['subjects', 'totals']

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

    // Attach pages back to grouped
    $grouped[$enroll]['pages'] = $pages;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ISBM MarkMate</title>
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/perfect-scrollbar/css/perfect-scrollbar.css" />
    <link rel="shortcut icon" href="assets/images/logo-mini.png" />

    <style>
        /* Screen + PDF basic layout for transcript page */
        .transcript-container {
            width: 21cm;
            /* you can remove height to avoid extra blank pages in PDF */
            height: 29.7cm;
            padding: 0.8cm 2cm 2.1cm 2cm;
            border: 3px solid #06355f;
            font-family: calibri, sans-serif !important;
            background: url(../images/bc.jpg);
            background-position: center;
            background-size: cover;
            position: relative;
            /* for absolute footer */
            box-sizing: border-box;
            margin: 0 auto 1cm auto;
        }

        .marksheet {
            margin-top: 3.4cm;
        }

        .coe-sign {
            position: absolute;
            right: 2.5cm;
            bottom: 1.3cm;
            font-weight: bold;
            font-size: 9pt;
            width: 17cm !important;
            text-align: end;
        }

        #scrollableView {
            width: 21cm !important;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <?php include 'assets/partials/_navbar.php' ?>
        <div class="container-fluid page-body-wrapper p-0">
            <?php include 'assets/partials/_sidebar.php' ?>
            <div class="main-panel position-relative">
                <div class="content-wrapper">
                    <div class="card overflow-hidden p-4" style="height: 80vh">
                        <div class="row justify-content-between mb-4 mx-0">
                            <h4 class="card-title" style="">Transcripts</h4>
                            <button type="button" class="btn btn-primary btn-icon-text print">
                                Print
                                <i class="ti-printer btn-icon-append"></i>
                            </button>
                        </div>
                        <div class="card-body p-0" id="scrollableView">
                            <?php foreach ($grouped as $enroll => $data):
                                $info  = $data['student_info'];
                                $pages = $data['pages'] ?? [];
                                $lastPageIndex = count($pages) - 1;
                            ?>
                                <?php foreach ($pages as $pageIndex => $resultsByPeriod): ?>
                                    <div class="transcript-container" id="<?= $enroll . '-' . ($pageIndex + 1) ?>">
                                        <div class="marksheet">

                                            <?php if ($pageIndex === 0): ?>
                                                <!-- Heading on FIRST PAGE -->
                                                <h4 class="text-center w-100"
                                                    style="font-family: calibri, sans-serif !important;font-size: 12pt;font-weight: bold;width: 17cm !important;text-align: center !important;">
                                                    TRANSCRIPT
                                                </h4>

                                                <!-- Student Info Table ONLY ON FIRST PAGE -->
                                                <table class="w-100 text-start"
                                                    style="margin-top:0.5cm;margin-bottom:0.5cm;width:17cm;">
                                                    <tr>
                                                        <td class="text-start border"
                                                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                                                            Name of Candidate
                                                        </td>
                                                        <td class="text-start border"
                                                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;">
                                                            <?= $info['student_name'] ?>
                                                        </td>

                                                        <td class="text-start border"
                                                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                                                            Enrollment No
                                                        </td>
                                                        <td class="text-start border"
                                                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;">
                                                            <?= $info['enrollment_no'] ?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start border"
                                                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:8pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                                                            Father’s/Husband’s Name
                                                        </td>
                                                        <td class="text-start border"
                                                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;">
                                                            <?= $info['father_name'] ?>
                                                        </td>

                                                        <td class="text-start border"
                                                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                                                            Course
                                                        </td>
                                                        <td class="text-start border"
                                                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;">
                                                            <?= $info['program'] ?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start border"
                                                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                                                            Passing Year
                                                        </td>
                                                        <td class="text-start border"
                                                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;">
                                                            <?= $info['passing_year'] ?>
                                                        </td>

                                                        <td class="text-start border"
                                                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;font-weight:bold;">
                                                            Specialization
                                                        </td>
                                                        <td class="text-start border"
                                                            style="height:0.5cm;padding:0 0 0 5px;font-family:calibri,sans-serif !important;font-size:9pt;border-color:#111 !important;vertical-align:bottom !important;">
                                                            <?= $info['specialization'] ?: "-" ?>
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
                                            <table class="table table-bordered table-marks mb-0 w-100"
                                                style="width:17cm;">
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
                                                            <th colspan="2"
                                                                style="font-family:calibri,sans-serif !important;font-size:8pt;font-weight:bold;">
                                                                External<br>Marks
                                                            </th>
                                                            <th colspan="2"
                                                                style="font-family:calibri,sans-serif !important;font-size:8pt;font-weight:bold;">
                                                                Internal<br>Marks
                                                            </th>
                                                            <th rowspan="2"
                                                                style="font-family:calibri,sans-serif !important;font-size:8pt;font-weight:bold;">
                                                                Total<br>Marks<br>OBTD
                                                            </th>
                                                            <th rowspan="2"
                                                                style="font-family:calibri,sans-serif !important;font-size:8pt;font-weight:bold;">
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
                                                    <?php foreach ($resultsByPeriod as $period => $pdata): ?>
                                                        <?php $subjects = $pdata['subjects']; ?>
                                                        <?php $totals   = $pdata['totals']; ?>

                                                        <!-- Semester / Period Heading -->
                                                        <tr>
                                                            <td colspan="8" class="text-start"
                                                                style="font-family:calibri,sans-serif !important;font-size:8pt;padding-left:5px;border:1px solid #111 !important;">
                                                                <?= formatSemester($period) ?>
                                                            </td>
                                                        </tr>

                                                        <!-- Subject rows -->
                                                        <?php foreach ($subjects as $sub): ?>
                                                            <tr>
                                                                <td class="text-start"
                                                                    style="font-family:calibri,sans-serif !important;font-size:8pt;padding-left:5px;border:1px solid #111 !important;">
                                                                    <?= $sub['sub_code'] ?>
                                                                </td>
                                                                <td class="text-start"
                                                                    style="font-family:calibri,sans-serif !important;font-size:8pt;padding-left:5px;border:1px solid #111 !important;">
                                                                    <?= $sub['sub_name'] ?>
                                                                </td>

                                                                <td class="text-center"
                                                                    style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;">
                                                                    <?= $sub['sub_ext_max'] ?>
                                                                </td>
                                                                <td class="text-center"
                                                                    style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;">
                                                                    <?= $sub['sub_ext_obt'] ?>
                                                                </td>

                                                                <td class="text-center"
                                                                    style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;">
                                                                    <?= $sub['sub_int_max'] ?>
                                                                </td>
                                                                <td class="text-center"
                                                                    style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;">
                                                                    <?= $sub['sub_int_obt'] ?>
                                                                </td>

                                                                <td class="text-center"
                                                                    style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;">
                                                                    <?= $sub['sub_total_obt'] ?>
                                                                </td>
                                                                <td class="text-start"
                                                                    style="font-family:calibri,sans-serif !important;font-size:8pt;padding-left:5px;border:1px solid #111 !important;">
                                                                    <?= $sub['sub_result_remark'] ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>

                                                        <!-- Grand Total for that period -->
                                                        <tr>
                                                            <td colspan="2" class="text-center"
                                                                style="font-weight:bold;font-family:calibri,sans-serif !important;font-size:8pt;padding-left:5px;border:1px solid #111 !important;">
                                                                <div>Grand Total</div>
                                                            </td>
                                                            <td class="text-center"
                                                                style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;">
                                                                <?= $totals['Ext_max_total'] ?>
                                                            </td>
                                                            <td class="text-center"
                                                                style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;">
                                                                <?= $totals['Ext_max_obt_total'] ?>
                                                            </td>
                                                            <td class="text-center"
                                                                style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;">
                                                                <?= $totals['int_max_total'] ?>
                                                            </td>
                                                            <td class="text-center"
                                                                style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;">
                                                                <?= $totals['int_max_obt_total'] ?>
                                                            </td>
                                                            <td class="text-center"
                                                                style="font-weight:bold;font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;">
                                                                <?= $totals['total_obt'] ?>
                                                            </td>
                                                            <td class="text-center"
                                                                style="font-family:calibri,sans-serif !important;font-size:8pt;border:1px solid #111 !important;">
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
                                                            style="font-weight:bold;padding:0 !important;border:none !important;width:2.5cm;font-size:10pt;">
                                                            Grand Total
                                                        </td>
                                                        <td class="text-start border"
                                                            style="font-weight:bold;padding:0 !important;border:none !important;font-size:10pt;">
                                                            : <?= $info['grand_total_obt'] ?>/<?= $info['grand_total'] ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start border"
                                                            style="font-weight:bold;padding:0 !important;border:none !important;width:2.5cm;font-size:10pt;">
                                                            Percentage
                                                        </td>
                                                        <td class="text-start border"
                                                            style="font-weight:bold;padding:0 !important;border:none !important;font-size:10pt;">
                                                            : <?= number_format($info['percentage'], 2) ?>%
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start border"
                                                            style="font-weight:bold;padding:0 !important;border:none !important;width:2.5cm;font-size:10pt;">
                                                            Division
                                                        </td>
                                                        <td class="text-start border"
                                                            style="font-weight:bold;padding:0 !important;border:none !important;font-size:10pt;">
                                                            : <?= $info['division'] ?? '' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start border"
                                                            style="font-weight:bold;padding:0 !important;border:none !important;width:2.5cm;font-size:10pt;">
                                                            Date of Issue
                                                        </td>
                                                        <td class="text-start border"
                                                            style="font-weight:bold;padding:0 !important;border:none !important;font-size:10pt;">
                                                            : <?= $info['doi'] ?? '' ?>
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
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>
                <!-- content-wrapper ends -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                            Copyright © 2021.
                            All rights reserved.
                        </span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- PerfectScrollbar JS -->
    <?php include 'assets/partials/plugins_js.html'; ?>
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var scrollbar1 = document.getElementById("scrollableView");
        if (scrollbar1) {
            new PerfectScrollbar(scrollbar1, {
                wheelPropagation: false
            });
        }

        $(".print").on("click", function() {
            generatePDF();
        });

        function generatePDF() {
            var scrollableViewContent = $("#scrollableView").html();
            var page = window.location.href;
            var pageSegments = page.split('/');
            var idsArray = (pageSegments[3] || '').split("=");
            var ids = idsArray[1] || '';

            $.ajax({
                type: "POST",
                url: "generate_pdf_transcript.php",
                data: {
                    content: scrollableViewContent
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // updatePrinted(ids);
                        alert("PDF generated successfully. Click here to view.");
                        window.open(response.pdf_path, '_blank');
                    } else {
                        console.error(response.message);
                        alert('Failed to generate PDF: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while generating the PDF');
                }
            });
        }

        function updatePrinted(ids) {
            $.ajax({
                type: "POST",
                url: "update_printed.php",
                data: {
                    ids: ids
                },
                dataType: "json",
                success: function(response) {
                    if (!response.success) {
                        console.error(response.message);
                        alert('Failed to generate PDF: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while updating print flags');
                }
            });
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>