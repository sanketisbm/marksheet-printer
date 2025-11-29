<div class="offcanvas offcanvas-end" tabindex="-1" id="filtersOffcanvasEnd" aria-labelledby="filtersOffcanvasEndLabel">
    <div class="offcanvas-header">
        <h5 id="filtersOffcanvasEndLabel" class="offcanvas-title">Filter Leads</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close">X</button>
    </div>
    <div class="offcanvas-body m-0 flex-grow-0">
        <form action="action-filters" method="post">
            <div class="d-flex justify-content-end mb-4">
                <button id="addFilter" type="button" class="btn btn-sm btn-primary mdi mdi-plus-circle"></button>
                <input type="text" id="date-range-filter"
                    class="btn btn-light bg-white dropdown-toggle text-right ml-auto d-flex" />
            </div>
            <div id="filterBox">
                <div class="row filterSet">
                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                        <select name="filterTitle" class="form-control js-example-basic-single w-100">
                            <option value="id"> Id </option>
                            <option value="tmr_id"> Tmr Id </option>
                            <option value="application_id"> Application Id </option>
                            <option value="branch"> Branch </option>
                            <option value="period"> Period </option>
                            <option value="enrollment_no"> Enrollment No </option>
                            <option value="roll_no"> Roll No </option>
                            <option value="program"> Program </option>
                            <option value="student_name"> Student Name </option>
                            <option value="father_name"> Father Name </option>
                            <option value="mother_name"> Mother Name </option>
                            <option value="program_print_name"> Program Print Name </option>
                            <option value="stream"> Stream </option>
                            <option value="exam_session"> Exam Session </option>
                            <option value="entry_date"> Entry Date </option>
                            <option value="sl_no"> Sl No </option>
                            <option value="doi"> Doi </option>
                            <option value="pccs"> Pccs </option>
                            <option value="co_na"> Co Na </option>
                            <option value="m_wn"> M Wn </option>
                            <option value="no_of_exams"> No Of Exams </option>
                            <option value="signature"> Signature </option>
                            <option value="template_id"> Template Id </option>
                            <option value="sub1_code"> Sub1 Code </option>
                            <option value="sub1_name"> Sub1 Name </option>
                            <option value="sub1_ext_max"> Sub1 Ext Max </option>
                            <option value="sub1_ext_obt"> Sub1 Ext Obt </option>
                            <option value="sub1_int_max"> Sub1 Int Max </option>
                            <option value="sub1_int_obt"> Sub1 Int Obt </option>
                            <option value="sub1_total_obt"> Sub1 Total Obt </option>
                            <option value="sub1_result_Remark"> Sub1 Result Remark </option>
                            <option value="sub2_code"> Sub2 Code </option>
                            <option value="sub2_name"> Sub2 Name </option>
                            <option value="sub2_ext_max"> Sub2 Ext Max </option>
                            <option value="sub2_ext_obt"> Sub2 Ext Obt </option>
                            <option value="sub2_int_max"> Sub2 Int Max </option>
                            <option value="sub2_int_obt"> Sub2 Int Obt </option>
                            <option value="sub2_total_obt"> Sub2 Total Obt </option>
                            <option value="sub2_result_Remark"> Sub2 Result Remark </option>
                            <option value="sub3_code"> Sub3 Code </option>
                            <option value="sub3_name"> Sub3 Name </option>
                            <option value="sub3_ext_max"> Sub3 Ext Max </option>
                            <option value="sub3_ext_obt"> Sub3 Ext Obt </option>
                            <option value="sub3_int_max"> Sub3 Int Max </option>
                            <option value="sub3_int_obt"> Sub3 Int Obt </option>
                            <option value="sub3_total_obt"> Sub3 Total Obt </option>
                            <option value="sub3_result_Remark"> Sub3 Result Remark </option>
                            <option value="sub4_code"> Sub4 Code </option>
                            <option value="sub4_name"> Sub4 Name </option>
                            <option value="sub4_ext_max"> Sub4 Ext Max </option>
                            <option value="sub4_ext_obt"> Sub4 Ext Obt </option>
                            <option value="sub4_int_max"> Sub4 Int Max </option>
                            <option value="sub4_int_obt"> Sub4 Int Obt </option>
                            <option value="sub4_total_obt"> Sub4 Total Obt </option>
                            <option value="sub4_result_Remark"> Sub4 Result Remark </option>
                            <option value="sub5_code"> Sub5 Code </option>
                            <option value="sub5_name"> Sub5 Name </option>
                            <option value="sub5_ext_max"> Sub5 Ext Max </option>
                            <option value="sub5_ext_obt"> Sub5 Ext Obt </option>
                            <option value="sub5_int_max"> Sub5 Int Max </option>
                            <option value="sub5_int_obt"> Sub5 Int Obt </option>
                            <option value="sub5_total_obt"> Sub5 Total Obt </option>
                            <option value="sub5_result_Remark"> Sub5 Result Remark </option>
                            <option value="sub6_code"> Sub6 Code </option>
                            <option value="sub6_name"> Sub6 Name </option>
                            <option value="sub6_ext_max"> Sub6 Ext Max </option>
                            <option value="sub6_ext_obt"> Sub6 Ext Obt </option>
                            <option value="sub6_int_max"> Sub6 Int Max </option>
                            <option value="sub6_int_obt"> Sub6 Int Obt </option>
                            <option value="sub6_total_obt"> Sub6 Total Obt </option>
                            <option value="sub6_result_Remark"> Sub6 Result Remark </option>
                            <option value="sub7_code"> Sub7 Code </option>
                            <option value="sub7_name"> Sub7 Name </option>
                            <option value="sub7_ext_max"> Sub7 Ext Max </option>
                            <option value="sub7_ext_obt"> Sub7 Ext Obt </option>
                            <option value="sub7_int_max"> Sub7 Int Max </option>
                            <option value="sub7_int_obt"> Sub7 Int Obt </option>
                            <option value="sub7_total_obt"> Sub7 Total Obt </option>
                            <option value="sub7_result_Remark"> Sub7 Result Remark </option>
                            <option value="sub8_code"> Sub8 Code </option>
                            <option value="sub8_name"> Sub8 Name </option>
                            <option value="sub8_ext_max"> Sub8 Ext Max </option>
                            <option value="sub8_ext_obt"> Sub8 Ext Obt </option>
                            <option value="sub8_int_max"> Sub8 Int Max </option>
                            <option value="sub8_int_obt"> Sub8 Int Obt </option>
                            <option value="sub8_total_obt"> Sub8 Total Obt </option>
                            <option value="sub8_result_Remark"> Sub8 Result Remark </option>
                            <option value="sub9_code"> Sub9 Code </option>
                            <option value="sub9_name"> Sub9 Name </option>
                            <option value="sub9_ext_max"> Sub9 Ext Max </option>
                            <option value="sub9_ext_obt"> Sub9 Ext Obt </option>
                            <option value="sub9_int_max"> Sub9 Int Max </option>
                            <option value="sub9_int_obt"> Sub9 Int Obt </option>
                            <option value="sub9_total_obt"> Sub9 Total Obt </option>
                            <option value="sub9_result_Remark"> Sub9 Result Remark </option>
                            <option value="sub10_code"> Sub10 Code </option>
                            <option value="sub10_name"> Sub10 Name </option>
                            <option value="sub10_ext_max"> Sub10 Ext Max </option>
                            <option value="sub10_ext_obt"> Sub10 Ext Obt </option>
                            <option value="sub10_int_max"> Sub10 Int Max </option>
                            <option value="sub10_int_obt"> Sub10 Int Obt </option>
                            <option value="sub10_total_obt"> Sub10 Total Obt </option>
                            <option value="sub10_result_Remark"> Sub10 Result Remark </option>
                            <option value="sub11_code"> Sub11 Code </option>
                            <option value="sub11_name"> Sub11 Name </option>
                            <option value="sub11_ext_max"> Sub11 Ext Max </option>
                            <option value="sub11_ext_obt"> Sub11 Ext Obt </option>
                            <option value="sub11_int_max"> Sub11 Int Max </option>
                            <option value="sub11_int_obt"> Sub11 Int Obt </option>
                            <option value="sub11_total_obt"> Sub11 Total Obt </option>
                            <option value="sub11_result_Remark"> Sub11 Result Remark </option>
                            <option value="sub12_code"> Sub12 Code </option>
                            <option value="sub12_name"> Sub12 Name </option>
                            <option value="sub12_ext_max"> Sub12 Ext Max </option>
                            <option value="sub12_ext_obt"> Sub12 Ext Obt </option>
                            <option value="sub12_int_max"> Sub12 Int Max </option>
                            <option value="sub12_int_obt"> Sub12 Int Obt </option>
                            <option value="sub12_total_obt"> Sub12 Total Obt </option>
                            <option value="sub12_result_Remark"> Sub12 Result Remark </option>
                            <option value="sub13_code"> Sub13 Code </option>
                            <option value="sub13_name"> Sub13 Name </option>
                            <option value="sub13_ext_max"> Sub13 Ext Max </option>
                            <option value="sub13_ext_obt"> Sub13 Ext Obt </option>
                            <option value="sub13_int_max"> Sub13 Int Max </option>
                            <option value="sub13_int_obt"> Sub13 Int Obt </option>
                            <option value="sub13_total_obt"> Sub13 Total Obt </option>
                            <option value="sub13_result_Remark"> Sub13 Result Remark </option>
                            <option value="percentage"> Percentage </option>
                            <option value="Ext_max_total"> Ext Max Total </option>
                            <option value="Ext_max_obt_total"> Ext Max Obt Total </option>
                            <option value="int_max_total"> Int Max Total </option>
                            <option value="int_max_obt_total"> Int Max Obt Total </option>
                            <option value="total_obt"> Total Obt </option>
                            <option value="total_marks_obt_words"> Total Marks Obt Words </option>
                            <option value="result"> Result </option>
                            <option value="division"> Division </option>
                            <option value="qr_code_data"> Qr Code Data </option>
                            <option value="sem1_max_marks"> Sem1 Max Marks </option>
                            <option value="sem1_max_obt"> Sem1 Max Obt </option>
                            <option value="sem2_max_marks"> Sem2 Max Marks </option>
                            <option value="sem2_max_obt"> Sem2 Max Obt </option>
                            <option value="sem3_max_marks"> Sem3 Max Marks </option>
                            <option value="sem3_max_obt"> Sem3 Max Obt </option>
                            <option value="sem4_max_marks"> Sem4 Max Marks </option>
                            <option value="sem4_max_obt"> Sem4 Max Obt </option>
                            <option value="sem5_max_marks"> Sem5 Max Marks </option>
                            <option value="sem5_max_obt"> Sem5 Max Obt </option>
                            <option value="sem6_max_marks"> Sem6 Max Marks </option>
                            <option value="sem6_max_obt"> Sem6 Max Obt </option>
                            <option value="sem7_max_marks"> Sem7 Max Marks </option>
                            <option value="sem7_max_obt"> Sem7 Max Obt </option>
                            <option value="sem8_max_marks"> Sem8 Max Marks </option>
                            <option value="sem8_max_obt"> Sem8 Max Obt </option>
                            <option value="sem9_max_marks"> Sem9 Max Marks </option>
                            <option value="sem9_max_obt"> Sem9 Max Obt </option>
                            <option value="sem10_max_marks"> Sem10 Max Marks </option>
                            <option value="sem10_max_obt"> Sem10 Max Obt </option>
                            <option value="grand_total_max"> Grand Total Max </option>
                            <option value="grand_total_obt"> Grand Total Obt </option>
                            <option value="final_result"> Final Result </option>
                            <option value="cGrand_Total_Max"> Cgrand Total Max </option>
                            <option value="cGrand_Total_Obt"> Cgrand Total Obt </option>
                            <option value="cGrand_Total_Inwords"> Cgrand Total Inwords </option>
                            <option value="cPercentage"> Cpercentage </option>
                            <option value="cDivision"> Cdivision </option>
                            <option value="hindi_flag"> Hindi Flag </option>
                            <option value="created_at"> Created At </option>
                            <option value="updated_at"> Updated At </option>
                        </select>
                    </div>
                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                        <select name="filterSearch" class="form-control js-example-basic-single w-100">
                            <option value="">Search</option>
                            <option value="=">EQUAL</option>
                            <option value="!=">NOT EQUAL</option>
                            <option value="LIKE">LIKE</option>
                            <option value="NOT LIKE">NOT LIKE</option>
                            <option value="BETWEEN">BETWEEN</option>
                            <option value=">">GREATER THAN</option>
                            <option value="<">LESS THAN</option>
                            <option value=">=">GREATER THAN OR EQUAL TO</option>
                            <option value="<=">LESS THAN OR EQUAL TO</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-4 col-md-6 col-sm-12">
                        <input name="filterValue" type="text" class="form-control" placeholder="Enter Search Value">
                    </div>
                    <input type="hidden" name="dateRange" id="dateRange" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            <button type="button" class="btn btn-sm btn-inverse-danger btn-fw"
                data-bs-dismiss="offcanvas">Close</button>
        </form>
    </div>
</div>
<script>
    var dateRange = "";
    var startDate = moment().startOf('week');
    var endDate = moment().endOf('week');

    $('#date-range-filter').daterangepicker({
        opens: 'left',
        locale: {
            format: 'YYYY-MM-DD'
        },
        startDate: startDate,
        endDate: endDate
    }, function(start, end) {
        dateRange = start.format('YYYY-MM-DD') + '*' + end.format('YYYY-MM-DD');
        $('#dateRange').val(dateRange);
    });

    var today = new Date();
    var formatDate = function(date) {
        var year = date.getFullYear();
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var day = ('0' + date.getDate()).slice(-2);
        return year + '-' + month + '-' + day;
    };
    var currentdateRange = formatDate(startDate.toDate()) + "*" + formatDate(endDate.toDate());
    $('#dateRange').val(currentdateRange);
</script>