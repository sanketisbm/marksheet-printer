<script src="assets/vendors/js/vendor.bundle.base.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

<script src="assets/vendors/chart.js/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script
  src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="assets/datatable_assets/datatables/jquery.dataTables.min.js"></script>
<script src="assets/datatable_assets/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/datatable_assets/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/datatable_assets/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="assets/datatable_assets/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/datatable_assets/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="assets/datatable_assets/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="assets/datatable_assets/datatables-buttons/js/buttons.print.min.js"></script>
<script src="assets/datatable_assets/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="assets/js/off-canvas.js"></script>
<script src="assets/js/hoverable-collapse.js"></script>
<script src="assets/js/template.js"></script>
<script src="assets/js/settings.js"></script>
<script src="assets/js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="assets/js/dashboard.js"></script>
<!-- <script src="assets/partials/main.php"></script> -->
<script src="assets/js/Chart.roundedBarCharts.js"></script>
<!-- End custom js for this page-->
<script src="assets/summernote/summernote-bs4.min.js"></script>
<!-- End custom js for this page-->

<script src="b5-assets/vendor/js/bootstrap.js"></script>
<script>
  $(function () {
    $("#summernote").summernote({
      tabsize: 2,
      height: 300,
    });
  });

  $(document).ready(function () {
    function loadContent() {
      var width = $(window).width();
      <?php $windowWidth = "<script>document.write(width);</script>"; ?>
      console.log(width); // to check the width in console
    }
    loadContent();
    $(window).resize(function () {
      loadContent();
    });
    var active_user = "<?php echo $_SESSION['employee_code'] ?>";

    //get Checked Values
    var checkedValues = [];

    function getCheckedValues() {
      $("input[name='check']").prop("checked", $(this).prop("checked"));

      $("input[name='check']:checked").each(function () {
        checkedValues.push($(this).val());
      });
    }

    $("#checkAll").click(function () {
      $("input[name='check']").prop("checked", $(this).prop("checked"));
    });

    // Check/uncheck the "checkAll" checkbox based on the state of individual checkboxes
    $("input[name='check']").click(function () {
      if (
        $("input[name='check']:checked").length ===
        $("input[name='check']").length
      ) {
        $("#checkAll").prop("checked", true);
      } else {
        $("#checkAll").prop("checked", false);
      }
    });

    // Reassign Leads
    $("#leadManagerTable")
      .DataTable({
        scrollX: true,
        paging: false,
        scrollCollapse: true,
        scrollY: "60vh",
        dom: "Bfrtip",
        buttons: [
          {
            text: "Reassign Lead",
            className: "btn btn-primary reassignleads",
            action: function (e, dt, node, config) {
              var offcanvasElement = document.getElementById(
                "reassignleadsOffcanvasEnd"
              );
              var bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
              bsOffcanvas.show();

              alert("reassignleads");
              getCheckedValues();
              console.log("checkedValues " + checkedValues);
              console.log("active_user " + active_user);
              $.ajax({
                type: "POST",
                url: "dbFiles/fetch_emp_branchwise.php",
                dataType: "json",
                data: { active_user: active_user },
                success: function (response) {
                  var lead_id = $("#lead_id");
                  lead_id.val(checkedValues);

                  var employees = response.emp;
                  var employee_code = $("#employee_code");
                  employee_code.empty();

                  for (var i = 0; i < employees.length; i++) {
                    var emp = employees[i];
                    employee_code.append(
                      $("<option>", {
                        value: emp,
                        text: emp,
                      })
                    );
                  }
                },
                error: function (error) {
                  console.error("Error fetching Employee Name:", error);
                },
              });
            },
          },
          {
            text: "",
            className: "btn btn-primary mdi mdi-filter filters",
            action: function (e, dt, node, config) {
              var offcanvasElement = document.getElementById(
                "reassignleadsOffcanvasEnd"
              );
              var bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
              bsOffcanvas.show();

              alert("reassignleads");
              getCheckedValues();
              console.log("checkedValues " + checkedValues);
              console.log("active_user " + active_user);
              $.ajax({
                type: "POST",
                url: "dbFiles/fetch_emp_branchwise.php",
                dataType: "json",
                data: { active_user: active_user },
                success: function (response) {
                  var lead_id = $("#lead_id");
                  lead_id.val(checkedValues);

                  var employees = response.emp;
                  var employee_code = $("#employee_code");
                  employee_code.empty();

                  for (var i = 0; i < employees.length; i++) {
                    var emp = employees[i];
                    employee_code.append(
                      $("<option>", {
                        value: emp,
                        text: emp,
                      })
                    );
                  }
                },
                error: function (error) {
                  console.error("Error fetching Employee Name:", error);
                },
              });
            },
          },
        ],
      })
      .buttons()
      .container()
      .appendTo($("leadManagerTable_wrapper .col-md-6:eq(0)"));

    // Fetch lead_source
    function fetch_lead_source() {
      $.ajax({
        type: "POST",
        url: "dbFiles/fetch_lead_source.php",
        dataType: "json",
        data: "",
        success: function (response) {
          var leadsources = response.leadsources;
          var leadsourcesSelect = $("#lead_source");
          leadsourcesSelect.empty();

          $.each(leadsources, function (index, leadsource) {
            leadsourcesSelect.append(
              $("<option>", {
                value: leadsource,
                text: leadsource,
              })
            );
          });
        },
        error: function (error) {
          console.error("Error fetching lead_source:", error);
        },
      });
    }
    fetch_lead_source();

    // Fetch states
    function fetch_states() {
      $.ajax({
        type: "POST",
        url: "dbFiles/fetch_states.php",
        dataType: "json",
        data: "",
        success: function (response) {
          var states = response.states;
          var statesSelect = $("#state");
          statesSelect.empty();

          $.each(states, function (index, state) {
            statesSelect.append(
              $("<option>", {
                value: state,
                text: state,
              })
            );
          });
        },
        error: function (error) {
          console.error("Error fetching states:", error);
        },
      });
    }
    fetch_states();

    // Attach onchange event handler to the #state select element
    $("#state").on("change", function () {
      var state = $(this).val();
      fetch_cities(state);
    });

    function fetch_cities(state) {
      $.ajax({
        type: "POST",
        url: "dbFiles/fetch_cities.php",
        dataType: "json",
        data: { state: state },
        success: function (response) {
          var cities = response.cities;
          var citiesSelect = $("#city");
          citiesSelect.empty();

          $.each(cities, function (index, city) {
            citiesSelect.append(
              $("<option>", {
                value: city,
                text: city,
              })
            );
          });
        },
        error: function (error) {
          console.error("Error fetching cities:", error);
        },
      });
    }

    // Fetch levels
    function fetch_levels() {
      $.ajax({
        type: "POST",
        url: "dbFiles/fetch_levels.php",
        dataType: "json",
        data: "",
        success: function (response) {
          var levels = response.levels;
          var levelsSelect = $("#level");
          levelsSelect.empty();

          $.each(levels, function (index, level) {
            levelsSelect.append(
              $("<option>", {
                value: level,
                text: level,
              })
            );
          });
        },
        error: function (error) {
          console.error("Error fetching levels:", error);
        },
      });
    }
    fetch_levels();

    // Attach onchange event handler to the #level select element
    $("#level").on("change", function () {
      var level = $(this).val();
      fetch_courses(level);
    });

    function fetch_courses(level) {
      $.ajax({
        type: "POST",
        url: "dbFiles/fetch_courses.php",
        dataType: "json",
        data: { level: level },
        success: function (response) {
          var courses = response.courses;
          var coursesSelect = $("#course");
          coursesSelect.empty();

          $.each(courses, function (index, course) {
            coursesSelect.append(
              $("<option>", {
                value: course,
                text: course,
              })
            );
          });
        },
        error: function (error) {
          console.error("Error fetching courses:", error);
        },
      });
    }

    // Send email button action
    $(".sendEmailBtn").on("click", function () { });

    // Email config
    $(".emailConfig").on("click", function () {

    });

    // Telegram config
    $(".telegramConfig").on("click", function () {

    });

    // Deactivate Users config
    $(".deactiveUsers").on("click", function () {

    });

    // Activate Users config
    $(".activeUsers").on("click", function () {

    });

    // Email templates
    $(".emailTemplates").on("click", function () {
      getCheckedValues();
      console.log("checkedValues " + checkedValues);
      $.ajax({
        type: "POST",
        url: "dbFiles/fetch_emp.php",
        dataType: "json",
        data: { checkedValues: checkedValues },
        success: function (response) {
          var emp = response.emp;
          var employee_code = $("#employee_code");
          employee_code.val(emp);
        },
        error: function (error) {
          console.error("Error fetching Employee Name:", error);
        },
      });
    });

    // $(".reassignleads").on("click", function () {
    //   alert("reassignleads");
    //   getCheckedValues();
    //   console.log("checkedValues " + checkedValues);
    //   $.ajax({
    //     type: "POST",
    //     url: "dbFiles/fetch_emp.php",
    //     dataType: "json",
    //     data: { checkedValues: checkedValues },
    //     success: function (response) {
    //       var emp = response.emp;
    //       var employees = $("#employees");
    //       employees.val(emp);
    //     },
    //     error: function (error) {
    //       console.error("Error fetching Employee Name:", error);
    //     },
    //   });
    // });

    // Get QR
    $(".qrscan").on("click", function () {
      alert("QR");

    });

    // Runo Calling Number Config
    $(".runoNumber").on("click", function () {

    });
  });

  $(document).ready(function () {
    $("#leadsTable").DataTable({
      scrollX: true,
      paging: false,
      scrollCollapse: true,
      scrollY: "60vh",
    });
    $("#statTable1").DataTable({
      scrollX: true,
      paging: false,
      searching: false,
      scrollCollapse: true,
      width: "100%",
      scrollY: "300px",
    });
    $("#statTable2").DataTable({
      scrollX: true,
      paging: false,
      searching: false,
      scrollCollapse: true,
      width: "100%",
      scrollY: "300px",
    });
    $("#statTable3").DataTable({
      scrollX: true,
      paging: false,
      searching: false,
      scrollCollapse: true,
      width: "100%",
      scrollY: "300px",
    });
    $("#statTable4").DataTable({
      scrollX: true,
      paging: false,
      searching: false,
      scrollCollapse: true,
      width: "100%",
      scrollY: "300px",
    });
    $("#statTable5").DataTable({
      scrollX: true,
      paging: false,
      searching: false,
      scrollCollapse: true,
      width: "100%",
      scrollY: "300px",
    });

    $("#chatleadsTable")
      .DataTable({
        scrollX: true,
        paging: false,
        scrollCollapse: true,
        scrollY: "60vh",
        dom: "Bfrtip",
        buttons: [
          {
            text: "Add Lead",
            className: "btn btn-primary chatleads",
            action: function (e, dt, node, config) {
              $("#chatleads").modal("show");
            },
          },
        ],
      })
      .buttons()
      .container()
      .appendTo($("chatleadsTable_wrapper .col-md-6:eq(0)"));

    $("#activeUser")
      .DataTable({
        scrollX: true,
        paging: false,
        scrollCollapse: true,
        scrollY: "50vh",
        dom: "Bfrtip",
        buttons: [
          {
            className:
              "btn px-3 py-2 btn-primary mdi mdi-email-variant emailConfig",
            titleAttr: "Configure Email",
            action: function (e, dt, node, config) {
              $("#emailConfig").modal("show");
            },
          },
          {
            titleAttr: "Configure Telegram",
            className:
              "btn px-3 py-2 btn-primary mdi mdi-telegram telegramConfig",
            action: function (e, dt, node, config) {
              $("#telegramConfig").modal("show");
            },
          },
          {
            text: "",
            titleAttr: "Activate Users",
            className:
              "btn px-3 py-2 btn-primary mdi mdi-account-check activeUsers",
            action: function (e, dt, node, config) { },
          },
          {
            titleAttr: "Deactivate Users",
            className:
              "btn px-3 py-2 btn-primary mdi mdi-account-off deactiveUsers",
            action: function (e, dt, node, config) {
              $("#deactiveUsers").modal("show");
            },
          },
          {
            className: "btn px-3 py-2 btn-primary mdi mdi-qrcode-scan qrscan",
            action: function (e, dt, node, config) {
              var offcanvasElement = document.getElementById("qrscanoffcanvas");
              var bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
              bsOffcanvas.show();
            },
          },
          {
            className:
              "btn px-3 py-2 btn-primary mdi mdi-phone-plus runoNumber",
            action: function (e, dt, node, config) {
              $("#runoNumber").modal("show");
            },
          },
        ],
      })
      .buttons()
      .container()
      .appendTo($("#activeUserTable_wrapper .col-md-6:eq(0)"));

    // $("#activeUserEmp")
    //   .DataTable({
    //     scrollX: true,
    //     paging: false,
    //     scrollCollapse: true,
    //     scrollY: "50vh",
    //     dom: "Bfrtip",
    //     buttons: [
    //       {
    //         text: "Configure Email",
    //         className: "btn btn-primary emailConfig",
    //         action: function (e, dt, node, config) {
    //           $("#emailConfig").modal("show");
    //         },
    //       },
    //       {
    //         text: "Configure Telegram",
    //         className: "btn btn-primary telegramConfig",
    //         action: function (e, dt, node, config) {
    //           $("#telegramConfig").modal("show");
    //         },
    //       },
    //     ],
    //   })
    //   .buttons()
    //   .container()
    //   .appendTo($("#activeUserEmpTable_wrapper .col-md-6:eq(0)"));

    $("#rawDataTable")
      .DataTable({
        fixedColumns: {
          leftColumns: 2,
          rightColumns: 1,
        },
        scrollX: true,
        paging: false,
        order: [[11, "desc"]],
        scrollCollapse: true,
        scrollY: "60vh",
        ordering: false,
        dom: "Bfrtip",
        buttons: [
          {
            text: "Bulk Email",
            className: "btn btn-primary sendEmailBtn",
            id: "sendEmailBtn",
            action: function (e, dt, node, config) {
              $("#emailSend").modal("show");
              getCheckedValues();
              fetch_email_body();
              console.log("sendEmailBtn " + checkedValues);

              // fetch_template_sub
              $.ajax({
                type: "POST",
                url: "dbFiles/fetch_template_sub.php",
                dataType: "json",
                success: function (response) {
                  var subjects = response.subjects;
                  var subjectsSelect = $("#subject");
                  subjectsSelect.empty();

                  for (var i = 0; i < subjects.length; i++) {
                    var subject = subjects[i];
                    subjectsSelect.append(
                      $("<option>", {
                        value: subject,
                        text: subject,
                      })
                    );
                  }
                },
                error: function (error) {
                  console.error("Error fetching subjects:", error);
                },
              });

              // fetch_to_emails
              $.ajax({
                type: "POST",
                url: "dbFiles/fetch_to_emails.php",
                dataType: "json",
                data: { checkedValues: checkedValues },
                success: function (response) {
                  var email_id = response.email_id;
                  var toEmail = $("#toEmail");
                  var id = $("#id");
                  toEmail.empty();
                  toEmail.val(email_id);
                  id.empty();
                  id.val(checkedValues);
                },
                error: function (error) {
                  console.error("Error fetching Emails:", error);
                },
              });

              // fetch_from_emails
              $.ajax({
                type: "POST",
                url: "dbFiles/fetch_from_emails.php",
                dataType: "json",
                success: function (response) {
                  var emailIds = response.emailId;
                  var fromEmail = $("#fromEmail");
                  fromEmail.empty();

                  for (var i = 0; i < emailIds.length; i++) {
                    var emailId = emailIds[i];
                    fromEmail.append(
                      $("<option>", {
                        value: emailId,
                        text: emailId,
                      })
                    );
                  }
                },
                error: function (error) {
                  console.error("Error fetching Emails:", error);
                },
              });

              // fetch_email_body
              $("#subject").on("change", function () {
                fetch_email_body();
              });

              function fetch_email_body() {
                var subject = document.getElementById("subject").value;
                $.ajax({
                  type: "POST",
                  url: "dbFiles/fetch_email_body.php",
                  dataType: "json",
                  data: { subject: subject },
                  success: function (response) {
                    var body = response.body;
                    $("#template").html(body);
                  },
                  error: function (error) {
                    console.error("Error fetching counselors:", error);
                  },
                });
              }
            },
          },
          {
            text: "Configure New Email",
            className: "btn btn-primary",
            action: function (e, dt, node, config) {
              $("#emailConfig").modal("show");
            },
          },
        ],
      })
      .buttons()
      .container()
      .appendTo($("#emailConfigTable_wrapper .col-md-6:eq(0)"));

    $("#emailConfigTable")
      .DataTable({
        scrollX: true,
        paging: false,
        scrollCollapse: true,
        scrollY: "60vh",
        dom: "Bfrtip",
        buttons: [
          {
            text: "Configure New Email",
            className: "btn btn-primary",
            action: function (e, dt, node, config) {
              $("#emailConfig").modal("show");
            },
          },
        ],
      })
      .buttons()
      .container()
      .appendTo($("#emailConfigTable_wrapper .col-md-6:eq(0)"));

    $("#campaignManager")
      .DataTable({
        scrollX: true,
        paging: false,
        scrollCollapse: true,
        scrollY: "60vh",
        dom: "Bfrtip",
        buttons: [
          {
            text: "Add Campaign",
            className: "btn btn-primary",
            action: function (e, dt, node, config) {
              $("#campaignManagerModal").modal("show");
            },
          },
        ],
      })
      .buttons()
      .container()
      .appendTo($("#campaignManager_wrapper .col-md-6:eq(0)"));

    $("#emailTemplate")
      .DataTable({
        scrollX: true,
        paging: false,
        scrollCollapse: true,
        scrollY: "60vh",
        dom: "Bfrtip",
        buttons: [
          {
            text: "Add New Template",
            className: "btn btn-primary",
            action: function (e, dt, node, config) {
              $("#emailTemplateModal").modal("show");
            },
          },
        ],
      })
      .buttons()
      .container()
      .appendTo($("#emailTemplate_wrapper .col-md-6:eq(0)"));

    $("#smsTemplate")
      .DataTable({
        scrollX: true,
        paging: false,
        scrollCollapse: true,
        scrollY: "60vh",
        dom: "Bfrtip",
        buttons: [
          {
            text: "Add New Template",
            className: "btn btn-primary",
            action: function (e, dt, node, config) {
              $("#smsTemplateModal").modal("show");
            },
          },
        ],
      })
      .buttons()
      .container()
      .appendTo($("#smsTemplate_wrapper .col-md-6:eq(0)"));
  });
</script>