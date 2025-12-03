<?php
global $conn;

if (empty($data) || !is_array($data)) {
    return;
}
?>

<?php foreach ($data as $info): ?>
    <div class="doc-container" id="<?= htmlspecialchars($info['enrollment_no']) ?>">
        <div style="margin-top:4.8cm; display: flex; flex-direction:column;">
            <p style="font-size: 12pt;font-weight: 400;width:17cm;text-align:right;">
                Date: <?php echo date('d-m-Y'); ?> </p>

            <p class="text-center"
                style="margin-top: 1.5cm;font-size: 14pt;font-weight: bold;text-decoration: underline;width:17cm">
                Letter of Recommendation
            </p>

            <div style="display:flex;flex-direction:column;margin-top:1.5cm;width:17cm">
                <p style="font-size: 12pt;margin-bottom: 10pt;line-height: 1.25;">I am
                    <?= htmlspecialchars($info['professor'] ?? '-') ?> -
                    <?= htmlspecialchars($info['professor_desg'] ?? '-') ?> - Department of
                    <?= htmlspecialchars($info['professor_dept'] ?? '-') ?> - ISBM University, India. I am writing this
                    letter to recommend my student
                    <?= htmlspecialchars($info['student_name'] ?? '-') ?> for a place on the
                    master programme offered by your
                    institution. </p>

                <p style="font-size: 12pt;margin-bottom: 10pt;line-height: 1.25;">
                    <?= htmlspecialchars($resultData['student_name'] ?? $info['student_name'] ?? '-') ?> has completed
                    <?= htmlspecialchars($resultData['program'] ?? $info['program'] ?? '-') ?> with first division. He is a
                    hard-working student and has good
                    communication skills. He has pleasing manner and is lively, attentive and punctual. He is capable of
                    successfully completing multiple tasks with favourable results despite deadline pressure.</p>

                <p style="font-size: 12pt;margin-bottom: 10pt;line-height: 1.25;">I wish all the very best for the academic
                    pursuits and would like to recommend for a place master’s programme in your institution.</p>

                <p style="font-size: 12pt;margin-bottom: 10pt;line-height: 1.25;">I hope
                    <?= htmlspecialchars($resultData['student_name'] ?? $info['student_name'] ?? '-') ?> gets to study the
                    preferred course. </p>

                <p style="font-size: 12pt;margin-bottom: 10pt;line-height: 1.25;">Thanking you. </p>

                <p style="font-size: 12pt;line-height: 1.25;">Yours truly, </p>
            </div>

            <div style="width:17cm; margin-top:2cm;">
                <p style="font-size: 12pt;line-height: 1.25;"><?= htmlspecialchars($info['professor'] ?? '-') ?></p>
                <p style="font-size: 12pt;line-height: 1.25;"><?= htmlspecialchars($info['professor_desg'] ?? '-') ?> - Department of <?= htmlspecialchars($info['professor_dept'] ?? '-') ?></p>
                <p style="font-size: 12pt;line-height: 1.25;">ISBM University</p>
                <p style="font-size: 12pt;line-height: 1.25;">India</p>
            </div>
        </div>
    </div>

<?php endforeach; ?>