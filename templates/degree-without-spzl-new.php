<?php
global $conn;

if (empty($data) || !is_array($data)) {
    return;
}

foreach ($data as $info):
    $sl_no = random_int(10000000000, 99999999999);
    $enroll = (string) ($info['enrollment_no'] ?? '');
    $division = (string) ($info['division'] ?? '');
    $flag = "FALSE";
    $envArray = str_split($info['enrollment_no']); // split into characters
    if ($envArray[2] === "1") {
        $flag = "TRUE";
    }

    if ($division === "FIRST") {
        $divisionHiKruti = "çFke";
    } elseif ($division === "SECOND") {
        $divisionHiKruti = "f}rh;";
    } elseif ($division === "THIRD") {
        $divisionHiKruti = "r`rh;";
    } elseif ($division === "Distinction") {
        $divisionHiKruti = "fMfLVaD'ku";
    }
?>
    <div class="doc-container" id="<?= htmlspecialchars($enroll) ?>" style="padding-left: 0.8cm !important;
                padding-right: 1.2cm !important;
                padding-top: 4.4cm !important;
                padding-bottom: 2cm !important;
                ">

        <div style="display: flex; flex-direction:column;">
            <table style="width: 18cm !important;margin-left: 0.8cm !important;">
                <tr>
                    <td class="text-left" colspan="2" style="border:none !important;
                        font-family: KrutiDev, sans-serif !important;
                        font-size:11pt;line-height: 1;">
                        ukekadu la[;k
                    </td>
                </tr>
                <tr>
                    <td
                        style="border:none !important;
                               font-size:11pt;
                               font-weight:400;
                               text-align: left !important;line-height: 1;font-family: calibri, sans-serif !important;">
                        Enrollment No.
                        <?= htmlspecialchars($enroll ?: '-') ?>
                    </td>
                    <td class="text-right"
                        style="border:none !important;
                               font-size:11pt;
                               font-weight:400;
                               text-align: right !important;line-height: 1;font-family: calibri, sans-serif !important;">
                        C. No.<?= htmlspecialchars($info['doc_no'] ?? $sl_no) ?>
                    </td>
                </tr>
            </table>

            <table style="width: 18cm !important;margin-left: 0.5cm !important;margin-right: 0.5cm !important;">
                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 18pt;
                               padding-top: 1.5cm !important;
                               line-height: 1;
                               font-family: calibri, sans-serif !important;
                               padding-left: 0.3cm !important;">
                        This is to certify that
                    </td>
                </tr>

                <tr>
                    <td style="width:2cm;
                               height:2cm;
                               border:none !important;
                               padding-top: 0 !important;
                               padding-left: 1.2cm !important;">
                        <img src="http://api.qrserver.com/v1/create-qr-code/?data=<?= urlencode($info['qr_code_data'] ?? '-') ?>&size=300x300"
                            style="width:2cm; height:2cm;" alt="QR Code" />
                    </td>

                    <td style="border:none !important;
                               padding-top: 0.3cm !important;
                               width: 100%;">
                        <table style="width: 95%;">
                            <tr>
                                <td class="text-center"
                                    style="border:none !important;
                                           font-size:20pt;
                                           padding-top: 0.5cm !important;
                                           font-weight: bold;line-height: 1;padding: 0 !important;font-family: calibri, sans-serif !important;">
                                    <?= htmlspecialchars(titleCase($info['student_name']) ?: '-') ?>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center"
                                    style="border:none !important;
                                           font-size:14.5pt;
                                           padding: 0;
                                           padding-top: 0.4cm !important;
                                           line-height: 1;
                                           font-family: calibri, sans-serif !important;">
                                    Who passed the examination for the degree of
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td style="border:none !important;">
                        <img src="uploaded_images/<?= urlencode($info['uploaded_image'] ?? '-') ?>"
                            style="width:2cm; height:2cm;" alt="Student Picture" />
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                                font-size:17.5pt;
                                font-weight: bold;
                                padding: 0;
                                padding-top: 0.4cm !important;
                                line-height: 1;
                                font-family: calibri, sans-serif !important;">
                        <?= htmlspecialchars($info['program']) ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 14.5pt;
                               padding: 0;
                                padding-top: 0.5cm !important;
                               line-height: 1;
                               font-family: calibri, sans-serif !important;">
                        held in the month of <?= htmlspecialchars($info['passing_year'] ?: '-') ?> and was placed in the
                        <b><?= htmlspecialchars(titleCase($info['division']) ?: '-') ?></b> Division.
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 35pt;
                               padding: 0;
                               padding-top: 1.3cm !important;
                               font-family:KrutiDev, sans-serif !important;
                               font-weight: 600;
                               line-height: 1;">
                        vkÃ-,l-ch-,e- fo'ofo|ky;
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 9pt;
                               padding: 0 !important;
                               font-family:KrutiDev, sans-serif !important;line-height: 0.9;">
                        NÙkhlx<+ futh fo'ofo|ky; ¼LFkkiuk ,oa lapkyu½ vf/kfu;e] <span
                            style="font-family: Mangal, sans-serif !important; font-size: 7pt !important;">२००५</span>
                            ,oa fo'ofo|ky; vuqnku vk;ksx ¼;w-th-lh-½ vf/kfu;e
                            <span style="font-family: Mangal, sans-serif !important; font-size: 7pt !important;">१९५६</span>
                            dh /kkjk
                            <span style="font-family: Mangal, sans-serif !important; font-size: 7pt !important;">२</span>¼
                            ,Q½ vUrxZr LFkkfir
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 18pt;
                               padding: 0;
                               padding-top: 0.9cm !important;
                               padding-left: 1.5cm !important;
                               padding-right: 1.5cm !important;
                                font-weight: 300 !important;
                               font-family:KrutiDev, sans-serif !important;line-height: 1;">
                        ;g çekf.kr fd;k tkrk gS fd
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 24pt;
                               padding: 0;
                               padding-top: 0.4cm !important;
                               font-family:KrutiDev, sans-serif !important;
                               font-weight: 700;
                               line-height: 0.8;">
                        <?= htmlspecialchars(unicode_to_krutidev($info['student_name_hindi']) ?? '-') ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                                font-size: 16pt;
                                line-height: 0;
                                padding: 0 !important;
                                padding-top: 0.3cm !important;
                                font-weight: 300 !important;
                                font-family:KrutiDev, sans-serif !important;line-height: 1;">
                        us
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 20pt;
                               padding: 0 !important;
                                padding-top: 0.3cm !important;
                               font-family:KrutiDev, sans-serif !important;
                               font-weight: 700;
                               line-height: 1;">
                        <?= htmlspecialchars($info['program_name_hindi'] ?? '-') ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 15.5pt;
                               padding: 0 !important;
                               padding-top: 0.5cm !important;
                               padding-left: 0.6cm !important;
                               padding-right: 0.6cm !important;
                               font-weight: 300 !important;
                               font-family:KrutiDev, sans-serif !important;line-height: 1;">
                        dh mikfèk gsrq vk;ksftr ijh{kk
                        <?= htmlspecialchars($info['passout_session_hindi'] ?? '-') ?>
                        esa <span style="font-weight:700;"><?= htmlspecialchars($divisionHiKruti ?? '-') ?></span>
                        Js.kh ls mÙkh.kZZ fd;k gSA
                    </td>
                </tr>
            </table>

            <?php if ($flag === "TRUE") { ?>
                <table
                    style="width: 18cm !important;margin-left: 0.5cm !important;margin-right: 0.5cm !important;margin-top: 0.6cm !important;position:absolute;bottom:6.2cm">
                    <tr>
                        <td style="border:none !important;
                               font-size:11pt;
                               font-weight:400;
                               text-align: left !important;
                               line-height: 1;
                               padding-left: 1cm;
                               padding-top: 0cm;">
                            <img src="sign/registrar.png" style="width: 5cm;" alt="" />
                        </td>
                        <td class="text-right" style="border:none !important;
                               font-size:11pt;
                               font-weight:400;
                               text-align: right !important;
                               line-height: 1;
                               padding-right: 0.3cm;
                               padding-top: 0cm;">
                            <img src="sign/vc.png" style="width: 5cm;" alt="" />
                        </td>
                    </tr>
                </table>
            <?php } ?>
        </div>
    </div>
<?php endforeach; ?>