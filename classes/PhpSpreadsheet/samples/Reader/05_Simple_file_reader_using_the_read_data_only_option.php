<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';

$inputFileType = 'Xlsx';
// $inputFileName = __DIR__ . '/sampleData/example1.xls';
$inputFileName = __DIR__ . '/sampleData/quiz.xlsx';

$helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using IOFactory with a defined reader type of ' . $inputFileType);
$reader = IOFactory::createReader($inputFileType);
$helper->log('Turning Formatting off for Load');
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($inputFileName);

$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

echo '<pre>';
print_r($sheetData);
echo '</pre>';
