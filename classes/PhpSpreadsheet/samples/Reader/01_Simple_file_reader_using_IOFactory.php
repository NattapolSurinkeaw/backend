<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';

#$inputFileName = __DIR__ . '/sampleData/example1.xls';
$inputFileName = __DIR__ . '/sampleData/quiz.xlsx';

//$helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using IOFactory to identify the format');

/**
* ส่งไฟล์อะไรมาระบบก็จะอ่านเองอัตโนมัติ
*/
$spreadsheet = IOFactory::load($inputFileName);
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
echo '<pre>';
print_r($sheetData);
echo '</pre>';
