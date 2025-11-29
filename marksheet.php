<?php
require 'dbFiles/db.php';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function marksheet()
{
    global $conn;

    $idsList = $_GET['param'];

    // Query to retrieve results matching the IDs
     $sql = "SELECT * FROM results WHERE id IN ($idsList) ORDER BY `student_name`, `period`, `print_flag` ASC";
     // $sql = "SELECT * FROM results WHERE id IN ($idsList) ORDER BY `application_id`,`print_flag` ASC";
      //  $sql = "SELECT * FROM results WHERE id IN ($idsList) ORDER BY `application_id`, `print_flag` ASC";


    // Execute the query
    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) == 0) {
        // If no results are found, return a 404-like response
        http_response_code(404);
        echo 'No results found for the provided IDs.';
        exit;
    }

    $renderedHtmls = [];

    while ($resultRow = mysqli_fetch_assoc($result)) {
        // Fetch the corresponding template for the result
        $templateSql = "SELECT * FROM templates WHERE template_id = '{$resultRow['template_id']}'";
        $templateResult = mysqli_query($conn, $templateSql);

        if (!$templateResult || mysqli_num_rows($templateResult) == 0) {
            // If no template is found, return a 404-like response
            http_response_code(404);
            echo "Template not found for result ID: {$resultRow['id']}";
            exit;
        }

        $template = mysqli_fetch_assoc($templateResult);

        // Render the template using PHP
        $renderedHtmls[] = renderTemplate($template['template'], $resultRow);
    }

    return $renderedHtmls;
}
// Template rendering function (basic replacement of Blade)
function renderTemplate($template, $data)
{
    // Extract data as variables
    extract($data);

    // Start output buffering
    ob_start();

    // Include the template file if it exists
    $templateFile = 'templates/' . $template;
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
function numberToRoman($num)
{
    // Define the mapping of Roman numerals
    $map = [
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1,
    ];

    $result = '';

    foreach ($map as $roman => $value) {
        // Determine how many times the Roman numeral fits into the number
        while ($num >= $value) {
            $result .= $roman;
            $num -= $value;
        }
    }

    return $result;
}
// Example usage: call marksheet function with comma-separated IDs
$renderedHtmls = marksheet();
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
                            <?php
                            // Loop through each rendered HTML and display it
                            foreach ($renderedHtmls as $html) {
                            ?>
                                <!-- <div class="canvas-container"> -->
                                <div class="result-container">
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
                        updatePrinted(ids)
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