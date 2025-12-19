<div class="d-flex w-100 justify-content-end " style="margin-top:4.8cm; width: 18.92cm !important;">
    <h4 class="text-end mt-2 font-14-5pt" style="font-size: 14.5pt;font-family:  Arial, sans-serif; font-weight: 400;">
        No. <?php echo $sl_no ?> </h4>
</div>

<div class="marksheet mb-0 margin-0-5" style="margin-top:0.5cm;width: 18.92cm !important;">
    <?php
    $subCountTable = 0;
    for ($i = 1; $i < 14; $i++) {
        if (!empty(${'sub' . $i . '_code'})) {
            $subCountTable++;
        }
    }
    if ($subCountTable === 12) {
        $topSpacing = "m-2-space";
    } elseif ($subCountTable === 13) {
        $topSpacing = "m-2-space";
    } else {
        $topSpacing = "m-3-space";
    }
    if ($program_print_name === "Master in Business Administration") {
        $streamCss = 'style = "font-size: 12pt !important"';
    } else {
        $streamCss = '';
    }
    $envArray = str_split($enrollment_no); // split into characters
    $startYr = $envArray[0] . $envArray[1];
    ?>
    <div class="subject-info text-center">
        <p class="program_print_name custom-margin-bottom"><?php echo  $program_print_name ?></p>
        <?php if (!empty($stream)) { ?>
            <p class="program_print_name custom-margin-bottom" <?php echo $streamCss ?>>(<?php echo  $stream ?>)</p>
        <?php } ?>
        <p class="custom-margin-bottom fw-bold font-11pt">Examination: <?php echo  $exam_session ?></p>
        <p class="custom-margin-bottom fw-bold font-11pt"><?php echo  $period ?></p>
    </div>

    <table class="mb-2 w-100 font-11pt text-start <?php echo $topSpacing ?>" style="width: 18.92cm !important;">
        <tr>
            <td class="pn-col-1 text-start">Name of Student</td>
            <td colspan="3" class="pn-col-2 text-start">: <?php echo  $student_name ?></td>
        </tr>
        <tr>
            <td class="pn-col-1 text-start">Father’s/Husband’s Name</td>
            <td class="pn-col-2 text-start">: <?php echo  $father_name ?></td>
            <td class="pn-col-3 text-start">Roll No</td>
            <td class="pn-col-4 text-start">:<?php echo  $roll_no ?></td>
        </tr>
        <tr>
            <td class="pn-col-1 text-start">Mother’s Name</td>
            <td class="pn-col-2 text-start">: <?php echo  $mother_name ?></td>
            <td class="pn-col-3 text-start">Session</td>
            <td class="pn-col-4 text-start">:20<?php echo  $startYr ?></td>
        </tr>
    </table>

    <table class="table table-bordered table-marks" style="width: 18.92cm !important;">
        <thead>
            <tr>
                <th rowspan="2" class="column-87 font-12pt border-start-1">Subject<br>Code</th>
                <th rowspan="2" class="column-336 font-12pt" style="vertical-align: text-top !important;">
                    <div style="padding-top: 0.45cm !important;">Subjects</div>
                </th>
                <th colspan="2" class="font-12pt" style="height: 1.04cm !important;">External<br>Marks</th>
                <th colspan="2" class="font-12pt" style="height: 1.04cm !important;">Internal<br>Marks</th>
                <th rowspan="2" class="column-47 font-11pt">Total Marks OBTD</th>
                <th rowspan="2" class="column-57 font-11-5pt" style="vertical-align: text-top !important;">
                    <div style="padding-top: 0.3cm !important;">Result/ Remark</div>
                </th>
            </tr>
            <tr>
                <th class="column-47 border-top-0 font-10pt" style="height: 0.50cm !important;">MAX</th>
                <th class="column-47 border-top-0 font-10pt" style="height: 0.50cm !important;">OBTD</th>
                <th class="column-47 border-top-0 font-10pt" style="height: 0.50cm !important;">MAX</th>
                <th class="column-47 border-top-0 font-10pt" style="height: 0.50cm !important;">OBTD</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 1; $i < 14; $i++) {
                $sub_code = ${'sub' . $i . '_code'};
                if (isset(${'sub' . $i . '_code'})) {
                    $classValue = "";
                    $fontSizeValue = "";

                    $sub_codes = ["HSD-MEPS801", "HSD-MEPS701", "HSD-MERBL701", "HSD-MERBL801", "HSD-MEPBL501", "HSD-MERBL501", "HSD-ETCPS601", "HSD-ETCPBL601", "HSD-ETCABL601"];

                    if (in_array(${'sub' . $i . '_code'}, $sub_codes)) {
                        $fontSizeValue .= 'font-size: 9pt !important';
                    }

                    if (preg_match('/[\p{Devanagari}]/u', ${'sub' . $i . '_name'})) {
                        $classValue = 'Mangal, sans-serif !important; font-size: 8pt !important';
                    } elseif (${'sub' . $i . '_name'} === "Philosophical & Conceptual Foundation of Research Methodology") {
                        $classValue .= 'calibri, sans-serif !important; font-size: 9pt !important';
                    } elseif (${'sub' . $i . '_name'} === "System Development Project (System Design & Implementation)") {
                        $classValue .= 'calibri, sans-serif !important; font-size: 9pt !important';
                    } elseif (strlen(${'sub' . $i . '_name'}) > 55 && strlen(${'sub' . $i . '_name'}) < 65) {
                        $classValue .= 'calibri, sans-serif !important; font-size: 9.5pt !important';
                    } elseif (strlen(${'sub' . $i . '_name'}) > 65 && strlen(${'sub' . $i . '_name'}) < 70) {
                        $classValue .= 'calibri, sans-serif !important; font-size: 9pt !important';
                    } elseif (strlen(${'sub' . $i . '_name'}) > 70) {
                        $classValue .= 'calibri, sans-serif !important; font-size: 8pt !important';
                    } else {
                        $classValue = 'calibri, sans-serif !important;';
                    }
            ?>
                    <tr>
                        <td class="column-47 text-start ps-1 font-10pt border-start-1" style="<?php echo  $fontSizeValue; ?>">
                            <?php echo  ${'sub' . $i . '_code'} ?>
                        </td>
                        <td class="column-47 text-start ps-1 font-10pt" style="font-family: <?php echo  $classValue; ?>;">
                            <?php echo  ${'sub' . $i . '_name'} ?>
                        </td>
                        <td class="column-47 text-center font-10pt"><?php echo  ${'sub' . $i . '_ext_max'} ?>
                        </td>
                        <td class="column-47 text-center font-10pt"><?php echo  ${'sub' . $i . '_ext_obt'} ?>
                        </td>
                        <td class="column-47 text-center font-10pt"><?php echo  ${'sub' . $i . '_int_max'} ?>
                        </td>
                        <td class="column-47 text-center font-10pt"><?php echo  ${'sub' . $i . '_int_obt'} ?>
                        </td>
                        <td class="column-47 text-center font-10pt">
                            <?php echo  ${'sub' . $i . '_total_obt'} ?>
                        </td>
                        <td class="column-47 text-center font-10pt">
                            <?php echo  ${'sub' . $i . '_result_Remark'} ?>
                        </td>
                    </tr>
            <?php }
            } ?>
            <tr>
                <td colspan="2" class="font-12pt fw-bold border-start-1" style="vertical-align: text-top !important;">
                    <div>Grand Total</div>
                </td>
                <td class="column-47 footer-total" style="vertical-align: text-top !important;font-size: 10pt !important;">
                    <div style="padding-top: 0.1cm !important;"><?php echo  $Ext_max_total ?></div>
                </td>
                <td class="column-47 footer-total" style="vertical-align: text-top !important;font-size: 10pt !important;">
                    <div style="padding-top: 0.1cm !important;"><?php echo  $Ext_max_obt_total ?></div>
                </td>
                <td class="column-47 footer-total" style="vertical-align: text-top !important;font-size: 10pt !important;">
                    <div style="padding-top: 0.1cm !important;"><?php echo  $int_max_total ?></div>
                </td>
                <td class="column-47 footer-total" style="vertical-align: text-top !important;font-size: 10pt !important;">
                    <div style="padding-top: 0.1cm !important;"><?php echo  $int_max_obt_total ?></div>
                </td>
                <td class="column-47 footer-total fw-bold" style="vertical-align: text-top !important;font-size: 10pt !important;">
                    <div style="padding-top: 0.1cm !important;"><?php echo  $total_obt ?></div>
                </td>
                <td class="column-47 footer-total"></td>
            </tr>
        </tbody>
    </table>

    <table style="width: 18.92cm !important; margin-top: 1rem !important">
        <tr>
            <td style="padding: 0.1cm 0 0 0; display:flex; flex-direction: column;">
                <table style="width: 17.21cm !important">
                    <tr>
                        <td class="font-11pt text-start fw-bold" style="border:0 !important; padding: 0 0 5px 0 !important">
                            Total Marks Obtained (In words) <span>: <?php echo  $total_marks_obt_words ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 0 5px 0 !important">
                            <table>
                                <tr>
                                    <td class="font-11pt text-start fw-bold w-2-5" style="padding: 0 !important">Result</td>
                                    <td class="font-11pt text-start fw-bold" style="padding: 0 !important"> : <?php echo  $result ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 0 5px 0 !important">
                            <table>
                                <tr>
                                    <td class="font-11pt text-start fw-bold w-2-5" style="padding: 0 !important">Division</td>
                                    <td class="font-11pt text-start fw-bold" style="padding: 0 !important"> : <?php echo  $division ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 0 5px 0 !important">
                            <table>
                                <tr>
                                    <td class="font-11pt text-start fw-bold w-2-5" style="padding: 0 !important">Date of Issue</td>
                                    <td class="font-11pt text-start fw-bold" style="padding: 0 !important"> : <?php echo  $doi ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 1.71cm !important;vertical-align: top !important;padding: 0 !important;">
                <table style="width: 1.71cm !important;">
                    <tr>
                        <td>
                            <img src="http://api.qrserver.com/v1/create-qr-code/?data=<?php echo  urlencode($qr_code_data) ?>&size=65x65"
                                alt="QR Code">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<table class="table table-marks table-times-new-roman" style="padding: 5px !important;width: 18.92cm !important;margin-top: 0.01cm;position: absolute;bottom: 3.5cm;">
    <?php
    // Split and sanitize the `pccs` string
    $signs = explode('#', $pccs);
    $preparedBy = trim($signs[0] ?? 'default');
    $checkedBy = trim($signs[1] ?? 'default');
    $controllerBy = trim($signs[2] ?? 'default');
    ?>
    <tr>
        <td class="<?php echo  $preparedBy ?>" style="border:0 !important;height: 1.8cm;vertical-align: bottom !important;">
            <?php if ($signature === "SIGN") { ?>
                <img class="<?php echo  $preparedBy ?>" src="<?php echo 'assets/images/' . $preparedBy . '.png' ?>"
                    style="float:left">
            <?php } ?>
        </td>
        <td style="border:0 !important;height: 1.8cm;vertical-align: bottom !important;">
            <?php if ($signature === "SIGN") { ?>
                <img class="<?php echo  $checkedBy ?>" src="<?php echo 'assets/images/' . $checkedBy . '.png' ?>">
            <?php } ?>
        </td>
        <td style="border:0 !important;height: 1.8cm;vertical-align: bottom !important;">
            <?php if ($signature === "SIGN") { ?>
                <img class="<?php echo  $controllerBy ?>" src="<?php echo 'assets/images/' . $controllerBy . '.png' ?>"
                    style="float:right">
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td style="width: 33.33%;border:0 !important;vertical-align: bottom !important;">
            <p class="fw-bold font-11pt m-0 text-start">Prepared By</p>
        </td>
        <td style="width: 33.33%;border:0 !important;vertical-align: bottom !important;">
            <p class="fw-bold font-11pt m-0 text-center">Checked By</p>
        </td>
        <td style="width: 33.33%;border:0 !important;vertical-align: bottom !important;">
            <p class="fw-bold font-11pt m-0 text-end">Controller of Examination</p>
        </td>
    </tr>
</table>