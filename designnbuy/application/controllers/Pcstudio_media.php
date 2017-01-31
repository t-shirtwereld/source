<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pcstudio_media extends PC_Controller {

    private $pc_rootURL;

    public function __construct() {
	parent::__construct();
	$postdata = $this->input->post();

	if (!empty($postdata)) {
	    if (!empty($postdata['postData'])) {
		$this->pc_rootURL = $postdata['postData']['siteBaseUrl'];
	    } else {
		$this->pc_rootURL = $postdata['siteBaseUrl'];
	    }
	}
	$this->plateform_solution = plateform_solutions(PLATEFORM);
	$this->load->library('curl'); //added by somin
	$this->load->model('Designnbuy_common_webservice_model');
    }

    public function generatePreviewPng() {
	if ($this->input->post('current_time')) {
	    $cur_time = $this->input->post('current_time');
	} else {
	    $cur_time = $current_time;
	}
	if ($this->input->post('side')) {
	    $currentSide = $this->input->post('side');
	} else {
	    $currentSide = $side;
	}
	$imageDir = TOOL_IMG_PATH . '/cartimages/';
	$static_string = '<svg xmlns="http://www.w3.org/2000/svg"></svg>';
	$ret_string_font = '';
	$outputPath = TOOL_IMG_PATH . '/cartimages/temp-' . $cur_time . '/';
	if (!is_dir($outputPath)) {
	    mkdir($outputPath, 0777);
	}
	if ($this->input->post('svg')) {
	    $ret_string = $this->input->post('svg');
	} else {
	    $ret_string = "undefined";
	}
	$ret_string = str_replace('<?xml version="1.0" encoding="UTF-8"?>', " ", $ret_string);
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}

	$svgFileName = $this->removePaths($ret_string_font . $ret_string, $cur_time, $currentSide);
	$pngFileName = $this->generatePNG($outputPath . $svgFileName, $cur_time);
	echo $pngFileName;
	die;
    }

    public function removePaths($svgContent, $cur_time, $side) {
	$image_type = 'design_image';
	if ($svgContent != '') {
	    if ($this->input->post('image_type')) {
		$imageType = $this->input->post('image_type');
	    } else {
		$imageType = $image_type;
	    }
	    $mediaPath = TOOL_IMG_PATH . '/';
	    $ipadUploadPath = realpath('.') . '/cartimages/';
	    $vectorImagePath = TOOL_IMG_PATH . '/cartimages/';
	    $outputPath = TOOL_IMG_PATH . '/cartimages/temp-' . $cur_time . '/';
	    $doc = new DOMDocument();

	    //$dom->preserveWhiteSpace = False;
	    $doc->loadXML($svgContent);
	    if ($imageType == 'product_image') {
		$suffix = 'product';
	    } else if ($imageType == 'design_image') {
		$suffix = 'design';
	    }
	    $svg = $side . '_' . $cur_time . '_' . $suffix . '.svg';
	    foreach ($doc->getElementsByTagNameNS('http://www.w3.org/2000/svg', 'svg') as $element) {

		foreach ($element->getElementsByTagName("*") as $tags) {
		    if ($tags->localName == 'image' && $tags->getAttribute('xlink:href') != '') {
			$imageUrl = $tags->getAttribute('xlink:href');
			$isAdmin = $tags->getAttribute('isAdminUploaded');
			if($isAdmin == "true"){
				$tags->setAttribute("svgtype", 'dragImage');
			    $tags->setAttribute("display", 'none');
			    $tags->parentNode->setAttribute("display", "none");
			    $children = $tags->parentNode->childNodes;
			    foreach ($children as $child) {
				$child->setAttribute("display", 'none');
			    }
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
			    copy($imageUrl, $outputPath . $name);
			}
		    }
		}
	    }
	    $doc->save($outputPath . $svg, $cur_time);
	    return $svg;
	}
    }

    public function generatePNG($svg, $cur_time) {
	$image_type = 'design_image';
	if (file_exists($svg)) {
	    if ($this->input->post('image_type')) {
		$imageType = $this->input->post('image_type');
	    } else {
		$imageType = 0;
	    }
	    $outputImagePath = TOOL_IMG_PATH . '/cartimages/temp-' . $cur_time . '/';
	    //   $imageUrl = base_url('/assets/images') . '/cartimages/temp-' . $cur_time . '/';
	    $imageUrl = $this->pc_rootURL . 'designnbuy//assets/images/cartimages/temp-' . $cur_time . '/';
	    $svgName = pathinfo($svg, PATHINFO_FILENAME);
	    if ($imageType == 'product_image') {
		$suffix = 'product';
	    } else if ($imageType == 'design_image') {
		$suffix = 'design';
	    }
	    $pngFileName = $svgName . '_' . $suffix . '.png';
	    $param = array('filename' => $svg);
	    $this->load->library('Inkscape', $param);

	    // $inkscape = new Inkscape($svg);

	    $this->inkscape->exportAreaSnap();
	    //$inkscape->exportAreaSnap();
	    //better pixel art
	    $this->inkscape->exportTextToPath();
	    //$inkscape->exportTextToPath();
	    // $inkscape->setSize($width=792, $height=408);
	    // $inkscape->setDpi(96);	
	    try {
		$ok = $this->inkscape->export('png', $outputImagePath . $pngFileName);
	    } catch (Exception $exc) {
		echo $exc->getMessage();
		echo $exc->getTraceAsString();
	    }

	    return $imageUrl . $pngFileName;
	}
    }

    public function removeTempImageDir($dir) {
	if (is_dir($dir)) {
	    $objects = scandir($dir);
	    foreach ($objects as $object) {
		if ($object != "." && $object != "..") {
		    if (filetype($dir . "/" . $object) == "dir")
			rrmdir($dir . "/" . $object); else
			unlink($dir . "/" . $object);
		}
	    }
	    reset($objects);
	    rmdir($dir);
	}
    }

    public function previewPdfDownload() {
	if ($this->input->post('pdfdata')) {
	    $pdfData = $this->input->post('pdfdata');
	} else {
	    $pdfData = 0;
	}
	$response = $this->getResponse();
	$response->setHeader('HTTP/1.1 200 OK', '');
	$response->setHeader('Pragma', 'public', true);
	$response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
	$response->setHeader('Content-Disposition', 'attachment; filename=result.pdf');
	$response->setHeader('Last-Modified', date('r'));
	$response->setHeader('Accept-Ranges', 'bytes');
	$response->setHeader('Content-Length', strlen($pdfData));
	$response->setHeader('Content-type', 'application/pdf');
	$response->setBody($pdfData);
	$response->sendResponse();
	$this->getResponse()->sendHeaders();
    }

    public function previewPdf() {
	require_once('./Zend/Pdf.php');
	$pdf = new Zend_Pdf();
	$wm = $this->Designnbuy_common_webservice_model->getGeneralConfigData();

	$watermarkpath = TOOL_IMG_PATH . "/logo/" . $wm['watermark_logo'];
	$timeStamp = time();
	$imageDir = TOOL_IMG_PATH . "/previewimages/";
	if ($this->input->post('png_data1')) {
	    $png_data1 = $this->input->post('png_data1');
	} else {
	    $png_data1 = "undefined";
	}

	if ($png_data1 != "undefined") {
	    $pngimage = $imageDir . 'side1_' . $timeStamp . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data1);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimage, $imgdata);
	    if (file_exists($pngimage)) {

		$image_size = getimagesize($pngimage);

		$page_width = $image_size[0];
		$page_hieght = $image_size[1];

		$arg["page_width"] = $page_width;
		$arg["page_hieght"] = $page_hieght;
		$arg["image_width"] = $image_size[0];
		$arg["image_hieght"] = $image_size[1];
		$arg["images"] = $pngimage;

		$page = $pdf->newPage($arg["page_width"], $arg["page_hieght"]);
		$images = $arg["images"];
		$left = 0;
		$right = $arg["image_width"];
		$top = $arg["page_hieght"];
		$bottom = 0;
		if ($wm['watermark_status'] == '1') {
		    $image = new Imagick($images);
		    $watermark = new Imagick($watermarkpath);
		    $iWidth = $image->getImageWidth();
		    $iHeight = $image->getImageHeight();
		    $wWidth = $watermark->getImageWidth();
		    $wHeight = $watermark->getImageHeight();

		    if ($iHeight < $wHeight || $iWidth < $wWidth) {
			// resize the watermark
			$watermark->scaleImage($iWidth, $iHeight);

			// get new size
			$wWidth = $watermark->getImageWidth();
			$wHeight = $watermark->getImageHeight();
		    }
		    $x = ($iWidth - $wWidth) / 2;
		    $y = ($iHeight - $wHeight) / 2;

		    $image->compositeImage($watermark, imagick::COMPOSITE_OVERLAY, $x, $y);
		    $image->writeImage($images);
		}
		$image = Zend_Pdf_Image::imageWithPath($images);
		$page->drawImage($image, $left, $bottom, $right, $top);
		$pdf->pages[] = $page;
		unlink($pngimage);
	    }
	}
	if ($this->input->post('png_data2')) {
	    $png_data2 = $this->input->post('png_data2');
	} else {
	    $png_data2 = "undefined";
	}
	if ($png_data2 != "undefined") {
	    $pngimage = $imageDir . 'side2_' . $timeStamp . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data2);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimage, $imgdata);
	    if (file_exists($pngimage)) {
		$image_size = getimagesize($pngimage);

		$page_width = $image_size[0];
		$page_height = $image_size[1];

		$arg["page_width"] = $page_width;
		$arg["page_height"] = $page_height;
		$arg["image_width"] = $image_size[0];
		$arg["image_hieght"] = $image_size[1];
		$arg["images"] = $pngimage;

		$page = $pdf->newPage($arg["page_width"], $arg["page_height"]);
		$images = $arg["images"];
		$left = 0;
		$right = $arg["image_width"];
		$top = $arg["page_height"];
		$bottom = 0;
		if ($wm['watermark_status'] == '1') {
		    $image = new Imagick($images);
		    $watermark = new Imagick($watermarkpath);
		    $iWidth = $image->getImageWidth();
		    $iHeight = $image->getImageHeight();
		    $wWidth = $watermark->getImageWidth();
		    $wHeight = $watermark->getImageHeight();

		    if ($iHeight < $wHeight || $iWidth < $wWidth) {
			// resize the watermark
			$watermark->scaleImage($iWidth, $iHeight);

			// get new size
			$wWidth = $watermark->getImageWidth();
			$wHeight = $watermark->getImageHeight();
		    }
		    $x = ($iWidth - $wWidth) / 2;
		    $y = ($iHeight - $wHeight) / 2;

		    $image->compositeImage($watermark, imagick::COMPOSITE_OVERLAY, $x, $y);
		    $image->writeImage($images);
		}
		$image = Zend_Pdf_Image::imageWithPath($images);
		$page->drawImage($image, $left, $bottom, $right, $top);
		$pdf->pages[] = $page;
		unlink($pngimage);
	    }
	}
	if ($this->input->post('png_data3')) {
	    $png_data3 = $this->input->post('png_data3');
	} else {
	    $png_data3 = "undefined";
	}
	if ($png_data3 != "undefined") {
	    $pngimage = $imageDir . 'side3_' . $timeStamp . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data3);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimage, $imgdata);
	    if (file_exists($pngimage)) {
		$image_size = getimagesize($pngimage);

		$page_width = $image_size[0];
		$page_height = $image_size[1];

		$arg["page_width"] = $page_width;
		$arg["page_height"] = $page_height;
		$arg["image_width"] = $image_size[0];
		$arg["image_hieght"] = $image_size[1];
		$arg["images"] = $pngimage;

		$page = $pdf->newPage($arg["page_width"], $arg["page_height"]);
		$images = $arg["images"];
		$left = 0;
		$right = $arg["image_width"];
		$top = $arg["page_height"];
		$bottom = 0;
		if ($wm['watermark_status'] == '1') {
		    $image = new Imagick($images);
		    $watermark = new Imagick($watermarkpath);
		    $iWidth = $image->getImageWidth();
		    $iHeight = $image->getImageHeight();
		    $wWidth = $watermark->getImageWidth();
		    $wHeight = $watermark->getImageHeight();

		    if ($iHeight < $wHeight || $iWidth < $wWidth) {
			// resize the watermark
			$watermark->scaleImage($iWidth, $iHeight);

			// get new size
			$wWidth = $watermark->getImageWidth();
			$wHeight = $watermark->getImageHeight();
		    }
		    $x = ($iWidth - $wWidth) / 2;
		    $y = ($iHeight - $wHeight) / 2;

		    $image->compositeImage($watermark, imagick::COMPOSITE_OVERLAY, $x, $y);
		    $image->writeImage($images);
		}
		$image = Zend_Pdf_Image::imageWithPath($images);
		$page->drawImage($image, $left, $bottom, $right, $top);
		$pdf->pages[] = $page;
		unlink($pngimage);
	    }
	}
	if ($this->input->post('png_data4')) {
	    $png_data4 = $this->input->post('png_data4');
	} else {
	    $png_data4 = "undefined";
	}
	if ($png_data4 != "undefined") {
	    $pngimage = $imageDir . 'side4_' . $timeStamp . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data4);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimage, $imgdata);
	    if (file_exists($pngimage)) {
		$image_size = getimagesize($pngimage);

		$page_width = $image_size[0];
		$page_height = $image_size[1];

		$arg["page_width"] = $page_width;
		$arg["page_height"] = $page_height;
		$arg["image_width"] = $image_size[0];
		$arg["image_hieght"] = $image_size[1];
		$arg["images"] = $pngimage;

		$page = $pdf->newPage($arg["page_width"], $arg["page_height"]);
		$images = $arg["images"];
		$left = 0;
		$right = $arg["image_width"];
		$top = $arg["page_height"];
		$bottom = 0;
		if ($wm['watermark_status'] == '1') {
		    $image = new Imagick($images);
		    $watermark = new Imagick($watermarkpath);
		    $iWidth = $image->getImageWidth();
		    $iHeight = $image->getImageHeight();
		    $wWidth = $watermark->getImageWidth();
		    $wHeight = $watermark->getImageHeight();

		    if ($iHeight < $wHeight || $iWidth < $wWidth) {
			// resize the watermark
			$watermark->scaleImage($iWidth, $iHeight);

			// get new size
			$wWidth = $watermark->getImageWidth();
			$wHeight = $watermark->getImageHeight();
		    }
		    $x = ($iWidth - $wWidth) / 2;
		    $y = ($iHeight - $wHeight) / 2;

		    $image->compositeImage($watermark, imagick::COMPOSITE_OVERLAY, $x, $y);
		    $image->writeImage($images);
		}
		$image = Zend_Pdf_Image::imageWithPath($images);
		$page->drawImage($image, $left, $bottom, $right, $top);
		$pdf->pages[] = $page;
		unlink($pngimage);
	    }
	}
	if ($this->input->post('png_data5')) {
	    $png_data5 = $this->input->post('png_data5');
	} else {
	    $png_data5 = "undefined";
	}
	if ($png_data5 != "undefined") {
	    $pngimage = $imageDir . 'side5_' . $timeStamp . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data5);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimage, $imgdata);
	    if (file_exists($pngimage)) {
		$image_size = getimagesize($pngimage);

		$page_width = $image_size[0];
		$page_height = $image_size[1];

		$arg["page_width"] = $page_width;
		$arg["page_height"] = $page_height;
		$arg["image_width"] = $image_size[0];
		$arg["image_hieght"] = $image_size[1];
		$arg["images"] = $pngimage;

		$page = $pdf->newPage($arg["page_width"], $arg["page_height"]);
		$images = $arg["images"];
		$left = 0;
		$right = $arg["image_width"];
		$top = $arg["page_height"];
		$bottom = 0;
		if ($wm['watermark_status'] == '1') {
		    $image = new Imagick($images);
		    $watermark = new Imagick($watermarkpath);
		    $iWidth = $image->getImageWidth();
		    $iHeight = $image->getImageHeight();
		    $wWidth = $watermark->getImageWidth();
		    $wHeight = $watermark->getImageHeight();

		    if ($iHeight < $wHeight || $iWidth < $wWidth) {
			// resize the watermark
			$watermark->scaleImage($iWidth, $iHeight);

			// get new size
			$wWidth = $watermark->getImageWidth();
			$wHeight = $watermark->getImageHeight();
		    }
		    $x = ($iWidth - $wWidth) / 2;
		    $y = ($iHeight - $wHeight) / 2;

		    $image->compositeImage($watermark, imagick::COMPOSITE_OVERLAY, $x, $y);
		    $image->writeImage($images);
		}
		$image = Zend_Pdf_Image::imageWithPath($images);
		$page->drawImage($image, $left, $bottom, $right, $top);
		$pdf->pages[] = $page;
		unlink($pngimage);
	    }
	}
	if ($this->input->post('png_data6')) {
	    $png_data6 = $this->input->post('png_data6');
	} else {
	    $png_data6 = "undefined";
	}
	if ($png_data6 != "undefined") {
	    $pngimage = $imageDir . 'side6_' . $timeStamp . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data6);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimage, $imgdata);
	    if (file_exists($pngimage)) {
		$image_size = getimagesize($pngimage);

		$page_width = $image_size[0];
		$page_height = $image_size[1];

		$arg["page_width"] = $page_width;
		$arg["page_height"] = $page_height;
		$arg["image_width"] = $image_size[0];
		$arg["image_hieght"] = $image_size[1];
		$arg["images"] = $pngimage;

		$page = $pdf->newPage($arg["page_width"], $arg["page_height"]);
		$images = $arg["images"];
		$left = 0;
		$right = $arg["image_width"];
		$top = $arg["page_height"];
		$bottom = 0;
		if ($wm['watermark_status'] == '1') {
		    $image = new Imagick($images);
		    $watermark = new Imagick($watermarkpath);
		    $iWidth = $image->getImageWidth();
		    $iHeight = $image->getImageHeight();
		    $wWidth = $watermark->getImageWidth();
		    $wHeight = $watermark->getImageHeight();

		    if ($iHeight < $wHeight || $iWidth < $wWidth) {
			// resize the watermark
			$watermark->scaleImage($iWidth, $iHeight);

			// get new size
			$wWidth = $watermark->getImageWidth();
			$wHeight = $watermark->getImageHeight();
		    }
		    $x = ($iWidth - $wWidth) / 2;
		    $y = ($iHeight - $wHeight) / 2;

		    $image->compositeImage($watermark, imagick::COMPOSITE_OVERLAY, $x, $y);
		    $image->writeImage($images);
		}
		$image = Zend_Pdf_Image::imageWithPath($images);
		$page->drawImage($image, $left, $bottom, $right, $top);
		$pdf->pages[] = $page;
		unlink($pngimage);
	    }
	}
	if ($this->input->post('png_data7')) {
	    $png_data7 = $this->input->post('png_data7');
	} else {
	    $png_data7 = "undefined";
	}
	if ($png_data7 != "undefined") {
	    $pngimage = $imageDir . 'side7_' . $timeStamp . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data7);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimage, $imgdata);
	    if (file_exists($pngimage)) {
		$image_size = getimagesize($pngimage);

		$page_width = $image_size[0];
		$page_height = $image_size[1];

		$arg["page_width"] = $page_width;
		$arg["page_height"] = $page_height;
		$arg["image_width"] = $image_size[0];
		$arg["image_hieght"] = $image_size[1];
		$arg["images"] = $pngimage;

		$page = $pdf->newPage($arg["page_width"], $arg["page_height"]);
		$images = $arg["images"];
		$left = 0;
		$right = $arg["image_width"];
		$top = $arg["page_height"];
		$bottom = 0;
		if ($wm['watermark_status'] == '1') {
		    $image = new Imagick($images);
		    $watermark = new Imagick($watermarkpath);
		    $iWidth = $image->getImageWidth();
		    $iHeight = $image->getImageHeight();
		    $wWidth = $watermark->getImageWidth();
		    $wHeight = $watermark->getImageHeight();

		    if ($iHeight < $wHeight || $iWidth < $wWidth) {
			// resize the watermark
			$watermark->scaleImage($iWidth, $iHeight);

			// get new size
			$wWidth = $watermark->getImageWidth();
			$wHeight = $watermark->getImageHeight();
		    }
		    $x = ($iWidth - $wWidth) / 2;
		    $y = ($iHeight - $wHeight) / 2;

		    $image->compositeImage($watermark, imagick::COMPOSITE_OVERLAY, $x, $y);
		    $image->writeImage($images);
		}
		$image = Zend_Pdf_Image::imageWithPath($images);
		$page->drawImage($image, $left, $bottom, $right, $top);
		$pdf->pages[] = $page;
		unlink($pngimage);
	    }
	}
	if ($this->input->post('png_data8')) {
	    $png_data8 = $this->input->post('png_data8');
	} else {
	    $png_data8 = "undefined";
	}
	if ($png_data8 != "undefined") {
	    $pngimage = $imageDir . 'side8_' . $timeStamp . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data8);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimage, $imgdata);
	    if (file_exists($pngimage)) {
		$image_size = getimagesize($pngimage);

		$page_width = $image_size[0];
		$page_height = $image_size[1];

		$arg["page_width"] = $page_width;
		$arg["page_height"] = $page_height;
		$arg["image_width"] = $image_size[0];
		$arg["image_hieght"] = $image_size[1];
		$arg["images"] = $pngimage;

		$page = $pdf->newPage($arg["page_width"], $arg["page_height"]);
		$images = $arg["images"];
		$left = 0;
		$right = $arg["image_width"];
		$top = $arg["page_height"];
		$bottom = 0;
		if ($wm['watermark_status'] == '1') {
		    $image = new Imagick($images);
		    $watermark = new Imagick($watermarkpath);
		    $iWidth = $image->getImageWidth();
		    $iHeight = $image->getImageHeight();
		    $wWidth = $watermark->getImageWidth();
		    $wHeight = $watermark->getImageHeight();

		    if ($iHeight < $wHeight || $iWidth < $wWidth) {
			// resize the watermark
			$watermark->scaleImage($iWidth, $iHeight);

			// get new size
			$wWidth = $watermark->getImageWidth();
			$wHeight = $watermark->getImageHeight();
		    }
		    $x = ($iWidth - $wWidth) / 2;
		    $y = ($iHeight - $wHeight) / 2;

		    $image->compositeImage($watermark, imagick::COMPOSITE_OVERLAY, $x, $y);
		    $image->writeImage($images);
		}
		$image = Zend_Pdf_Image::imageWithPath($images);
		$page->drawImage($image, $left, $bottom, $right, $top);
		$pdf->pages[] = $page;
		unlink($pngimage);
	    }
	}

	$pdfData = $pdf->render();
	header("Content-Disposition: inline; filename=preview.pdf");
	header("Content-type: application/x-pdf");
	echo $pdfData;
	exit();
    }

    public function savebase64onserver() {
	if ($this->input->post('currentTime')) {
	    $currentTime = $this->input->post('currentTime');
	} else {
	    $currentTime = 0;
	}

	if ($this->input->post('sideNameAry')) {
	    $sideNameAry = $this->input->post('sideNameAry');
	} else {
	    $sideNameAry = array();
	}
	$sideNameAry = explode(",", $sideNameAry);

	$tempDir = TOOL_IMG_PATH . '/cartimages/temp-' . $currentTime . '/';
	$this->removeTempImageDir($tempDir);

	$cur_time = time();
	$imageDir = TOOL_IMG_PATH . '/cartimages/' . $currentTime . '/';
	if (!is_dir($imageDir)) {
	    mkdir($imageDir, 0777);
	}
	$static_string = '<svg xmlns="http://www.w3.org/2000/svg"></svg>';
	if ($this->input->post('fonts_used')) {
	    $fonts_used = $this->input->post('fonts_used');
	} else {
	    $fonts_used = '';
	}
	$fonts_used_arr = explode('||', $fonts_used);
	$ret_string_font = '';

	if (!empty($fonts_used_arr)) {
	    foreach ($fonts_used_arr as $font_name) {
		if ($font_name != '')
		    $ret_string_font .= '<?xml-stylesheet href="' . $font_name . '" type="text/css"?>';
	    }
	}
	if ($this->input->post('png_data1')) {
	    $png_data1 = $this->input->post('png_data1');
	} else {
	    $png_data1 = 0;
	}
	$pngimageDir = $imageDir . $sideNameAry[0] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data1);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data2')) {
	    $png_data2 = $this->input->post('png_data2');
	} else {
	    $png_data2 = 0;
	}
	$pngimageDir = $imageDir . $sideNameAry[1] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data2);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data3')) {
	    $png_data3 = $this->input->post('png_data3');
	} else {
	    $png_data3 = 0;
	}
	$pngimageDir = $imageDir . $sideNameAry[2] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data3);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data4')) {
	    $png_data4 = $this->input->post('png_data4');
	} else {
	    $png_data4 = 0;
	}
	$pngimageDir = $imageDir . $sideNameAry[3] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data4);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data5')) {
	    $png_data5 = $this->input->post('png_data5');
	} else {
	    $png_data5 = 0;
	}
	$pngimageDir = $imageDir . $sideNameAry[4] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data5);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data6')) {
	    $png_data6 = $this->input->post('png_data6');
	} else {
	    $png_data6 = 0;
	}
	$pngimageDir = $imageDir . $sideNameAry[5] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data6);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data7')) {
	    $png_data7 = $this->input->post('png_data7');
	} else {
	    $png_data7 = 0;
	}
	$pngimageDir = $imageDir . $sideNameAry[6] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data7);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data8')) {
	    $png_data8 = $this->input->post('png_data8');
	} else {
	    $png_data8 = 0;
	}
	$pngimageDir = $imageDir . $sideNameAry[7] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data8);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('svg_1')) {
	    $ret_string = $this->input->post('svg_1');
	} else {
	    $ret_string = "undefined";
	}
	$ret_string = str_replace('<?xml version="1.0" encoding="UTF-8"?>', " ", $ret_string);
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $sideNameAry[0] . '.svg', $ret_string_font . $ret_string);

	if ($this->input->post('svg_2')) {
	    $ret_string = $this->input->post('svg_2');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $sideNameAry[1] . '.svg', $ret_string_font . $ret_string);

	if ($this->input->post('svg_3')) {
	    $ret_string = $this->input->post('svg_3');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $sideNameAry[2] . '.svg', $ret_string_font . $ret_string);

	if ($this->input->post('svg_4')) {
	    $ret_string = $this->input->post('svg_4');
	} else {
	    $ret_string = "undefined";
	}

	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $sideNameAry[3] . '.svg', $ret_string_font . $ret_string);

	if ($this->input->post('svg_5')) {
	    $ret_string = $this->input->post('svg_5');
	} else {
	    $ret_string = "undefined";
	}

	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $sideNameAry[4] . '.svg', $ret_string_font . $ret_string);

	if ($this->input->post('svg_6')) {
	    $ret_string = $this->input->post('svg_6');
	} else {
	    $ret_string = "undefined";
	}

	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $sideNameAry[5] . '.svg', $ret_string_font . $ret_string);

	if ($this->input->post('svg_7')) {
	    $ret_string = $this->input->post('svg_7');
	} else {
	    $ret_string = "undefined";
	}

	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $sideNameAry[6] . '.svg', $ret_string_font . $ret_string);

	if ($this->input->post('svg_8')) {
	    $ret_string = $this->input->post('svg_8');
	} else {
	    $ret_string = "undefined";
	}

	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $sideNameAry[7] . '.svg', $ret_string_font . $ret_string);

	$file_name['f1'] = $sideNameAry[0] . '.svg';
	$file_name['f2'] = $sideNameAry[1] . '.svg';
	$file_name['f3'] = $sideNameAry[2] . '.svg';
	$file_name['f4'] = $sideNameAry[3] . '.svg';
	$file_name['f5'] = $sideNameAry[4] . '.svg';
	$file_name['f6'] = $sideNameAry[5] . '.svg';
	$file_name['f7'] = $sideNameAry[6] . '.svg';
	$file_name['f8'] = $sideNameAry[7] . '.svg';
	$file_name['f9'] = $sideNameAry[0] . '.png';
	$file_name['f10'] = $sideNameAry[1] . '.png';
	$file_name['f11'] = $sideNameAry[2] . '.png';
	$file_name['f12'] = $sideNameAry[3] . '.png';
	$file_name['f13'] = $sideNameAry[4] . '.png';
	$file_name['f14'] = $sideNameAry[5] . '.png';
	$file_name['f15'] = $sideNameAry[6] . '.png';
	$file_name['f16'] = $sideNameAry[7] . '.png';
	echo json_encode($file_name);
	die;
    }

    public function savemydesign() {
	if ($this->input->post('currentTime')) {
	    $currentTime = $this->input->post('currentTime');
	} else {
	    $currentTime = 0;
	}

	if ($this->input->post('sideNameAry')) {
	    $sideNameAry = $this->input->post('sideNameAry');
	} else {
	    $sideNameAry = array();
	}
	$sideNameAry = explode(",", $sideNameAry);

	$imageDir = TOOL_IMG_PATH . '/saveimg/' . $currentTime . '/';
	if (!is_dir($imageDir)) {
	    mkdir($imageDir, 0777);
	}

	$static_string = '<svg xmlns="http://www.w3.org/2000/svg"></svg>';
	$tempDir = TOOL_IMG_PATH . '/cartimages/temp-' . $currentTime . '/';
	$this->removeTempImageDir($tempDir);
	$data = array();

	if ($this->input->post('addtocartparam')) {
	    $addtocartparam = json_decode(html_entity_decode($this->input->post('addtocartparam')), true);
	} else {
	    $addtocartparam = 0;
	}

	$product_id = $addtocartparam['productID'];
	$option = array();
	foreach ($addtocartparam as $id => $value) {
	    if ($id != 'productID') {
		$option += array(
		    $id => $value
		);
	    }
	}

	if ($this->input->post('no_of_side')) {
	    $no_of_sides = $this->input->post('no_of_side');
	} else {
	    $no_of_sides = 0;
	}

	if ($this->input->post('cust_id')) {
	    $data['customer_id'] = $this->input->post('cust_id');
	} else {
	    $data['customer_id'] = 0;
	}

	if ($this->input->post('color_id') && $this->input->post('color_id') != 'undefined') {
	    $data['color_id'] = $this->input->post('color_id');
	} else {
	    $data['color_id'] = NULL;
	}

	if ($this->input->post('size_id') && $this->input->post('size_id') != 'undefined') {
	    $data['size_id'] = $this->input->post('size_id');
	} else {
	    $data['size_id'] = NULL;
	}

	if ($this->input->post('design_name')) {
	    $data['design_name'] = $this->input->post('design_name');
	} else {
	    $data['design_name'] = 0;
	}

	if ($this->input->post('printingMethod')) {
	    $printingMethod = json_decode(html_entity_decode($this->input->post('printingMethod')), true);
	} else {
	    $printingMethod = '';
	}

	$data['designed_id'] = $currentTime;
	$data['product_id'] = $product_id;
	$data['product_options_id'] = json_encode($option);
	$data['printing_method'] = json_encode($printingMethod);
	$data['no_of_sides'] = $no_of_sides;

	$data['side1_svg'] = $sideNameAry[0] . '.svg';
	$data['side2_svg'] = $sideNameAry[1] . '.svg';
	$data['side3_svg'] = $sideNameAry[2] . '.svg';
	$data['side4_svg'] = $sideNameAry[3] . '.svg';
	$data['side5_svg'] = $sideNameAry[4] . '.svg';
	$data['side6_svg'] = $sideNameAry[5] . '.svg';
	$data['side7_svg'] = $sideNameAry[6] . '.svg';
	$data['side8_svg'] = $sideNameAry[7] . '.svg';

	$data['side1_png'] = $sideNameAry[0] . '.png';
	$data['side2_png'] = $sideNameAry[1] . '.png';
	$data['side3_png'] = $sideNameAry[2] . '.png';
	$data['side4_png'] = $sideNameAry[3] . '.png';
	$data['side5_png'] = $sideNameAry[4] . '.png';
	$data['side6_png'] = $sideNameAry[5] . '.png';
	$data['side7_png'] = $sideNameAry[6] . '.png';
	$data['side8_png'] = $sideNameAry[7] . '.png';

	$side1_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side1'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side1'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side1']
	);

	$side2_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side2'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side2'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side2']
	);

	$side3_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side3'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side3'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side3']
	);

	$side4_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side4'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side4'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side4']
	);

	$side5_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side5'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side5'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side5']
	);

	$side6_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side6'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side6'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side6']
	);

	$side7_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side7'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side7'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side7']
	);

	$side8_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side8'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side8'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side8']
	);
	$data['side1_otherdata'] = json_encode($side1_otherdata);
	$data['side2_otherdata'] = json_encode($side2_otherdata);
	$data['side3_otherdata'] = json_encode($side3_otherdata);
	$data['side4_otherdata'] = json_encode($side4_otherdata);
	$data['side5_otherdata'] = json_encode($side5_otherdata);
	$data['side6_otherdata'] = json_encode($side6_otherdata);
	$data['side7_otherdata'] = json_encode($side7_otherdata);
	$data['side8_otherdata'] = json_encode($side8_otherdata);
	$data['side8_otherdata'] = json_encode($side8_otherdata);
	$data['name_number_data'] = $this->input->post('namenumData');


	if ($this->input->post('png_data1')) {
	    $png_data1 = $this->input->post('png_data1');
	} else {
	    $png_data1 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[0] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data1);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data2')) {
	    $png_data2 = $this->input->post('png_data2');
	} else {
	    $png_data2 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[1] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data2);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data3')) {
	    $png_data3 = $this->input->post('png_data3');
	} else {
	    $png_data3 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[2] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data3);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data4')) {
	    $png_data4 = $this->input->post('png_data4');
	} else {
	    $png_data4 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[3] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data4);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data5')) {
	    $png_data5 = $this->input->post('png_data5');
	} else {
	    $png_data5 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[4] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data5);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data6')) {
	    $png_data6 = $this->input->post('png_data6');
	} else {
	    $png_data6 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[5] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data6);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data7')) {
	    $png_data7 = $this->input->post('png_data7');
	} else {
	    $png_data7 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[6] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data7);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data8')) {
	    $png_data8 = $this->input->post('png_data8');
	} else {
	    $png_data8 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[7] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data8);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('svg_1')) {
	    $ret_string = $this->input->post('svg_1');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side1_svg'], $ret_string);

	if ($this->input->post('svg_2')) {
	    $ret_string = $this->input->post('svg_2');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}

	file_put_contents($imageDir . $data['side2_svg'], $ret_string);

	if ($this->input->post('svg_3')) {
	    $ret_string = $this->input->post('svg_3');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side3_svg'], $ret_string);

	if ($this->input->post('svg_4')) {
	    $ret_string = $this->input->post('svg_4');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side4_svg'], $ret_string);

	if ($this->input->post('svg_5')) {
	    $ret_string = $this->input->post('svg_5');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side5_svg'], $ret_string);

	if ($this->input->post('svg_6')) {
	    $ret_string = $this->input->post('svg_6');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side6_svg'], $ret_string);

	if ($this->input->post('svg_7')) {
	    $ret_string = $this->input->post('svg_7');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side7_svg'], $ret_string);

	if ($this->input->post('svg_8')) {
	    $ret_string = $this->input->post('svg_8');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side8_svg'], $ret_string);

	if ($this->input->post('design_id') && $this->input->post('design_id') != '') {
	    $id = $this->input->post('design_id');
	    $this->Designnbuy_common_webservice_model->updateMyDesign($id, $data);
	} else {
	    $id = $this->Designnbuy_common_webservice_model->addMyDesign($data);
	}
	$postdata = array();
	$postdata['design_id'] = $id;
	$postdata['design_name'] = $data['design_name'];
	echo json_encode($postdata);
	exit;
    }

    public function sharemydesign() {
	if ($this->input->post('currentTime')) {
	    $currentTime = $this->input->post('currentTime');
	} else {
	    $currentTime = 0;
	}

	if ($this->input->post('sideNameAry')) {
	    $sideNameAry = $this->input->post('sideNameAry');
	} else {
	    $sideNameAry = array();
	}
	$sideNameAry = explode(",", $sideNameAry);

	$imageDir = TOOL_IMG_PATH . '/saveimg/' . $currentTime . '/';
	if (!is_dir($imageDir)) {
	    mkdir($imageDir, 0777);
	}

	$static_string = '<svg xmlns="http://www.w3.org/2000/svg"></svg>';
	$tempDir = TOOL_IMG_PATH . '/cartimages/temp-' . $currentTime . '/';
	$this->removeTempImageDir($tempDir);
	$data = array();

	if ($this->input->post('addtocartparam')) {
	    $addtocartparam = json_decode(html_entity_decode($this->input->post('addtocartparam')), true);
	} else {
	    $addtocartparam = 0;
	}

	$product_id = $addtocartparam['productID'];
	$option = array();
	foreach ($addtocartparam as $id => $value) {
	    if ($id != 'productID') {
		$option += array(
		    $id => $value
		);
	    }
	}

	if ($this->input->post('no_of_side')) {
	    $no_of_sides = $this->input->post('no_of_side');
	} else {
	    $no_of_sides = 0;
	}

	if ($this->input->post('cust_id')) {
	    $data['customer_id'] = $this->input->post('cust_id');
	} else {
	    $data['customer_id'] = 0;
	}

	if ($this->input->post('color_id')) {
	    $data['color_id'] = $this->input->post('color_id');
	} else {
	    $data['color_id'] = '';
	}

	if ($this->input->post('size_id')) {
	    $data['size_id'] = $this->input->post('size_id');
	} else {
	    $data['size_id'] = '';
	}
  
	if($data['size_id'] == "undefined"){
		$data['size_id'] = 0;
	}

	if ($this->input->post('design_name')) {
	    $data['design_name'] = $this->input->post('design_name');
	} else {
	    $data['design_name'] = 0;
	}

	if ($this->input->post('printingMethod')) {
	    $printingMethod = json_decode(html_entity_decode($this->input->post('printingMethod')), true);
	} else {
	    $printingMethod = '';
	}

	$data['designed_id'] = $currentTime;
	$data['product_id'] = $product_id;
	$data['product_options_id'] = json_encode($option);
	$data['printing_method'] = json_encode($printingMethod);
	$data['no_of_sides'] = $no_of_sides;

	$data['side1_svg'] = $sideNameAry[0] . '.svg';
	$data['side2_svg'] = $sideNameAry[1] . '.svg';
	$data['side3_svg'] = $sideNameAry[2] . '.svg';
	$data['side4_svg'] = $sideNameAry[3] . '.svg';
	$data['side5_svg'] = $sideNameAry[4] . '.svg';
	$data['side6_svg'] = $sideNameAry[5] . '.svg';
	$data['side7_svg'] = $sideNameAry[6] . '.svg';
	$data['side8_svg'] = $sideNameAry[7] . '.svg';

	$data['side1_png'] = $sideNameAry[0] . '.png';
	$data['side2_png'] = $sideNameAry[1] . '.png';
	$data['side3_png'] = $sideNameAry[2] . '.png';
	$data['side4_png'] = $sideNameAry[3] . '.png';
	$data['side5_png'] = $sideNameAry[4] . '.png';
	$data['side6_png'] = $sideNameAry[5] . '.png';
	$data['side7_png'] = $sideNameAry[6] . '.png';
	$data['side8_png'] = $sideNameAry[7] . '.png';

	$side1_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side1'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side1'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side1']
	);

	$side2_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side2'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side2'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side2']
	);

	$side3_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side3'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side3'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side3']
	);

	$side4_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side4'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side4'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side4']
	);

	$side5_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side5'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side5'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side5']
	);

	$side6_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side6'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side6'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side6']
	);

	$side7_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side7'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side7'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side7']
	);

	$side8_otherdata = array(
	    'colors' => $printingMethod['sideWiseColorInfo']['Side8'],
	    'fonts' => $printingMethod['sideWiseFontIDs']['Side8'],
	    'images' => $printingMethod['sideWiseImgIDs']['Side8']
	);
	$data['side1_otherdata'] = json_encode($side1_otherdata);
	$data['side2_otherdata'] = json_encode($side2_otherdata);
	$data['side3_otherdata'] = json_encode($side3_otherdata);
	$data['side4_otherdata'] = json_encode($side4_otherdata);
	$data['side5_otherdata'] = json_encode($side5_otherdata);
	$data['side6_otherdata'] = json_encode($side6_otherdata);
	$data['side7_otherdata'] = json_encode($side7_otherdata);
	$data['side8_otherdata'] = json_encode($side8_otherdata);
	$data['name_number_data'] = $this->input->post('namenumData');

	if ($this->input->post('png_data1')) {
	    $png_data1 = $this->input->post('png_data1');
	} else {
	    $png_data1 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[0] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data1);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data2')) {
	    $png_data2 = $this->input->post('png_data2');
	} else {
	    $png_data2 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[1] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data2);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data3')) {
	    $png_data3 = $this->input->post('png_data3');
	} else {
	    $png_data3 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[2] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data3);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data4')) {
	    $png_data4 = $this->input->post('png_data4');
	} else {
	    $png_data4 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[3] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data4);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data5')) {
	    $png_data5 = $this->input->post('png_data5');
	} else {
	    $png_data5 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[4] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data5);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data6')) {
	    $png_data6 = $this->input->post('png_data6');
	} else {
	    $png_data6 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[5] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data6);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data7')) {
	    $png_data7 = $this->input->post('png_data7');
	} else {
	    $png_data7 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[6] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data7);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('png_data8')) {
	    $png_data8 = $this->input->post('png_data8');
	} else {
	    $png_data8 = 'undefined';
	}
	$pngimageDir = $imageDir . $sideNameAry[7] . '.png';

	$img = str_replace('data:image/png;base64,', '', $png_data8);
	$img = str_replace(' ', '+', $img);
	$imgdata = base64_decode($img);
	file_put_contents($pngimageDir, $imgdata);

	if ($this->input->post('svg_1')) {
	    $ret_string = $this->input->post('svg_1');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side1_svg'], $ret_string);

	if ($this->input->post('svg_2')) {
	    $ret_string = $this->input->post('svg_2');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side2_svg'], $ret_string);

	if ($this->input->post('svg_3')) {
	    $ret_string = $this->input->post('svg_3');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side3_svg'], $ret_string);

	if ($this->input->post('svg_4')) {
	    $ret_string = $this->input->post('svg_4');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side4_svg'], $ret_string);

	if ($this->input->post('svg_5')) {
	    $ret_string = $this->input->post('svg_5');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side5_svg'], $ret_string);

	if ($this->input->post('svg_6')) {
	    $ret_string = $this->input->post('svg_6');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side6_svg'], $ret_string);

	if ($this->input->post('svg_7')) {
	    $ret_string = $this->input->post('svg_7');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side7_svg'], $ret_string);

	if ($this->input->post('svg_8')) {
	    $ret_string = $this->input->post('svg_8');
	} else {
	    $ret_string = "undefined";
	}
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}
	file_put_contents($imageDir . $data['side8_svg'], $ret_string);
	$id = $this->Designnbuy_common_webservice_model->addMyDesign($data);
	$filename['file_path'] = base_url('assets/images/saveimg') . '/' . $currentTime . '/';
	$filename['design_name'] = $data['design_name'];
	$filename['side1_png'] = $sideNameAry[0] . '.png';
	$filename['side2_png'] = $sideNameAry[1] . '.png';
	$filename['side3_png'] = $sideNameAry[2] . '.png';
	$filename['side4_png'] = $sideNameAry[3] . '.png';
	$filename['side5_png'] = $sideNameAry[4] . '.png';
	$filename['side6_png'] = $sideNameAry[5] . '.png';
	$filename['side7_png'] = $sideNameAry[6] . '.png';
	$filename['side8_png'] = $sideNameAry[7] . '.png';
	$filename['design_id'] = $this->pc_rootURL . $this->plateform_solution['sharedesign'] . $id;
	echo json_encode($filename);
	die;
    }

    public function upload() {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	/*
	  // Support CORS
	  header("Access-Control-Allow-Origin: *");
	  // other CORS headers if any...
	  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	  exit; // finish preflight CORS requests here
	  }
	 */
	if (isset($_REQUEST["isUpload"]) && $_REQUEST["isUpload"] == 1) {

	    // 5 minutes execution time
	    @set_time_limit(5 * 60);

	    // Uncomment this one to fake upload time
	    // usleep(5000);
	    // Settings
	    //$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
	    $targetDir = TOOL_IMG_PATH . DIRECTORY_SEPARATOR . 'uploadedImage' . DIRECTORY_SEPARATOR;
	    // $targetDir = 'uploads';
	    $cleanupTargetDir = true; // Remove old files
	    $maxFileAge = 5 * 3600; // Temp file age in seconds
	    // Create target dir
	    if (!file_exists($targetDir)) {
		@mkdir($targetDir);
	    }

	    // Get a file name
	    if (isset($_REQUEST["name"])) {
		$fileName = $_REQUEST["name"];
	    } elseif (!empty($_FILES)) {
		$fileName = $_FILES["file"]["name"];
	    } else {
		$fileName = uniqid("file_");
	    }
	    if ($_REQUEST['isFront'] == 0) {
		$fileName = 'admin_' . $fileName;
	    }
	    $filePath = $targetDir . $fileName;

	    // Chunking might be enabled
	    $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
	    $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;


	    // Remove old temp files	
	    if ($cleanupTargetDir) {
		if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
		    die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
		}

		while (($file = readdir($dir)) !== false) {
		    $tmpfilePath = $targetDir . $file;

		    // If temp file is current file proceed to the next
		    if ($tmpfilePath == "{$filePath}.part") {
			continue;
		    }

		    // Remove temp file if it is older than the max age and is not the current file
		    if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
			@unlink($tmpfilePath);
		    }
		}
		closedir($dir);
	    }


	    // Open temp file
	    if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	    }

	    if (!empty($_FILES)) {
		if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
		    die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		}

		// Read binary input stream and append it to temp file
		if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
		    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		}
	    } else {
		if (!$in = @fopen("php://input", "rb")) {
		    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		}
	    }

	    while ($buff = fread($in, 4096)) {
		fwrite($out, $buff);
	    }

	    @fclose($out);
	    @fclose($in);

	    // Check if file has been uploaded
	    if (!$chunks || $chunk == $chunks - 1) {
		$extensions = array('psd', 'pdf', 'ai', 'cdr', 'eps', 'tif', 'tiff', 'ps');
		// Strip the temp .part suffix off 
		rename("{$filePath}.part", $filePath);
		$isHD = '';
		$imageId = '';
		$id = '';
		$response = array();
		//$customerId = '1';
		if (isset($_REQUEST['customer_id'])) {
		    $user_id = $_REQUEST['customer_id'];
		} else {
		    $user_id = 0;
		}

		if (isset($_REQUEST['isHD'])) {
		    $isHD = $_REQUEST['isHD'];
		}
		if (isset($_REQUEST['imageId'])) {
		    $imageId = $_REQUEST['imageId'];
		}

		//if ($_REQUEST['isFront'] == 1) {
		/* if ($_REQUEST["toolType"] == 'producttool') {
		  //$id = Mage::helper('design')->saveUserImage($fileName, $isHD, $imageId);
		  $id = 1;
		  } else {
		  //$id = Mage::helper('web2print')->saveUserImage($fileName, $isHD, $imageId);
		  $id = 1;
		  } */

		if ($imageId != '') {
		    $data = array(
			'user_id' => $user_id,
			'user_session_id' => '0',
			'image_hd' => $fileName
		    );
		    $id = $this->Designnbuy_common_webservice_model->saveUserImageHdSingle($data, $imageId);
		    $response = array(
			'id' => $imageId,
			'images' => $fileName
		    );
		} else {
		    if (PACKAGE_TYPE == 'PRO') {

			$imageext = pathinfo($fileName, PATHINFO_EXTENSION);
			if (in_array(strtolower($imageext), $extensions)) {
			    $im = new Imagick($filePath);

			    if ($imageext == "psd") {
				$data['number_pages'] = '1';
			    } else {
				$data['number_pages'] = $im->getNumberImages();
			    }
			    for ($i = 0; $i < $data['number_pages']; $i++) {
				try {
				    $data['image'][$i] = $this->convert_to_png($fileName, $i);
				} catch (Exception $e) {
				    log_message('error', "There was an error converting pdf page to thumnail: " . $e);
				}
			    }

			    $hddata = array(
				'user_id' => $user_id,
				'user_session_id' => '0',
				'image_hd' => $fileName
			    );
			    $id = $this->Designnbuy_common_webservice_model->saveUserImageHd($hddata, $imageId);

			    foreach ($data['image'] as $image) {
				$generatedimage = array(
				    'user_id' => $user_id,
				    'user_session_id' => '0',
				    'image' => $image
				);
				$generatedimageid = $this->Designnbuy_common_webservice_model->saveUserImage($generatedimage, $imageId);
				$this->Designnbuy_common_webservice_model->saveUserImageHdImage($generatedimageid, $id);
				$response['images'][] = array(
				    'id' => $generatedimageid,
				    'image_name' => $image,
				    'vectorname' => 'true'
				);
			    }
			} else {
			    $data = array(
				'user_id' => $user_id,
				'user_session_id' => '0',
				'image' => $fileName
			    );
			    $id = $this->Designnbuy_common_webservice_model->saveUserImage($data, $imageId);
			    $response['images'][0] = array(
				'id' => $id,
				'image_name' => $fileName
			    );
			}
		    } else {

			$data = array(
			    'user_id' => $user_id,
			    'user_session_id' => '0',
			    'image' => $fileName
			);
			$id = $this->Designnbuy_common_webservice_model->saveUserImage($data, $imageId);
			$response['images'][0] = array(
			    'id' => $id,
			    'image_name' => $fileName
			);
		    }
		}
		//}

		$response['jsonrpc'] = 2.0;
		$response['status'] = 'success';
		$response['OK'] = 1;
		die(json_encode($response));
	    }
	} else {
	    if ($_REQUEST['isFront'] == 1) {
		$response = array();
		$isHD = '';
		$imageId = '';
		$images = $_REQUEST['images'];
		if (isset($_REQUEST['isHD'])) {
		    $isHD = $_REQUEST['isHD'];
		}
		if (isset($_REQUEST['customer_id'])) {
		    $user_id = $_REQUEST['customer_id'];
		} else {
		    $user_id = 0;
		}
		$id = '';
		$extensions = array('psd', 'pdf', 'ai', 'cdr', 'eps', 'tif', 'tiff', 'ps');
		/* if ($_REQUEST["toolType"] == 'producttool') {
		  // $helper = Mage::helper('design');
		  } else {
		  // $helper = Mage::helper('web2print');
		  } */
		foreach ($images as $image) {
		    $imageext = pathinfo($image, PATHINFO_EXTENSION);
		    if (PACKAGE_TYPE == 'PRO') {
			if (in_array($imageext, $extensions)) {
			    $data = array(
				'user_id' => $user_id,
				'user_session_id' => '0',
				'image_hd' => $image
			    );
			    $id = $this->Designnbuy_common_webservice_model->saveUserImageHd($data, $imageId);
			} else {
			    $data = array(
				'user_id' => $user_id,
				'user_session_id' => '0',
				'image' => $image
			    );
			    $id = $this->Designnbuy_common_webservice_model->saveUserImage($data, $imageId);
			}
		    } else {
			$data = array(
			    'user_id' => $user_id,
			    'user_session_id' => '0',
			    'image' => $image
			);
			$id = $this->Designnbuy_common_webservice_model->saveUserImage($data, $imageId);
		    }
		    $response['images'][] = array(
			'id' => $id,
			'image_name' => $image
		    );
		}
	    }

	    $response['jsonrpc'] = 2.0;
	    $response['status'] = 'success';
	    $response['OK'] = 1;
	    die(json_encode($response));
	}
    }

    protected function convert_to_png($file_name, $page_number) {
	$targetDir = TOOL_IMG_PATH . DIRECTORY_SEPARATOR . 'uploadedImage' . DIRECTORY_SEPARATOR;
	$uploadDir = 'assets/images/uploadedImage/';
	try {
	    $im = new Imagick($targetDir . $file_name . '[' . $page_number . ']');
	    $fileRelativeLocation = preg_replace("/\\.[^.\\s]{3,4}$/", "", $file_name) . '_' . $page_number . '.png';
	    $fileHandle = getcwd() . '/' . $uploadDir . $fileRelativeLocation;
	    $data = $im->identifyimage();
	    $height = ($data['geometry']['height'] * 300) / $data['resolution']['y'];
	    $width = ($data['geometry']['width'] * 300) / $data['resolution']['x'];
	    $transparent = new ImagickPixel('#000000');
	    $im->paintTransparentImage($transparent, 0, 10);
	    $im->resizeImage($width, $height, Imagick::FILTER_LANCZOS, 1);
	    $im->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
	    $im->setImageResolution(300, 300);
	    $im->setImageFormat("png");
	    $im->writeImage($fileHandle);
	    $im->clear();
	    $im->destroy();
	} catch (Exception $e) {

	    log_message('error', "There was an error using Imagick library on given path: " . $e);
	}
	return $fileRelativeLocation;
    }

    public function phpqrcode() {
	if (PACKAGE_TYPE == 'PRO') {
	    $directory = TOOL_IMG_PATH . DIRECTORY_SEPARATOR . 'uploadedImage' . DIRECTORY_SEPARATOR;
	    // echo "<h1>PHP QR Code</h1><hr/>";
	    session_start();
	    //set it to writable location, a place for temp generated PNG files
	    $PNG_TEMP_DIR = TOOL_IMG_PATH . DIRECTORY_SEPARATOR . 'qrcode' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
	    //html PNG location prefix
	    $PNG_WEB_DIR = 'temp/';


	    include $_SERVER['DOCUMENT_ROOT'] . '/designtool/phpqrcode/qrlib.php';

	    //ofcourse we need rights to create temp dir
	    if (!file_exists($PNG_TEMP_DIR))
		mkdir($PNG_TEMP_DIR);
	    $uniqueid = $_REQUEST['uniqueid'];

	    $colorCode = $_REQUEST['color'];
	    //processing form input
	    //remember to sanitize user input in real-life solution !!!
	    $errorCorrectionLevel = 'L';
	    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L', 'M', 'Q', 'H')))
		$errorCorrectionLevel = $_REQUEST['level'];

	    $matrixPointSize = 4;
	    if (isset($_REQUEST['size']))
		$matrixPointSize = min(max((int) $_REQUEST['size'], 1), 10);

	    $backColor = '';
	    if (isset($_REQUEST['data'])) {

		//it's very important!
		if (trim($_REQUEST['data']) == '')
		    die('data cannot be empty! <a href="?">back</a>');

		// user data
		$filename = 'QRcode' . date("YmdHis") . '.svg';
		// $directory = $webpath.'/qrcode/';
		// $directory = $webpath;
		if (!file_exists($directory)) {
		    mkdir($directory, 0777);
		}
		if (is_dir($directory)) {
		    QRcode::svg($_REQUEST['data'], $directory . '/' . $filename, "L", 4, 0, false, $backColor, $colorCode);
		    //$customer_id = $_SESSION['customer_id'];
		    $customer_id = $this->input->post('customer_id');
		    $imageId = '';
		    if (isset($customer_id) && $customer_id != '') {
			$data = array(
			    'user_id' => $customer_id,
			    'user_session_id' => '0',
			    'image' => $filename
			);
			$id = $this->Designnbuy_common_webservice_model->saveUserImage($data, $imageId);
		    }
		    $postdata['id'] = $id;
		    $postdata['imageName'] = $filename;
		    $postdata['response'] = 'true';
		    die(json_encode($postdata));
		}
	    } else {
		echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';
		QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);
	    }
	} else {
	    $postdata['response'] = 'false';
	    die(json_encode($postdata));
	}
    }

    public function getUserImages() {
		$param = $this->input->post();
		$imageUrl = $this->pc_rootURL . 'designnbuy/assets/images/uploadedImage/';
		$imageDir = TOOL_IMG_PATH . DIRECTORY_SEPARATOR . 'uploadedImage' . DIRECTORY_SEPARATOR;
		$returnlist = array();
		if ($param['customer_id'] != '' && $param['user'] != 'admin') {
			$customer_id = $param['customer_id'];
			$collection = $this->Designnbuy_common_webservice_model->getCustomerInsertUploadImages($customer_id);
			foreach ($collection as $col) {
				if (file_exists($imageDir . $col['image'])) {
					$vectorname = '';
					$checkImageHd = $this->Designnbuy_common_webservice_model->checkImageHd($col['userimage_id']);
					if (!empty($checkImageHd)) {
						$vectorname = 'true';
					}
					$returnlist[$col['userimage_id']] = array(
						'id' => $col['userimage_id'],
						'imageUrl' => $imageUrl . $col['image'],
						'vectorname' => $vectorname
					);
				}
			}
		}else if(isset($param['user']) && $param['user'] == 'admin'){
			$files = array();
			$files = preg_grep('~^admin_.*\.*.$~', scandir($imageDir));
			foreach($files as $key => $file){
				$returnlist[$key]['id'] = $key;
				$returnlist[$key]['imageUrl'] = $imageUrl.$file;
				$returnlist[$key]['vectorname'] = '';
			}
		}
		echo json_encode($returnlist);
		die;
    }

    public function deleteUserImage() {
		$param = $this->input->post();
		if(isset($param['userImageId']) && $param['userImageId'] != ''){
			$result = $this->Designnbuy_common_webservice_model->deleteUserImage($param['userImageId']);
			if ($result > 0) {
				echo "true";
			} else {
				echo "false";
			}
		}else if(isset($param['userImageName'])){
			$imagepath = TOOL_IMG_PATH . '/uploadedImage/';
			if (file_exists($imagepath . $param['userImageName'])) {
				unlink($imagepath . $param['userImageName']);
			}
			echo "true";
		}
		exit;
    }

    /*
     *  My Design Listing
     * 
     */

    public function mydesign() {
	$param = $this->input->get();
	$data['designs'] = $this->Designnbuy_common_webservice_model->getMydesigns($param['customer_id']);
	$data['plateform'] = $param['plateform'];
	$data['siteBaseUrl'] = unserialize(base64_decode($param['siteBaseUrl']));
	$param['language_id'] = $this->getLanguageIdBasedOnConnector($param['language_id']);
	$languagedata = $this->Designnbuy_common_webservice_model->getLanguageData($param['language_id']);
	$data['language_id'] = $param['language_id'];
	$data['language'] = $languagedata['others'];
	$data['mydesign_plateform_path'] = $this->plateform_solution['mydesign'];
	$this->load->view('pcmedia/mydesign', $data);
    }

    public function deleteMydesign($my_design_id) {
	$result = $this->Designnbuy_common_webservice_model->deleteMydesign($my_design_id);
	if ($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }

    /*
     *  My Design Listing
     * 
     */

    public function mymedia() {
		$param = $this->input->get();
		$data['media'] = $this->Designnbuy_common_webservice_model->getMyMedia($param['customer_id']);
		$data['user_id'] = $param['customer_id'];
		$data['plateform'] = $param['plateform'];
		$data['siteBaseUrl'] = unserialize(base64_decode($param['siteBaseUrl']));
		$param['language_id'] = $this->getLanguageIdBasedOnConnector($param['language_id']);
		$languagedata = $this->Designnbuy_common_webservice_model->getLanguageData($param['language_id']);
		$data['language_id'] = $param['language_id'];
		$data['language'] = $languagedata['others'];
		$this->load->view('pcmedia/mymedia', $data);
    }

    public function deleteMyMedia($media_id) {
	$result = $this->Designnbuy_common_webservice_model->deleteMyMedia($media_id);
	if ($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }

    public function deleteHdMedia($hd_media_id) {
	$result = $this->Designnbuy_common_webservice_model->deleteHdMedia($hd_media_id);
	if ($result > 0) {
	    echo "true";
	} else {
	    echo "false";
	}
	exit;
    }

    public function uploadHd() {
	$param = $this->input->post();
	$languagedata = $this->Designnbuy_common_webservice_model->getLanguageData($param['language_id']);
	$file_element_name = 'image_hd';
	$config['upload_path'] = TOOL_IMG_PATH . DIRECTORY_SEPARATOR . 'uploadedImage' . DIRECTORY_SEPARATOR;
	$config['allowed_types'] = 'ai|eps|psd|pdf|cdr|ps|tiff|tif';
	$config['max_size'] = 1024 * 20;
	$config['encrypt_name'] = TRUE;

	$this->load->library('upload', $config);
	if (!$this->upload->do_upload($file_element_name)) {
	    $html['result'] = '<p class="hd_error_message" style="color:red;">' . $this->upload->display_errors('', '') . '</p>';
	} else {
	    $data = $this->upload->data();
	    $imgdata = array(
		'user_id' => $param['user_id'],
		'user_session_id' => '0',
		'image_hd' => $data['file_name']
	    );
	    $imageId = $param['userimage_id'];
	    $file_id = $this->Designnbuy_common_webservice_model->saveUserImageHdSingle($imgdata, $imageId);
	    if ($file_id) {
		$longString = $data["file_name"];
		$separator = "/...../";
		$separatorlength = strlen($separator);
		$maxlength = 20 - $separatorlength;
		$start = $maxlength / 2;
		$trunc = strlen($longString) - $maxlength;
		$truncimagename = substr_replace($longString, $separator, $start, $trunc);
		$imagepath = $this->pc_rootURL . 'designnbuy/assets/images/uploadedImage/';
		$ext = pathinfo($data['file_name'], PATHINFO_EXTENSION);
		//echo '<div class="thume_name" id="thume_name-' . $imageId . '"><a target="_blank" href="' . $imagepath . $data["file_name"] . '">' . $truncimagename . '</a><a class="deleteHD" href="' . $this->pc_rootURL . 'designnbuy/pcstudio_media/deleteHdMedia/' . $file_id . '"><img src="' . base_url("assets/pcmedia/images/delete.svg") . '" alt="" /></a></div>';
		$html['result'] = '<div class="upload-textcon"> <a target="_blank" href="' . $imagepath . $data["file_name"] . '">' . $truncimagename . '</a><a class="deleteHD" href="' . get_base_url() . 'designnbuy/pcstudio_media/deleteHdMedia/' . $file_id . '"><img src="' . base_url("assets/pcmedia/images/delete.png") . '" alt="" /></a></div>';
		$html['id'] = $ext;
	    } else {
		unlink($data['full_path']);
		$html['result'] = '<p class="hd_error_message" style="color:red;">'.$languagedata['others']['somethingwentwrong'].'<p>';
	    }
	}
	echo json_encode($html);
	exit;
    }

    public function uploadNewMedia() {
	$param = $this->input->post();
	$user_id = $param['user_id'];
	$languagedata = $this->Designnbuy_common_webservice_model->getLanguageData($param['language_id']);
	$file_element_name = 'new_image';
	$config['upload_path'] = TOOL_IMG_PATH . DIRECTORY_SEPARATOR . 'uploadedImage' . DIRECTORY_SEPARATOR;
	$config['allowed_types'] = '*';
	$config['max_size'] = 1024 * 20;
	$config['encrypt_name'] = TRUE;
	$imageId = '';
	$this->load->library('upload', $config);

	if (!$this->upload->do_upload($file_element_name)) {
	    echo $this->upload->display_errors('', '');
	} else {
	    $mainfile = $_FILES['new_image']['name'];
	    if (PACKAGE_TYPE == 'PRO') {
		$mainextensions = array('psd', 'pdf', 'ai', 'cdr', 'eps', 'tif', 'tiff', 'ps', 'jpg', 'jpeg', 'png', 'gif');
	    } else {
		$mainextensions = array('jpg', 'jpeg', 'png', 'gif');
	    }
	    $mainimageext = pathinfo($mainfile, PATHINFO_EXTENSION);
	    if (in_array(strtolower($mainimageext), $mainextensions)) {
		$data = $this->upload->data();
		if (PACKAGE_TYPE == 'PRO') {
		    $extensions = array('psd', 'pdf', 'ai', 'cdr', 'eps', 'tif', 'tiff', 'ps');
		    $filePath = $config['upload_path'] . $data['file_name'];
		    $fileName = $data['file_name'];
		    $imageext = pathinfo($fileName, PATHINFO_EXTENSION);
		    if (in_array(strtolower($imageext), $extensions)) {
			$im = new Imagick($filePath);
			if ($imageext == "psd") {
			    $data['number_pages'] = '1';
			} else {
			    $data['number_pages'] = $im->getNumberImages();
			}
			for ($i = 0; $i < $data['number_pages']; $i++) {
			    try {
				$data['image'][$i] = $this->convert_to_png($fileName, $i);
			    } catch (Exception $e) {
				$erroconvertingpdf = $languagedata['others']['erroconvertingpdf'];
				log_message('error', $erroconvertingpdf . $e);
			    }
			}

			$hddata = array(
			    'user_id' => $user_id,
			    'user_session_id' => '0',
			    'image_hd' => $fileName
			);

			$id = $this->Designnbuy_common_webservice_model->saveUserImageHd($hddata, $imageId);

			foreach ($data['image'] as $image) {
			    $generatedimage = array(
				'user_id' => $user_id,
				'user_session_id' => '0',
				'image' => $image
			    );
			    $generatedimageid = $this->Designnbuy_common_webservice_model->saveUserImage($generatedimage, $imageId);
			    $this->Designnbuy_common_webservice_model->saveUserImageHdImage($generatedimageid, $id);
			}
		    } else {
			$imgdata = array(
			    'user_id' => $user_id,
			    'user_session_id' => '0',
			    'image' => $data['file_name']
			);
			$file_id = $this->Designnbuy_common_webservice_model->saveUserImage($imgdata, $imageId);
		    }
		} else {
		    $imgdata = array(
			'user_id' => $user_id,
			'user_session_id' => '0',
			'image' => $data['file_name']
		    );
		    $file_id = $this->Designnbuy_common_webservice_model->saveUserImage($imgdata, $imageId);
		}
		echo "true";
	    } else {
		echo $languagedata['others']['filetypenotallowed'];
	    }
	}
	exit;
    }

    /**
     *  Pretemplate
     */
    public function savePreTemplate() {
	$response = array();
	if (PACKAGE_TYPE == 'PRO') {
	    if ($this->input->post('currentTime')) {
		$currentTime = $this->input->post('currentTime');
	    } else {
		$currentTime = 0;
	    }

	    if ($this->input->post('sideNameAry')) {
		$sideNameAry = $this->input->post('sideNameAry');
	    } else {
		$sideNameAry = array();
	    }
	    $sideNameAry = explode(",", $sideNameAry);

	    $imageDir = TOOL_IMG_PATH . '/pretemplate/' . $currentTime . '/';
	    if (!is_dir($imageDir)) {
		mkdir($imageDir, 0777);
	    }

	    $static_string = '<svg xmlns="http://www.w3.org/2000/svg"></svg>';
	    $tempDir = TOOL_IMG_PATH . '/cartimages/temp-' . $currentTime . '/';
	    $this->removeTempImageDir($tempDir);
	    $data = array();

	    if ($this->input->post('addtocartparam')) {
		$addtocartparam = json_decode(html_entity_decode($this->input->post('addtocartparam')), true);
	    } else {
		$addtocartparam = 0;
	    }

	    $product_id = $addtocartparam['productID'];
	    $option = array();
	    foreach ($addtocartparam as $id => $value) {
		if ($id != 'productID') {
		    $option += array(
			$id => $value
		    );
		}
	    }

	    if ($this->input->post('no_of_side')) {
		$no_of_sides = $this->input->post('no_of_side');
	    } else {
		$no_of_sides = 0;
	    }

	    if ($this->input->post('color_id')) {
		$data['color_id'] = $this->input->post('color_id');
	    } else {
		$data['color_id'] = 0;
		//$data['color_id'] = '';
	    }

	    if ($this->input->post('size_id') && $this->input->post('size_id')!= 'undefined') {
		$data['size_id'] = $this->input->post('size_id');
	    } else {
		$data['size_id'] = 0;
		//$data['size_id'] = '';
	    }

	    /* if ($this->input->post('printingMethod')) {
	      $printingMethod = json_decode(html_entity_decode($this->input->post('printingMethod')), true);
	      } else {
	      $printingMethod = '';
	      } */

	    $data['designed_id'] = $currentTime;
	    $data['product_id'] = $product_id;
	    $data['product_options_id'] = json_encode($option);
	    $data['printing_method'] = json_encode($printingMethod);
	    $data['no_of_sides'] = $no_of_sides;

	    $data['side1_svg'] = $sideNameAry[0] . '.svg';
	    $data['side2_svg'] = $sideNameAry[1] . '.svg';
	    $data['side3_svg'] = $sideNameAry[2] . '.svg';
	    $data['side4_svg'] = $sideNameAry[3] . '.svg';
	    $data['side5_svg'] = $sideNameAry[4] . '.svg';
	    $data['side6_svg'] = $sideNameAry[5] . '.svg';
	    $data['side7_svg'] = $sideNameAry[6] . '.svg';
	    $data['side8_svg'] = $sideNameAry[7] . '.svg';

	    $data['side1_png'] = $sideNameAry[0] . '.png';
	    $data['side2_png'] = $sideNameAry[1] . '.png';
	    $data['side3_png'] = $sideNameAry[2] . '.png';
	    $data['side4_png'] = $sideNameAry[3] . '.png';
	    $data['side5_png'] = $sideNameAry[4] . '.png';
	    $data['side6_png'] = $sideNameAry[5] . '.png';
	    $data['side7_png'] = $sideNameAry[6] . '.png';
	    $data['side8_png'] = $sideNameAry[7] . '.png';

	    /* $side1_otherdata = array(
	      'colors' => $printingMethod['sideWiseColorInfo']['Side1'],
	      'fonts' => $printingMethod['sideWiseFontIDs']['Side1'],
	      'images' => $printingMethod['sideWiseImgIDs']['Side1']
	      );

	      $side2_otherdata = array(
	      'colors' => $printingMethod['sideWiseColorInfo']['Side2'],
	      'fonts' => $printingMethod['sideWiseFontIDs']['Side2'],
	      'images' => $printingMethod['sideWiseImgIDs']['Side2']
	      );

	      $side3_otherdata = array(
	      'colors' => $printingMethod['sideWiseColorInfo']['Side3'],
	      'fonts' => $printingMethod['sideWiseFontIDs']['Side3'],
	      'images' => $printingMethod['sideWiseImgIDs']['Side3']
	      );

	      $side4_otherdata = array(
	      'colors' => $printingMethod['sideWiseColorInfo']['Side4'],
	      'fonts' => $printingMethod['sideWiseFontIDs']['Side4'],
	      'images' => $printingMethod['sideWiseImgIDs']['Side4']
	      );

	      $side5_otherdata = array(
	      'colors' => $printingMethod['sideWiseColorInfo']['Side5'],
	      'fonts' => $printingMethod['sideWiseFontIDs']['Side5'],
	      'images' => $printingMethod['sideWiseImgIDs']['Side5']
	      );

	      $side6_otherdata = array(
	      'colors' => $printingMethod['sideWiseColorInfo']['Side6'],
	      'fonts' => $printingMethod['sideWiseFontIDs']['Side6'],
	      'images' => $printingMethod['sideWiseImgIDs']['Side6']
	      );

	      $side7_otherdata = array(
	      'colors' => $printingMethod['sideWiseColorInfo']['Side7'],
	      'fonts' => $printingMethod['sideWiseFontIDs']['Side7'],
	      'images' => $printingMethod['sideWiseImgIDs']['Side7']
	      );

	      $side8_otherdata = array(
	      'colors' => $printingMethod['sideWiseColorInfo']['Side8'],
	      'fonts' => $printingMethod['sideWiseFontIDs']['Side8'],
	      'images' => $printingMethod['sideWiseImgIDs']['Side8']
	      );
	      $data['side1_otherdata'] = json_encode($side1_otherdata);
	      $data['side2_otherdata'] = json_encode($side2_otherdata);
	      $data['side3_otherdata'] = json_encode($side3_otherdata);
	      $data['side4_otherdata'] = json_encode($side4_otherdata);
	      $data['side5_otherdata'] = json_encode($side5_otherdata);
	      $data['side6_otherdata'] = json_encode($side6_otherdata);
	      $data['side7_otherdata'] = json_encode($side7_otherdata);
	      $data['side8_otherdata'] = json_encode($side8_otherdata); */

	    if ($this->input->post('png_data1')) {
		$png_data1 = $this->input->post('png_data1');
	    } else {
		$png_data1 = 'undefined';
	    }
	    $pngimageDir = $imageDir . $sideNameAry[0] . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data1);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimageDir, $imgdata);

	    if ($this->input->post('png_data2')) {
		$png_data2 = $this->input->post('png_data2');
	    } else {
		$png_data2 = 0;
	    }
	    $pngimageDir = $imageDir . $sideNameAry[1] . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data2);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimageDir, $imgdata);

	    if ($this->input->post('png_data3')) {
		$png_data3 = $this->input->post('png_data3');
	    } else {
		$png_data3 = 0;
	    }
	    $pngimageDir = $imageDir . $sideNameAry[2] . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data3);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimageDir, $imgdata);

	    if ($this->input->post('png_data4')) {
		$png_data4 = $this->input->post('png_data4');
	    } else {
		$png_data4 = 0;
	    }
	    $pngimageDir = $imageDir . $sideNameAry[3] . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data4);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimageDir, $imgdata);

	    if ($this->input->post('png_data5')) {
		$png_data5 = $this->input->post('png_data5');
	    } else {
		$png_data5 = 0;
	    }
	    $pngimageDir = $imageDir . $sideNameAry[4] . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data5);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimageDir, $imgdata);

	    if ($this->input->post('png_data6')) {
		$png_data6 = $this->input->post('png_data6');
	    } else {
		$png_data6 = 0;
	    }
	    $pngimageDir = $imageDir . $sideNameAry[5] . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data6);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimageDir, $imgdata);

	    if ($this->input->post('png_data7')) {
		$png_data7 = $this->input->post('png_data7');
	    } else {
		$png_data7 = 0;
	    }
	    $pngimageDir = $imageDir . $sideNameAry[6] . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data7);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimageDir, $imgdata);

	    if ($this->input->post('png_data8')) {
		$png_data8 = $this->input->post('png_data8');
	    } else {
		$png_data8 = 0;
	    }
	    $pngimageDir = $imageDir . $sideNameAry[7] . '.png';

	    $img = str_replace('data:image/png;base64,', '', $png_data8);
	    $img = str_replace(' ', '+', $img);
	    $imgdata = base64_decode($img);
	    file_put_contents($pngimageDir, $imgdata);

	    if ($this->input->post('svg_1')) {
		$ret_string = $this->input->post('svg_1');
	    } else {
		$ret_string = "undefined";
	    }
	    if ($ret_string == "undefined" || trim($ret_string) == "") {
		$ret_string = $static_string;
	    }
	    file_put_contents($imageDir . $data['side1_svg'], $ret_string);

	    if ($this->input->post('svg_2')) {
		$ret_string = $this->input->post('svg_2');
	    } else {
		$ret_string = "undefined";
	    }
	    if ($ret_string == "undefined" || trim($ret_string) == "") {
		$ret_string = $static_string;
	    }
	    file_put_contents($imageDir . $data['side2_svg'], $ret_string);

	    if ($this->input->post('svg_3')) {
		$ret_string = $this->input->post('svg_3');
	    } else {
		$ret_string = "undefined";
	    }
	    if ($ret_string == "undefined" || trim($ret_string) == "") {
		$ret_string = $static_string;
	    }
	    file_put_contents($imageDir . $data['side3_svg'], $ret_string);

	    if ($this->input->post('svg_4')) {
		$ret_string = $this->input->post('svg_4');
	    } else {
		$ret_string = "undefined";
	    }
	    if ($ret_string == "undefined" || trim($ret_string) == "") {
		$ret_string = $static_string;
	    }
	    file_put_contents($imageDir . $data['side4_svg'], $ret_string);

	    if ($this->input->post('svg_5')) {
		$ret_string = $this->input->post('svg_5');
	    } else {
		$ret_string = "undefined";
	    }
	    if ($ret_string == "undefined" || trim($ret_string) == "") {
		$ret_string = $static_string;
	    }
	    file_put_contents($imageDir . $data['side5_svg'], $ret_string);

	    if ($this->input->post('svg_6')) {
		$ret_string = $this->input->post('svg_6');
	    } else {
		$ret_string = "undefined";
	    }
	    if ($ret_string == "undefined" || trim($ret_string) == "") {
		$ret_string = $static_string;
	    }
	    file_put_contents($imageDir . $data['side6_svg'], $ret_string);

	    if ($this->input->post('svg_7')) {
		$ret_string = $this->input->post('svg_7');
	    } else {
		$ret_string = "undefined";
	    }
	    if ($ret_string == "undefined" || trim($ret_string) == "") {
		$ret_string = $static_string;
	    }
	    file_put_contents($imageDir . $data['side7_svg'], $ret_string);

	    if ($this->input->post('svg_8')) {
		$ret_string = $this->input->post('svg_8');
	    } else {
		$ret_string = "undefined";
	    }
	    if ($ret_string == "undefined" || trim($ret_string) == "") {
		$ret_string = $static_string;
	    }
	    file_put_contents($imageDir . $data['side8_svg'], $ret_string);

	    if ($this->input->post('pretemplate_id') && $this->input->post('pretemplate_id') != '') {
		$id = $this->input->post('pretemplate_id');
		$this->Designnbuy_common_webservice_model->updatePretemplate($id, $data);
	    } else {
		$id = $this->Designnbuy_common_webservice_model->addPretemplate($data);
	    }

	    $response['response'] = 'true';
	    $response['pretemplate_id'] = $id;
	    die(json_encode($response));
	} else {
	    $response['response'] = 'false';
	    die(json_encode($response));
	}
    }

    public function downloadImage() {
	if (PACKAGE_TYPE == 'PRO') {
	    if ($this->input->post('url')) {
		$url = $this->input->post('url');
	    } else {
		$url = 0;
	    }
	    $url = urldecode($url);
	    $url = str_replace(' ', '+', $url);
	    $imageData = explode('.', $url);
	    $dotCount = count($imageData);
	    $image = uniqid() . '.' . $imageData[$dotCount - 1];
	    $imageName = 'uploadedImage' . DIRECTORY_SEPARATOR . $image;
	    $saveto = TOOL_IMG_PATH . DIRECTORY_SEPARATOR;
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	    $raw = curl_exec($ch);
	    curl_close($ch);
	    if (file_exists($saveto . $imageName)) {
		unlink($saveto . $imageName);
	    }
	    $fp = fopen($saveto . $imageName, 'w');
	    fwrite($fp, $raw);
	    fclose($fp);
	    $imageId = '';
	    if ($this->input->post('customer_id')) {
		$user_id = $this->input->post('customer_id');
	    } else {
		$user_id = 0;
	    }
	    $data = array(
		'user_id' => $user_id,
		'user_session_id' => '0',
		'image' => $image
	    );
	    $id = $this->Designnbuy_common_webservice_model->saveUserImage($data, $imageId);
	    //$response['images'][0] = array(
	    //    'id' => $id,
	    //    'image_name' => $imageName
	    //);
	    $response['id'] = $id;
	    $response['imageName'] = $imageName;
	    die(json_encode($response));
	} else {
	    $response['id'] = '';
	    $response['imageName'] = '';
	    die(json_encode($postdata));
	}
    }
    
    public function getLanguageIdBasedOnConnector($connector = 'en') {
	$language_id = $this->Designnbuy_common_webservice_model->getLanguageIdBasedOnConnector($connector);
	return $language_id;
    }

