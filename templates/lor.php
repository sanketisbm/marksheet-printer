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
        $initials2 = "him";
    } elseif ($env === "02" || $env === "12") {
        $initials = "She";
        $initials2 = "her";
    }
?>
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>" style="font-family: 'Times New Roman', Times, serif !important;">
        <div style="margin-top:4.5cm; display: flex; flex-direction:column;">
            <p style="font-size: 12pt;font-weight: 400;width:17cm;text-align:right;">
                Date: <?= htmlspecialchars($info['print_date'] ?? date('d-m-Y')) ?> </p>

            <p class="text-center" style="margin-top: 1.5cm;font-size: 14pt;text-decoration: underline;width:17cm">
                Letter of Recommendation
            </p>

            <div style="display:flex;flex-direction:column;margin-top:1.5cm;width:17cm">
                <p style="font-size: 12pt;margin-bottom: 10pt;line-height: 1.25;">I am
                    <?= htmlspecialchars($info['professor'] ?? '-') ?> -
                    <?= htmlspecialchars($info['professor_desg'] ?? '-') ?> - Department of
                    <?= htmlspecialchars($info['professor_dept'] ?? '-') ?> - ISBM University, India. I am writing this
                    letter to recommend my student
                    <?= htmlspecialchars(titleCase($info['student_name']) ?? '-') ?> for a place in programme offered by your
                    institution. </p>

                <p style="font-size: 12pt;margin-bottom: 10pt;line-height: 1.25;">
                    <?= htmlspecialchars(titleCase($info['student_name']) ?? '-') ?> has completed
                    <?= htmlspecialchars($info['program'] ?? '-') ?> with first
                    division. <?= htmlspecialchars($initials ?? 'He/She') ?> is a
                    hard-working student and has good
                    communication skills. <?= htmlspecialchars($initials ?? 'He/She') ?> has pleasing manner and is lively,
                    attentive and punctual. <?= htmlspecialchars($initials ?? 'He/She') ?> is capable of
                    successfully completing multiple tasks with favourable results despite deadline pressure.</p>

                <p style="font-size: 12pt;margin-bottom: 10pt;line-height: 1.25;">I wish <?= htmlspecialchars(titleCase($info['student_name']) ?? '-') ?> all the very best for the academic
                    pursuits and would like to recommend <?= htmlspecialchars($initials2 ?? 'him/her') ?> for further studies in your institution.</p>

                <p style="font-size: 12pt;margin-bottom: 10pt;line-height: 1.25;">I hope
                    <?= htmlspecialchars(titleCase($info['student_name']) ?? '-') ?> gets to study the
                    preferred course. </p>

                <p style="font-size: 12pt;margin-bottom: 10pt;line-height: 1.25;">Thanking you. </p>

                <p style="font-size: 12pt;line-height: 1.25;">Yours truly, </p>
            </div>

            <div style="width:17cm; margin-top:2cm;">
                <p style="font-size: 12pt;line-height: 1.25;"><?= htmlspecialchars($info['professor'] ?? '-') ?></p>
                <p style="font-size: 12pt;line-height: 1.25;"><?= htmlspecialchars($info['professor_desg'] ?? '-') ?> -
                    Department of <?= htmlspecialchars($info['professor_dept'] ?? '-') ?></p>
                <p style="font-size: 12pt;line-height: 1.25;">ISBM University</p>
                <p style="font-size: 12pt;line-height: 1.25;">India</p>
            </div>
        </div>
    </div>

<?php endforeach; ?>