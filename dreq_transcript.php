<?php
// dreq_transcript.php
// NOTE: This file is included from generated-documents.php,
// which already has session + DB connection.

// Safeguard against redeclaration if included more than once
if (!function_exists('generate_document')) {

    function generate_document($enroll)
    {
        global $conn, $grouped;

        // If this enrollment isn't in the grouped array, nothing to do
        if (!isset($grouped[$enroll])) {
            return;
        }

        $enrollEscaped = mysqli_real_escape_string($conn, $enroll);

        $templateSql    = "SELECT * FROM results WHERE enrollment_no = '{$enrollEscaped}' ORDER BY period ASC";
        $templateResult = mysqli_query($conn, $templateSql);

        if (!$templateResult || mysqli_num_rows($templateResult) == 0) {
            // No results for this enrollment
            return;
        }

        // Overall totals per enrollment
        $grand_total     = 0;
        $grand_total_obt = 0;

        while ($resultData = mysqli_fetch_assoc($templateResult)) {

            // Build student info ONCE
            if (empty($grouped[$enroll]['student_info'])) {

                $passing_year = "";

                if (!empty($resultData['exam_session'])) {
                    $parts        = explode("-", $resultData['exam_session']);
                    $passing_year = $parts[1] ?? "";
                }

                $grouped[$enroll]['student_info'] = [
                    'student_name'   => $resultData['student_name'] ?? '',
                    'enrollment_no'  => $resultData['enrollment_no'] ?? '',
                    'father_name'    => $resultData['father_name'] ?? '',
                    'program'        => $resultData['program'] ?? '',
                    'specialization' => $resultData['stream'] ?? '',
                    'exam_session'   => $resultData['exam_session'] ?? '',
                    'passing_year'   => $passing_year ?: '-',
                ];
            }

            $period = $resultData['period'];

            if (!isset($grouped[$enroll]['results'][$period])) {
                $grouped[$enroll]['results'][$period] = [
                    'subjects' => [],
                    'totals'   => []
                ];
            }

            // Push subjects for this period
            for ($i = 1; $i <= 13; $i++) {
                if (!empty($resultData['sub' . $i . '_code'])) {
                    $grouped[$enroll]['results'][$period]['subjects'][] = [
                        'sub_code'          => $resultData['sub' . $i . '_code'],
                        'sub_name'          => $resultData['sub' . $i . '_name'],
                        'sub_ext_max'       => $resultData['sub' . $i . '_ext_max'],
                        'sub_ext_obt'       => $resultData['sub' . $i . '_ext_obt'],
                        'sub_int_max'       => $resultData['sub' . $i . '_int_max'],
                        'sub_int_obt'       => $resultData['sub' . $i . '_int_obt'],
                        'sub_total_obt'     => $resultData['sub' . $i . '_total_obt'],
                        'sub_result_remark' => $resultData['sub' . $i . '_result_Remark'],
                    ];
                }
            }

            // Per-period totals
            $grouped[$enroll]['results'][$period]['totals'] = [
                'Ext_max_total'     => $resultData['Ext_max_total'],
                'Ext_max_obt_total' => $resultData['Ext_max_obt_total'],
                'int_max_total'     => $resultData['int_max_total'],
                'int_max_obt_total' => $resultData['int_max_obt_total'],
                'total_obt'         => $resultData['total_obt']
            ];

            // Accumulate grand totals for this enrollment
            $grand_total     += (float)$resultData['Ext_max_total'] + (float)$resultData['int_max_total'];
            $grand_total_obt += (float)$resultData['total_obt'];
        }

        // After processing all result rows for this enrollment
        $percentage = $grand_total > 0 ? ($grand_total_obt / $grand_total) * 100 : 0;

        // Append totals to existing student_info
        $grouped[$enroll]['student_info']['grand_total']      = $grand_total;
        $grouped[$enroll]['student_info']['grand_total_obt']  = $grand_total_obt;
        $grouped[$enroll]['student_info']['percentage']       = $percentage;
        $grouped[$enroll]['student_info']['division']         = 'First'; // or compute based on % if you want
        $grouped[$enroll]['student_info']['doi']              = date('d/m/Y');
    }
}
