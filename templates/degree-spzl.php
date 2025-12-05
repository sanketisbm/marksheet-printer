<?php
global $conn;

if (empty($data) || !is_array($data)) {
    return;
}

function titleCase($string)
{
    $string = (string) $string;
    if ($string === '') {
        return '';
    }

    return mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
}

function english_to_hindi_unicode($text)
{
    $text = trim((string) $text);
    if ($text === '') {
        return $text;
    }

    // You can normalize common patterns if you want:
    // $text = str_replace(["D/o", "S/o", "W/o"], ["Daughter of", "Son of", "Wife of"], $text);

    $url = "https://inputtools.google.com/request?text=" . urlencode($text)
        . "&itc=hi-t-i0-und&num=1";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // For local/XAMPP – disable SSL verification. On production, you should enable this.
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false || $response === '') {
        // API not reachable → fallback to original
        return $text;
    }

    $json = json_decode($response, true);

    // Correct extraction:
    if (
        isset($json[1][0][1][0])
    ) {
        return $json[1][0][1][0];
    }
    // If API doesn’t return proper structure
    return $text;
}

function unicode_to_krutidev($text)
{
    $map = array(
        // Vowels
        "अ" => "v",
        "आ" => "vk",
        "इ" => "b",
        "ई" => "bZ",
        "उ" => "m",
        "ऊ" => "Å",
        "ए" => ",",
        "ऐ" => ",s",
        "ओ" => "vks",
        "औ" => "vkS",

        // Matras
        "ा" => "k",
        "ि" => "f",
        "ी" => "h",
        "ु" => "q",
        "ू" => "w",
        "े" => "s",
        "ै" => "S",
        "ो" => "ks",
        "ौ" => "kS",

        // Consonants
        "क" => "d",
        "ख" => "[k",
        "ग" => "x",
        "घ" => "?k",
        "ङ" => "¡",
        "च" => "p",
        "छ" => "N",
        "ज" => "t",
        "झ" => ">",
        "ञ" => "¤",
        "ट" => "V",
        "ठ" => "B",
        "ड" => "M",
        "ढ" => "<",
        "ण" => ".k",
        "त" => "r",
        "थ" => "Fk",
        "द" => "n",
        "ध" => "/k",
        "न" => "u",
        "प" => "i",
        "फ" => "Q",
        "ब" => "c",
        "भ" => "Hk",
        "म" => "e",
        "य" => ";",
        "र" => "j",
        "ल" => "y",
        "व" => "o",
        "श" => "'k",
        "ष" => "\"k",
        "स" => "l",
        "ह" => "g",

        // Conjuncts
        "ज्ञ" => "K",
        "क्ष" => "Ñ",
        "श्र" => "…",

        // Half letter (virama forms)
        "्क" => "d`",
        "्ख" => "[k`",
        "्ग" => "x`",
        "्घ" => "?k`",
        "्च" => "p`",
        "्ज" => "t`",
        "्ट" => "V`",
        "्ठ" => "B`",
        "्ड" => "M`",
        "्ढ" => "<`",
        "्त" => "r`",
        "्थ" => "Fk`",
        "्द" => "n`",
        "्ध" => "/k`",
        "्न" => "u`",
        "्प" => "i`",

        // Nukta letters
        "क़" => "d",
        "ख़" => "[k",
        "ग़" => "x",
        "ज़" => "t",
        "ड़" => "M",
        "ढ़" => "<",

        // Chandrabindu / Anuswar / Visarga
        "ँ" => "~",
        "ं" => "a",
        "ः" => ":",

        // Numbers
        "०" => "0",
        "१" => "1",
        "२" => "2",
        "३" => "3",
        "४" => "4",
        "५" => "5",
        "६" => "6",
        "७" => "7",
        "८" => "8",
        "९" => "9",
    );

    // Basic reph handling: "र्" before a consonant
    $text = preg_replace_callback('/र्(.)/u', function ($m) {
        return $m[1] . "Z"; // Kruti reph after consonant
    }, $text);

    // Reorder the "ि" matra (pre-base vowel)
    if (mb_strpos($text, "ि", 0, 'UTF-8') !== false) {
        $text = preg_replace('/([क-ह]़?)(ि)/u', 'f$1', $text);
    }

    // Apply mapping (longest keys first)
    uksort($map, function ($a, $b) {
        return mb_strlen($b, 'UTF-8') <=> mb_strlen($a, 'UTF-8');
    });

    foreach ($map as $uni => $krut) {
        $text = str_replace($uni, $krut, $text);
    }

    return $text;
}

