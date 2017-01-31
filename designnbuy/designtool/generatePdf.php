<?php
require '../app/Mage.php';
Mage::app();
ini_set('display_errors', 1);
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/fpdi.php'); 

function convertUnit($value){	
	$productId = 46;
	$_product =  Mage::getModel('catalog/product')->load($productId);
	if($_product->getBaseUnit()!='') {
		$unit = $_product->getAttributeText('base_unit');
	} else {
		$unit = Mage::getStoreConfig('customProduct/configuration/base_unit');
	}
	echo "unit".$unit;
	if($unit == 'px'){
		$value = ($value*25.4)/96;
		echo "value".$value;
		return $value;
	}else if($unit == 'in'){
		$value = $value/0.03937;
		echo "value".$value;
		return $value;
	}else if($unit == 'cm'){
		return $value = $value * 10;
	}else{
		return $value;
	}
}

$productId = 46;
$_product =  Mage::getModel('catalog/product')->load($productId);
$attributeSetId = $_product->getAttributeSetId();
$configAreaData = Mage::getModel('design/configarea')->load($productId,'product_id');

$svgName = 'front_1413890796';
$sideName = explode('_',$svgName);
if($sideName[0] == 'front'){	
	$outputWidth = $configAreaData->getFaOutputWidth();
	$outputHeight = $configAreaData->getFaOutputHeight();
}else if($sideName[0] == 'back'){
	$outputWidth = $configAreaData->getBaOutputWidth();
	$outputHeight = $configAreaData->getBaOutputHeight();
}else if($sideName[0] == 'left'){
	$outputWidth = $configAreaData->getLeOutputWidth();
	$outputHeight = $configAreaData->getLeOutputHeight();
}else if($sideName[0] == 'right'){
	$outputWidth = $configAreaData->getRiOutputWidth();
	$outputHeight = $configAreaData->getRiOutputHeight();
}


$designToolDir = Mage::getBaseDir(). DS .'media' . DS .'output'. DS. 'order-327-650'. DS;
$width = convertUnit($outputWidth);
echo "<br/>";
echo "width = ".$width;
$height = convertUnit($outputHeight);
echo "<br/>";
echo "height = ".$height;

class PDF extends FPDI{
}
// initiate FPDI
$pdf = new FPDI();
// $pdf->setPageUnit("mm");
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile($designToolDir."front_1414492195.pdf");

// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 210mm (width of A4)
$pdf->useTemplate($tplIdx, $x = null, $y = null, $width, $height, $adjustPageSize = true);   
$pdf->Output($designToolDir.'front_in.pdf', 'F');
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

$pdf->registrationMark(200, 110, 2.5, false, array(0,0,0), array(255,255,255));
$pdf->registrationMark(210, 100, 2.5, false, array(0,0,0), array(255,255,255)); */

?>