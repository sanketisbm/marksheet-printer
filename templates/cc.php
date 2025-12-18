<?php
global $conn;

if (empty($data) || !is_array($data)) {
    return;
}
?>

<?php foreach ($data as $info):
    $sl_no = random_int(10000000, 99999999);
?>
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>" style="padding-left: 2.5cm !important;padding-right: 2.5cm !important;font-family: 'Times New Roman', Times, serif !important;">
        <div style="margin-top:5cm; display: flex; flex-direction:column;">
            <p style="font-size: 12pt;font-weight: 400;"> S. No.: <?php htmlspecialchars($info['doc_no'] ?? $sl_no) ?> </p>
            <p style="font-size: 12pt;font-weight: 400;margin-top:0.4cm;width:16cm;text-align:right;">
                Dated: <?= htmlspecialchars($info['print_date'] ?? date('d-m-Y')) ?> </p>

            <p class="text-center" style="margin-top: 1.3cm;font-size: 18pt;font-weight: bold;text-decoration: underline;width:16cm">
                Character Certificate
            </p>

            <table style="margin-top:1cm;width:16cm">
                <tr>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:5cm">This is to certify that</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:1.3cm">: -</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;"><?= htmlspecialchars($info['student_name'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:5cm">Father's/Husband’s name</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:1.3cm">: -</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;"><?= htmlspecialchars($info['fathers_husbands_name'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:5cm">Mother's name</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:1.3cm">: -</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;"><?= htmlspecialchars($info['mother_name'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:5cm">Enrollment No.</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:1.3cm">: -</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;"><?= htmlspecialchars($info['enrollment_no'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:14pt;width:5cm">Conduct</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;width:1.3cm">: -</td>
                    <td class="text-start" style="padding-top: 0.3cm !important;border:none !important;font-size:12pt;">GOOD</td>
                </tr>

                <tr>
                    <td colspan="3" class="text-start" style="padding-top: 0.5cm !important;border:none !important;font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;    text-align: justify;">
                        has been a regular student of the University in class / course
                        <b><?= htmlspecialchars($info['program'] ?? '-') ?></b> and passed his / her
                        examination of the above course in
                        <b><?= htmlspecialchars($info['passing_year'] ?? '-') ?></b>.
                    </td>
                </tr>

                <tr>
                    <td colspan="3" class="text-start" style="padding-top: 0.5cm !important;border:none !important;font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;    text-align: justify;">
                        He / She is reliable, sincere, hardworking and bears a good moral character.
                    </td>
                </tr>

                <tr>
                    <td colspan="3" class="text-end" style="padding-top: 2.4cm !important;border:none !important;text-align:right;font-size:12pt;font-weight:400;padding-right:1cm !important;">
                        <img src="sign/krishna Sign.jpeg" style="width: 3cm;" alt="" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end" style="padding-top: 0cm !important;border:none !important;text-align:right;font-size:12pt;font-weight:400;padding-right:1.5cm !important;">
                        For Registrar
                    </td>
                </tr>
            </table>

        </div>
    </div>

<?php endforeach; ?>