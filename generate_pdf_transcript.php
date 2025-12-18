<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['content'])) {
    echo json_encode(['success' => false, 'message' => 'No content received']);
    exit;
}

$content = $_POST['content'];

try {
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'tempDir' => __DIR__ . '/assets/mpdf_tmp'   // make sure writable
    ]);

    // Register custom fonts
    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'tempDir' => __DIR__ . '/assets/mpdf_tmp',
        'fontDir' => array_merge($fontDirs, [ __DIR__ . '/assets/fonts' ]),
        'fontdata' => $fontData + [
            'mangal' => [
                'R' => 'Mangal Regular.ttf',
                'B' => 'Mangal Bold.ttf',
            ],
            'calibri' => [
                'R' => 'calibri.ttf',
            ],
        ],
        'default_font' => 'mangal' // Hindi safe default
    ]);

    // Your CSS (optional)
    ob_start();
    include 'css_t.php';
    $css = ob_get_clean();
    if (stripos($css, '<style') === false && trim($css) !== '') {
        $css = "<style>\n" . $css . "\n</style>";
    }

    // Force unicode font for Hindi parts (you can override in body too)
    $fontCss = "<style>
        body { font-family: mangal; }
        .eng { font-family: calibri; }
        .hin { font-family: mangal; }
    </style>";

    $html = "<!DOCTYPE html><html><head><meta charset='UTF-8'>{$fontCss}{$css}</head><body>{$content}</body></html>";

    $mpdf->WriteHTML($html);

    $outDir = __DIR__ . '/generated_documents';
    if (!is_dir($outDir)) mkdir($outDir, 0777, true);

    $fileName = date('Ymd') . "_" . time() . "_document.pdf";
    $pdfOutputPath = $outDir . '/' . $fileName;

    $mpdf->Output($pdfOutputPath, \Mpdf\Output\Destination::FILE);

    echo json_encode([
        'success' => true,
        'message' => 'PDF generated successfully (Unicode Hindi supported)',
        'pdf_path' => 'generated_documents/' . $fileName,
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
