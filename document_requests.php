<?php
session_start();
if (!isset($_SESSION['employee_name']) && !isset($_SESSION['session_id'])) {
    echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'assets/partials/header.html' ?> <style>
        .url-list {
            display: flex;
            flex-wrap: wrap;
        }

        .url-list a {
            margin-left: 0.5rem;
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
                            <h4 class="card-title">Document Requests</h4>
                            <div class="col-lg-4 col-md-4 p-0">
                                <input type="text" id="date-range"
                                    class="btn btn-light bg-white dropdown-toggle text-right ml-auto d-flex" />
                            </div>
                        </div>
                        <div class="row justify-content-between mb-4 mx-0">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button class="btn btn-primary mdi mdi-file-send get-document-request" tabindex="0"
                                    aria-controls="documentRequestsTableTable" type="button" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="" data-bs-original-title="Filters"
                                    aria-label="Filters"><span></span></button>
                                <button class="btn btn-primary mdi mdi-download download-csv" tabindex="0"
                                    aria-controls="documentRequestsTableTable" type="button" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="" data-bs-original-title="Download"
                                    aria-label="Download"><span></span></button>
                            </div>
                            <div class="col-lg-4 col-md-4 p-0">
                                <input type="text" id="searchInput" class="form-control ml-auto "
                                    placeholder="Search...">
                            </div>
                        </div>
                        <div class="card-body p-0" id="documentRequestsTable">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-primary text-white" style="position: sticky; top: 0; z-index: 1;">
                                        <th><input type="checkbox" name="checkAll" id="checkAll"> </th>
                                        <th>Upload</th>
                                        <th>Doc Type</th>
                                        <th>Id</th>
                                        <th>Application Id</th>
                                        <th>Enrollment No</th>
                                        <th>Program</th>
                                        <th>Student Name</th>
                                        <th>Father/Husband Name</th>
                                        <th>Passing Year</th>
                                        <th>Specialization</th>
                                        <th>Print Status</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </thead>
                                    <tbody id="documentRequestsTableBody"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3 mx-0">
                            <p>Showing <span id="entries">0</span> Entries</p>
                        </div>
                    </div>
                </div>
                <?php include 'assets/partials/_footer.html' ?>
            </div>
        </div>
    </div>

    <!-- Upload Image Modal -->
    <div class="modal fade" id="uploadImageModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="uploadImageForm" enctype="multipart/form-data" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Upload Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="uploadRowId">

                    <label class="form-label">Choose Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>

            </form>
        </div>
    </div>


    <?php
    include 'assets/partials/plugins_js.html';
    ?>
    <script>
        var dateRange = "";
        var currentdateRange = "";
        var startDate = moment().subtract(7, 'days').startOf('day');
        var endDate = moment().endOf('day');

        $('#date-range').daterangepicker({
            opens: 'left',
            locale: {
                format: 'YYYY-MM-DD'
            },
            startDate: startDate,
            endDate: endDate
        }, function(start, end) {
            dateRange = start.format('YYYY-MM-DD') + '*' + end.format('YYYY-MM-DD');
            fetch_data_tLeads(dateRange);
        });

        var scrollbar1 = document.getElementById("documentRequestsTable");
        if (scrollbar1) {
            new PerfectScrollbar(scrollbar1, {
                wheelPropagation: false
            });
        }

        var today = new Date();
        var formatDate = function(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        };

        currentdateRange = formatDate(startDate.toDate()) + "*" + formatDate(endDate.toDate());
        fetch_data_tLeads(currentdateRange);

        function fetch_data_tLeads(dateRange) {
            var tableBody = $("#documentRequestsTableBody");
            $('#entries').empty();
            $('#entries').text(0);
            tableBody.empty();
            $.ajax({
                url: "dbFiles/fetch_document_requests.php",
                type: "GET",
                data: {
                    dateRange: dateRange,
                },
                dataType: "json",
                success: function(response) {
                    if (response && response.result && Array.isArray(response.result)) {
                        var count = response.result.length;
                        $('#entries').text(count);

                        const urls = [];
                        const batchSize = 30;
                        let idBatch = [];

                        // Clear table body before appending new rows
                        const tableBody = $("#documentRequestsTable tbody");
                        tableBody.empty();

                        response.result.forEach((item, index) => {
                            // ✅ URL Batching Logic
                            if (item.id) {
                                idBatch.push(item.id);
                            }

                            // ✅ Row Creation Logic (moved inside loop)
                            let accessRow = '';
                            let print_status = '';
                            let uploadBtn = '';

                            if (item.print_flag === "1") {
                                print_status = "Printed";
                                accessRow =
                                    `<input type="checkbox" name="check" class="check" disabled>`;
                            } else {
                                print_status = "Not Printed";
                                accessRow =
                                    `<input type="checkbox" value="${item.id || ''}" name="check" class="check">`;
                            }

                            if (["DEGREE SPZL", "DEGREE PHD", "DEGREE WITHOUT SPZL", "DIPLOMA WITHOUT SPZL"].includes(item.request_type)) {
                                uploadBtn = `
                                    <button class="btn btn-sm btn-success upload-image-btn" data-id="${item.id}">
                                        Upload Image
                                    </button>`;
                            }

                            let tableRow = `<tr>
                                <td>${accessRow}</td>
                                <td>${uploadBtn} </td>
                                <td>${item.request_type || ''}</td>
                                <td>${item.id || ''}</td>
                                <td>${item.application_id || ''}</td>
                                <td>${item.enrollment_no || ''}</td>
                                <td>${item.program || ''}</td>
                                <td>${item.student_name || ''}</td>
                                <td>${item.fathers_husbands_name || ''}</td>
                                <td>${item.passing_year || ''}</td>
                                <td>${item.specialization || ''}</td>
                                <td>${print_status}</td>
                                <td>${item.created_at || ''}</td>
                                <td>${item.updated_at || ''}</td>
                            </tr>`;

                            tableBody.append(tableRow);
                        });

                        // ✅ Add table row click handler
                        $("#documentRequestsTable tbody").on("click", "tr", function() {
                            $("#documentRequestsTable tbody tr").removeClass("selected-row");
                            $(this).addClass("selected-row");
                        });
                    } else {
                        console.error('Unexpected response format:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error);
                    console.error("Response:", xhr.responseText);
                }
            });

        }

        $("#checkAll").on("change", function() {
            let count = 0;
            $(".check").each(function() {
                if (!$(this).prop("disabled") && count < 30) {
                    $(this).prop("checked", $("#checkAll").prop("checked"));
                    count++;
                }
            });
        });

        $(".get-document-request").on("click", function() {
            let checkedValues = [];
            $(".check:checked").each(function() {
                checkedValues.push($(this).val());
            });

            if (checkedValues.length === 0) {
                alert("Please select at least one record.");
                return;
            }

            window.location.href = "generated-documents?param=" + checkedValues.join(',');
        });

        $(".download-csv").on("click", function() {
            downloadCSV2("#documentRequestsTableBody", "Document Requests Data.csv");
            alert("Your data has been downloaded.");
        });

        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#documentRequestsTableBody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

        });

        function downloadCSV2(tableSelector, fileName) {
            var table = document.querySelector(tableSelector).closest("table");
            var rows = table.querySelectorAll("thead, tbody tr"); // Include both header and body rows

            var csvContent = "";

            rows.forEach((row) => {
                const cells = row.querySelectorAll("td, th");

                // Convert the cells to an array and slice it to skip the first two columns
                const rowData = Array.from(cells)
                    .slice(2) // Skip the first two columns
                    .map((cell) => `"${cell.textContent.replace(/"/g, '""')}"`)
                    .join(",");

                csvContent += rowData + "\n";
            });

            const blob = new Blob([csvContent], {
                type: "text/csv;charset=utf-8;"
            });
            const link = document.createElement("a");
            if (link.download !== undefined) {
                const url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", fileName);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        $(document).on("click", ".upload-image-btn", function() {
            let rowId = $(this).data("id");
            $("#uploadRowId").val(rowId);
            $("#uploadImageModal").modal("show");
        });
        $("#uploadImageForm").on("submit", function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "dbFiles/upload_document_image.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert("Image uploaded successfully!");
                    $("#uploadImageModal").modal("hide");
                },
                error: function() {
                    alert("Error uploading image");
                }
            });
        });
    </script>
</body>

</html>