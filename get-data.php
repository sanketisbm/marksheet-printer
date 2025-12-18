<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');
date_default_timezone_set('Asia/Kolkata');

require 'dbFiles/db.php';

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

// print_r($data);
// // die();

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid JSON input',
        'error' => json_last_error_msg(),
    ]);
    exit;
}

function fontValueCheck($value)
{
    if ($value === "izkphu fgUnh dkO;") {
        return "प्राचीन हिन्दी काव्य";
    } else if ($value === "vokZphu fgUnh dkO;") {
        return "अर्वाचीन हिन्दी काव्य";
    } else if ($value === "Nk;koknh dkO;") {
        return "छायावादी काव्य";
    } else if ($value === "tuinh; Hkk’kk&lkfgR; ¼NRrhlx<+h½") {
        return "जनपदीय भाषा-साहित्य (छत्तीसगढ़ी)";
    } else if ($value === "fgUnh dFkk lkfgR;") {
        return "हिन्दी कथा साहित्य";
    } else if ($value === "x| jax ¼fgUnh fuca/k rFkk vU; x| fo/kk,¡½" || $value === "x| jax Â¼fgUnh fuca/k rFkk vU; x| fo/kk,Â¡Â½") {
        return "गद्य रंग (हिन्दी निबंध तथा अन्य गद्य विधाएँ)";
    } else if ($value === "vk/kqfud fgUnh dfork ¼iz;ksxoknh ,oa izxfroknh dkO;½") {
        return "आधुनिक हिन्दी कविता (प्रयोगवादी एवं प्रगतिवादी काव्य)";
    } else if ($value === "fgUnh Hkk’kk&lkfgR; dk bfrgkl rFkk dkO;kax foospu" || $value === "fgUnh Hkkâ€™kk&lkfgR; dk bfrgkl rFkk dkO;kax foospu") {
        return "हिन्दी भाषा-साहित्य का इतिहास तथा काव्यांग विवेचन";
    } else if ($value === "fgUnh lkfgR; dk bfrgkl") {
        return "हिन्दी साहित्य का इतिहास";
    } else if ($value === "dkO;'kkL= ,oa lkfgR;kykspu") {
        return "काव्यशास्त्र एवं साहित्यालोचन";
    } else if ($value === "oSfnd lkfgR;e~ & ƒ") {
        return "वैदिक साहित्यम् - १";
    } else if ($value === "oSfnd lkfgR;e~ & „") {
        return "वैदिक साहित्यम् - २";
    } else if ($value === "dkO;e~ & ƒ") {
        return "काव्यम् - १";
    } else if ($value === "dkO;e~ & „") {
        return "काव्यम् - २";
    } else if ($value === "oSfnd lkfgR;e~") {
        return "वैदिक साहित्यम्";
    } else if ($value === "dkO;e~") {
        return "काव्यम्";
    } else if ($value === 'NRrhlx<+h ds HkkSxksfyd vm ,sfrgkfld i`"BHkwfe' || $value === "NRrhlx<+h ds HkkSxksfyd vm ,sfrgkfld i`\"BHkwfe") {
        return "छत्तीसगढ़ी के भौगोलिक अउ ऐतिहासिक पृष्ठभूमि";
    } else if ($value === "NRrhlx<+ ds yksd&lkfgR; vm laLd`fr") {
        return "छत्तीसगढ़ के लोक-साहित्य अउ संस्कृति";
    } else if ($value === "NÙkhlx<+h ds 'kCn&lajpuk") {
        return "छत्तीसगढ़ी के शब्द-संरचना";
    } else if ($value === "NÙkhlx<+h ds okD;&lajpuk") {
        return "छत्तीसगढ़ी के वाक्य-संरचना";
    } else if ($value === "NRrhlx<+h Hkk\"kk ds ifjp; vm lkfgR; ds bfrgkl") {
        return "छत्तीसगढ़ी भाषा के परिचय अउ साहित्य के इतिहास";
    } else if ($value === "NÙkhlx<+h ds Hkk\"kk&Hkwxksy vm 'kCn&lajpuk") {
        return "छत्तीसगढ़ी के भाषा-भूगोल अउ शब्द-संरचना";
    } else if ($value === "izkphu ,oa e/;dkyhu dkO;") {
        return "प्राचीन एवं मध्यकालीन काव्य";
    } else if ($value === "Hkk\"kkfoKku ,oa fgUnh Hkk\"kk") {
        return "भाषाविज्ञान एवं हिन्दी भाषा";
    } else if ($value === "O;kdj.k'kkL=e~ & ƒ") {
        return "व्याकरणशास्त्रम् - १";
    } else if ($value === "O;kdj.k'kkL=e~ & „") {
        return "व्याकरणशास्त्रम् - २";
    } else if ($value === "dkO;'kkL=e~ & ƒ") {
        return "काव्यशास्त्रम् - १";
    } else if ($value === "dkO;'kkL=e~ & „") {
        return "काव्यशास्त्रम् - २";
    } else if ($value === "O;kdj.k'kkL=e~") {
        return "व्याकरणशास्त्रम्";
    } else if ($value === "dkO;'kkL=e~") {
        return "काव्यशास्त्रम्";
    } else if ($value === "NÙkhlx<+h ds /ofu&lajpuk") {
        return "छत्तीसगढ़ी के ध्वनि-संरचना";
    } else if ($value === "NRrhlx<+ ds lhekorhZ Hkk\"kk vm cksyh") {
        return "छत्तीसगढ़ के सीमावर्ती भाषा अउ बोली";
    } else if ($value === "NÙkhlx<+h ds Hkk\"kk&Hkwxksy") {
        return "छत्तीसगढ़ी के भाषा-भूगोल";
    } else if ($value === "NÙkhlx<+h vm vuqokn") {
        return "छत्तीसगढ़ी अउ अनुवाद";
    } else if ($value === "NÙkhlx<+h ds O;kdj.k vm /ofu&lajpuk") {
        return "छत्तीसगढ़ी के व्याकरण अउ ध्वनि-संरचना";
    } else if ($value === "jktHkk\"kk vm iz;kstu ewyd NÙkhlx<+h") {
        return "राजभाषा अउ प्रयोजन मूलक छत्तीसगढ़ी";
    } else if ($value === "vk/kqfud fgUnh dkO;") {
        return "आधुनिक हिन्दी काव्य";
    } else if ($value === "Ikz;kstuewyd fgUnh") {
        return "प्रयोजनमूलक हिन्दी";
    } else if ($value === "Hkk\"kkfoKkue~ & ƒ") {
        return "भाषाविज्ञानम् - १";
    } else if ($value === "Hkk\"kkfoKkue~ & „") {
        return "भाषाविज्ञानम् - २";
    } else if ($value === "lkfgR;fl)kUr% & ƒ") {
        return "साहित्यसिद्धान्तः - १";
    } else if ($value === "lkfgR;fl)kUr% & „") {
        return "साहित्यसिद्धान्तः - २";
    } else if ($value === "Hkk\"kkfoKkue~") {
        return "भाषाविज्ञानम्";
    } else if ($value === "lkfgR;fl)kUr%") {
        return "साहित्यसिद्धान्तः";
    } else if ($value === "NÙkhlx<+h ds O;kdj.k") {
        return "छत्तीसगढ़ी के व्याकरण";
    } else if ($value === "NRrhlx<+h&dkO;") {
        return "छत्तीसगढ़ी-काव्य";
    } else if ($value === "iz;kstu ewyd NÙkhlx<+h") {
        return "प्रयोजन मूलक छत्तीसगढ़ी";
    } else if ($value === "NÙkhlx<+h ds rht frgkj vm ijaijk") {
        return "छत्तीसगढ़ी के तीज तिहार अउ परंपरा";
    } else if ($value === "NÙkhlx<+h dkO;] yksd&lkfgR; vm laLd`fr") {
        return "छत्तीसगढ़ी काव्य, लोक-साहित्य अउ संस्कृति";
    } else if ($value === "NÙkhlx<+h ds okD;&lajpuk vm vuqokn") {
        return "छत्तीसगढ़ी के वाक्य-संरचना अउ अनुवाद";
    } else if ($value === "vk/kqfud x|&lkfgR;") {
        return "आधुनिक गद्य-साहित्य";
    } else if ($value === "Hkkjrh; lkfgR;") {
        return "भारतीय साहित्य";
    } else if ($value === "n'kZu'kkL=e~ & ƒ") {
        return "दर्शनशास्त्रम् - १";
    } else if ($value === "n'kZu'kkL=e~ & „") {
        return "दर्शनशास्त्रम् - २";
    } else if ($value === "/kekZFkZ'kkL=kf.k & ƒ") {
        return "धर्मार्थशास्त्राणि - १";
    } else if ($value === "/kekZFkZ'kkL=kf.k & „") {
        return "धर्मार्थशास्त्राणि - २";
    } else if ($value === "n'kZu'kkL=e") {
        return "दर्शनशास्त्रम";
    } else if ($value === "/kekZFkZ'kkL=kf.k") {
        return "धर्मार्थशास्त्राणि";
    } else if ($value === "NÙkhlx<+h lkfgR; ds bfrgkl") {
        return "छत्तीसगढ़ी साहित्य के इतिहास";
    } else if ($value === "dk;kZy;hu NRrhlx<+h") {
        return "कार्यालयीन छत्तीसगढ़ी";
    } else if ($value === "jktHkk\"kk NÙkhlx<+h") {
        return "राजभाषा छत्तीसगढ़ी";
    } else if ($value === "izk;ksfxd izf”k{k.k vm vkarfjd&ewY;kadu") {
        return "प्रायोगिक प्रशिक्षण अउ आंतरिक-मूल्यांकन";
    } else if ($value === "NRrhlx<+h ds lhekorhZ Hkk\"kk] cksyh vm dk;kZy;hu NRrhlx<+h") {
        return "छत्तीसगढ़ी के सीमावर्ती भाषा, बोली अउ कार्यालयीन छत्तीसगढ़ी";
    } else if ($value === "NÙkhlx<+ ds rht frgkj vm ijaijk") {
        return "छत्तीसगढ़ के तीज तिहार अउ परंपरा";
    } else if ($value === "tuinh; Hkk\"kk vkSj lkfgR; ¼NRrhlx<+h½" || $value === "tuinh; Hkkâ€™kk&lkfgR; Â¼NRrhlx<+hÂ½") {
        return "जनपदीय भाषा और साहित्य (छत्तीसगढ़ी)";
    } else if ($value === "Ik=dkfjrk izf'k{k.k") {
        return "पत्रकारिता प्रशिक्षण";
    } else if ($value === "lkfgR;'kkL=e~ & ƒ") {
        return "साहित्यशास्त्रम् - १";
    } else if ($value === "lkfgR;'kkL=e~ & „") {
        return "साहित्यशास्त्रम् - २";
    } else if ($value === "Hkk\"kkdkS'kye~ & ƒ") {
        return "भाषाकौशलम् - १";
    } else if ($value === "Hkk\"kkdkS'kye~ & „") {
        return "भाषाकौशलम् - २";
    } else if ($value === "lkfgR;'kkL=e") {
        return "साहित्यशास्त्रम";
    } else if ($value === "Hkk\"kkdkS'kye~") {
        return "भाषाकौशलम्";
    } else if ($value === "Lakxks\"Bh @vkarfjd ewY;kadu") {
        return "स्ंागोष्ठी /आंतरिक मूल्यांकन";
    } else if ($value === "laxks\"Bh@vkarfjd ewY;kadu") {
        return "संगोष्ठी/आंतरिक मूल्यांकन";
    } else if ($value === "Lakxks\"Bh@vkarfjd ewY;kadu") {
        return "स्ंागोष्ठी/आंतरिक मूल्यांकन";
    } else if ($value === "izk;ksfxd izf'k{k.k vm vkarfjd&ewY;kadu") {
        return "प्रायोगिक प्रशिक्षण अउ आंतरिक-मूल्यांकन";
    } else if ($value === "vkfndky ,oa iwoZ e/;dky") {
        return "आदिकाल एवं पूर्व मध्यकाल";
    } else if ($value === "mRrj e/;dky ,oa vk/kqfud dky") {
        return "उत्तर मध्यकाल एवं आधुनिक काल";
    } else if ($value === "lkfgR; ds fl)kar rFkk vkykspuk 'kkL=") {
        return "साहित्य के सिद्धांत तथा आलोचना शास्त्र";
    } else if ($value === "fgUnh vkykspuk rFkk leh{kk 'kkL=") {
        return "हिन्दी आलोचना तथा समीक्षा शास्त्र";
    } else if ($value === "e/;dkyhu dkO;") {
        return "मध्यकालीन काव्य";
    } else if ($value === "Hkk\"kk foKku") {
        return "भाषा विज्ञान";
    } else if ($value === "fgUnh Hkk\"kk") {
        return "हिन्दी भाषा";
    } else if ($value === "Nk;kokn ,oa iwoZorhZ dkO;") {
        return "छायावाद एवं पूर्ववर्ती काव्य";
    } else if ($value === "iz;ksxoknh ,oa izxfroknh dkO;") {
        return "प्रयोगवादी एवं प्रगतिवादी काव्य";
    } else if ($value === "dkedkth fgUnh ,oa i=dkfjrk") {
        return "कामकाजी हिन्दी एवं पत्रकारिता";
    } else if ($value === "ehfM;k ys[ku ,oa vuqokn") {
        return "मीडिया लेखन एवं अनुवाद";
    } else if ($value === "ukVd] ,dkadh ,oa pfjrkRed d`fr") {
        return "नाटक, एकांकी एवं चरितात्मक कृति";
    } else if ($value === "miU;kl] fuca/k ,oa dgkuh") {
        return "उपन्यास, निबंध एवं कहानी";
    } else {
        return $value;
    }
}