function english_to_krutidev($text)
{
    $unicode = english_to_hindi_unicode($text);
    return unicode_to_krutidev($unicode);
}
?>

<?php foreach ($data as $info):

    // Generate D. No.
    $sl_no = random_int(10000000000, 99999999999);

    // S/o / D/o detection from enrollment_no
    $enroll = (string) ($info['enrollment_no'] ?? '');
    $initials = 'S/o / D/o / W/o';

    if (strlen($enroll) >= 4) {
        $env = $enroll[2] . $enroll[3];
        if ($env === "01" || $env === "11") {
            $initials = "S/o";
            $initials2 = "son";
        } elseif ($env === "02" || $env === "12") {
            $initials = "D/o";
            $initials2 = "daughter";
        }
    }

    $studentNameEn   = $info['student_name'] ?? '';
    $fatherHusbandEn = $info['fathers_husbands_name'] ?? '';
    $program         = $info['program'] ?? '-';
    $specialization  = $info['specialization'] ?? '-';
    $passingYearRaw  = $info['passing_year'] ?? '';
    $division        = $info['division'] ?? '-';

    $passingParts = explode('-', $passingYearRaw);
    $passingMonth = $passingParts[0] ?? '';
    $passingYear  = $passingParts[1] ?? '';

    // Hindi name in KrutiDev (for Hindi section)
    $studentNameHiKruti = english_to_krutidev($studentNameEn);
    $fatherHusbandEnHiKruti = english_to_krutidev($fatherHusbandEn);
    $initialsHiKruti = english_to_krutidev($initials2);
    $programHiKruti = english_to_krutidev($program);
    $specializationHiKruti = english_to_krutidev($specialization);
    $passingMonthHiKruti = english_to_krutidev($passingMonth);
    $passingYearHiKruti = english_to_krutidev($passingYear);
    $divisionHiKruti = english_to_krutidev($division);

