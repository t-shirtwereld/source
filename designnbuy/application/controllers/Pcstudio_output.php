<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pcstudio_output extends PC_Controller {

    public function __construct() {
	parent::__construct();
	//$this->pc_rootURL = get_base_url();
	$getdata = $this->input->get();
	if (!empty($getdata['postData'])) {
	    $this->pc_rootURL = $getdata['postData']['siteBaseUrl'];
	} else {
	    $this->pc_rootURL = $getdata['siteBaseUrl'];
	}
	$this->plateform_solution = plateform_solutions(PLATEFORM);
	$this->load->library('curl');
	$this->load->model('Designnbuy_common_webservice_model');
    }

    public function index() {
	$param = $this->input->get();
	$siteBaseUrl = unserialize(base64_decode($param['siteBaseUrl']));
	$url = $siteBaseUrl . $this->plateform_solution['order_details_path'] . 'order_id=' . $param['order_id'];
	$this->curl->create($url);
	$this->curl->option('returntransfer', 1);
	$data = $this->curl->execute();

	$data = json_decode($data, true);

	//$orderdata['orderdesigns'] = $this->Designnbuy_common_webservice_model->getOrderDetailsbyOrderId($order_id);
	for ($i = 0; $i < count($data['orderproducts']); $i++) {
	    $data['orderproducts'][$i]['output_file'] = $this->generateOutput($data['orderproducts'][$i]['order_design_id'], $data['orderproducts'][$i]['size_id']);
	}
	$data['plateform'] = $param['plateform'];
	$data['siteBaseUrl'] = $siteBaseUrl;
	$this->load->view('pcoutput/order_output', $data);
    }

    private function objectsIntoArray($arrObjData, $arrSkipIndices = array()) {
	$arrData = array();
	// if input is object, convert into array
	if (is_object($arrObjData)) {
	    $arrObjData = get_object_vars($arrObjData);
	}

	if (is_array($arrObjData)) {
	    foreach ($arrObjData as $index => $value) {
		if (is_object($value) || is_array($value)) {
		    $value = $this->objectsIntoArray($value, $arrSkipIndices);
		}
		if (in_array($index, $arrSkipIndices)) {
		    continue;
		}
		$arrData[$index] = $value;
	    }
	}
	return $arrData;
    }

    public function generateOutput($order_design_id, $size_id) {
	$outPutFormat = '';
	$zipFileName = '';
	$result = $this->Designnbuy_common_webservice_model->getOrderDetailsById($order_design_id);
	if (!empty($result)) {
	   // $itemId = $result['order_product_id'];
	    $order_id = $result['order_id'];
	    $itemId = $result['cart_design_id'];
	    $designed_id = $result['designed_id'];
	    $product_id = $result['product_id'];
	    $namenumberdata = json_decode(htmlspecialchars_decode($result['name_number_data']), true);

	    $nameArray = array();
	    $numberArray = array();
	    if (!empty($namenumberdata)) {
		foreach ($namenumberdata as $n) {
		    if ($n['id'] == $size_id) {
			$nameArray[] = array(
			    'd' => $n['nameData'][0],
			    'transform' => $n['nameData'][1]
			);
			$numberArray[] = array(
			    'd' => $n['numberData'][0],
			    'transform' => $n['numberData'][1]
			);
		    }
		}
	    }

	    if (!file_exists(TOOL_IMG_PATH . '/output/' . $designed_id . '/order-' . $order_id . '-' . $itemId . '.zip')) {
		for ($i = 1; $i <= $result['no_of_sides']; $i++) {
		    $svgOutputFiles[] = $result['side' . $i . '_svg'];
		}
		/* $svgOutputFiles = array(
		  '0' => $result['side1_svg'],
		  '1' => $result['side2_svg'],
		  '2' => $result['side3_svg'],
		  '3' => $result['side4_svg'],
		  '4' => $result['side5_svg'],
		  '5' => $result['side6_svg'],
		  '6' => $result['side7_svg'],
		  '7' => $result['side8_svg']
		  ); */

		$format = $this->Designnbuy_common_webservice_model->getGeneralConfigData();
		$outPutFormat = $format['output_format'];
		$svgNameNumberArray = $this->generateSVG($svgOutputFiles, $order_id, $itemId, $designed_id, $nameArray, $numberArray, $product_id);
		$this->generatePdf($svgOutputFiles, $order_id, $itemId, $designed_id, $product_id, $svgNameNumberArray);

		$zipFileName = $this->zipOutputFiles($order_id, $itemId, $designed_id);
	    } else {
		$zipFileName = 'order-' . $order_id . '-' . $itemId . '.zip';
	    }
	}
	return $zipFileName;
    }

    public function generatePNG($svgOutputFiles, $orderEntityId, $itemId) {
	$svgFilesToZip = array();
	$imageFilesToZip = array();
	$uploadedImage = array();
	$pdfFilesToZip = array();
	$sourceImagePath = TOOL_IMG_PATH . '/output/' . $designed_id . '/order-' . $orderEntityId . '-' . $itemId . '/';
	foreach ($svgOutputFiles as $svgFile):
	    $svgFileName = explode('.', $svgFile);
	    if ($svgFileName[1] == 'svg'):
		$svgFilePath = $sourceImagePath . $svgFile;
		if (file_exists($svgFilePath)) {
		    $svgName = pathinfo($svgFilePath, PATHINFO_FILENAME);
		    $pdfName = $svgName . '.pdf';
		    $pdfName = str_replace(' ', '_', $pdfName);
		    $pdfFilesToZip[] = $pdfName;
		    // require_once(DIR_SYSTEM . 'library/inkscape.php');
		    //  $inkscape = new Inkscape($svgFilePath);

		    $param = array('filename' => $svgFilePath);
		    $this->load->library('Inkscape', $param);

		    $this->inkscape->exportAreaSnap();
		    //better pixel art
		    $this->inkscape->exportTextToPath();
		    // $inkscape->setSize($width=792, $height=408);
		    // $inkscape->setDpi(96);	
		    try {
			$ok = $this->inkscape->export('pdf', $sourceImagePath . $pdfName);
		    } catch (Exception $exc) {
			echo $exc->getMessage();
			echo $exc->getTraceAsString();
		    }
		}
	    endif;
	endforeach;
	return;
    }

    public function generatePdf($svgOutputFiles, $orderEntityId, $itemId, $designed_id, $product_id, $nameNumberOutput) {
	$svgFilesToZip = array();
	$imageFilesToZip = array();
	$uploadedImage = array();
	$pdfFilesToZip = array();
	$sourceImagePath = TOOL_IMG_PATH . '/output/' . $designed_id . '/order-' . $orderEntityId . '-' . $itemId . '/';
	//$productdata = $this->Designnbuy_common_webservice_model->getProductConfigurationData($product_id);
	$totalsvg = count($svgOutputFiles);
	if (is_array($nameNumberOutput)) {
	    $svgOutputFiles = array_merge($svgOutputFiles, $nameNumberOutput);
	}
	$i = 1;
	foreach ($svgOutputFiles as $id => $svgFile):
	    $svgFileName = explode('.', $svgFile);
	    if ($svgFileName[1] == 'svg'):
		$svgFilePath = $sourceImagePath . $svgFile;
		if (file_exists($svgFilePath)) {
		    $svgName = pathinfo($svgFilePath, PATHINFO_FILENAME);
		    $pdfName = $svgName . '.pdf';
		    $pdfName = str_replace(' ', '_', $pdfName);
		    $pdf = $sourceImagePath . $pdfName;
		    $pdfFilesToZip[] = $pdfName;
		    //  require_once(DIR_SYSTEM . 'library/inkscape.php');
		    //  $inkscape = new Inkscape($svgFilePath);

		    $param = array('filename' => $svgFilePath);
		    $this->load->library('Inkscape', $param);
		    $this->inkscape->setParam('file', $param);
		    $this->inkscape->exportAreaSnap();
		    //better pixel art
		    $this->inkscape->exportTextToPath();

//		    $this->inkscape->setSize($width = 792, $height = 408);
		    // $inkscape->setDpi(96);	
		    try {
			$ok = $this->inkscape->export('pdf', $pdf);
			if ($ok) {
			    chmod($pdf, 0777);

			    /* resize generated pdf according to product size */
			    $this->resizeGeneratedPDF($svgFileName[0], $pdf, $product_id, $i, $totalsvg);
			}
		    } catch (Exception $exc) {
			echo $exc->getMessage();
			echo $exc->getTraceAsString();
		    }
 
	        $productoutput = $this->Designnbuy_common_webservice_model->getProductsettingdata($product_id);
			if($productoutput['output'] == "svgpdfpng"){
			  	$PngName = $svgName . '_PNG' . '.png';
	            $this->generateOutputPNG($pdf, $orderEntityId, $itemId, $designed_id, $PngName);
			}elseif($productoutput['output'] == "svgpdfeps"){
				 $EpsName = $svgName . '_EPS' . '.eps';
	            $this->generateOutputEPS($pdf, $orderEntityId, $itemId, $designed_id, $EpsName);
			 }

		    $format = $this->Designnbuy_common_webservice_model->getGeneralConfigData();
		    if ($format['pdf_output_type'] == 'CMYK') {
			$CMYKPdfName = $svgName . '_CMYK' . '.pdf';
			$CMYKPdf = $sourceImagePath . $CMYKPdfName;
				/*From TCPDF*/
				//$this->generateCMYKPDFTCPDF($svgFile, $sourceImagePath, $CMYKPdf, $product_id, $i, $totalsvg);
				/*From Ghostscript*/
				//$this->generateCMYKPDF($pdf, $CMYKPdf);
		    }
		}
	    endif;
	    $i++;
	endforeach;
	//exit;
	return;
    }

    public function resizeGeneratedPDF($svgFileName, $pdfFile, $product_id, $i, $totalsvg) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/designtool/tcpdf/tcpdf.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/designtool/tcpdf/fpdi.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/designtool/tcpdf/pdf.php');

	$configAreaData = $this->Designnbuy_common_webservice_model->getProductConfigurationData($product_id, '3');
	$nnfilename = explode('.', $svgFileName);

	if (($configAreaData['side_1_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_1_label']) !== false) && ($i == 1 || $i > $totalsvg)) {
	    $outputWidth = $configAreaData['side1_output_width'];
	    $outputHeight = $configAreaData['side1_output_height'];
	} else if (($configAreaData['side_2_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_2_label']) !== false) && ($i == 2 || $i > $totalsvg)) {
	    $outputWidth = $configAreaData['side2_output_width'];
	    $outputHeight = $configAreaData['side2_output_height'];
	} else if (($configAreaData['side_3_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_3_label']) !== false) && ($i == 3)) {
	    $outputWidth = $configAreaData['side3_output_width'];
	    $outputHeight = $configAreaData['side3_output_height'];
	} else if (($configAreaData['side_4_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_4_label']) !== false) && ($i == 4)) {
	    $outputWidth = $configAreaData['side4_output_width'];
	    $outputHeight = $configAreaData['side4_output_height'];
	} else if (($configAreaData['side_5_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_5_label']) !== false) && ($i == 5)) {
	    $outputWidth = $configAreaData['side5_output_width'];
	    $outputHeight = $configAreaData['side5_output_height'];
	} else if (($configAreaData['side_6_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_6_label']) !== false) && ($i == 6)) {
	    $outputWidth = $configAreaData['side6_output_width'];
	    $outputHeight = $configAreaData['side6_output_height'];
	} else if (($configAreaData['side_7_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_7_label']) !== false) && ($i == 7)) {
	    $outputWidth = $configAreaData['side7_output_width'];
	    $outputHeight = $configAreaData['side7_output_height'];
	} else if (($configAreaData['side_8_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_8_label']) !== false) && ($i == 8)) {
	    $outputWidth = $configAreaData['side8_output_width'];
	    $outputHeight = $configAreaData['side8_output_height'];
	}

	
	if ($configAreaData['base_unit'] != '') {
	    $unit = $configAreaData['base_unit'];
	} else {
	    $gen_cnf_data = $this->Designnbuy_common_webservice_model->getGeneralConfigData();
	    $unit = $gen_cnf_data['base_unit'];
	}

	$width = $this->convertUnit($outputWidth, $unit);
	$height = $this->convertUnit($outputHeight, $unit);


	try {
	    // initiate FPDI
	    $pdf = new FPDI();
	    $pdf->setPageUnit("mm");
	    $pdf->SetPrintHeader(false);
	    $pdf->SetPrintFooter(false);
	    // add a page
	    $pdf->AddPage();
	    // set the source file
	    $pdf->setSourceFile($pdfFile);

	    // import page 1
	    $tplIdx = $pdf->importPage(1);
	    // use the imported page and place it at point 10,10 with a width of 210mm (width of A4)
	    $pdf->useTemplate($tplIdx, $x = null, $y = null, $width, $height, $adjustPageSize = true);
	    $pdf->Output($pdfFile, 'F');
	} catch (Exception $exc) {
	    echo $exc->getMessage();
	    echo $exc->getTraceAsString();
	}
    }
	public function generateCMYKPDF($RGBPDF, $CMYKPDF){
		try{
			$this->load->library('Ghostscript');
			//$ghost = new Ghostscript(); // the location of 'gs' if your PHP does not execute it correctly by default. To find, enter 'which gs' in Command Line
			$this->ghostscript->set_input($RGBPDF);  // your input file
			$this->ghostscript->add_option("-dSAFER");
			$this->ghostscript->add_option("-dBATCH");
			$this->ghostscript->add_option("-dNOPAUSE");
			$this->ghostscript->add_option("-dAutoFilterColorImages=true");
			$this->ghostscript->add_option("-sProcessColorModel=DeviceCMYK");
			$this->ghostscript->add_option("-sColorConversionStrategy=CMYK");
			$this->ghostscript->add_option("-sColorConversionStrategyForImages=CMYK");
			$this->ghostscript->add_option("-sDEVICE=pdfwrite");
			$this->ghostscript->add_option("-q");
			$this->ghostscript->add_option("-sOutputFile=".$CMYKPDF);
			$ok = $this->ghostscript->export(); // run the 'gs' command
			if($ok != 0){
				//Mage::log("Ghostscript Message".$ok, null, "dnb.log");
			}
		}catch ( Exception $exc ){
			echo $exc->getMessage();
		}
		return;
	}

	public function generateOutputPNG($RGBPDF, $orderEntityId, $itemId, $designed_id, $CMYKPDF){
		   $sourceImagePath = TOOL_IMG_PATH . '/output/' . $designed_id . '/order-' . $orderEntityId . '-' . $itemId . '/';
		    $param = array('filename' => $RGBPDF);
		    $this->load->library('Inkscape', $param);
		    $this->inkscape->setParam('file', $param);
		    $this->inkscape->exportAreaSnap();
		    //better pixel art
		    $this->inkscape->exportTextToPath();
			$ok = $this->inkscape->export('png', $sourceImagePath.$CMYKPDF);
		    return;
	}

	public function generateOutputEPS($RGBPDF, $orderEntityId, $itemId, $designed_id, $CMYKPDF){
		$sourceImagePath = TOOL_IMG_PATH . '/output/' . $designed_id . '/order-' . $orderEntityId . '-' . $itemId . '/';
		   $param = array('filename' => $RGBPDF);
		    $this->load->library('Inkscape', $param);
		    $this->inkscape->setParam('file', $param);
		    $this->inkscape->exportAreaSnap();
		    //better pixel art
		    $this->inkscape->exportTextToPath();
			$ok = $this->inkscape->export('eps', $sourceImagePath.$CMYKPDF);
		    return;
	}


    public function generateCMYKPDFTCPDF($svgFileName, $sourceImagePath, $pdfFile, $product_id, $i, $totalsvg) {
	require_once($_SERVER['DOCUMENT_ROOT'] . '/designtool/tcpdf/tcpdf.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/designtool/tcpdf/fpdi.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/designtool/tcpdf/pdf.php');

	$configAreaData = $this->Designnbuy_common_webservice_model->getProductConfigurationData($product_id, '1');
	
	$nnfilename = explode('.', $svgFileName);
	if (($configAreaData['side_1_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_1_label']) !== false) && ($i == 1 || $i > $totalsvg)) {
	    $outputWidth = $configAreaData['side1_output_width'];
	    $outputHeight = $configAreaData['side1_output_height'];
	} else if (($configAreaData['side_2_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_2_label']) !== false) && ($i == 2 || $i > $totalsvg)) {
	    $outputWidth = $configAreaData['side2_output_width'];
	    $outputHeight = $configAreaData['side2_output_height'];
	} else if (($configAreaData['side_3_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_3_label']) !== false) && ($i == 3)) {
	    $outputWidth = $configAreaData['side3_output_width'];
	    $outputHeight = $configAreaData['side3_output_height'];
	} else if (($configAreaData['side_4_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_4_label']) !== false) && ($i == 4)) {
	    $outputWidth = $configAreaData['side4_output_width'];
	    $outputHeight = $configAreaData['side4_output_height'];
	} else if (($configAreaData['side_5_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_5_label']) !== false) && ($i == 5)) {
	    $outputWidth = $configAreaData['side5_output_width'];
	    $outputHeight = $configAreaData['side5_output_height'];
	} else if (($configAreaData['side_6_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_6_label']) !== false) && ($i == 6)) {
	    $outputWidth = $configAreaData['side6_output_width'];
	    $outputHeight = $configAreaData['side6_output_height'];
	} else if (($configAreaData['side_7_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_7_label']) !== false) && ($i == 7)) {
	    $outputWidth = $configAreaData['side7_output_width'];
	    $outputHeight = $configAreaData['side7_output_height'];
	} else if (($configAreaData['side_8_label'] . '.svg' == $svgFileName || strpos($nnfilename[0], $configAreaData['side_8_label']) !== false) && ($i == 8)) {
	    $outputWidth = $configAreaData['side8_output_width'];
	    $outputHeight = $configAreaData['side8_output_height'];
	}

	if ($configAreaData['base_unit'] != '') {
	    $unit = $configAreaData['base_unit'];
	} else {
	    $gen_cnf_data = $this->Designnbuy_common_webservice_model->getGeneralConfigData();
	    $unit = $gen_cnf_data['base_unit'];
	}
	$width = $this->convertUnit($outputWidth, $unit);
	$height = $this->convertUnit($outputHeight, $unit);
	
	// create new PDF document
	$pdf = new TCPDF();

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Design N Buy');
	$pdf->SetTitle('RGB to CMYK');
	$pdf->SetSubject('RGB to CMYK');
	$pdf->SetKeywords('RGB to CMYK, PDF, example, test, guide');
	$pdf->setRasterizeVectorImages(false);
	$pdf->SetPrintHeader(false);
	$pdf->SetPrintFooter(false);
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

	// set auto page breaks
	$pdf->SetAutoPageBreak(false);

	// set image scale factor

	$pdf->setPageUnit("mm");
	$orientation = ($height > $width) ? 'P' : 'L';
	$page_format = array($width, $height);
	// add a page
	$pdf->AddPage($orientation, $page_format, false, false);

	// NOTE: Uncomment the following line to rasterize SVG image using the ImageMagick library.
	//$pdf->setRasterizeVectorImages(true);
	ob_clean();
	$doc = new DomDocument();
	$doc->loadXML(file_get_contents($sourceImagePath . $svgFileName));

	$cmykSvgFileName = "cmyk_" . $svgFileName;
	$doc->preserveWhiteSpace = false;
	$doc->formatOutput = true;
	//If round corner
	//$domemlement = $doc->documentElement;
	//$clip_path = $domemlement->attribute['clip-path'];
	//$test = $doc->documentElement->getAttribute('clip-path'); 
	//echo $test; exit;
	if ($doc->documentElement->getAttribute('clip-path')) {
	    $this->modifySVG($doc, $sourceImagePath . $cmykSvgFileName);
	}
	//}
	//DOMNode::$root;
	$root = $doc->firstChild;
	//print "<pre>"; print_r($root); exit;
	$this->traverse($root, $sourceImagePath);

	$doc->save($sourceImagePath . $cmykSvgFileName);

	$pdf->ImageSVG($file = $sourceImagePath . $cmykSvgFileName, $x = 0, $y = 0, $w = $width, $h = $height, $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);

	unlink($sourceImagePath . $cmykSvgFileName);
	$pdf->Output($pdfFile, 'F');
    }

    function modifySVG($doc, $svgPath) {
	if ($doc->hasChildNodes()) {
	    /* $doc = new DOMDocument();
	      $doc->load("images/design_1433829094_0.svg");
	      $doc->preserveWhiteSpace = false;
	      $doc->formatOutput = true; */
	    // Get first child
	    $firstChildNode = $doc->firstChild;
	    $clipPathAttrValue = $firstChildNode->getAttribute('clip-path');
	    // Crete magic element to hold all children in blabla 
	    $gElement = $doc->createElement('g');
	    $gElement->setAttribute('clip-path', $clipPathAttrValue);
	    //$firstChildNode->hasChildNodes();
	    while ($firstChildNode->childNodes->length > 1) {
		if ($firstChildNode->firstChild->nodeName != 'defs') {
		    // echo $firstChildNode->firstChild->nodeName;
		    $removedChild = $firstChildNode->removeChild($firstChildNode->firstChild);
		    $gElement->appendChild($removedChild);
		} else {
		    $removedChild = $firstChildNode->removeChild($firstChildNode->firstChild->nextSibling);
		    $gElement->appendChild($removedChild);
		}
	    }
	    // Append gElement to firstChildNode
	    $gElement = $firstChildNode->appendChild($gElement);
	}
	$doc->save($svgPath);
    }

    public function traverse(DomNode $node, $sourceImagePath, $level = 0) {
	//echo $sourceImagePath;exit;
	if ($node->nodeName != 'defs' && $node->nodeName != 'use') { //Exclude defs and use tags			
	    $this->handle_node($node, $level);
	}

	if ($node->nodeName != 'defs' && $node->hasChildNodes()) {
	    $children = $node->childNodes;
	    if (!empty($children)) {

		foreach ($children as $child) {
		    if ($child->nodeName == 'image') {//For QR Code
			$imageType = array();
			$doc = new DomDocument("1.0");

			$name = pathinfo($child->getAttribute('xlink:href'), PATHINFO_BASENAME);

			$imageType = explode('.', $name);
			if ($imageType[1] == 'svg' && $imageType[0] != "dragImage") {
			    //For QR code as it is itself a seperate SVG file					
			    $doc->loadXML(file_get_contents($sourceImagePath . $child->getAttribute('xlink:href')));
			    $doc->saveXML();
			    $root = $doc->firstChild;
			    $this->traverse($root, $sourceImagePath);
			    $doc->save($sourceImagePath . "cmyk_" . $child->getAttribute('xlink:href'));
			    $child->setAttribute('xlink:href', 'cmyk_' . $child->getAttribute('xlink:href'));
			}
		    }
		    if ($child->nodeType == XML_ELEMENT_NODE) {
			$this->traverse($child, $sourceImagePath, $level + 1);
		    }
		}
	    }
	}
    }

    public function handle_node(DomNode $node, $level) {
	/* for ( $x=0; $x<$level; $x++ ) {

	  } */
	if ($node->nodeType == XML_ELEMENT_NODE) {
	    /*
	      If there is no any fill then browser render it as black but TCPDF consider as the fill none. So object display with the fill none.
	      So to avoid this fill black as default.
	     */
	    if (!$node->getAttribute('fill')) {
		$node->setAttribute('fill', '#000000');
	    }

	    if ($node->getAttribute('fill') && $node->getAttribute('fill') != 'none') {
		$hexColor = $node->getAttribute('fill');
		$rgbColor = $this->hex2rgb($hexColor);
		$cmykColor = $this->rgb2cmyk($rgbColor);
		$node->setAttribute('fill', $cmykColor);
	    }
	    //If there is a only stroke or stroke-width, set the other value
	    if ($node->getAttribute('stroke-width') != "" || $node->getAttribute('stroke') != '') {	
		if ($node->getAttribute('stroke-width') == "") {
		    $node->setAttribute('stroke-width', "1");
		} else if ($node->getAttribute('stroke-width') == "0" || $node->getAttribute('stroke-width') == 0) {
		    $node->setAttribute('stroke', 'none');
		}
		
		if($node->getAttribute('stroke') == null || $node->getAttribute('stroke') == 'null') {
		    $node->setAttribute('stroke','none'); 
		}
		if ($node->getAttribute('stroke') != 'none') {
		    $hexColor = $node->getAttribute('stroke');
		    $rgbColor = $this->hex2rgb($hexColor);
		    $cmykColor = $this->rgb2cmyk($rgbColor);
		    $node->setAttribute('stroke', $cmykColor);
		}
	    }
	}
    }

    /*
      Convert HEX color code to RGB color code
     */

    public function hex2rgb($hex) {
	$color = str_replace('#', '', $hex);

	if (strlen($color) == 3) { // three letters color code
	    $rgb = array('r' => hexdec(substr($color, 0, 1) . substr($color, 0, 1)),
		'g' => hexdec(substr($color, 1, 1) . substr($color, 1, 1)),
		'b' => hexdec(substr($color, 2, 1) . substr($color, 2, 1)));
	} else { // six letters color code
	    $rgb = array('r' => hexdec(substr($color, 0, 2)),
		'g' => hexdec(substr($color, 2, 2)),
		'b' => hexdec(substr($color, 4, 2)));
	}
	return $rgb;
    }

    /*
      Convert RGB color code to CMYK color code
     */

    public function rgb2cmyk($var1, $g = 0, $b = 0) {
	if (is_array($var1)) {
	    $r = $var1['r'];
	    $g = $var1['g'];
	    $b = $var1['b'];
	} else {
	    $r = $var1;
	}
	$cyan = 255 - $r;
	$magenta = 255 - $g;
	$yellow = 255 - $b;
	$black = min($cyan, $magenta, $yellow);
	$cyan = @(($cyan - $black) / (255 - $black)) * 255;
	$magenta = @(($magenta - $black) / (255 - $black)) * 255;
	$yellow = @(($yellow - $black) / (255 - $black)) * 255;
	$cmyk = array('c' => ($cyan / 255) * 100,
	    'm' => ($magenta / 255) * 100,
	    'y' => ($yellow / 255) * 100,
	    'k' => ($black / 255) * 100);
	return "cmyk(" . implode(',', $cmyk) . ")";
    }

    /*   public function resizeGeneratedPDF($svgFileName, $pdfFile) {
      require_once(Mage::getBaseDir() . '/designtool/tcpdf/tcpdf.php');
      require_once(Mage::getBaseDir() . '/designtool/tcpdf/fpdi.php');
      require_once(Mage::getBaseDir() . '/designtool/tcpdf/pdf.php');

      if (($this->_product->getType_id() == 'simple') && ($this->_product->getAttributeSetId() == $this->_customCanvasAttributeSetId)):

      $size = $this->_productOptions['info_buyRequest']['size'];
      $size = json_decode($size);
      $outputWidth = floatval($size[0]);
      $outputHeight = floatval($size[1]);
      /* $outputWidth = $this->_product->getSizeWidth();
      $outputHeight = $this->_product->getSizeHeight();
      endif;

      if (($this->_product->getIsCustomizable()) && ($this->_product->getAttributeSetId() == $this->_customProductAttributeSetId)):
      $configAreaData = Mage::getModel('design/configarea')->load($this->_product->getEntityId(), 'product_id');

      $sideName = explode('_', $svgFileName);

      if ($sideName[0] == 'front') {
      $outputWidth = $configAreaData->getFaOutputWidth();
      $outputHeight = $configAreaData->getFaOutputHeight();
      } else if ($sideName[0] == 'back') {
      $outputWidth = $configAreaData->getBaOutputWidth();
      $outputHeight = $configAreaData->getBaOutputHeight();
      } else if ($sideName[0] == 'left') {
      $outputWidth = $configAreaData->getLeOutputWidth();
      $outputHeight = $configAreaData->getLeOutputHeight();
      } else if ($sideName[0] == 'right') {
      $outputWidth = $configAreaData->getRiOutputWidth();
      $outputHeight = $configAreaData->getRiOutputHeight();
      }
      endif;
      if ($this->_product->getBaseUnit() != '') {
      $unit = $this->_product->getAttributeText('base_unit');
      } else {
      $unit = Mage::getStoreConfig('customProduct/configuration/base_unit');
      }
      $width = $this->convertUnit($outputWidth, $unit);
      $height = $this->convertUnit($outputHeight, $unit);

      try {
      // initiate FPDI
      $pdf = new FPDI();
      $pdf->setPageUnit("mm");
      $pdf->SetPrintHeader(false);
      $pdf->SetPrintFooter(false);
      // add a page
      $pdf->AddPage();
      // set the source file
      $pdf->setSourceFile($pdfFile);

      // import page 1
      $tplIdx = $pdf->importPage(1);
      // use the imported page and place it at point 10,10 with a width of 210mm (width of A4)
      $pdf->useTemplate($tplIdx, $x = null, $y = null, $width, $height, $adjustPageSize = true);
      $pdf->Output($pdfFile, 'F');
      } catch (Exception $exc) {
      Mage::log("Inkscape Message" . $exc->getMessage(), null, "dnb.log");
      Mage::log("Inkscape TraceAsString" . $exc->getTraceAsString(), null, "dnb.log");
      }

      // print_r($this->_product->getData());
      // die;
      }
     */

    public function convertUnit($value, $unit) {
	if ($unit == 'px') {
	    $value = ($value * 25.4) / 96;
	    return $value;
	} else if ($unit == 'in') {
	    $value = $value / 0.03937;
	    return $value;
	} else if ($unit == 'cm') {
	    $value = $value * 10;
	    return $value;
	} else {
	    return $value;
	}
    }

    public function generateSVG($svgOutputFiles, $orderEntityId = '', $itemId = '', $designed_id = '', $nameArray = array(), $numberArray = array(), $product_id) {
	$mediaPath = TOOL_IMG_PATH . '/';
	$vectorImagePath = TOOL_IMG_PATH . '/orderimages/' . $designed_id . '/';
	$outputPath = TOOL_IMG_PATH . '/output/' . $designed_id . '/order-' . $orderEntityId . '-' . $itemId . '/';
	//$dir = TOOL_IMG_PATH . '/output/';
	//chmod($dir, 0777);
	if (!is_dir($outputPath)) {
	    $old = umask(0);
	    mkdir($outputPath, 0777, true);
	    umask($old);
	}

	$svgFilesToZip = array();
	$imageFilesToZip = array();
	$uploadedImage = array();
	$svgArray = array();
	$svgNameNumberArray = array();
	$svgNumber = 0;
	$productimagepath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/productimage/';
	$maskimages = $this->Designnbuy_common_webservice_model->getProductMaskImages($product_id);
	if (!empty($maskimages)) {
	    foreach ($maskimages as $key => $maskimage) {
		if (isset($maskimage) && $maskimage != '') {
		    copy($productimagepath . $maskimage, $outputPath . $maskimage);
		}
	    }
	}
	foreach ($svgOutputFiles as $svg):
	    $nameNumberFlag = 0;
	    $svgFileName = explode('.', $svg);
	    if ($svgFileName[1] == 'svg'):
		if (file_exists($vectorImagePath . $svg)):
		    $svgFilesToZip[] = $svg;
		    ob_clean();
		    $svgfileContents = file_get_contents($vectorImagePath . $svg);
		    $doc = new DOMDocument();
		    //$dom->preserveWhiteSpace = False;	     
		    $doc->loadXML($svgfileContents);
		    foreach ($doc->getElementsByTagNameNS('http://www.w3.org/2000/svg', 'svg') as $element):
			foreach ($element->getElementsByTagName("*") as $tags):
			    if ($tags->localName == 'image' && $tags->getAttribute('xlink:href') != ''): $imageUrl = $tags->getAttribute('xlink:href');
				$canvasOverlayImg = $tags->getAttribute('id');
				if ($canvasOverlayImg == 'canvas_overlay_img') {
				    $tags->parentNode->setAttribute("display", "none");
				}
				$name = pathinfo($imageUrl, PATHINFO_BASENAME);
				if ($name == 'dragImage.svg') {
				    $tags->setAttribute("svgtype", 'dragImage');
				    $tags->setAttribute("display", 'none');
				    $tags->parentNode->setAttribute("display", "none");
				    $children = $tags->parentNode->childNodes;
				    foreach ($children as $child) {
					$child->setAttribute("display", 'none');
				    }
				} else {
				    $tags->setAttribute('xlink:href', $name);
				    $uploadedImage = explode('images/', $imageUrl);
				    if ($uploadedImage[1] != ''):
					if (file_exists($mediaPath . $uploadedImage[1])) {
					    copy($mediaPath . $uploadedImage[1], $outputPath . $name);
					}
					$image = explode('/', $uploadedImage[1]);
					$hdimages = $this->Designnbuy_common_webservice_model->attachHdImageToOrderOutput($image[1]);
					foreach ($hdimages as $hd) {
					    if (file_exists($mediaPath . 'uploadedImage/' . $hd['image_hd'])) {
						copy($mediaPath . 'uploadedImage/' . $hd['image_hd'], $outputPath . $hd['image_hd']);
					    }
					}

				    // $imageFilesToZip[] = $uploadedImage[1];
				    endif;
				}
			    endif;
			    if ($tags->getAttribute('id') == 'nameText' || $tags->getAttribute('id') == 'numberText') {
				$nameNumberFlag = 1;
			    }

			endforeach;
		    endforeach;
		    $doc->save($outputPath . $svg);
		    if ($nameNumberFlag == 1) {
			$svgArray = $this->setName($svg, $vectorImagePath, $outputPath, $nameArray, $numberArray, $svgNumber);
			foreach ($svgArray as $file) {
			    $svgNameNumberArray[] = $file;
			}
		    }
		    $svgNumber++;
		endif;
	    endif;
	endforeach;
	//return array($svgFilesToZip, $imageFilesToZip);
	return $svgNameNumberArray;
    }

    public function setName($outputSvgFile, $vectorFilePath, $outputPath, $nameArray, $numberArray, $svgNumber) {
	$tempVar = 0;
	$outputSvgFilesArray = array();
	$svgfileContents = file_get_contents($outputPath . $outputSvgFile);
	$doc = new DOMDocument();
	//$dom->preserveWhiteSpace = False;
	$doc->loadXML($svgfileContents);
	$svgFileName = explode('.', $outputSvgFile);
	if (count($nameArray) > 0) {
	    for ($i = 0; $i < count($nameArray); $i++) {
		foreach ($doc->getElementsByTagNameNS('http://www.w3.org/2000/svg', 'svg') as $element) {
		    foreach ($element->getElementsByTagName("*") as $tags) {
			if (is_array($nameArray) && $tags->getAttribute('id') == 'nameText') {
			    //  $tags->nodeValue = $nameArray[$i];
			    $children = $tags->childNodes;
			    if (!empty($children)) {
				foreach ($children as $child) {
				    $child->setAttribute('d', $nameArray[$i]['d']);
				    $child->setAttribute('transform', $nameArray[$i]['transform']);
				}
			    }
			}
		    }
		}
		$filename = $svgFileName[0] . '_NN_' . $i . '.svg';
		$doc->save($outputPath . $filename);
		$number = $numberArray[$i];
		$outputModifiedSvgFile = $this->setNumber($filename, $vectorFilePath, $outputPath, $number);
		$outputSvgFilesArray[] = $outputModifiedSvgFile;
		$tempVar++;
	    }
	    unlink($outputPath . $outputSvgFile);
	    return $outputSvgFilesArray;
	}
    }

    public function setNumber($outputSvgFile, $vectorFilePath, $outputPath, $number) {
	$svgfileContents = file_get_contents($outputPath . $outputSvgFile);
	$doc = new DOMDocument();
	//$dom->preserveWhiteSpace = False;
	$doc->validateOnParse = true;
	$doc->loadXML($svgfileContents);
	foreach ($doc->getElementsByTagNameNS('http://www.w3.org/2000/svg', 'svg') as $element) {
	    foreach ($element->getElementsByTagName("*") as $tags) {
		if ($number != '' && $tags->getAttribute('id') == 'numberText') {
		    // $tags->nodeValue = $number;
		    $children = $tags->childNodes;
		    if (!empty($children)) {
			foreach ($children as $child) {
			    $child->setAttribute('d', $number['d']);
			    $child->setAttribute('transform', $number['transform']);
			}
		    }
		}
	    }
	}
	$doc->save($outputPath . $outputSvgFile);
	return $outputSvgFile;
    }

    public function zipOutputFiles($orderEntityId, $itemId, $designed_id) {
	$outputPath = TOOL_IMG_PATH . '/output/' . $designed_id . '/order-' . $orderEntityId . '-' . $itemId . '/';
	$zipPath = TOOL_IMG_PATH . '/output/' . $designed_id . '/';

	$zipFileName = "order-" . $orderEntityId . "-" . $itemId . ".zip";
	$destination = $zipPath . $zipFileName;

	$zip = new ZipArchive();

	if ($zip->open($destination, ZIPARCHIVE::CREATE) === true) {
	    foreach (glob($outputPath . '/*') as $file) {
		if ($file !== $destination) {
		    $zip->addFile($file, substr($file, strlen($outputPath)));
		}
	    }
	    $zip->close();
	    chmod($destination, 0755);
	}
	$this->deleteDir($outputPath);
	return $zipFileName;
    }

    public function deleteDir($dirPath) {
	if (!is_dir($dirPath)) {
	    //throw new InvalidArgumentException("$dirPath must be a directory");
	}
	if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	    $dirPath .= '/';
	}
	$files = glob($dirPath . '*', GLOB_MARK);
	foreach ($files as $file) {
	    if (is_dir($file)) {
		self::deleteDir($file);
	    } else {
		unlink($file);
	    }
	}
	rmdir($dirPath);
    }

    /*
     *   Message board
     */

    public function message_board() {
	$param = $this->input->get();
	$siteBaseUrl = unserialize(base64_decode($param['siteBaseUrl']));
	$url = $siteBaseUrl . $this->plateform_solution['order_details_path'] . '&order_id=' . $param['order_id'];
	$this->curl->create($url);
	$this->curl->option('returntransfer', 1);
	$data = $this->curl->execute();
	$data = json_decode($data, true);
	$thread_id = $this->Designnbuy_common_webservice_model->getOrderMessageBoardThread($param['order_id']);
	$data['thread_id'] = $thread_id;
	$data['user_type'] = 'customer';
	$data['plateform'] = $param['plateform'];
	$data['siteBaseUrl'] = $siteBaseUrl;
	$data['notificationEmails'] = $this->Designnbuy_common_webservice_model->getNotificationEmails($thread_id);
	$data['comments'] = $this->Designnbuy_common_webservice_model->getComments($thread_id);
	$param['language_id'] = $this->getLanguageIdBasedOnConnector($param['language_id']);
	$languagedata = $this->Designnbuy_common_webservice_model->getLanguageData($param['language_id']);
	$data['language_id'] = $param['language_id'];
	$data['language'] = $languagedata['others'];
	$this->load->view('pcoutput/message_board', $data);
    }

    public function message_board_admin() {
	$param = $this->input->get();
	$siteBaseUrl = unserialize(base64_decode($param['siteBaseUrl']));
	$url = $siteBaseUrl . $this->plateform_solution['order_details_path'] . '&order_id=' . $param['order_id'];
	$this->curl->create($url);
	$this->curl->option('returntransfer', 1);
	$data = $this->curl->execute();
	$data = json_decode($data, true);
	
	$thread_id = $this->Designnbuy_common_webservice_model->getOrderMessageBoardThread($param['order_id']);
	$data['thread_id'] = $thread_id;
	$data['user_type'] = 'admin';
	$data['plateform'] = $param['plateform'];
	$data['siteBaseUrl'] = $siteBaseUrl;
	$data['notificationEmails'] = $this->Designnbuy_common_webservice_model->getNotificationEmails($thread_id);
	$data['comments'] = $this->Designnbuy_common_webservice_model->getComments($thread_id);
	$param['language_id'] = $this->getLanguageIdBasedOnConnector($param['language_id']);
	$languagedata = $this->Designnbuy_common_webservice_model->getLanguageData($param['language_id']);
	$data['language_id'] = $param['language_id'];
	$data['language'] = $languagedata['others'];
	$this->load->view('pcoutput/message_board', $data);
    }

    public function addNotificationEmail() {
	$param = $this->input->post();
	$languagedata = $this->Designnbuy_common_webservice_model->getLanguageData($param['language_id']);
	$postdata = array(
		'email' => $param['email'],
		'thread_id' => $param['thread_id'],
		'user_type' => $param['user_type']
	    );
	$result = $this->Designnbuy_common_webservice_model->checkNotificationEmail($postdata);
	if ($result > 0) {
	    $data['response'] = 'false';
	    $data['message'] = $languagedata['others']['emailalreadyexists'];
	} else {
	    $notification_id = $this->Designnbuy_common_webservice_model->addNotificationEmail($postdata);
	    if ($notification_id) {
		$data['response'] = 'true';
		$data['data'] = $postdata;
		$data['notification_id'] = $notification_id;
		$data['message'] = 'Succcess';
	    } else {
		$data['response'] = 'false';
		$data['message'] = $languagedata['others']['errorsavingemail'];
	    }
	}
	echo json_encode($data);
	exit;
    }

    public function deleteNotificationEmail($notification_id,$language_id) {
	$languagedata = $this->Designnbuy_common_webservice_model->getLanguageData($language_id);
	$result = $this->Designnbuy_common_webservice_model->deleteNotificationEmail($notification_id);
	if ($result > 0) {
	    $data['response'] = 'true';
	    $data['message'] = 'Succcess';
	} else {
	    $data['response'] = 'false';
	    $data['message'] = $languagedata['others']['errordeletingemail'];
	}
	echo json_encode($data);
	exit;
    }

    public function addComment($thread_id) {	
	$param = $this->input->post();
	$languagedata = $this->Designnbuy_common_webservice_model->getLanguageData($param['language_id']);
	$siteBaseUrl = $param['siteBaseUrl'];
	$filepath = TOOL_IMG_PATH . '/message_board/' . $thread_id . '_' . $param['order_id'] . '/';
	$dirpath = TOOL_IMG_PATH . '/message_board/';

	$param['thread_id'] = $thread_id;
	if (trim($param['comment']) != '') {
	    $comment_row = $this->Designnbuy_common_webservice_model->addComment($param);

	    if (!is_dir($filepath)) {
		$old = umask(0);
		mkdir($filepath, 0777, true);
		umask($old);
	    }
	    if (!empty($_FILES['file']['tmp_name'][0])) {
		$upload_conf = array(
		    'upload_path' => $filepath,
		    'allowed_types' => '*',
		    'max_size' => '30000',
		    'max_filename' => '255',
		    'encrypt_name' => TRUE
		);
		$this->load->library('upload');
		$this->upload->initialize($upload_conf);

		foreach ($_FILES['file'] as $key => $val) {
		    $i = 1;
		    foreach ($val as $v) {
			$field_name = "file_" . $i;
			$_FILES[$field_name][$key] = $v;
			$i++;
		    }
		}
		unset($_FILES['file']);

		$error = array();
		$success = array();

		foreach ($_FILES as $field_name => $file) {
		    if (!$this->upload->do_upload($field_name)) {
			$error['upload'][] = $this->upload->display_errors();
		    } else {
			$imagedata = $this->upload->data();
			$image_param = array(
			    'comment_id' => $comment_row['comment_id'],
			    'file_name' => $imagedata['file_name'],
			    'real_file_name' => $imagedata['orig_name']
			);
			$file_id = $this->Designnbuy_common_webservice_model->addCommentFile($image_param);

			$upload_data[] = array(
			    'file_id' => $file_id,
			    'file_name' => $imagedata['file_name'],
			    'real_file_name' => $imagedata['orig_name']
			);
		    }
		}
		if (count($error) > 0) {
		    $data['error'] = $error;
		    $data['response'] = 'false';
		} else {
		    $html = '<div class="comments_box">
			<p><strong>' . $param['customer_name'] . '</strong>&nbsp;&nbsp;' . date("D, M j g:i a", strtotime($comment_row['created'])) . ' </p>
			<p id="comment">' . htmlspecialchars_decode($comment_row['comment']) . '</p>
			<div class="file_conntten">
			    <div class="file_name">';
		    foreach ($upload_data as $cimg) {
			$ext = pathinfo($cimg['file_name'], PATHINFO_EXTENSION);
			if (strtolower($ext) == 'pdf') {
			    $extimage = 'pdf-icon.png';
			} else if (strtolower($ext) == 'docx') {
			    $extimage = 'wordpad-icon.png';
			} else if (strtolower($ext) == 'psd') {
			    $extimage = 'psd-icon.png';
			} else {
			    $extimage = 'image-icon.png';
			}
			$realimage = $siteBaseUrl . 'designnbuy/assets/images/message_board/' . $thread_id . '_' . $param['order_id'] . '/' . $cimg['file_name'];
			$realextimage = $siteBaseUrl . 'designnbuy/assets/pcmedia/images/' . $extimage;
			$html.= '<span><a href="' . $realimage . '" target="_blank" download><img src="' . $realextimage . '" />' . $cimg['real_file_name'] . '</a></span>';
		    }
		    $html .= '</div>
			</div>
		    </div>';
		    //$this->sendNotificationEmails($comment_row['thread_id'], $comment_row['comment_id'], $param['order_id']);
		    $data['html'] = $html;
		    $data['response'] = 'true';
		}
	    } else {
		if ($comment_row) {
		    $html = '<div class="comments_box">
			<p><strong>' . $param['customer_name'] . '</strong>&nbsp;&nbsp;' . date("D, M j g:i a", strtotime($comment_row['created'])) . ' </p>
			<p id="comment">' . htmlspecialchars_decode($comment_row['comment']) . '</p>
			<div class="file_conntten">
			    <div class="file_name">
			    </div>
			</div>
		    </div>';
		    //$this->sendNotificationEmails($comment_row['thread_id'], $comment_row['comment_id'], $param['order_id']);
		    $data['html'] = $html;
		    $data['response'] = 'true';
		} else {
		    $data['message'] = '<p id="error-comment">'.$languagedata['others']['errorcomment'].'</p>';
		    $data['response'] = 'false';
		}
	    }
	} else {
	    $data['message'] = '<p id="error-comment" style="color:red;">'.$languagedata['others']['blankcommment'].'</p>';
	    $data['response'] = 'false';
	}
	echo die(json_encode($data));
    }

    public function sendNotificationEmails($thread_id, $comment_id, $order_id) {
	$comment = $this->Designnbuy_common_webservice_model->getComment($thread_id, $comment_id);
	$notification_emails = $this->Designnbuy_common_webservice_model->getNotificationEmails($thread_id);
	foreach ($notification_emails as $ne) {
	    $arr[] = $ne['email'];
	}
	$from = 'no-reply@designnbuy.com';
	$to = implode(", ", $arr);
	$subject = "Comment on order thread";
	$message = "<p>" . $comment['comment'] . " at " . date("D, M j g:i a", strtotime($comment['created'])) . "</p>";
	if (!empty($comment['files'])) {
	    foreach ($comment['files'] as $file) {
		$realimage = $this->pc_rootURL . 'designnbuy/assets/images/message_board/' . $thread_id . '_' . $order_id . '/' . $file['file_name'];
		$message .= "<br /><p><a href='" . $realimage . "'>" . $file['real_file_name'] . "</a></p>";
	    }
	}
	send_email($from, $to, $subject, $message);
	return true;
    }
    public function getLanguageIdBasedOnConnector($connector = 'en') {
	$language_id = $this->Designnbuy_common_webservice_model->getLanguageIdBasedOnConnector($connector);
	return $language_id;
    }

}