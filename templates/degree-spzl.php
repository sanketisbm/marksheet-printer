<?php
global $conn;

if (empty($data) || !is_array($data)) {
    return;
}

foreach ($data as $info):
    $sl_no = random_int(10000000000, 99999999999);
    $enroll = (string) ($info['enrollment_no'] ?? '');
    $division = (string) ($info['division'] ?? '');
    $envPng = $info['enrollment_no'] . ".png";

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
    <div class="doc-container" id="<?= htmlspecialchars($enroll) ?>" style="padding-left: 1.2cm !important;
                padding-right: 1.2cm !important;
                padding-top: 4.5cm !important;
                padding-bottom: 2cm !important;
                ">

        <div style="display: flex; flex-direction:column;">
            <table style="width: 18.6cm !important;">
                <tr>
                    <td class="text-left" colspan="2" style="border:none !important;
                        font-family: Mangal, sans-serif !important;
                        font-size:9pt;line-height: 1;">
                        नामांकन <span style="font-family:'KrutiDev' !important;font-size:12pt;line-height:1;">la[;k</span>
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
                               text-align: right !important;line-height: 1;padding-right: 0.1cm;font-family: calibri, sans-serif !important;">
                        D. No. 0<?= htmlspecialchars($info['doc_no'] ?? $sl_no) ?>
                    </td>
                </tr>
            </table>

            <table style="width: 18.3cm !important;">
                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-size: 13.5pt;
                               padding-top: 0.6cm !important;line-height: 1;font-family: calibri, sans-serif !important;">
                        Upon the recommendation of the Academic Council and successful completion of the
                        requirements prescribed under the relevant Ordinance.
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
                                           font-weight: bold;line-height: 1;padding: 0 !important;font-family: calibri, sans-serif !important;">
                                    <?= htmlspecialchars(titleCase($info['student_name']) ?: '-') ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    style="border:none !important;
                                           font-size:15pt;line-height: 1;padding: 0 !important;font-family: calibri, sans-serif !important;">
                                    <?= htmlspecialchars($info['prefix_eng'] ?? 'S/o / D/o / W/o') ?>
                                    <?= htmlspecialchars(titleCase($info['fathers_husbands_name']) ?: '-') ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    style="border:none !important;
                                           font-size:14.5pt;
                                           padding: 0;
                                           padding-top: 0.2cm !important;
                                           padding-bottom: 0.2cm !important;line-height: 1;font-family: calibri, sans-serif !important;">
                                    Has this day been conferred the Degree of
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    style="border:none !important;
                                           font-size:17.5pt;
                                           font-weight: bold;line-height: 1;padding: 0 !important;font-family: calibri, sans-serif !important;">
                                    <?= htmlspecialchars($info['program']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center"
                                    style="border:none !important;
                                           font-size:15pt;
                                           font-weight: bold;line-height: 0.8;padding: 0 !important;font-family: calibri, sans-serif !important;">
                                    (<?= htmlspecialchars($info['specialization']) ?>)
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td style="border:none !important;
                               padding-right: 1cm !important;">
                        <img src="uploaded_images/<?= urlencode($info['uploaded_image'] ?? $envPng ?? '-') ?>"
                            style="width:2cm; height:2cm;" alt="Student Picture" />
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3"
                        style="border:none !important;
                               font-size: 14.5pt;
                               padding: 0;
                               padding-top: 0.3cm !important;line-height: 1;font-family: calibri, sans-serif !important;">
                        on having passed the Examination held in
                        <?= htmlspecialchars($info['passing_year'] ?: '-') ?> with
                        <b><?= htmlspecialchars($info['division'] ?: '-') ?></b> Division.
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-weight: bold;
                               font-size: 35pt;
                               padding: 0;
                               padding-top: 0.5cm !important;
                               font-family:KrutiDev, sans-serif !important;line-height: 1;">
                        vkÃ-,l-ch-,e- fo'ofo|ky;
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 9pt;
                               padding: 0 !important;
                               font-family:KrutiDev, sans-serif !important;line-height: 0.5;">
                        NÙkhlx<+ futh fo'ofo|ky; ¼LFkkiuk ,oa lapkyu½ vf/kfu;e] <span
                            style="font-family: Mangal, sans-serif !important; font-size: 9pt !important;">२००५</span>
                            ,oa fo'ofo|ky; vuqnku vk;ksx ¼;w-th-lh-½ vf/kfu;e
                            <span style="font-family: Mangal, sans-serif !important; font-size: 9pt !important;">१९५६</span>
                            dh /kkjk
                            <span style="font-family: Mangal, sans-serif !important; font-size: 9pt !important;">२</span>¼
                            ,Q½ vUrxZr LFkkfir
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 15.5pt;
                               padding: 0;
                               padding-top: 0.3cm !important;
                               padding-left: 1.5cm !important;
                               padding-right: 1.5cm !important;
                               font-family:KrutiDev, sans-serif !important;line-height: 1;">
                        vdknfed ifj"kn dh laLrqfr ij] rFkk vè;kns'kkuqlkj fuèkkZfjr vgrkZvksa dks
                        lQyrkiwoZd iw.kZ djus ij
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 24pt;
                               padding: 0;
                               padding-top: 0.3cm !important;
                               font-family:Mangal, sans-serif !important;line-height: 0.5;">
                        <?= htmlspecialchars($info['student_name_hindi'] ?? '-') ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 16pt;
                               padding: 0;
                               font-family:Mangal, sans-serif !important;line-height: 1;">
                        <?= htmlspecialchars($info['prefix_hindi'] ?? '-') ?>
                        <?= htmlspecialchars($info['father_name_hindi'] ?? '-') ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 14pt;
                               padding: 0 !important;
                               padding-top: 0.2cm;
                                padding-bottom: 0.2cm;
                               font-family:KrutiDev, sans-serif !important;line-height: 1;">
                        dks
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 20pt;
                               padding: 0 !important;
                               font-family:Mangal, sans-serif !important;line-height: 1;">
                        <?= htmlspecialchars($info['program_name_hindi'] ?? '-') ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 16pt;
                               padding: 0 !important;
                               font-family:Mangal, sans-serif !important;line-height: 0.5;">
                        <?= htmlspecialchars($info['splz_name_hindi'] ?? '-') ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-center" colspan="3" style="border:none !important;
                               font-size: 15.5pt;
                               padding: 0 !important;
                               padding-top: 0.5cm !important;
                               padding-left: 1cm !important;
                               padding-right: 1cm !important;
                               font-family:KrutiDev, sans-serif !important;line-height: 1;">
                        dh mikfèk
                        <span style="font-family: Mangal, sans-serif !important; font-size: 9pt !important;">
                            <?= htmlspecialchars($info['passout_session_hindi'] ?? '-') ?></span>
                        esa vk;ksftr ijh{kk <b><?= htmlspecialchars($divisionHiKruti ?? '-') ?></b>
                        Js.kh esa mÙkh.kZ djus ds
                        mijkar vkt fnukad dks
                        çnku dh tkrh gSA
                    </td>
                </tr>

                <tr>
                    <td class="text-left" colspan="3" style="border:none !important;
                               font-size: 11pt;
                               padding: 0 !important;
                               padding-left: 0.5cm !important;
                               padding-top: 0.5cm !important;
                               line-height: 1;font-family: calibri, sans-serif !important;">
                        Date: <?= htmlspecialchars($info['print_date']) ?>
                    </td>
                </tr>
            </table>

            <table style="width: 18.6cm !important;">
                <tr>
                    <td style="border:none !important;
                               font-size:11pt;
                               font-weight:400;
                               text-align: left !important;
                               line-height: 1;
                               padding-left: 0.3cm;
                               padding-top: 0.5cm;">
                        <img src="sign/registrar.png" style="width: 5cm;" alt="" />
                    </td>
                    <td class="text-right" style="border:none !important;
                               font-size:11pt;
                               font-weight:400;
                               text-align: right !important;
                               line-height: 1;
                               padding-right: 0.3cm;
                               padding-top: 0.5cm;">
                        <img src="sign/vc.png" style="width: 5cm;" alt="" />
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php endforeach; ?>