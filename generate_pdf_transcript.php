<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php'; // Include Composer autoloader
require 'dbFiles/db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

header('Content-Type: application/json; charset=utf-8');

define("DOMPDF_UNICODE_ENABLED", true);

// Initialize DOMPDF with options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('defaultFont', 'arial'); // Or remove this line entirely
$options->set('isRemoteEnabled', true);
$options->set('isUnicodeEnabled', true); // Required for Hindi and Unicode fonts
$options->set('isFontSubsettingEnabled', true);

$options->set('margin_top', 0);    // No top margin
$options->set('margin_right', 0);  // No right margin
$options->set('margin_bottom', 0); // No bottom margin
$options->set('margin_left', 0);

$dompdf = new Dompdf($options);

// Define the path to your fonts directory
$fontDir = __DIR__ . '/assets/fonts/';
$fontCacheDir = __DIR__ . '/assets/fonts_cache/';

// Register fonts
$options->set('fontDir', $fontDir);
$options->set('fontCache', $fontCacheDir);
// Add the font manually to DOMPDF
$dompdf->getOptions()->set('chroot', __DIR__);
$fontMetrics = $dompdf->getFontMetrics();
$fontMetrics->getFont('calibri', $fontDir . 'calibri.ttf');
$fontMetrics->getFont('calibri', $fontDir . 'calibri.ttf');
$fontMetrics->getFont('calibri-bold', $fontDir . 'calibrib.ttf');
$fontMetrics->getFont('calibri-italic', $fontDir . 'calibrii.ttf');
$fontMetrics->getFont('calibri-bold-italic', $fontDir . 'calibriz.ttf');

// Add Noto Sans Devanagari fonts
$fontMetrics->getFont('Noto Sans Devanagari', $fontDir . 'NotoSansDevanagari.ttf');

// Set paper size to a custom size (in points)
$dompdf->setPaper([0, 0, 595, 842], 'portrait'); // 21.38 cm x 30.48 cm converted to points

// Check if it's a POST request and content exists
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = $_POST['content']; // Get content to render the PDF
    ob_start();
    include 'css_t.php';
    $css = ob_get_clean();

    $content = $css . $content;
    $encodedText = htmlentities($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');


    // $content = preg_replace('/<div class="canvas-container">.*?<\/div>/s', '', $content);

    try {
        // Load the HTML content

        $dompdf->loadHtml($content);
        $dompdf->render();
        $pdfOutputPath = "transcripts/" . date('Ymd') . "_" . time() . "_" . 'transcript.pdf';

        file_put_contents($pdfOutputPath, $dompdf->output());
        echo json_encode(['success' => true, 'message' => 'PDF generated successfully', 'pdf_path' => $pdfOutputPath]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error generating PDF: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No content received or invalid request']);
}
