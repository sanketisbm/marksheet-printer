<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require 'dbFiles/db.php';

use Dompdf\Dompdf;
use Dompdf\Options;

header('Content-Type: application/json; charset=utf-8');

define("DOMPDF_UNICODE_ENABLED", true);

/**
 * Optional: remove invisible/control chars that can sometimes show as boxes
 */
function clean_control_chars($text)
{
    return preg_replace('/[\x00-\x1F\x7F\x{200B}-\x{200D}\x{FEFF}]/u', '', $text);
}

// Paths for fonts
$fontDir      = __DIR__ . '/assets/fonts/';
$fontCacheDir = __DIR__ . '/assets/fonts_cache/';

// ----- DOMPDF OPTIONS -----
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('isUnicodeEnabled', true);
$options->set('isFontSubsettingEnabled', true);

// Default Latin font (for English text)
$options->set('defaultFont', 'calibri');

// Where dompdf looks for fonts / caches them
$options->set('fontDir', $fontDir);
$options->set('fontCache', $fontCacheDir);

// Restrict file access to this folder tree
$options->setChroot(__DIR__);

$dompdf = new Dompdf($options);

// A4 portrait (no extra margins)
$dompdf->setPaper([0, 0, 595, 842], 'portrait');

// ----- HANDLE REQUEST -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = $_POST['content'];
    $content = clean_control_chars($content);

    // Get your CSS from css_t.php
    ob_start();
    include 'css_t.php';      // this should echo CSS or <style>...</style>
    $css = ob_get_clean();

    // If css_t.php outputs raw CSS (no <style> tag), wrap it
    if (stripos($css, '<style') === false && trim($css) !== '') {
        $css = "<style>\n" . $css . "\n</style>";
    }

    $fontCss = <<<CSS
<style>
@font-face {
    font-family: 'KrutiDev';
    font-style: normal;
    font-weight: 400;
    src: url('assets/fonts/KrutiDev.ttf') format('truetype');
}
@font-face {
    font-family: 'KrutiDev';
    font-style: normal;
    font-weight: 300;
    src: url('assets/fonts/KrutiDev.ttf') format('truetype');
}
@font-face {
    font-family: 'KrutiDev';
    font-style: normal;
    font-weight: 500;
    src: url('assets/fonts/KrutiDev.ttf') format('truetype');
}
@font-face {
    font-family: 'KrutiDev';
    font-style: normal;
    font-weight: 600;
    src: url('assets/fonts/KrutiDev.ttf') format('truetype');
}
@font-face {
    font-family: 'KrutiDev';
    font-style: normal;
    font-weight: 700;
    src: url('assets/fonts/KrutiDev.ttf') format('truetype');
}
@font-face {
    font-family: 'KrutiDev';
    font-style: normal;
    font-weight: bold;
    src: url('assets/fonts/Kruti Dev 010 Bold.ttf') format('truetype');
}
@font-face {
    font-family: 'NirmalaUI';         /* alias without space */
    font-style: normal;
    font-weight: 400;
    src: url('assets/fonts/Nirmala.ttf') format('truetype');
}
@font-face {
    font-family: 'Nirmala UI';        /* original name if you want */
    font-style: normal;
    font-weight: 400;
    src: url('assets/fonts/Nirmala.ttf') format('truetype');
}
@font-face {
    font-family: 'Mangal';
    src: url('assets/fonts/Mangal.ttf') format('truetype');
}
@font-face {
    font-family: 'calibri';
    src: url('assets/fonts/calibri.ttf') format('truetype');
}
@font-face {
    font-family: 'arial';
    src: url('assets/fonts/arial.ttf') format('truetype');
}

/* Do NOT override your inline font-family styles here.
   This just makes sure dompdf knows what 'KrutiDev', 'Nirmala UI', etc. mean. */
</style>
CSS;

    // Build full HTML
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
' . $fontCss . $css . '
</head>
<body>' . $content . '</body>
</html>';

    try {
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->render();

        // Ensure output folder exists
        $outDir = __DIR__ . '/generated_documents';
        if (!is_dir($outDir)) {
            mkdir($outDir, 0777, true);
        }

        $fileName = date('Ymd') . "_" . time() . "_transcript.pdf";
        $pdfOutputPath = $outDir . '/' . $fileName;

        file_put_contents($pdfOutputPath, $dompdf->output());

        echo json_encode([
            'success'  => true,
            'message'  => 'PDF generated successfully',
            'pdf_path' => 'generated_documents/' . $fileName,
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error generating PDF: ' . $e->getMessage(),
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No content received or invalid request',
    ]);
}
