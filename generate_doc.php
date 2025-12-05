<?php
require 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

header('Content-Type: application/json; charset=UTF-8');

function cleanHtml($html) {
    // Replace problematic tags
    $html = preg_replace('/<br\s*\/?>/', '<br>', $html);
    $html = preg_replace('/<img[^>]*>/', '', $html); // Remove <img> tags
    // Add other cleaning as needed
    return $html;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = $_POST['content'];

    try {
        // Clean and validate HTML
        $content = cleanHtml($content);

        // Initialize PHPWord
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Add HTML content
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $content, false, false);

        // Define output path
        $docxOutputPath = "marksheets/" . date('Ymd') . "_" . time() . "_results.docx";

        // Save the Word document
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($docxOutputPath);

        echo json_encode([
            'success' => true,
            'message' => 'Word document generated successfully',
            'docx_path' => $docxOutputPath,
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error generating Word document: ' . $e->getMessage(),
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No content received or invalid request']);
}