?>
    <div class="doc-container"
        id="<?= htmlspecialchars($enroll) ?>"
        style="padding-left: 1.2cm !important;
                padding-right: 1.2cm !important;
                padding-top: 4.6cm !important;
                padding-bottom: 2.54cm !important;
                font-family: calibri, sans-serif !important;">

        <div style="display: flex; flex-direction:column;">
            <table style="width: 18.6cm !important;">
                <tr>
                    <td class="text-left" colspan="2"
                        style="border:none !important;
                               font-family: 'Nirmala UI', 'Noto Sans Devanagari', sans-serif !important;
                               font-size:9pt;">
                        नामांकन संख्या
                    </td>
                </tr>
                <tr>
                    <td style="border:none !important;
                               font-size:11pt;
                               font-weight:400;
                               text-align: left !important;">
                        Enrollment No.:
                        <?= htmlspecialchars($enroll ?: '-') ?>
                    </td>
                    <td class="text-right"
                        style="border:none !important;
                               font-size:11pt;
                               font-weight:400;
                               text-align: right !important;">
                        D. No.: 0<?= $sl_no ?>
                    </td>
                </tr>
            </table>

            <table style="width: 18.6cm !important;">
                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-size: 13.5pt;
                               padding-top: 0.9cm !important;">
                        Upon the recommendation of the Academic Council and successful completion of the
                        requirements prescribed under the relevant Ordinance.
                    </td>
                </tr>

                <tr>
                    <td
                        style="width:2cm;
                               height:2cm;
                               border:none !important;
                               padding-top: 0.9cm !important;
                               padding-left: 1.2cm !important;">
                        <img src="http://api.qrserver.com/v1/create-qr-code/?data=<?= urlencode($info['qr_code_data'] ?? '-') ?>&size=300x300"
                            style="width:2cm; height:2cm;"
                            alt="QR Code" />
                    </td>

                    <td style="border:none !important;
                               padding-top: 0.8cm !important;
                               width: 100%;">
                        <table style="width: 100%;">
                            <tr>
                                <td class="text-center"
                                    style="border:none !important;
                                           font-size:20pt;
                                           font-weight: bold;">
                                    <?= htmlspecialchars(titleCase($studentNameEn) ?: '-') ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    style="border:none !important;
                                           font-size:15pt;">
                                    <?= htmlspecialchars($initials) ?>
                                    <?= htmlspecialchars(titleCase($fatherHusbandEn) ?: '-') ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    style="border:none !important;
                                           font-size:14.5pt;
                                           padding-top: 0.4cm;
                                           padding-bottom: 0.4cm;">
                                    Has this day been conferred the Degree of
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    style="border:none !important;
                                           font-size:17.5pt;
                                           font-weight: bold;">
                                    <?= htmlspecialchars($program) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    style="border:none !important;
                                           font-size:15pt;
                                           font-weight: bold;">
                                    <?= htmlspecialchars($specialization) ?>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td style="border:none !important;
                               padding-right: 1cm !important;">
                        <img src="uploaded_images/<?= urlencode($info['uploaded_image'] ?? '-') ?>"
                            style="width:2cm; height:2cm;" alt="Student Picture" />
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-size: 14.5pt;
                               padding-top: 0.5cm !important;">
                        on having passed the Examination held in
                        <?= htmlspecialchars(titleCase($passingMonth) ?: '-') ?>,
                        <?= htmlspecialchars($passingYear ?: '-') ?> with
                        <b><?= htmlspecialchars(titleCase($division) ?: '-') ?></b> Division.
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-weight: bold;
                               font-size: 35pt;
                               padding-top: 1cm !important;
                               font-family:'KrutiDev' !important;">
                        vkÃ-,l-ch-,e- fo'ofo|ky;
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-size: 9pt;
                               font-family:'KrutiDev' !important;">
                        NÙkhlx<+ futh fo'ofo|ky; ¼LFkkiuk ,oa lapkyu½ vf/kfu;e]
                            <span style="font-family: Mangal, sans-serif !important; font-size: 9pt !important;">२००५</span>
                            ,oa fo'ofo|ky; vuqnku vk;ksx ¼;w-th-lh-½ vf/kfu;e
                            <span style="font-family: Mangal, sans-serif !important; font-size: 9pt !important;">१९५६</span>
                            dh /kkjk
                            <span style="font-family: Mangal, sans-serif !important; font-size: 9pt !important;">२</span>¼ ,Q½ vUrxZr LFkkfir
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-size: 14.5pt;
                               padding-top: 0.5cm !important;
                               font-family:'KrutiDev' !important;">
                        vdknfed ifj"kn dh laLrqfr ij] rFkk vè;kns'kkuqlkj fuèkkZfjr vgrkZvksa dks
                        lQyrkiwoZd iw.kZ djus ij
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-size: 14.5pt;
                               padding-top: 0.5cm !important;
                               font-family:'KrutiDev' !important;">
                        <?= htmlspecialchars($studentNameHiKruti) ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-size: 14.5pt;
                               padding-top: 0.5cm !important;
                               font-family:'KrutiDev' !important;">
                        <?= htmlspecialchars($initialsHiKruti) ?> <?= htmlspecialchars($fatherHusbandEnHiKruti) ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-size: 14.5pt;
                               padding-top: 0.5cm !important;
                               font-family:'KrutiDev' !important;">
                        dks
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-size: 14.5pt;
                               padding-top: 0.5cm !important;
                               font-family:'KrutiDev' !important;">
                        <?= htmlspecialchars($programHiKruti) ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-size: 14.5pt;
                               padding-top: 0.5cm !important;
                               font-family:'KrutiDev' !important;">
                        <?= htmlspecialchars($specializationHiKruti) ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-size: 14.5pt;
                               padding-top: 0.5cm !important;
                               font-family:'KrutiDev' !important;">
                        dh mikfèk <?= htmlspecialchars($passingMonthHiKruti) ?> <?= htmlspecialchars($passingYearHiKruti) ?> esa vk;ksftr ijh{kk <b><?= htmlspecialchars($divisionHiKruti) ?></b> Js.kh esa mÙkh.kZ djus ds mijkar vkt fnukad dks
                        çnku dh tkrh gSA
                    </td>
                </tr>

                <tr>
                    <td colspan="3"
                        style="border:none !important;
                               font-size: 14.5pt;
                               padding-top: 0.5cm !important;">
                        Date: 18 January, 2021
                    </td>
                </tr>
            </table>
        </div>
    </div>

<?php endforeach; ?>