foreach ($data as $record) {
    // print_r($record);

    $columns = [
        'tmr_id',
        'application_id',
        'branch',
        'period',
        'enrollment_no',
        'roll_no',
        'program',
        'student_name',
        'father_name',
        'mother_name',
        'program_print_name',
        'stream',
        'exam_session',
        'entry_date',
        'sl_no',
        'doi',
        'pccs',
        'co_na',
        'm_wn',
        'no_of_exams',
        'signature',
        'template_id',
        'Ext_max_total',
        'Ext_max_obt_total',
        'int_max_total',
        'int_max_obt_total',
        'total_obt',
        'total_marks_obt_words',
        'result',
        'division',
        'qr_code_data',
        'sem1_max_marks',
        'sem1_max_obt',
        'sem2_max_marks',
        'sem2_max_obt',
        'sem3_max_marks',
        'sem3_max_obt',
        'sem4_max_marks',
        'sem4_max_obt',
        'sem5_max_marks',
        'sem5_max_obt',
        'sem6_max_marks',
        'sem6_max_obt',
        'sem7_max_marks',
        'sem7_max_obt',
        'sem8_max_marks',
        'sem8_max_obt',
        'sem9_max_marks',
        'sem9_max_obt',
        'sem10_max_marks',
        'sem10_max_obt',
        'sem11_max_marks',
        'sem11_max_obt',
        'sem12_max_marks',
        'sem12_max_obt',
        'sem13_max_marks',
        'sem13_max_obt',
        'grand_total_max',
        'grand_total_obt',
        'final_result',
        'cGrand_Total_Max',
        'cGrand_Total_Obt',
        'cGrand_Total_Inwords',
        'cPercentage',
        'cDivision',
        'print_flag'
    ];

    // Add subject-specific columns
    for ($i = 1; $i <= 13; $i++) {
        $columns[] = "sub{$i}_code";
        $columns[] = "sub{$i}_name";
        $columns[] = "sub{$i}_ext_max";
        $columns[] = "sub{$i}_ext_obt";
        $columns[] = "sub{$i}_int_max";
        $columns[] = "sub{$i}_int_obt";
        $columns[] = "sub{$i}_total_obt";
        $columns[] = "sub{$i}_result_Remark";
    }

    $escaped_values = [];
    foreach ($columns as $column) {
        // Only escape if the value exists and is a string (we don't want to escape numbers or dates)
        if (isset($record[$column])) {
            $escaped_values[$column] =  $record[$column];
        } else {
            $escaped_values[$column] = null;
        }
    }
    // print_r($escaped_values);
    // Ensure print_flag is set to 0
    $application_id = $escaped_values['application_id'];
    $period = $escaped_values['period'];
    $sl_no = $escaped_values['sl_no'];

    for ($i = 1; $i <= 13; $i++) {
        $escaped_values["sub{$i}_name"] = fontValueCheck($escaped_values["sub{$i}_name"]);
    }

    $select_query = "SELECT * FROM `results` WHERE `application_id` = '$application_id' AND `period` = '$period' AND `sl_no` = '$sl_no'";
    $select_result = mysqli_query($conn, $select_query);
    if ($select_result && mysqli_num_rows($select_result) === 0) {
        // Insert new record
        $column_list = [];
        $value_list = [];
        foreach ($columns as $column) {
            if ($escaped_values[$column] !== null) {
                $column_list[] = "`$column`";
                $value_list[] = "'" . mysqli_real_escape_string($conn, $escaped_values[$column]) . "'";
            }
        }

        $insert_query = "INSERT INTO `results` (" . implode(", ", $column_list) . ") VALUES (" . implode(", ", $value_list) . ")";
        $insert_result = mysqli_query($conn, $insert_query);
        if ($insert_query) {
            echo "INSERTED";
        } else {
            echo "Error: " . mysqli_error($conn);
            $errorinsert_query = "INSERT INTO `hindi` (`queryData`, `errorData`) VALUES ('$update_query', 'mysqli_error($conn)')";
            $errorinsert_result = mysqli_query($conn, $errorinsert_query);
        }
    } else {
        // Update existing record
        // Set UTF-8 character set for MySQL connection

        // Prepare the update query
        $update_fields = [];
        foreach ($columns as $column) {
            if ($escaped_values[$column] !== null) {
                // Ensure proper escaping of data
                $update_fields[] = "`$column` = '" . mysqli_real_escape_string($conn, $escaped_values[$column]) . "'";
            }
        }

        // Build the update query
        $update_query = "UPDATE `results` SET " . implode(", ", $update_fields) . " WHERE `sl_no` = '$sl_no'";

        // Execute the query
        $update_result = mysqli_query($conn, $update_query);

        if ($update_result) {
            echo "UPDATED";
        } else {
            echo "Error: " . mysqli_error($conn);
            $errorinsert_query = "INSERT INTO `hindi` (`queryData`, `errorData`) VALUES ('$update_query', 'mysqli_error($conn)')";
            $errorinsert_result = mysqli_query($conn, $errorinsert_query);
        }
    }
}
