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
                        <div class="row justify-content-between mb-4 mx-0 align-items-center">
                            <div class="d-flex">
                                <div class="col-auto">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button class="btn btn-primary mdi mdi-file-send get-document-request" tabindex="0"
                                            aria-controls="documentRequestsTableTable" type="button" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="" data-bs-original-title="Filters"
                                            aria-label="Filters"><span></span></button>
                                        <button class="btn btn-primary mdi mdi-upload upload-pics" tabindex="0"
                                            aria-controls="documentRequestsTableTable" type="button" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="" data-bs-original-title="Upload Pictures"
                                            aria-label="Upload Pictures"><span></span></button>
                                        <button class="btn btn-primary mdi mdi-download download-csv" tabindex="0"
                                            aria-controls="documentRequestsTableTable" type="button" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="" data-bs-original-title="Download"
                                            aria-label="Download"><span></span></button>
                                        <button class="btn btn-primary mdi mdi-pencil update-hindi-btn"
                                            data-bs-toggle="tooltip"
                                            title="Update Hindi Names">
                                        </button>

                                    </div>
                                </div>

                                <div class="col-auto d-flex align-items-center gap-2 p-2">
                                    <select id="rowsPerPageSelect" class="form-select form-select-sm w-auto mr-2" style="padding: 5px;">
                                        <option value="10">10</option>
                                        <option value="25" selected>25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <select id="requestTypeFilter" class="form-select form-select-sm w-auto" style="padding: 5px;">
                                        <option value="">All Request Types</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 p-0">
                                <input type="text" id="searchInput" class="form-control ml-auto"
                                    placeholder="Search...">
                            </div>
                        </div>

                        <div class="card-body p-0" id="documentRequestsTable">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-primary text-white" style="position: sticky; top: 0; z-index: 1;">
                                        <th><input type="checkbox" name="checkAll" id="checkAll"> </th>
                                        <th>Upload</th>
                                        <th>Request Type</th>
                                        <th>Student Name</th>
                                        <th>Enrollment No</th>
                                        <th>Application Id</th>
                                        <th>Fathers Husbands Name</th>
                                        <th>Program</th>
                                        <th>Passing Year</th>
                                        <th>Division</th>
                                        <th>Specialization</th>
                                        <th>Print Flag</th>
                                        <th>Mother Name</th>
                                        <th>Professor</th>
                                        <th>Professor Desg</th>
                                        <th>Professor Dept</th>
                                        <th>Doc No</th>
                                        <th>Student Name Hindi</th>
                                        <th>Father Name Hindi</th>
                                        <th>Qr Code Data</th>
                                        <th>Department</th>
                                        <th>Research Topic</th>
                                        <th>Uploaded Image</th>
                                        <th>Issued Date</th>
                                        <th>Print Date</th>
                                        <th>Branch</th>
                                        <th>Program Name Hindi</th>
                                        <th>Splz Name Hindi</th>
                                        <th>Passout Session Hindi</th>
                                        <th>Division Hindi</th>
                                        <th>Prefix Eng</th>
                                        <th>Prefix Hindi</th>
                                    </thead>
                                    <tbody id="documentRequestsTableBody"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-3 mx-0 justify-content-between align-items-center">
                            <div class="col-auto">
                                <p class="mb-0">Showing <span id="entries">0</span> Entries</p>
                            </div>
                            <div class="col-auto">
                                <nav aria-label="Document requests pagination">
                                    <ul class="pagination pagination-sm mb-0" id="paginationControls"></ul>
                                </nav>
                            </div>
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
                    <input type="hidden" name="enrollmentNo" id="enrollmentNo">

                    <label class="form-label">Choose Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>

            </form>
        </div>
    </div>

    <!-- Upload Multiple Images Modal -->
    <div class="modal fade" id="uploadMultipleImagesModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="uploadMultipleImagesForm" enctype="multipart/form-data" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Upload Multiple Images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="enrollmentNo" id="multiEnrollmentNo">

                    <label class="form-label">Choose Images</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple required>

                    <small class="text-muted d-block mt-2">
                        Tip: You can select multiple images at once.
                    </small>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>

            </form>
        </div>
    </div>

    <!-- Update Hindi Names Modal -->
    <div class="modal fade" id="updateHindiModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="updateHindiForm" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Update Hindi Names</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="hindiRowId">

                    <div class="mb-3">
                        <label class="form-label">Student Name (Hindi)</label>
                        <input type="text" name="student_name_hindi" id="studentNameHindi"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Father Name (Hindi)</label>
                        <input type="text" name="father_name_hindi" id="fatherNameHindi"
                            class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
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

        // 🔹 Pagination & filter state
        let allDocumentRequests = [];
        let currentPage = 1;
        let rowsPerPage = 25; // will be controlled by dropdown
        let searchQuery = "";
        let filterRequestType = "";

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

        function buildTableRow(item) {
            let accessRow = '';
            let print_status = '';
            let uploadBtn = '';

            if (item.print_flag === "1") {
                print_status = "Printed";
                accessRow = `<input type="checkbox" name="check" class="check" disabled>`;
            } else {
                print_status = "Not Printed";
                accessRow = `<input type="checkbox" value="${item.id || ''}" name="check" class="check">`;
            }

            if (["DEGREE SPZL", "DEGREE PHD", "DEGREE WITHOUT SPZL", "DEGREE WITHOUT SPZL NEW", "DIPLOMA WITHOUT SPZL"].includes(item.request_type)) {
                uploadBtn = `
            <button class="btn btn-sm btn-success upload-image-btn" data-id="${item.id}" data-env="${item.enrollment_no}">
                Upload Image
            </button>`;
            }

            return `<tr>
                <td>${accessRow || ''}</td>
                <td>${uploadBtn}</td>
                <td>${item.request_type || ''}</td>
                <td>${item.student_name || ''}</td>
                <td>${item.enrollment_no || ''}</td>
                <td>${item.application_id || ''}</td>
                <td>${item.fathers_husbands_name || ''}</td>
                <td>${item.program || ''}</td>
                <td>${item.passing_year || ''}</td>
                <td>${item.division || ''}</td>
                <td>${item.specialization || ''}</td>
                <td>${item.print_flag || ''}</td>
                <td>${item.mother_name || ''}</td>
                <td>${item.professor || ''}</td>
                <td>${item.professor_desg || ''}</td>
                <td>${item.professor_dept || ''}</td>
                <td>${item.doc_no || ''}</td>
                <td>${item.student_name_hindi || ''}</td>
                <td>${item.father_name_hindi || ''}</td>
                <td>${item.qr_code_data || ''}</td>
                <td>${item.department || ''}</td>
                <td>${item.research_topic || ''}</td>
                <td>${item.uploaded_image || ''}</td>
                <td>${item.issued_date || ''}</td>
                <td>${item.print_date || ''}</td>
                <td>${item.branch || ''}</td>
                <td>${item.program_name_hindi || ''}</td>
                <td>${item.splz_name_hindi || ''}</td>
                <td>${item.passout_session_hindi || ''}</td>
                <td>${item.division_hindi || ''}</td>
                <td>${item.prefix_eng || ''}</td>
                <td>${item.prefix_hindi || ''}</td>
            </tr>`;
        }

        function renderPagination(total) {
            const pagination = $("#paginationControls");
            pagination.empty();

            const totalPages = Math.ceil(total / rowsPerPage) || 1;
            if (totalPages <= 1) return; // no controls if only one page

            const addPageItem = (label, page, disabled = false, active = false) => {
                const liClass = [
                    'page-item',
                    disabled ? 'disabled' : '',
                    active ? 'active' : ''
                ].join(' ').trim();

                const a = `<a class="page-link" href="#" data-page="${page}">${label}</a>`;
                pagination.append(`<li class="${liClass}">${a}</li>`);
            };

            // Prev
            addPageItem('«', currentPage - 1, currentPage === 1);

            // Page numbers (simple version: all pages)
            for (let i = 1; i <= totalPages; i++) {
                addPageItem(i, i, false, i === currentPage);
            }

            // Next
            addPageItem('»', currentPage + 1, currentPage === totalPages);
        }

        function renderTablePage() {
            const tableBody = $("#documentRequestsTableBody");
            tableBody.empty();

            // Start from full data
            let filtered = allDocumentRequests;

            // 🎯 Filter by Request Type (if selected)
            if (filterRequestType && filterRequestType !== "") {
                filtered = filtered.filter(item => item.request_type === filterRequestType);
            }

            // 🔍 Filter for search
            if (searchQuery && searchQuery.trim() !== "") {
                const q = searchQuery.toLowerCase();
                filtered = filtered.filter(item =>
                    Object.values(item).some(val =>
                        val && String(val).toLowerCase().includes(q)
                    )
                );
            }

            const total = filtered.length;
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, total);

            for (let i = startIndex; i < endIndex; i++) {
                const item = filtered[i];
                tableBody.append(buildTableRow(item));
            }

            // update entries count
            $("#entries").text(total);

            // re-render pagination
            renderPagination(total);
        }

        $(document).on("click", "#paginationControls .page-link", function(e) {
            e.preventDefault();
            const page = Number($(this).data("page"));
            if (!page || page < 1) return;
            currentPage = page;
            renderTablePage();
        });

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
                        // save all rows in memory
                        allDocumentRequests = response.result;
                        currentPage = 1; // reset to first page on new fetch
                        renderTablePage();

                        // 🔹 Populate Request Type filter dropdown dynamically
                        const uniqueTypes = [...new Set(
                            allDocumentRequests
                            .map(item => item.request_type)
                            .filter(Boolean)
                        )];

                        const filterSelect = $("#requestTypeFilter");
                        filterSelect.find("option:not(:first)").remove(); // keep "All Request Types" only
                        uniqueTypes.forEach(type => {
                            filterSelect.append(`<option value="${type}">${type}</option>`);
                        });

                        // 🔹 Row click highlight (keep as before)
                        $("#documentRequestsTable tbody").off("click", "tr").on("click", "tr", function() {
                            $("#documentRequestsTable tbody tr").removeClass("selected-row");
                            $(this).addClass("selected-row");
                        });
                    } else {
                        console.error('Unexpected response format:', response);
                        allDocumentRequests = [];
                        currentPage = 1;
                        renderTablePage();
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
            // 🔍 Search
            $('#searchInput').on('keyup', function() {
                searchQuery = $(this).val();
                currentPage = 1; // go back to first page on new search
                renderTablePage();
            });

            // 📄 Rows per page
            $('#rowsPerPageSelect').on('change', function() {
                const value = parseInt($(this).val(), 10);
                rowsPerPage = isNaN(value) ? 25 : value;
                currentPage = 1;
                renderTablePage();
            });

            // 🎯 Request Type filter
            $('#requestTypeFilter').on('change', function() {
                filterRequestType = $(this).val();
                currentPage = 1;
                renderTablePage();
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
            let enrollmentNo = $(this).data("env");
            $("#uploadRowId").val(rowId);
            $("#enrollmentNo").val(enrollmentNo);

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

        // open multiple upload modal
        $(document).on("click", ".upload-pics", function() {
            let enrollmentNo = $(this).data("env");
            $("#multiEnrollmentNo").val(enrollmentNo);
            $("#uploadMultipleImagesModal").modal("show");
        });

        // submit multiple upload
        $("#uploadMultipleImagesForm").on("submit", function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "dbFiles/upload_multiple_images.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        alert(res.message || "Images uploaded!");
                        $("#uploadMultipleImagesModal").modal("hide");
                    } else {
                        alert(res.message || "Upload failed!");
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("Error uploading images");
                }
            });
        });

        // Open update hindi modal
        $(document).on("click", ".update-hindi-btn", function() {
            let checked = $(".check:checked");

            if (checked.length !== 1) {
                alert("Please select exactly ONE student.");
                return;
            }

            let row = checked.closest("tr");

            let id = checked.val();
            let studentHindi = row.find("td:eq(17)").text().trim(); // Student Name Hindi column
            let fatherHindi = row.find("td:eq(18)").text().trim(); // Father Name Hindi column

            $("#hindiRowId").val(id);
            $("#studentNameHindi").val(studentHindi);
            $("#fatherNameHindi").val(fatherHindi);

            $("#updateHindiModal").modal("show");
        });

        // Submit update
        $("#updateHindiForm").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: "dbFiles/update_hindi_names.php",
                type: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        alert("Hindi names updated successfully!");
                        $("#updateHindiModal").modal("hide");
                        fetch_data_tLeads(currentdateRange); // refresh table
                    } else {
                        alert(res.message || "Update failed!");
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("Server error!");
                }
            });
        });
    </script>
</body>

</html>