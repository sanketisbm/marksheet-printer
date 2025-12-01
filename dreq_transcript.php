<?php
session_start();
if (!isset($_SESSION['employee_name']) && !isset($_SESSION['session_id'])) {
    echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head><?php include 'assets/partials/header.html' ?>
    <STYLE>
        .url-list {
            display: flex;
            flex-wrap: wrap;
        }

        .url-list a {
            margin-left: 0.5rem;
        }
    </STYLE>
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
                            <h4 class="card-title">Transcript</h4>
                            <div class="col-lg-4 col-md-4 p-0">
                                <input type="text" id="date-range"
                                    class="btn btn-light bg-white dropdown-toggle text-right ml-auto d-flex" />
                            </div>
                        </div>
                        <div class="row justify-content-between mb-4 mx-0">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button class="btn btn-primary mdi mdi-file-send get-transcript" tabindex="0"
                                    aria-controls="transcriptTableTable" type="button" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="" data-bs-original-title="Filters"
                                    aria-label="Filters"><span></span></button>
                                <button class="btn btn-primary mdi mdi-download download-csv" tabindex="0"
                                    aria-controls="transcriptTableTable" type="button" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="" data-bs-original-title="Download"
                                    aria-label="Download"><span></span></button>
                            </div>
                            <div class="col-lg-4 col-md-4 p-0">
                                <input type="text" id="searchInput" class="form-control ml-auto "
                                    placeholder="Search...">
                            </div>
                        </div>
                        <div class="card-body p-0" id="transcriptTable">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-primary text-white" style="position: sticky; top: 0; z-index: 1;">
                                        <th><input type="checkbox" name="checkAll" id="checkAll"> </th>
                                        <th></th>
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
                                    <tbody id="transcriptTableBody"></tbody>
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

        var scrollbar1 = document.getElementById("transcriptTable");
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
            var tableBody = $("#transcriptTableBody");
            $('#entries').empty();
            $('#entries').text(0);
            tableBody.empty();
            $.ajax({
                url: "dbFiles/fetch_transcript.php",
                type: "GET",
                data: {
                    dateRange: dateRange,
                    type: "transcript",
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
                        const tableBody = $("#transcriptTable tbody");
                        tableBody.empty();

                        response.result.forEach((item, index) => {
                            // ✅ URL Batching Logic
                            if (item.id) {
                                idBatch.push(item.id);
                            }

                            // ✅ Row Creation Logic (moved inside loop)
                            let accessRow = '';
                            let print_status = '';

                            if (item.print_flag === "1") {
                                print_status = "Printed";
                                accessRow = `<input type="checkbox" name="check" class="check" disabled>`;
                            } else {
                                print_status = "Not Printed";
                                accessRow = `<input type="checkbox" value="${item.id || ''}" name="check" class="check">`;
                            }

                            let tableRow = `<tr>
                                <td>${accessRow}</td>
                                <td><button class="border-0 bg-transparent p-0 mdi mdi-delete-forever trash text-primary" data-id="${item.id || ''}*${item.student_name || ''}"></button></td>
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
                        $("#transcriptTable tbody").on("click", "tr", function() {
                            $("#transcriptTable tbody tr").removeClass("selected-row");
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

        $(".get-transcript").on("click", function() {
            let checkedValues = [];
            $(".check:checked").each(function() {
                checkedValues.push($(this).val());
            });

            if (checkedValues.length === 0) {
                alert("Please select at least one record.");
                return;
            }

            window.location.href = "generated-transcripts?param=" + checkedValues.join(',');
        });

        $(".download-csv").on("click", function() {
            downloadCSV2("#transcriptTableBody", "Transcript Data.csv");
            alert("Your data has been downloaded.");
        });

        $(document).on("click", "#transcriptTable tbody tr .trash", function() {
            var dataId = $(this).attr("data-id");
            var dataIdArray = dataId.split("*");
            var id = dataIdArray[0]; // Extracted ID
            var name = dataIdArray[1]; // Extracted Name (if needed)

            if (!id) {
                alert("Error: Invalid ID.");
                return;
            }

            if (!confirm("Are you sure you want to delete this record?")) {
                return;
            }

            $.ajax({
                url: "dbFiles/delete_transcript.php", // Assuming a correct endpoint for deletion
                type: "POST", // Use POST for delete operations
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        alert(name + "'s Record deleted successfully.");
                        fetch_data_tLeads(currentdateRange);
                    } else {
                        alert("Failed to delete record.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error);
                    console.error("Response:", xhr.responseText);
                }
            });
        });

        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#transcriptTableBody tr').filter(function() {
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
    </script>
</body>

</html>