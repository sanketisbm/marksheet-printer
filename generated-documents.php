<?php
session_start();
if (!isset($_SESSION['employee_name']) && !isset($_SESSION['session_id'])) {
    echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
    exit();
}

require 'dbFiles/db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * Main function: load document_requests by IDs and render HTML chunks.
 *
 * @return array Array of rendered HTML strings.
 */
function documentRequests()
{
    global $conn;

    $idsList = base64_decode($_GET['param'] ?? '');

    if (empty($idsList)) {
        http_response_code(400);
        echo 'No valid IDs provided.';
        exit;
    }

    $sql = "SELECT * FROM document_requests WHERE id IN ($idsList) ORDER BY created_at, print_flag ASC";

    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) == 0) {
        http_response_code(404);
        echo 'No results found for the provided IDs.';
        exit;
    }

    $renderedHtmls = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $enroll = $row['enrollment_no'];
        $reqType = $row['request_type'];

        // Normalize
        $reqType = trim($reqType);
        $reqType = preg_replace('/\s+/', ' ', $reqType);

        // Debug
        echo "[" . $reqType . "]";

        if ($reqType === "Transcript") {
            $renderedHtmls[] = renderTemplate('transcripts.php', [
                'enrollment_no' => $enroll,
            ]);
        } elseif ($reqType === "MC CUM TC") {
            $renderedHtmls[] = renderTemplate('tcmc.php', ['data' => $row]);
        } elseif ($reqType === "MC TC CC") {
            $renderedHtmls[] = renderTemplate('mctccc.php', ['data' => $row]);
        } elseif ($reqType === "MOI") {
            $renderedHtmls[] = renderTemplate('moi.php', ['data' => $row]);
        } elseif ($reqType === "NOC LETTER LLB") {
            $renderedHtmls[] = renderTemplate('noc-letter-llb.php', ['data' => $row]);
        } elseif ($reqType === "Attendance LLB 75") {
            $renderedHtmls[] = renderTemplate('attendance-llb-75.php', ['data' => $row]);
        } elseif ($reqType === "Attendance LLB") {
            $renderedHtmls[] = renderTemplate('attendance-llb.php', ['data' => $row]);
        } elseif ($reqType === "Bonafide") {
            $renderedHtmls[] = renderTemplate('bonafide.php', ['data' => $row]);
        } elseif ($reqType === "CC") {
            $renderedHtmls[] = renderTemplate('cc.php', ['data' => $row]);
        } elseif ($reqType === "LOR 2") {
            $renderedHtmls[] = renderTemplate('lor-2.php', ['data' => $row]);
        } elseif ($reqType === "LOR 3 Hindi") {
            $renderedHtmls[] = renderTemplate('lor-3-hindi.php', ['data' => $row]);
        } elseif ($reqType === "LOR") {
            $renderedHtmls[] = renderTemplate('lor.php', ['data' => $row]);
        } elseif ($reqType === "PROVISIONAL") {
            $renderedHtmls[] = renderTemplate('provisional-certificate.php', ['data' => $row]);
        } else {
            // optional fallback
            $renderedHtmls[] = "<p>Unknown document type: $reqType</p>";
        }
    }

    return $renderedHtmls;
}

function renderTemplate($template, $data = [])
{
    // Extract data as local variables for the template
    if (is_array($data)) {
        extract($data, EXTR_SKIP);
    }

    // Start output buffering
    ob_start();

    $templateFile = __DIR__ . '/templates/' . $template;
    if (!file_exists($templateFile)) {
        http_response_code(404);
        echo "Template file not found: {$templateFile}";
        exit;
    }

    include $templateFile;

    // Return the rendered content
    return ob_get_clean();
}

// Optional helper (not used yet, but kept if you need it for semesters, etc.)
function numberToRoman($num)
{
    $map = [
        'M'  => 1000,
        'CM' => 900,
        'D'  => 500,
        'CD' => 400,
        'C'  => 100,
        'XC' => 90,
        'L'  => 50,
        'XL' => 40,
        'X'  => 10,
        'IX' => 9,
        'V'  => 5,
        'IV' => 4,
        'I'  => 1,
    ];

    $result = '';

    foreach ($map as $roman => $value) {
        while ($num >= $value) {
            $result .= $roman;
            $num -= $value;
        }
    }

    return $result;
}

// Example usage: call documentRequests() which returns an array of HTML chunks
$renderedHtmls = documentRequests();
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
            height: 29.7cm;
            padding: 0.8cm 2cm 2.1cm 2cm;
            border: 3px solid #06355f;
            font-family: calibri, sans-serif !important;
            background: url(../images/bc.jpg);
            background-position: center;
            background-size: cover;
            position: relative;
            box-sizing: border-box;
            margin: 0 auto 1cm auto;
        }

        .doc-container {
            width: 21cm;
            height: 29.7cm;
            padding: 0.8cm 2cm 2.1cm 2cm;
            border: 3px solid #06355f;
            font-family: "Times New Roman", Times, serif !important;
            background: url(../images/bc.jpg);
            background-position: center;
            background-size: cover;
            position: relative;
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
        <div class="container-fluid page-body-wrapper">
            <?php include 'assets/partials/_sidebar.php' ?>
            <div class="main-panel position-relative">
                <div class="content-wrapper">
                    <div class="card overflow-hidden p-4" style="height: 80vh">
                        <div class="row justify-content-between mb-4 mx-0">
                            <h4 class="card-title">Results</h4>
                            <button type="button" class="btn btn-primary btn-icon-text print">
                                Print
                                <i class="ti-printer btn-icon-append"></i>
                            </button>
                        </div>
                        <div class="card-body p-0" id="scrollableView">
                            <?php foreach ($renderedHtmls as $html):
                                echo $html;
                            endforeach; ?>
                        </div>
                    </div>
                </div>

                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021.
                            All rights reserved.</span>
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

            // Get selected IDs from URL (same logic you used before)
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
                        // updatePrinted(ids); // if you want to enable this later
                        alert("PDF generated successfully. Click OK to view.");
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
                        alert('Failed to update print flags: ' + response.message);
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