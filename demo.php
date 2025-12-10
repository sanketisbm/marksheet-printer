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

$idsList = $_GET['param'];
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
            "student_info" => [],
            "results" => []
        ];
    }

    // Fetch ALL RESULT ROWS
    $templateSql = "SELECT * FROM results WHERE enrollment_no = '$enroll' ORDER BY period ASC";
    $templateResult = mysqli_query($conn, $templateSql);

    if (!$templateResult || mysqli_num_rows($templateResult) == 0) {
        continue;
    }

    while ($resultData = mysqli_fetch_assoc($templateResult)) {

        // Build student info ONCE
        if (empty($grouped[$enroll]['student_info'])) {

            $passing_year = "";

            if (!empty($resultData['exam_session'])) {
                $parts = explode("-", $resultData['exam_session']);
                $passing_year = $parts[1] ?? "";
            }

            $grouped[$enroll]['student_info'] = [
                'student_name'   => '',
                'enrollment_no'  => '',
                'father_name'    => '',
                'program'        => $resultData['program'] ?? '',
                'specialization' => $resultData['stream'] ?? '',
                'exam_session'   => $resultData['exam_session'] ?? '',
                'passing_year'   => $passing_year ?: '-',
            ];
        }

        $period = $resultData['period'];

        if (!isset($grouped[$enroll]['results'][$period])) {
            $grouped[$enroll]['results'][$period] = [];
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

        $grouped[$enroll]['results'][$period]['totals'] = [
            'Ext_max_total'       => $resultData['Ext_max_total'],
            'Ext_max_obt_total'   => $resultData['Ext_max_obt_total'],
            'int_max_total'       => $resultData['int_max_total'],
            'int_max_obt_total'   => $resultData['int_max_obt_total'],
            'total_obt'           => $resultData['total_obt']
        ];
    }
}

