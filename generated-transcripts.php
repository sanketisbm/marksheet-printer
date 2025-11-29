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

function transcript()
{
    global $conn;

    $idsList = $_GET['param'];

    // Get transcript rows
    $sql = "SELECT * FROM transcript_request WHERE id IN ($idsList) ORDER BY `enrollment_no`, `print_flag` ASC";
    $transcript = mysqli_query($conn, $sql);

    if (!$transcript || mysqli_num_rows($transcript) == 0) {
        http_response_code(404);
        echo 'No transcripts found for the provided IDs.';
        exit;
    }

    // Group transcript rows by enrollment_no
    $grouped = [];

    while ($row = mysqli_fetch_assoc($transcript)) {
        $enroll = $row['enrollment_no'];
        $grouped[$enroll][] = $row;
    }

    // Rendered templates grouped by enrollment_no
    $finalOutput = [];

    foreach ($grouped as $enroll_no => $recordSet) {

        // Pick the first row to fetch results table
        $templateSql = "SELECT * FROM results WHERE enrollment_no = '{$enroll_no}'";
        $templateResult = mysqli_query($conn, $templateSql);

        if (!$templateResult || mysqli_num_rows($templateResult) == 0) {
            http_response_code(404);
            echo "Template not found for enrollment no: {$enroll_no}";
            exit;
        }

        $resultData = mysqli_fetch_assoc($templateResult);

        // Add grouped transcript records to template data
        $finalresultData['transcript_records'] = $recordSet;
        $finalresultData['result_records'] = $resultData;


        // Render the template
        $finalOutput[$enroll_no] = renderTemplate($finalresultData);
    }

    return $finalOutput;
}

function renderTemplate($data)
{
    // Extract data as variables
    extract($data);

    // Start output buffering
    ob_start();

    // Include the template file if it exists
    $templateFile = 'templates/transcripts.php';
    if (!file_exists($templateFile)) {
        http_response_code(404);
        echo "Template file not found: {$templateFile}";
        exit;
    }

    // Include the template file, with data injected as variables
    include $templateFile;

    // Get the rendered content
    return ob_get_clean();
}

$renderedHtmls = transcript();
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
                            <?php
                            // Loop through each rendered HTML and display it
                            foreach ($renderedHtmls as $html) {
                            ?>
                                <!-- <div class="canvas-container"> -->
                                <div class="transcript-container">
                                    <!-- Rendered HTML content goes here -->
                                    <?php echo $html; ?>
                                </div>
                                <!-- </div> -->
                            <?php
                            }
                            ?>
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