//ADDED BY SOMIN
 //FOR 3D PRODUCT DATA
  public function generateRootPngUrl() {
	if ($this->input->post('current_time')) {
	    $cur_time = $this->input->post('current_time');
	} else {
	    $cur_time = $current_time;
	}
	if ($this->input->post('side')) {
	    $currentSide = $this->input->post('side');
	} else {
	    $currentSide = $side;
	}

	$pricingData = $this->input->post('pricingData');
	
	$imageDir = TOOL_IMG_PATH . '/prev3dimages/';
	$static_string = '<svg xmlns="http://www.w3.org/2000/svg"></svg>';
	$ret_string_font = '';
	$outputPath = TOOL_IMG_PATH . '/prev3dimages/temp-' . $cur_time . '/';
    $mapImageUrl = $this->input->post('map_image');
	
	if (!is_dir($outputPath)) {
	    mkdir($outputPath, 0777);
	}
	if ($this->input->post('svg')) {
	    $ret_string = $this->input->post('svg');
	} else {
	    $ret_string = "undefined";
	}
	$ret_string = str_replace('<?xml version="1.0" encoding="UTF-8"?>', " ", $ret_string);
	if ($ret_string == "undefined" || trim($ret_string) == "") {
	    $ret_string = $static_string;
	}

    $svgFileName = $this->removeRootPaths($ret_string_font . $ret_string, $cur_time, $currentSide, $this->input->post('image_type'));
	$designImageName = $this->generateRootPNG($outputPath . $svgFileName, $cur_time, $this->input->post('image_type'));
	$pricingData = json_decode($pricingData, true);
	$productId = $this->input->post('product_id');
	echo $this->generate3DPreviewImage($productId, $outputPath, $designImageName, $mapImageUrl, $pricingData);
	die;

    }

  public function removeRootPaths($svgContent, $cur_time, $side) {
	$image_type = 'design_image';
	if ($svgContent != '') {
	    if ($this->input->post('image_type')) {
		$imageType = $this->input->post('image_type');
	    } else {
		$imageType = $image_type;
	    }
	    $mediaPath = TOOL_IMG_PATH . '/';
	    $ipadUploadPath = realpath('.') . '/prev3dimages/';
	    $vectorImagePath = TOOL_IMG_PATH . '/prev3dimages/';
	    $outputPath = TOOL_IMG_PATH . '/prev3dimages/temp-' . $cur_time . '/';
	    $doc = new DOMDocument();

	    //$dom->preserveWhiteSpace = False;
	    $doc->loadXML($svgContent);
	    if ($imageType == 'product_image') {
		$suffix = 'product';
	    } else if ($imageType == 'design_image') {
		$suffix = 'design';
	    }
	    $svg = $side . '_' . $cur_time . '_' . $suffix . '.svg';
	    foreach ($doc->getElementsByTagNameNS('http://www.w3.org/2000/svg', 'svg') as $element) {

		foreach ($element->getElementsByTagName("*") as $tags) {
		    if ($tags->localName == 'image' && $tags->getAttribute('xlink:href') != '') {
			$imageUrl = $tags->getAttribute('xlink:href');
			$isAdmin = $tags->getAttribute('isAdminUploaded');
			if($isAdmin == "true"){
				$tags->setAttribute("svgtype", 'dragImage');
			    $tags->setAttribute("display", 'none');
			    $tags->parentNode->setAttribute("display", "none");
			    $children = $tags->parentNode->childNodes;
			    foreach ($children as $child) {
				$child->setAttribute("display", 'none');
			    }
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
			    copy($imageUrl, $outputPath . $name);
			}
		    }
		}
	    }
	    $doc->save($outputPath . $svg, $cur_time);
	    return $svg;
	}
    }

  public function generateRootPNG($svg, $cur_time) {
	$image_type = 'design_image';
	if (file_exists($svg)) {
	    if ($this->input->post('image_type')) {
		$imageType = $this->input->post('image_type');
	    } else {
		$imageType = 0;
	    }
	    $outputImagePath = TOOL_IMG_PATH . '/prev3dimages/temp-' . $cur_time . '/';
	    //   $imageUrl = base_url('/assets/images') . '/cartimages/temp-' . $cur_time . '/';
	    $imageUrl = $this->pc_rootURL . 'designnbuy/assets/images/prev3dimages/temp-' . $cur_time . '/';
	    $svgName = pathinfo($svg, PATHINFO_FILENAME);
	    if ($imageType == 'product_image') {
		$suffix = 'product';
	    } else if ($imageType == 'design_image') {
		$suffix = 'design';
	    }
	    $pngFileName = $svgName . '_' . $suffix . '.png';
	    $param = array('filename' => $svg);
	    $this->load->library('Inkscape', $param);

	    // $inkscape = new Inkscape($svg);

	    $this->inkscape->exportAreaSnap();
	    //$inkscape->exportAreaSnap();
	    //better pixel art
	    $this->inkscape->exportTextToPath();
	    //$inkscape->exportTextToPath();
	    // $inkscape->setSize($width=792, $height=408);
	    // $inkscape->setDpi(96);	
	    try {
		$ok = $this->inkscape->export('png', $outputImagePath . $pngFileName);
	    } catch (Exception $exc) {
		echo $exc->getMessage();
		echo $exc->getTraceAsString();
	    }

	    return $pngFileName;
	}
    }

  public function generate3DPreviewImage($productId, $outputPath, $designImageName, $mapImageUrl, $pricingData)
	{
        ini_set("memory_limit", "-1");
     	$pc_product = $this->Designnbuy_common_webservice_model->getProductConfigurationData($productId, $language_id);
      	$pc_3dproduct = $this->Designnbuy_common_webservice_model->getProduct3dData($productId, $language_id);
      	$pc_3dproductconfig = $this->Designnbuy_common_webservice_model->getProduct3dDataconfig($productId, $language_id);

		$isMulticolor = $pc_product['is_multicolor'];
		 if($mapImageUrl == '') {
		 	$mapImageUrl = $this->pc_rootURL . 'designnbuy/uploads/productimage/'.$pc_3dproduct['map_image'];
		 }
         
		$configX = $pc_3dproductconfig['side1_x'];
		$configY = $pc_3dproductconfig['side1_y'];
		$configWidth = $pc_3dproductconfig['side1_width'];
		$configHeight = $pc_3dproductconfig['side1_height'];

        $filename = pathinfo($mapImageUrl, PATHINFO_FILENAME);
        $fileext = pathinfo($mapImageUrl, PATHINFO_EXTENSION);
        $mapimagePath = TOOL_ADMIN_IMG_PATH.'productimage/'.$filename.'.'.$fileext;
		$mapImage = imagecreatefrompng($mapimagePath);
		$mapWidth = imagesx($mapImage);
		$mapHeight = imagesy($mapImage);
		// Calculate multiplier based on the width of map and design tool product width which is fixed as 400
		$multiplier = $mapWidth/400;
		$rectWidth = $configWidth * $multiplier;
		$rectHeight = $configHeight * $multiplier;

		$designImage = imagecreatefrompng($outputPath . $designImageName);
		$width = imagesx($designImage);
		$height = imagesy($designImage);
		imagealphablending($designImage, false);
		imagesavealpha($designImage, true);

		// Resize design to configure area size
		$resizeDesign = imagecreatetruecolor($rectWidth, $rectHeight);
		imagesavealpha($resizeDesign, true);
		imagefill($resizeDesign,0,0,0x7fff0000);
		imagecopyresampled($resizeDesign, $designImage, 0, 0, 0, 0, $rectWidth, $rectHeight, $width, $height);
		imagealphablending($designImage, true);
		imagepng($resizeDesign, $outputPath.'design.png');

		$rectDesign = imagecreatetruecolor($rectWidth, $rectHeight);
		$url = $this->pc_rootURL.$this->plateform_solution['product_path'] . '&pid=' . $productId . '&qty=1&color_id=&size_id=&extraoptions=';
	    $this->curl->create($url);
		$this->curl->option('returntransfer', 1);
		$data = $this->curl->execute();
		$data = json_decode($data, true);
		$allOptions = $data['option'];
      
		 if($isMulticolor != 1){
			$colorId = $pricingData['colorId'];
			if ($allOptions['color']) {
				$allOptions = $allOptions;
				foreach ($allOptions['color'] as $option) {
					if($option['optionID'] == $colorId) {
						$colorLabel = $option['optionName'];
						break;
					}
				}
				$colorName = $colorLabel;
			}
			if(isset($colorName) && $colorName != '') {
				$rgb = $this->hex2rgb($colorName);
				if(!empty($rgb)){
					$background = imagecolorallocate($rectDesign, $rgb[0], $rgb[1], $rgb[2]);
					imagefill($rectDesign, 0, 0, $background);
				}
			}else{
				imagefill($rectDesign,0,0,0x7fff0000);
			}
		}else{
			imagefill($rectDesign,0,0,0x7fff0000);
		}
	
		$rectn = $outputPath.'rect.png';
		imagepng($rectDesign,$rectn);
		// Merge resized design and configured area size rectangle
		imagecopy($rectDesign, $resizeDesign,  0, 0, 0, 0, $rectWidth, $rectHeight);
		$rectName = $outputPath.'rectDesign.png';
		imagepng($rectDesign,$rectName);
		$rectX1 = $configX;
		$rectY1 = $configY;
		$rectX2 = $rectX1 + $rectWidth;
		$rectY2 = $rectY1 + $rectHeight;
		imagealphablending($mapImage, true);
		imagesavealpha($mapImage, true);

		// Merge map image and design image(resized design and configured area size rectangle)
		//imagecopy($mapImage, $rectDesign,  $rectX1 * $multiplier, $rectY1 * $multiplier, 0, 0, $rectWidth, $rectHeight);
		imagecopy($mapImage, $rectDesign,  $rectX1 * $multiplier, $rectY1 * $multiplier, 0, 0, $rectWidth, $rectHeight);
		imagealphablending($mapImage, false);
		imagesavealpha($mapImage, true);
		$imageName = $outputPath.rand().'.png';
		imagepng($mapImage,$imageName);
		imagedestroy($resizeDesign);
		imagedestroy($rectDesign);
		imagedestroy($mapImage);

		//$contents = ob_get_contents(); //Instead, output above is saved to $contents
		//ob_end_clean(); //End the output buffer.
		$dataUri =  $this->base64_encode_image($imageName,'');

		return $dataUri;
	}

      public function base64_encode_image ($filename=string,$filetype=string) {
		if ($filename) {
			$path = $filename;
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
			return $base64;
		}
	}

	function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);

		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		//return implode(",", $rgb); // returns the rgb values separated by commas
		return $rgb; // returns an array with the rgb values
	}
//ADDED BY SOMIN

}