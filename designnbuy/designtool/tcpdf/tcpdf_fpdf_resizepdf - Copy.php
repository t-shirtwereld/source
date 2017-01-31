<?php
ini_set('display_errors', 1);
require '../app/Mage.php';
Mage::app();
/* require_once('../tcpdf.php');
require_once('../fpdi.php');  */

/* class PDF extends TCPDF_FPDI{
}  */

// initiate FPDI
$pdf = new TCPDF_FPDI();

$pdf->setPageUnit("mm");
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile(Mage::getBaseDir(). DS .'designtool' . DS ."design_1413629940_0.pdf");
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 210mm (width of A4)
$pdf->useTemplate($tplIdx, $x = null, $y = null, $w = 266.7, $h = 139.7, $adjustPageSize = true); 
/* echo "<pre>";
print_r($pdf);
die; */
/* 
$pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(true, 40);
$pdf->setFontSubsetting(false);
 $page_format = array(
	'MediaBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 266.7, 'ury' => 139.7),
	'CropBox' => array ('llx' => 0, 'lly' => 0, 'urx' => 266.7, 'ury' => 139.7),
	'BleedBox' => array ('llx' => 5, 'lly' => 5, 'urx' => 211, 'ury' => 110),
	'TrimBox' => array ('llx' => 10, 'lly' => 10, 'urx' => 206, 'ury' => 105),
	'ArtBox' => array ('llx' => 15, 'lly' => 15, 'urx' => 201, 'ury' => 100),
	'Dur' => 3,
	'trans' => array(
		'D' => 1.5,
		'S' => 'Split',
		'Dm' => 'V',
		'M' => 'O'
	),
	'Rotate' => 0,
	'PZ' => 1,
); 

// add a page

$pdf->cropMark(10, 10, 15, 15, 'TL');
$pdf->cropMark(205, 10, 15, 15, 'TR');
$pdf->cropMark(10, 105, 15, 15, 'BL');
$pdf->cropMark(205, 105, 15, 15, 'BR'); 

// registration marks

$pdf->registrationMark(15, 5, 2.5, false, array(0,0,0), array(255,255,255));
$pdf->registrationMark(5, 15, 2.5, false, array(0,0,0), array(255,255,255));

$pdf->registrationMark(200, 5, 2.5, false, array(0,0,0), array(255,255,0));
$pdf->registrationMark(210, 15, 2.5, false, array(0,0,0), array(255,255,0));

$pdf->registrationMark(15, 110, 2.5, false, array(0,0,0), array(255,255,0));
$pdf->registrationMark(5, 100, 2.5, false, array(0,0,0), array(255,255,0));
*/  
$pdf->Output('newpdf.pdf', 'I');
?>