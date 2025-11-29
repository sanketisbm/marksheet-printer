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
    .url-list{
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
                            <h4 class="card-title">Results</h4>
                            <div class="col-lg-4 col-md-4 p-0">
                                <input type="text" id="date-range"
                                    class="btn btn-light bg-white dropdown-toggle text-right ml-auto d-flex" />
                            </div>
                        </div>
                        <div class="row justify-content-between mb-4 mx-0">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button class="btn btn-primary mdi mdi-file-send get-marksheet" tabindex="0"
                                    aria-controls="resultsTableTable" type="button" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="" data-bs-original-title="Filters"
                                    aria-label="Filters"><span></span></button>
                                <button class="btn btn-primary mdi mdi-filter filters" tabindex="0"
                                    aria-controls="resultsTableTable" type="button" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="" data-bs-original-title="Filters"
                                    aria-label="Filters"><span></span></button>
                                <button class="btn btn-primary mdi mdi-download download-csv" tabindex="0"
                                    aria-controls="resultsTableTable" type="button" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="" data-bs-original-title="Download"
                                    aria-label="Download"><span></span></button>
                                <button class="btn btn-primary mdi mdi-reload clear-filter" tabindex="0"
                                    aria-controls="resultsTableTable" type="button" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="" data-bs-original-title="Clear Filter"
                                    aria-label="Clear Filter"><span></span></button>
                            </div>
                            <div class="col-lg-4 col-md-4 p-0">
                                <input type="text" id="searchInput" class="form-control ml-auto "
                                    placeholder="Search...">
                            </div>
                        </div>
                        <div class="card-body p-0" id="resultsTable">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-primary text-white" style="position: sticky; top: 0; z-index: 1;">
                                        <th><input type="checkbox" name="checkAll" id="checkAll"> </th>
                                        <th></th>
                                        <th>Id</th>
                                        <th>Tmr Id</th>
                                        <th>Application Id</th>
                                        <th>Branch</th>
                                        <th>Period</th>
                                        <th>Enrollment No</th>
                                        <th>Roll No</th>
                                        <th>Program</th>
                                        <th>Student Name</th>
                                        <th>Father Name</th>
                                        <th>Mother Name</th>
                                        <th>Program Print Name</th>
                                        <th>Stream</th>
                                        <th>Exam Session</th>
                                        <th>Entry Date</th>
                                        <th>Sl No</th>
                                        <th>Doi</th>
                                        <th>Pccs</th>
                                        <th>Co Na</th>
                                        <th>M Wn</th>
                                        <th>No Of Exams</th>
                                        <th>Signature</th>
                                        <th>Template Id</th>
                                        <th>Sub1 Code</th>
                                        <th>Sub1 Name</th>
                                        <th>Sub1 Ext Max</th>
                                        <th>Sub1 Ext Obt</th>
                                        <th>Sub1 Int Max</th>
                                        <th>Sub1 Int Obt</th>
                                        <th>Sub1 Total Obt</th>
                                        <th>Sub1 Result Remark</th>
                                        <th>Sub2 Code</th>
                                        <th>Sub2 Name</th>
                                        <th>Sub2 Ext Max</th>
                                        <th>Sub2 Ext Obt</th>
                                        <th>Sub2 Int Max</th>
                                        <th>Sub2 Int Obt</th>
                                        <th>Sub2 Total Obt</th>
                                        <th>Sub2 Result Remark</th>
                                        <th>Sub3 Code</th>
                                        <th>Sub3 Name</th>
                                        <th>Sub3 Ext Max</th>
                                        <th>Sub3 Ext Obt</th>
                                        <th>Sub3 Int Max</th>
                                        <th>Sub3 Int Obt</th>
                                        <th>Sub3 Total Obt</th>
                                        <th>Sub3 Result Remark</th>
                                        <th>Sub4 Code</th>
                                        <th>Sub4 Name</th>
                                        <th>Sub4 Ext Max</th>
                                        <th>Sub4 Ext Obt</th>
                                        <th>Sub4 Int Max</th>
                                        <th>Sub4 Int Obt</th>
                                        <th>Sub4 Total Obt</th>
                                        <th>Sub4 Result Remark</th>
                                        <th>Sub5 Code</th>
                                        <th>Sub5 Name</th>
                                        <th>Sub5 Ext Max</th>
                                        <th>Sub5 Ext Obt</th>
                                        <th>Sub5 Int Max</th>
                                        <th>Sub5 Int Obt</th>
                                        <th>Sub5 Total Obt</th>
                                        <th>Sub5 Result Remark</th>
                                        <th>Sub6 Code</th>
                                        <th>Sub6 Name</th>
                                        <th>Sub6 Ext Max</th>
                                        <th>Sub6 Ext Obt</th>
                                        <th>Sub6 Int Max</th>
                                        <th>Sub6 Int Obt</th>
                                        <th>Sub6 Total Obt</th>
                                        <th>Sub6 Result Remark</th>
                                        <th>Sub7 Code</th>
                                        <th>Sub7 Name</th>
                                        <th>Sub7 Ext Max</th>
                                        <th>Sub7 Ext Obt</th>
                                        <th>Sub7 Int Max</th>
                                        <th>Sub7 Int Obt</th>
                                        <th>Sub7 Total Obt</th>
                                        <th>Sub7 Result Remark</th>
                                        <th>Sub8 Code</th>
                                        <th>Sub8 Name</th>
                                        <th>Sub8 Ext Max</th>
                                        <th>Sub8 Ext Obt</th>
                                        <th>Sub8 Int Max</th>
                                        <th>Sub8 Int Obt</th>
                                        <th>Sub8 Total Obt</th>
                                        <th>Sub8 Result Remark</th>
                                        <th>Sub9 Code</th>
                                        <th>Sub9 Name</th>
                                        <th>Sub9 Ext Max</th>
                                        <th>Sub9 Ext Obt</th>
                                        <th>Sub9 Int Max</th>
                                        <th>Sub9 Int Obt</th>
                                        <th>Sub9 Total Obt</th>
                                        <th>Sub9 Result Remark</th>
                                        <th>Sub10 Code</th>
                                        <th>Sub10 Name</th>
                                        <th>Sub10 Ext Max</th>
                                        <th>Sub10 Ext Obt</th>
                                        <th>Sub10 Int Max</th>
                                        <th>Sub10 Int Obt</th>
                                        <th>Sub10 Total Obt</th>
                                        <th>Sub10 Result Remark</th>
                                        <th>Sub11 Code</th>
                                        <th>Sub11 Name</th>
                                        <th>Sub11 Ext Max</th>
                                        <th>Sub11 Ext Obt</th>
                                        <th>Sub11 Int Max</th>
                                        <th>Sub11 Int Obt</th>
                                        <th>Sub11 Total Obt</th>
                                        <th>Sub11 Result Remark</th>
                                        <th>Sub12 Code</th>
                                        <th>Sub12 Name</th>
                                        <th>Sub12 Ext Max</th>
                                        <th>Sub12 Ext Obt</th>
                                        <th>Sub12 Int Max</th>
                                        <th>Sub12 Int Obt</th>
                                        <th>Sub12 Total Obt</th>
                                        <th>Sub12 Result Remark</th>
                                        <th>Sub13 Code</th>
                                        <th>Sub13 Name</th>
                                        <th>Sub13 Ext Max</th>
                                        <th>Sub13 Ext Obt</th>
                                        <th>Sub13 Int Max</th>
                                        <th>Sub13 Int Obt</th>
                                        <th>Sub13 Total Obt</th>
                                        <th>Sub13 Result Remark</th>
                                        <th>Percentage</th>
                                        <th>Ext Max Total</th>
                                        <th>Ext Max Obt Total</th>
                                        <th>Int Max Total</th>
                                        <th>Int Max Obt Total</th>
                                        <th>Total Obt</th>
                                        <th>Total Marks Obt Words</th>
                                        <th>Result</th>
                                        <th>Division</th>
                                        <th>Qr Code Data</th>
                                        <th>Sem1 Max Marks</th>
                                        <th>Sem1 Max Obt</th>
                                        <th>Sem2 Max Marks</th>
                                        <th>Sem2 Max Obt</th>
                                        <th>Sem3 Max Marks</th>
                                        <th>Sem3 Max Obt</th>
                                        <th>Sem4 Max Marks</th>
                                        <th>Sem4 Max Obt</th>
                                        <th>Sem5 Max Marks</th>
                                        <th>Sem5 Max Obt</th>
                                        <th>Sem6 Max Marks</th>
                                        <th>Sem6 Max Obt</th>
                                        <th>Sem7 Max Marks</th>
                                        <th>Sem7 Max Obt</th>
                                        <th>Sem8 Max Marks</th>
                                        <th>Sem8 Max Obt</th>
                                        <th>Sem9 Max Marks</th>
                                        <th>Sem9 Max Obt</th>
                                        <th>Sem10 Max Marks</th>
                                        <th>Sem10 Max Obt</th>
                                        <th>Grand Total Max</th>
                                        <th>Grand Total Obt</th>
                                        <th>Final Result</th>
                                        <th>Cgrand Total Max</th>
                                        <th>Cgrand Total Obt</th>
                                        <th>Cgrand Total Inwords</th>
                                        <th>Cpercentage</th>
                                        <th>Cdivision</th>
                                        <th>Printing Flag</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </thead>
                                    <tbody id="resultsTableBody"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3 mx-0">
                            <p>Showing <span id="entries">0</span> Entries</p>
                        </div>
                        
                        <div class="col url-list">
                            
                        </div>
                    </div>
                </div>
                <?php include 'assets/partials/_footer.html' ?>
            </div>
        </div>
    </div>

    <?php
    include 'assets/partials/plugins_js.html';
    include 'offcanvas_filters.php';
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

        var scrollbar1 = document.getElementById("resultsTable");
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
            var tableBody = $("#resultsTableBody");
            $('#entries').empty();
            $('#entries').text(0);
            tableBody.empty();
           $.ajax({
                url: "dbFiles/fetch_results.php",
                type: "GET",
                data: {
                    dateRange: dateRange,
                },
                dataType: "json",
                success: function (response) {
                    if (response && response.result && Array.isArray(response.result)) {
                        var count = response.result.length;
                        $('#entries').text(count);
            
                        const urls = [];
                        const batchSize = 30;
                        let idBatch = [];
            
                        // Clear table body before appending new rows
                        const tableBody = $("#resultsTable tbody");
                        tableBody.empty();
            
                        response.result.forEach((item, index) => {
                            // ✅ URL Batching Logic
                            if (item.id) {
                                idBatch.push(item.id);
                            }
            
                            if (idBatch.length === batchSize || index === response.result.length - 1) {
                                const url = `generated-marksheets?param=${idBatch.join(',')}`;
                                urls.push(url);
                                idBatch = []; // Reset for next batch
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
                                <td><button class="border-0 bg-transparent p-0 mdi mdi-delete-forever trash text-primary clear-filter" data-id="${item.id || ''}*${item.student_name || ''}"></button></td>
                                <td>${item.id || ''}</td>
                                <td>${item.tmr_id || ''}</td>
                                <td>${item.application_id || ''}</td>
                                <td>${item.branch || ''}</td>
                                <td>${item.period || ''}</td>
                                <td>${item.enrollment_no || ''}</td>
                                <td>${item.roll_no || ''}</td>
                                <td>${item.program || ''}</td>
                                <td>${item.student_name || ''}</td>
                                <td>${item.father_name || ''}</td>
                                <td>${item.mother_name || ''}</td>
                                <td>${item.program_print_name || ''}</td>
                                <td>${item.stream || ''}</td>
                                <td>${item.exam_session || ''}</td>
                                <td>${item.entry_date || ''}</td>
                                <td>${item.sl_no || ''}</td>
                                <td>${item.doi || ''}</td>
                                <td>${item.pccs || ''}</td>
                                <td>${item.co_na || ''}</td>
                                <td>${item.m_wn || ''}</td>
                                <td>${item.no_of_exams || ''}</td>
                                <td>${item.signature || ''}</td>
                                <td>${item.template_id || ''}</td>
                                <td>${item.sub1_code || ''}</td>
                                <td>${item.sub1_name || ''}</td>
                                <td>${item.sub1_ext_max || ''}</td>
                                <td>${item.sub1_ext_obt || ''}</td>
                                <td>${item.sub1_int_max || ''}</td>
                                <td>${item.sub1_int_obt || ''}</td>
                                <td>${item.sub1_total_obt || ''}</td>
                                <td>${item.sub1_result_Remark || ''}</td>
                                <td>${item.sub2_code || ''}</td>
                                <td>${item.sub2_name || ''}</td>
                                <td>${item.sub2_ext_max || ''}</td>
                                <td>${item.sub2_ext_obt || ''}</td>
                                <td>${item.sub2_int_max || ''}</td>
                                <td>${item.sub2_int_obt || ''}</td>
                                <td>${item.sub2_total_obt || ''}</td>
                                <td>${item.sub2_result_Remark || ''}</td>
                                <td>${item.sub3_code || ''}</td>
                                <td>${item.sub3_name || ''}</td>
                                <td>${item.sub3_ext_max || ''}</td>
                                <td>${item.sub3_ext_obt || ''}</td>
                                <td>${item.sub3_int_max || ''}</td>
                                <td>${item.sub3_int_obt || ''}</td>
                                <td>${item.sub3_total_obt || ''}</td>
                                <td>${item.sub3_result_Remark || ''}</td>
                                <td>${item.sub4_code || ''}</td>
                                <td>${item.sub4_name || ''}</td>
                                <td>${item.sub4_ext_max || ''}</td>
                                <td>${item.sub4_ext_obt || ''}</td>
                                <td>${item.sub4_int_max || ''}</td>
                                <td>${item.sub4_int_obt || ''}</td>
                                <td>${item.sub4_total_obt || ''}</td>
                                <td>${item.sub4_result_Remark || ''}</td>
                                <td>${item.sub5_code || ''}</td>
                                <td>${item.sub5_name || ''}</td>
                                <td>${item.sub5_ext_max || ''}</td>
                                <td>${item.sub5_ext_obt || ''}</td>
                                <td>${item.sub5_int_max || ''}</td>
                                <td>${item.sub5_int_obt || ''}</td>
                                <td>${item.sub5_total_obt || ''}</td>
                                <td>${item.sub5_result_Remark || ''}</td>
                                <td>${item.sub6_code || ''}</td>
                                <td>${item.sub6_name || ''}</td>
                                <td>${item.sub6_ext_max || ''}</td>
                                <td>${item.sub6_ext_obt || ''}</td>
                                <td>${item.sub6_int_max || ''}</td>
                                <td>${item.sub6_int_obt || ''}</td>
                                <td>${item.sub6_total_obt || ''}</td>
                                <td>${item.sub6_result_Remark || ''}</td>
                                <td>${item.sub7_code || ''}</td>
                                <td>${item.sub7_name || ''}</td>
                                <td>${item.sub7_ext_max || ''}</td>
                                <td>${item.sub7_ext_obt || ''}</td>
                                <td>${item.sub7_int_max || ''}</td>
                                <td>${item.sub7_int_obt || ''}</td>
                                <td>${item.sub7_total_obt || ''}</td>
                                <td>${item.sub7_result_Remark || ''}</td>
                                <td>${item.sub8_code || ''}</td>
                                <td>${item.sub8_name || ''}</td>
                                <td>${item.sub8_ext_max || ''}</td>
                                <td>${item.sub8_ext_obt || ''}</td>
                                <td>${item.sub8_int_max || ''}</td>
                                <td>${item.sub8_int_obt || ''}</td>
                                <td>${item.sub8_total_obt || ''}</td>
                                <td>${item.sub8_result_Remark || ''}</td>
                                <td>${item.sub9_code || ''}</td>
                                <td>${item.sub9_name || ''}</td>
                                <td>${item.sub9_ext_max || ''}</td>
                                <td>${item.sub9_ext_obt || ''}</td>
                                <td>${item.sub9_int_max || ''}</td>
                                <td>${item.sub9_int_obt || ''}</td>
                                <td>${item.sub9_total_obt || ''}</td>
                                <td>${item.sub9_result_Remark || ''}</td>
                                <td>${item.sub10_code || ''}</td>
                                <td>${item.sub10_name || ''}</td>
                                <td>${item.sub10_ext_max || ''}</td>
                                <td>${item.sub10_ext_obt || ''}</td>
                                <td>${item.sub10_int_max || ''}</td>
                                <td>${item.sub10_int_obt || ''}</td>
                                <td>${item.sub10_total_obt || ''}</td>
                                <td>${item.sub10_result_Remark || ''}</td>
                                <td>${item.percentage || ''}</td>
                                <td>${item.total_obt || ''}</td>
                                <td>${item.result || ''}</td>
                                <td>${item.division || ''}</td>
                                <td>${item.qr_code_data || ''}</td>
                                <td>${print_status}</td>
                                <td>${item.created_at || ''}</td>
                                <td>${item.updated_at || ''}</td>
                            </tr>`;
            
                            tableBody.append(tableRow);
                        });
            
                        // ✅ Add table row click handler
                        $("#resultsTable tbody").on("click", "tr", function () {
                            $("#resultsTable tbody tr").removeClass("selected-row");
                            $(this).addClass("selected-row");
                        });
            
                        // ✅ Render batch URLs after table
                        $('.url-list').empty();
                        let lists = '';
                        urls.forEach((url, index) => {
                            lists += `<a href="${url}" target="_blank">Batch ${index + 1}</a>, <br/>`;
                        });
                        $('.url-list').append(lists);
                    } else {
                        console.error('Unexpected response format:', response);
                    }
                },
                error: function (xhr, status, error) {
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

        $(".get-marksheet").on("click", function() {
            let checkedValues = [];
            $(".check:checked").each(function() {
                checkedValues.push($(this).val());
            });

            if (checkedValues.length === 0) {
                alert("Please select at least one record.");
                return;
            }

            window.location.href = "generated-marksheets?param=" + checkedValues.join(',');
        });

        $(".filters").on("click", function() {
            var offcanvasElement = document.getElementById(
                "filtersOffcanvasEnd"
            );
            var bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
            bsOffcanvas.show();
            offcanvasElement.addEventListener(
                "shown.bs.offcanvas",
                function() {
                    $(".js-example-basic-single").select2({
                        dropdownParent: $(
                            "#filtersOffcanvasEnd .offcanvas-body"
                        ),
                    });
                }, {
                    once: true
                }
            );
            $('#url').val(window.location.href);

        });

        $(".download-csv").on("click", function() {
            downloadCSV2("#resultsTableBody", "Results Data.csv");
            alert("Your data has been downloaded.");
        });

        $(".clear-filter").on("click", function() {
            $.ajax({
                url: "clear_filter.php",
                type: "POST",
                success: function(response) {
                    window.location.reload();
                },
                error: function() {
                    alert("Error clearing session.");
                },
            });
        });

        $(document).on("click", "#resultsTable tbody tr .trash", function() {
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
                url: "dbFiles/delete_result.php", // Assuming a correct endpoint for deletion
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
                $('#resultsTableBody tr').filter(function() {
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