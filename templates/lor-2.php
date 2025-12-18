<?php
global $conn;

if (empty($data) || !is_array($data)) {
    return;
}

?>

<?php foreach ($data as $info):
    $envArray = str_split($info['enrollment_no']); // split into characters
    $env = $envArray[2] . $envArray[3];
    if ($env === "01" || $env === "11") {
        $initials = "He";
        $initials2 = "he";
    } elseif ($env === "02" || $env === "12") {
        $initials = "She";
        $initials2 = "she";
    } ?>
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>" style="font-family: 'Times New Roman', Times, serif !important;">
        <div style="margin-top:4.5cm; display: flex; flex-direction:column;">
            <p style="font-size: 12pt;font-weight: 400;width:17cm;text-align:right;">
                Date: <?= htmlspecialchars($info['print_date'] ?? date('d-m-Y')) ?> </p>

            <p class="text-center" style="margin-top: 1.5cm;font-size: 14pt;text-decoration: underline;width:17cm">
                Letter of Recommendation
            </p>

            <div style="display:flex;flex-direction:column;margin-top:1.5cm;width:17cm">
                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;">I am very pleased to recommend
                    <?= htmlspecialchars(titleCase($info['student_name']) ?? '-') ?> for admission to
                    your institution program. I am <?= htmlspecialchars($info['professor'] ?? '-') ?> -
                    <?= htmlspecialchars($info['professor_desg'] ?? '-') ?> at ISBM University, India.</p>

                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;">
                    <?= htmlspecialchars(titleCase($info['student_name']) ?? '-') ?> is bright,
                    compassionate, genuinely well rounded and has actively participated in a diverse activity. <?= htmlspecialchars($initials ?? 'He/She') ?> is
                    very well liked and respected by both peers and teachers.</p>

                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;">I whole heartedly recommend
                    <?= htmlspecialchars(titleCase($info['student_name']) ?? '-') ?> for higher
                    education and hope <?= htmlspecialchars($initials2 ?? 'he/she') ?> gets to study the preferred course. </p>

                <p style="font-size: 14pt;margin-bottom: 10pt;line-height: 1.25;">Thanking you. </p>

                <p style="font-size: 14pt;line-height: 1.25;">Yours truly, </p>
            </div>

            <div style="width:17cm; margin-top:2cm;">
                <p style="font-size: 14pt;line-height: 1.25;"><?= htmlspecialchars($info['professor'] ?? '-') ?></p>
                <p style="font-size: 14pt;line-height: 1.25;"><?= htmlspecialchars($info['professor_desg'] ?? '-') ?> - <?= htmlspecialchars($info['professor_dept'] ?? '-') ?></p>
                <p style="font-size: 14pt;line-height: 1.25;">ISBM University</p>
                <p style="font-size: 14pt;line-height: 1.25;">India</p>
            </div>
        </div>
    </div>

<?php endforeach; ?>