function formatSemester($period)
{
    // Extract number from "Semester 1"
    preg_match('/(\d+)/', $period, $matches);
    if (!$matches) return $period;

    $num = (int)$matches[1];

    // Convert number to ordinal
    $suffix = "th";
    if ($num % 10 == 1 && $num % 100 != 11) $suffix = "st";
    elseif ($num % 10 == 2 && $num % 100 != 12) $suffix = "nd";
    elseif ($num % 10 == 3 && $num % 100 != 13) $suffix = "rd";

    return "{$num}{$suffix} Semester";
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
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
     <style>
        .table tbody td{
            line-height: 1.2 !important;
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
                            <h4 class="card-title">Transcripts</h4>
                            <button type="button" class="btn btn-primary btn-icon-text print">
                                Print
                                <i class="ti-printer btn-icon-append"></i>
                            </button>
                        </div>
                        <div class="card-body p-0" id="scrollableView">
                            <?php foreach ($grouped as $enroll => $data):
                                $info = $data['student_info'];
                                $resultsByPeriod = $data['results'];
                            ?>
                                <div class="transcript-container" id="<?= $enroll ?>">

                                    <!-- Heading -->
                                    <div class="d-flex w-100 justify-content-end" style="margin-top:4.3cm;">
                                        <h4 style="font-size: 12pt;font-weight: bold;margin: auto;">TRANSCRIPT</h4>
                                    </div>

                                    <div class="marksheet" style="margin-top:0.5cm;">

                                        <!-- Student Info Table -->
                                        <table class="w-100 text-start">
                                            <tr>
                                                <td class="text-start border" style="height:0.6cm;padding: 0 0 0 5px;font-size: 9pt;border-color: #111 !important;vertical-align: bottom !important;font-weight: bold;">Name of Candidate</td>
                                                <td class="text-start border" style="height:0.6cm;padding: 0 0 0 5px;font-size: 9pt;border-color: #111 !important;vertical-align: bottom !important;"><?= $info['student_name'] ?></td>

                                                <td class="text-start border" style="height:0.6cm;padding: 0 0 0 5px;font-size: 9pt;border-color: #111 !important;vertical-align: bottom !important;font-weight: bold;">Enrollment No</td>
                                                <td class="text-start border" style="height:0.6cm;padding: 0 0 0 5px;font-size: 9pt;border-color: #111 !important;vertical-align: bottom !important;"><?= $info['enrollment_no'] ?></td>
                                            </tr>

                                            <tr>
                                                <td class="text-start border" style="height:0.6cm;padding: 0 0 0 5px;font-size: 8pt;border-color: #111 !important;vertical-align: bottom !important;font-weight: bold;">Father’s/Husband’s Name</td>
                                                <td class="text-start border" style="height:0.6cm;padding: 0 0 0 5px;font-size: 9pt;border-color: #111 !important;vertical-align: bottom !important;"><?= $info['father_name'] ?></td>

                                                <td class="text-start border" style="height:0.6cm;padding: 0 0 0 5px;font-size: 9pt;border-color: #111 !important;vertical-align: bottom !important;font-weight: bold;">Course</td>
                                                <td class="text-start border" style="height:0.6cm;padding: 0 0 0 5px;font-size: 9pt;border-color: #111 !important;vertical-align: bottom !important;"><?= $info['program'] ?></td>
                                            </tr>

                                            <tr>
                                                <td class="text-start border" style="height:0.6cm;padding: 0 0 0 5px;font-size: 9pt;border-color: #111 !important;vertical-align: bottom !important;font-weight: bold;">Passing Year</td>
                                                <td class="text-start border" style="height:0.6cm;padding: 0 0 0 5px;font-size: 9pt;border-color: #111 !important;vertical-align: bottom !important;"><?= $info['passing_year'] ?></td>

                                                <td class="text-start border" style="height:0.6cm;padding: 0 0 0 5px;font-size: 9pt;border-color: #111 !important;vertical-align: bottom !important;font-weight: bold;">Specialization</td>
                                                <td class="text-start border" style="height:0.6cm;padding: 0 0 0 5px;font-size: 9pt;border-color: #111 !important;vertical-align: bottom !important;"><?= $info['specialization'] ?: "-" ?></td>
                                            </tr>

                                            <tr>
                                                <td class="text-start border" colspan="4" style="height:0.6cm;padding: 0 0 0 5px;font-size: 9pt;border-color: #111 !important;vertical-align: bottom !important;font-weight: bold;">Medium of Instruction : English</td>
                                            </tr>
                                        </table>


                                        <!-- SUBJECT TABLE -->
                                        <table class="table table-bordered table-marks mb-0 w-100" style="margin-top:0.5cm;">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="font-size: 9pt;font-weight: bold;width: 2cm;height: 1.1cm;border: 1px solid #111 !important;">Subject Code</th>
                                                    <th rowspan="2" style="font-size: 9pt;font-weight: bold;width: 8cm;height: 1.1cm;">Subjects</th>
                                                    <th colspan="2" style="font-size: 9pt;font-weight: bold;">External<br>Marks</th>
                                                    <th colspan="2" style="font-size: 9pt;font-weight: bold;">Internal<br>Marks</th>
                                                    <th rowspan="2" style="font-size: 9pt;font-weight: bold;height: 1.1cm;">Total Marks<br>OBTD</th>
                                                    <th rowspan="2" style="font-size: 9pt;font-weight: bold;height: 1.1cm;">Result/Remark</th>
                                                </tr>
                                                <tr>
                                                    <th style="font-size: 8pt;font-weight: bold;line-height: normal;">MAX</th>
                                                    <th style="font-size: 8pt;font-weight: bold;line-height: normal;">OBTD</th>
                                                    <th style="font-size: 8pt;font-weight: bold;line-height: normal;">MAX</th>
                                                    <th style="font-size: 8pt;font-weight: bold;line-height: normal;">OBTD</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($resultsByPeriod as $period => $data): ?>
                                                    <?php $subjects = $data['subjects']; ?>
                                                    <?php $totals   = $data['totals']; ?>
                                                    <tr>
                                                        <td colspan="8" class="text-start" style="font-weight:bold;font-size:8.5pt;padding-left:5px;border: 1px solid #111 !important;">
                                                            <?= formatSemester($period) ?>
                                                        </td>
                                                    </tr>

                                                    <?php foreach ($subjects as $sub): ?>
                                                        <tr>
                                                            <td class="text-start" style="font-size:8.5pt;padding-left:5px;border: 1px solid #111 !important;"><?= $sub['sub_code'] ?></td>
                                                            <td class="text-start" style="font-size:8.5pt;padding-left:5px;border: 1px solid #111 !important;"><?= $sub['sub_name'] ?></td>

                                                            <td class="text-center" style="font-size:8.5pt;border: 1px solid #111 !important;"><?= $sub['sub_ext_max'] ?></td>
                                                            <td class="text-center" style="font-size:8.5pt;border: 1px solid #111 !important;"><?= $sub['sub_ext_obt'] ?></td>

                                                            <td class="text-center" style="font-size:8.5pt;border: 1px solid #111 !important;"><?= $sub['sub_int_max'] ?></td>
                                                            <td class="text-center" style="font-size:8.5pt;border: 1px solid #111 !important;"><?= $sub['sub_int_obt'] ?></td>

                                                            <td class="text-center" style="font-size:8.5pt;border: 1px solid #111 !important;"><?= $sub['sub_total_obt'] ?></td>
                                                            <td class="text-start" style="font-size:8.5pt;padding-left:5px;border: 1px solid #111 !important;"><?= $sub['sub_result_remark'] ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <tr>
                                                        <td colspan="2" style="font-weight:bold;font-size:8.5pt;padding-left:5px;border: 1px solid #111 !important;">
                                                            <div>Grand Total</div>
                                                        </td>
                                                        <td style="font-size:8.5pt;border: 1px solid #111 !important;"><?= $totals['Ext_max_total'] ?></td>
                                                        <td style="font-size:8.5pt;border: 1px solid #111 !important;"><?= $totals['Ext_max_obt_total'] ?></td>
                                                        <td style="font-size:8.5pt;border: 1px solid #111 !important;"><?= $totals['int_max_total'] ?></td>
                                                        <td style="font-size:8.5pt;border: 1px solid #111 !important;"><?= $totals['int_max_obt_total'] ?></td>
                                                        <td style="font-weight:bold;font-size:8.5pt;border: 1px solid #111 !important;"><?= $totals['total_obt'] ?></td>
                                                        <td style="font-size:8.5pt;border: 1px solid #111 !important;"></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021.
                            All rights reserved.</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <!-- PerfectScrollbar JS -->
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var scrollbar1 = document.getElementById("scrollableView");

        if (scrollbar1) {
            new PerfectScrollbar(scrollbar1, {
                wheelPropagation: false
            });
        }
    </script>

    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>

    <script>
        $(".print").on("click", function() {
            generatePDF();
        });

        function generatePDF() {
            var scrollableViewContent = $("#scrollableView").html(); // Get HTML content to send
            var page = window.location.href;
            var pageSegments = page.split('/');
            var idsArray = pageSegments[3].split("=");
            var ids = idsArray[1];

            $.ajax({
                type: "POST",
                url: "generate_pdf.php", // Ensure this points to the correct PHP file
                data: {
                    content: scrollableViewContent,
                },
                dataType: "json", // Expect JSON response
                success: function(response) {
                    if (response.success) {
                        //updatePrinted(ids)
                        console.log(response.message); // Success message from PHP
                        alert("PDF generated successfully. Click here to view.");
                        window.open(response.pdf_path, '_blank'); // Opens the PDF in a new tab
                    } else {
                        console.error(response.message); // Display error message from PHP
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
                url: "update_printed.php", // Ensure this points to the correct PHP file
                data: {
                    ids: ids // Send the HTML content to the server
                },
                dataType: "json", // Expect JSON response
                success: function(response) {
                    if (response.success) {
                        console.log(response.message); // Success message from PHP
                    } else {
                        console.error(response.message); // Display error message from PHP
                        alert('Failed to generate PDF: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while generating the PDF');
                }
            });
        }
    </script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>