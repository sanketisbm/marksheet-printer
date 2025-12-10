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
        $initials = "him";
    } elseif ($env === "02" || $env === "12") {
        $initials = "her";
    } ?>
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>">
        <div style="margin-top:4.5cm; display: flex; flex-direction:column;">
            <p style="font-size: 16pt;font-weight: 400;width:17cm;text-align:right;">
                Date: <?= htmlspecialchars($info['print_date'] ?? date('d-m-Y')) ?> </p>

            <p class="text-center"
                style="margin-top: 1.5cm;font-size: 16pt;font-weight: bold;text-decoration: underline;width:17cm">
                TO WHOM IT MAY CONCERN
            </p>

            <div style="display:flex;flex-direction:column;margin-top:1.5cm;width:17cm">
                <p style="font-size: 16pt;margin-bottom: 10pt;line-height: 1.25;">As a professor I have had the pleasure of
                    knowing <?= htmlspecialchars($info['student_name'] ?? '-') ?> as a bright
                    and cheerful student. </p>

                <p style="font-size: 16pt;margin-bottom: 10pt;line-height: 1.25;">I am
                    <?= htmlspecialchars($info['professor'] ?? '-') ?>,
                    <?= htmlspecialchars($info['professor_desg'] ?? '-') ?> for
                    Subject Hindi - ISBM University, India.</p>

                <p style="font-size: 16pt;margin-bottom: 10pt;line-height: 1.25;">
                    <?= htmlspecialchars($info['student_name'] ?? '-') ?> is a student with
                    great effort,
                    endurance, diligence and scientific capabilities. </p>

                <p style="font-size: 16pt;margin-bottom: 40pt;line-height: 1.25;">I highly commend and recommend <?= htmlspecialchars($initials ?? 'him/her') ?> for
                    your institution esteem program.</p>

                <p style="font-size: 16pt;margin-bottom: 10pt;line-height: 1.25;">Thanking you. </p>

                <p style="font-size: 16pt;line-height: 1.25;">Yours truly, </p>
            </div>

            <div style="width:17cm; margin-top:40pt;">
                <p style="font-size: 16pt;line-height: 1.25;"><?= htmlspecialchars($info['professor'] ?? '-') ?></p>
                <p style="font-size: 16pt;line-height: 1.25;"><?= htmlspecialchars($info['professor_desg'] ?? '-') ?> -
                    Hindi</p>
                <p style="font-size: 16pt;line-height: 1.25;">ISBM University</p>
                <p style="font-size: 16pt;line-height: 1.25;">India</p>
            </div>
        </div>
    </div>

<?php endforeach; ?>