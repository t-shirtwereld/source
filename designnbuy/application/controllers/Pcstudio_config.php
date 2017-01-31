<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pcstudio_config extends PC_Controller {

    private $pc_rootURL;

    public function __construct() {
	parent::__construct();
	//$this->pc_rootURL = get_base_url();
	$postdata = $this->input->post();
	if(!empty($postdata['postData'])) {
	    $this->pc_rootURL = $postdata['postData']['siteBaseUrl'];
	} else {
	    $this->pc_rootURL = $postdata['siteBaseUrl'];
	}
	$this->plateform_solution = plateform_solutions(PLATEFORM);
	$this->load->library('curl');
	$this->load->model('Designnbuy_common_webservice_model');
    }

    public function getGeneralConfigData() {
	$output_data = $this->successStatus();
	$output_data['data']['generalconfig'] = $this->Designnbuy_common_webservice_model->getGeneralConfigData($this->pc_rootURL);
	$this->printResult($output_data);
    }

    public function getSocialMediaData() {
	$output_data = $this->successStatus();
	$output_data['data']['socialmedia'] = $this->Designnbuy_common_webservice_model->getSocialMediaData();
	$this->printResult($output_data);
    }

    public function getConfigureFeatureData() {
	$output_data = $this->successStatus();
	$output_data['data']['configurefeature'] = $this->Designnbuy_common_webservice_model->getConfigureFeatureData();
	$this->printResult($output_data);
    }

    public function getLanguageData() {
	$output_data = $this->successStatus();
	$output_data['data']['languages'] = $this->Designnbuy_common_webservice_model->getLanguageList();
	$this->printResult($output_data);
    }

    public function getClipartCategoryData() {
	$param = $this->input->post();
	$language_id = $param['language_id'];
	$output_data = $this->successStatus();
	$output_data['data']['clipartCategories'] = $this->Designnbuy_common_webservice_model->getClipartCategoryList($language_id);
	$this->printResult($output_data);
    }

    public function getSubClipartCategoryData() {
	$param = $this->input->post();
	$language_id = $param['language_id'];
	$output_data = $this->successStatus();
	$output_data['data']['clipartSubCategories'] = $this->Designnbuy_common_webservice_model->getClipartSubCategoryList($param['cat_id'], $language_id);
	$this->printResult($output_data);
    }

    public function getClipartData() {
	$param = $this->input->post();
	$language_id = $param['language_id'];
	$output_data = $this->successStatus();
	$output_data['data']['cliparts'] = $this->Designnbuy_common_webservice_model->getClipartList($param['cat_id'], $language_id, $this->pc_rootURL);
	$this->printResult($output_data);
    }

    public function getDesignideaData() {
	$param = $this->input->post();
	$language_id = $param['language_id'];
	$output_data = $this->successStatus();
	$output_data['data']['designideas'] = $this->Designnbuy_common_webservice_model->getDesignideaList($param['cat_id'], $language_id, $this->pc_rootURL);
	$this->printResult($output_data);
    }
    
    public function getFontData() {
	$output_data = $this->successStatus();
	$output_data['data']['fonts'] = $this->Designnbuy_common_webservice_model->getFontList();
	$this->printResult($output_data);
    }

    public function getColorCategoryData() {
	$output_data = $this->successStatus();
	$output_data['data']['colorCategories'] = $this->Designnbuy_common_webservice_model->getColorCategoryList();
	$this->printResult($output_data);
    }

    public function getPrintableColorData($color_category_id, $language_id = 1) {
	$output_data = $this->successStatus();
	$output_data['data']['printablecolors'] = $this->Designnbuy_common_webservice_model->getPrintableColorList($color_category_id, $language_id);
	$this->printResult($output_data);
    }

    public function getColorCountersData() {
	$output_data = $this->successStatus();
	$output_data['data']['colorcounters'] = $this->Designnbuy_common_webservice_model->getColorCountersList();
	$this->printResult($output_data);
    }

    public function getArtworkSizesData() {
	$output_data = $this->successStatus();
	$output_data['data']['artworksizes'] = $this->Designnbuy_common_webservice_model->getArtworkSizesList();
	$this->printResult($output_data);
    }

    public function getQuantityRangesData() {
	$output_data = $this->successStatus();
	$output_data['data']['quantityranges'] = $this->Designnbuy_common_webservice_model->getQuantityRangesList();
	$this->printResult($output_data);
    }

    public function getPrintingMethodData($product_id, $language_id = '1') {
	$output_data = $this->successStatus();
	$output_data['data']['printingmethods'] = $this->Designnbuy_common_webservice_model->getPrintingMethodList($product_id, $language_id);
	$this->printResult($output_data);
    }

    public function getLanguageIdBasedOnConnector($connector = 'en') {
	$language_id = $this->Designnbuy_common_webservice_model->getLanguageIdBasedOnConnector($connector);
	return $language_id;
    }
    
    public function getClipartPrice($clipart_ids = array()) {
	$clipart_ids = $this->input->post('ids');
	$clipart_price = $this->Designnbuy_common_webservice_model->getClipartPrice($clipart_ids);
	echo $clipart_price;
	exit;
    }

    public function getProductCategories() {

	//$url = $this->pc_rootURL . 'index.php?route=designnbuy/initdata/getProductCategory';

	$url = $this->pc_rootURL . $this->plateform_solution['product_category_path'];
	$this->curl->create($url);
	$this->curl->option('returntransfer', 1);
	$data = $this->curl->execute();
	$data = json_decode($data, true);

	$information = $this->successStatus();
	$information['data']['productCategories'] = $data;
	$this->printResult($information);
	//return $data;
    }

    public function getProductFromCategory() {
	$param = $this->input->post();

	$url = $this->pc_rootURL . $this->plateform_solution['products_from_category_path'] . '&category_id=' . $param['cat_id'];
	//$url = $this->pc_rootURL . 'index.php?route=designnbuy/initdata/getProductFromCategory&category_id=' . $param['cat_id'];
	$this->curl->create($url);
	$this->curl->option('returntransfer', 1);
	$data = $this->curl->execute();
	
	$data = json_decode($data, true);
	$information = $this->successStatus();
	$information['data']['products'] = $data;
	$this->printResult($information);
    }

    public function getAllInitialData() {
	$information = array();
	$postData = $this->input->post();
   
	$data = $postData['postData'];

	$language_id = $data['language_id'];
	//$language_id = $this->getLanguageIdBasedOnConnector($languageCode);
	$productData = $this->_getProductData($data);
	$settings = $this->_getSettings($data);
	$designideas = '';
	$clipartCategories = $this->Designnbuy_common_webservice_model->getClipartCategoryList($language_id);
	$designideaCategories = $this->Designnbuy_common_webservice_model->getDesignideaCategoryList($language_id);
	$fontList = $this->Designnbuy_common_webservice_model->getFontList();
	$configurefeature = $this->Designnbuy_common_webservice_model->getConfigureFeatureData();

	//$userimages = $this->getUserImages();
	$userimages = '';
	$information = $this->successStatus();
	$information['data']['settings'] = $settings;
	$information['data']['clipartCategories'] = $clipartCategories;
	$information['data']['designideaCategories'] = $designideaCategories;
	$information['data']['productdata'] = $productData;
	//$information['data']['fontlist'] = $fontList;
	$information['data']['configurefeature'] = $configurefeature;
	//$information['data']['userimages'] = $userimages;
	$this->printResult($information);
	//return $information;
    }

    public function _getSettings($postdata) { 
	$information = array();
	//$url = $this->pc_rootURL . 'index.php?route=designnbuy/initdata/getSettings';
	$url = $this->pc_rootURL . $this->plateform_solution['setting_path'];
	$this->curl->create($url);
	$this->curl->option('returntransfer', 1);
	$setting = $this->curl->execute();
	$setting = json_decode($setting, true);

	$productId = $postdata['product_id'];

	if (isset($postdata['user']) && $postdata['user'] == 'admin') {
	    $user = $postdata['user'];
	} else {
	    $user = '';
	}

	if (isset($postdata['user']) && $postdata['user'] == 'admin') {
	    $isFront = 0;
	} else {
	    $isFront = 1;
	}

	$productData = $this->_getProductData($postdata);

	$qty = $postdata['qty'];
	if (isset($postdata['clipart_id']) && $postdata['clipart_id'] != '') {
	    $template_id = $postdata['clipart_id'];
	} else {
	    $template_id = '';
	}
	
	$no_of_side = $productData['noofSides'];
	$Image = $productData['productImages'];
	$colorSizeData = $productData['allColors']['color'];
	$isMultiColor = $productData['multiColor'];

	$socialmedia = $this->Designnbuy_common_webservice_model->getSocialMediaData();

	$generalconfig = $this->Designnbuy_common_webservice_model->getGeneralConfigData();
	$configurefeature = $this->Designnbuy_common_webservice_model->getConfigureFeatureData();
	$flag = false;
    
	if ($isMultiColor == 'yes') {

	    foreach ($colorSizeData as $colorSize) {
	      
		if ($productData['postData']['colorOptionId'] != '') {
		    if ($colorSize['optionID'] == $productData['postData']['colorOptionId']) {
			$flag = true;
			$Image = $colorSize['image'];
		    }
		} else if ($flag == false) {
		    $flag = true;
		    $Image = $colorSize['image'];
		}
	    }
	}

	$productArea = $productData['Area'];
	$webpath = $setting['webpath'];

	$jspath = $this->pc_rootURL . 'designnbuy/assets/js/html5/';
	$colorCollection = array();
	$locale = 'en_US';
	$uniqueid = $_SESSION['uniqueid'] = md5(rand());
	if ($locale == 'de_CH')
	    $lang_id = 'lang=de';
	else if ($locale == 'en_US')
	    $lang_id = 'lang=en';
	else
	    $lang_id = '';

	$sideNameAry = $productData['sideLabels'];
	
	$text_area = '';

	$_design_idea = '';
	$savedPrintingMethod = "";
	$missing_images = 'false';
	if (isset($postdata['design_id']) && $postdata['design_id'] != '') {
	    $design_id = $postdata['design_id'];
	    $_design_idea = $this->Designnbuy_common_webservice_model->getMyDesignByDesignId($design_id);
	    $designimageids = array();
	    for($i =1; $i <= $_design_idea['no_of_sides']; $i++) {
			$sidewiseimagedata = json_decode(html_entity_decode($_design_idea['side'.$i.'_otherdata']), true);

			if(!empty($sidewiseimagedata['images'])) {
				foreach($sidewiseimagedata['images'] as $imageid) {
					if($imageid != 'null')
						$designimageids[] = $imageid;
				}
			}
	    }

	    $image_count = count(array_unique($designimageids));
	    if(!empty($designimageids) && $image_count > 0) {
			$count = $this->Designnbuy_common_webservice_model->checkImageExistInEditDesign($designimageids);
	    }
	    if($count != $image_count) {
			$missing_images = 'true';
	    }
	    $savedPrintingMethod = $_design_idea['printing_method'];
	    $svgDir = TOOL_IMG_PATH . '/saveimg/' . $_design_idea['designed_id'] . '/';
	} else if(isset($postdata['cart_id']) && $postdata['cart_id'] != '' && isset($postdata['cart_design_id']) && $postdata['cart_design_id'] != ''){
	    $_design_idea = $this->Designnbuy_common_webservice_model->getDesignDataByCartDesignId($postdata['cart_design_id']);
	    $pretemplate_id = $_design_idea[$postdata['cart_design_id']];
	    $savedPrintingMethod = $_design_idea['printing_method'];
	    $svgDir = TOOL_IMG_PATH . '/cartimages/' . $_design_idea['designed_id'] . '/';
	} else if (isset($template_id) && $template_id != '') {
	    $_design_idea = $this->Designnbuy_cliparts_model->getClipartRow($template_id);
	    $savedPrintingMethod = '';
	    $svgDir = TOOL_IMG_PATH . '/cliparts/';
	} else {
	    if ($configurefeature['9'] == '1' && PACKAGE_TYPE == 'PRO') {
		if (isset($productData['is_pretemplate']) && $productData['is_pretemplate'] == 'yes') {
		    $_design_idea = $this->Designnbuy_common_webservice_model->getPretemplateByProductId($productId);
		    $pretemplate_id = $_design_idea['pretemplate_id'];
		    $savedPrintingMethod = $_design_idea['printing_method'];
		    $svgDir = TOOL_IMG_PATH . '/pretemplate/' . $_design_idea['designed_id'] . '/';
		}
	    }
	}
	$fontCollection = $this->Designnbuy_common_webservice_model->getFontList();
    
	$fontArray = json_encode($fontCollection);

	for ($i = 1; $i <= $no_of_side; $i++) {
	    $init_data = '';

	    if ($i == 1) {
		$design_area = $productArea['side1Area'];
		$design_area_arr['side1'] = explode(",", $design_area);
		if ($_design_idea) {
		    if (isset($_design_idea['clipart_image'])) {
			$file_name = $_design_idea['clipart_image'];
		    } else {
			$file_name = $_design_idea['side1_svg'];
		    }

		    if ($file_name != '') {
			$file_name_dir = $svgDir . $file_name;
			$init_data = $this->setSvgSize(file_get_contents($file_name_dir), $design_area_arr['side1'][2], $design_area_arr['side1'][3]);
		    }
		}
	    } else if ($i == 2) {
		$design_area = $productArea['side2Area'];
		$design_area_arr['side2'] = explode(",", $design_area);
		if ($_design_idea) {
		    $file_name = $_design_idea['side2_svg'];
		    if ($file_name != '') {
			$file_name_dir = $svgDir . $file_name;
			$init_data = $this->setSvgSize(file_get_contents($file_name_dir), $design_area_arr['side2'][2], $design_area_arr['side2'][3]);
		    }
		}
	    } else if ($i == 3) {
		$design_area = $productArea['side3Area'];
		$design_area_arr['side3'] = explode(",", $design_area);
		if ($_design_idea) {
		    $file_name = $_design_idea['side3_svg'];
		    if ($file_name != '') {
			$file_name_dir = $svgDir . $file_name;
			$init_data = $this->setSvgSize(file_get_contents($file_name_dir), $design_area_arr['side3'][2], $design_area_arr['side3'][3]);
		    }
		}
	    } else if ($i == 4) {
		$design_area = $productArea['side4Area'];
		$design_area_arr['side4'] = explode(",", $design_area);
		if ($_design_idea) {
		    $file_name = $_design_idea['side4_svg'];
		    if ($file_name != '') {
			$file_name_dir = $svgDir . $file_name;
			$init_data = $this->setSvgSize(file_get_contents($file_name_dir), $design_area_arr['side4'][2], $design_area_arr['side4'][3]);
		    }
		}
	    } else if ($i == 5) {
		$design_area = $productArea['side5Area'];
		$design_area_arr['side5'] = explode(",", $design_area);
		if ($_design_idea) {
		    $file_name = $_design_idea['side5_svg'];
		    if ($file_name != '') {
			$file_name_dir = $svgDir . $file_name;
			$init_data = $this->setSvgSize(file_get_contents($file_name_dir), $design_area_arr['side5'][2], $design_area_arr['side5'][3]);
		    }
		}
	    } else if ($i == 6) {
		$design_area = $productArea['side6Area'];
		$design_area_arr['side6'] = explode(",", $design_area);
		if ($_design_idea) {
		    $file_name = $_design_idea['side6_svg'];
		    if ($file_name != '') {
			$file_name_dir = $svgDir . $file_name;
			$init_data = $this->setSvgSize(file_get_contents($file_name_dir), $design_area_arr['side6'][2], $design_area_arr['side6'][3]);
		    }
		}
	    } else if ($i == 7) {
		$design_area = $productArea['side7Area'];
		$design_area_arr['side7'] = explode(",", $design_area);
		if ($_design_idea) {
		    $file_name = $_design_idea['side7_svg'];
		    if ($file_name != '') {
			$file_name_dir = $svgDir . $file_name;
			$init_data = $this->setSvgSize(file_get_contents($file_name_dir), $design_area_arr['side7'][2], $design_area_arr['side7'][3]);
		    }
		}
	    } else if ($i == 8) {
		$design_area = $productArea['side8Area'];
		$design_area_arr['side8'] = explode(",", $design_area);
		if ($_design_idea) {
		    $file_name = $_design_idea['side8_svg'];
		    if ($file_name != '') {
			$file_name_dir = $svgDir . $file_name;
			$init_data = $this->setSvgSize(file_get_contents($file_name_dir), $design_area_arr['side8'][2], $design_area_arr['side8'][3]);
		    }
		}
	    }

	    $init_data_array[$i] = $init_data;
	    // $text_area .= '<textarea style="display:none;" id="textsvg_' . $i . '" name="textsvg_' . $i . '">' . htmlspecialchars($init_data) . '</textarea>';
	}

	$productData = json_encode($productData);

	$information['productId'] = $productId;
	$information['savedPrintingMethod'] = $savedPrintingMethod;
	$information['isFront'] = $isFront;
	$information['user'] = $user;
	if (isset($postdata['preTemplate']) && $postdata['preTemplate'] != '') {
	    $information['preTemplate'] = $postdata['preTemplate'];
	} else {
	    $information['preTemplate'] = '';
	}
	if (isset($pretemplate_id) && $pretemplate_id != '') {
	    $information['pretemplate_id'] = $pretemplate_id;
	} else {
	    $information['pretemplate_id'] = '';
	}
	if ($postdata['cart_id'] && $postdata['cart_id'] != '') {
	    $information['cart_id'] = $postdata['cart_id'];
	} else {
	    $information['cart_id'] = '';
	}
	if ($postdata['cart_design_id'] && $postdata['cart_design_id'] != '') {
	    $information['cart_design_id'] = $postdata['cart_design_id'];
	} else {
	    $information['cart_design_id'] = '';
	} 
	$information['template_id'] = $template_id;
	$information['backend_url'] = $this->pc_rootURL . 'designnbuy/admin/cliparts/saveclipart';
	$information['formKey'] = $setting['formKey'];
	$information['currentStore'] = $locale;
	$information['jspath'] = $jspath;
	$information['cartUrl'] = $setting['cartUrl'];
	$information['facebook'] = $socialmedia['facebook_app_id'];
	$information['flickr'] = $socialmedia['flicker_api_key'];
	$information['instagram'] = $socialmedia['instagram_client_id'];
	$information['imageDPI'] = $generalconfig['image_resolution'];
	$information['basepath'] = $this->pc_rootURL;
	$information['mediapath'] = $this->pc_rootURL . 'designnbuy/assets/images/';
	//$information['cofigcatid'] = Mage::app()->getRequest()->getParam('cat_id');
	$information['confignotfound'] = 'Item Not Found';
	$information['qrCodePath'] = $this->pc_rootURL . 'designnbuy/assets/images/uploadedImage/';
	$information['qrCodeLib'] = $this->pc_rootURL . 'designnbuy/pcstudio_media/phpqrcode';
	$information['deleteQrCodeUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_media/deleteqrcode';
	$information['removeuserImagesUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_media/deleteUserImage';
	$information['categoryUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_config/getProductCategories';
	//$information['relatedSubCategoryUrl'] = $this->pc_rootURL . 'designnbuy/design/index/getrelatedproducttype';
	$information['relatedProductUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_config/getProductFromCategory';	
	$information['relatedClipartPriceUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_config/getClipartPrice';
	$information['relatedClipartUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_config/getClipartData';
	$information['relatedDesignideaUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_config/getDesignideaData';
	//$information['relatedDesignIdeaUrl'] = $this->pc_rootURL . 'designnbuy/design/index/getrelateddesignidea';
	$information['productUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_config/product';
	$information['fontUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_config/getFontData';
	$information['clipartCategoryUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_config/getClipartCategoryData';
	$information['clipartSubCategoryUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_config/getSubClipartCategoryData';
	$information['productPriceUrl'] = $setting['productPriceUrl'];
	$information['crossOriginalUploadUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_media/downloadImage';
	$information['instagramChannelUrl'] = $this->pc_rootURL . 'designnbuy/designtool/instagram-channel.html';
	$information['loginUrl'] = $setting['loginUrl'];
	$information['registrationUrl'] = $setting['registrationUrl'];
	$information['loginCheckUrl'] = $setting['loginCheckUrl'];
	$information['saveBase64Url'] = $this->pc_rootURL . 'designnbuy/pcstudio_media/savebase64onserver';
	$information['savePreTemplate'] = $this->pc_rootURL . 'designnbuy/pcstudio_media/savePreTemplate';
	if ($template_id != '') {
	    $information['exitUrl'] = $this->pc_rootURL . 'designnbuy/admin/cliparts/editclipart/' . $template_id;
	} else {
	    $information['exitUrl'] = $this->pc_rootURL . 'designnbuy/admin/products/configuration/?product_id=' . $productId;
	}
	$information['saveDesignUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_media/savemydesign';
	$information['shareDesignUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_media/sharemydesign';
	$information['generatePreviewPngUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_media/generatePreviewPng';
	$information['generateRootPngUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_media/generateRootPngUrl';//added by somin
	$information['previewPdfUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_media/previewPdf';
	$information['backgroundImageUrl'] = $this->pc_rootURL . 'designnbuy/w2phtml5background/W2phtml5background/background/';
	$information['addToCartUrl'] = $setting['addToCartUrl'];
	$information['userImagesUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio_media/getUserImages';
	$information['welcomeMessageUrl'] = $setting['welcomeMessageUrl'];
	$information['updateTopLinksUrl'] = $setting['updateTopLinksUrl'];
	$information['topCartsUrl'] = $setting['topCartsUrl'];
	$information['FBredirectURI'] = $this->pc_rootURL . 'designnbuy/designtool/FBredirectURI.html';
	$information['uniqueid'] = $uniqueid;
	$information['fontlist'] = $fontArray;
	$information['colorlist'] = $colorCollection;
	$information['no_of_side'] = $no_of_side;
	$information['first_font'] = "";
	$information['text_svg1'] = "";
	$information['text_svg2'] = "";
	$information['text_svg3'] = "";
	$information['text_svg4'] = "";
	$information['text_svg5'] = "";
	$information['text_svg6'] = "";
	$information['text_svg7'] = "";
	$information['text_svg8'] = "";
	$information['side_1'] = "1";
	$information['side_2'] = "0";
	$information['side_3'] = "0";
	$information['side_4'] = "0";
	$information['side_5'] = "0";
	$information['side_6'] = "0";
	$information['side_7'] = "0";
	$information['side_8'] = "0";
	$information['png_data'] = "";
	$information['filearray'] = "";
	//$information['customer_id'] = $customer_id;
	$information['added_images'] = "";
	$information['fonts_used'] = "";
	$information['configtotalprice'] = 'Total Price';
	$information['configselectedsize'] = 'Selected Size';
	$information['configqtymessage'] = 'Please choose appropriate Quantity.';
	$information['clipartsloaded'] = "0";
	$information['designIdeasloaded'] = "0";
	$information['curr_side_id'] = "1";
	$information['datauri'] = "";
	$information['productImageCan'] = "";
	$information['customizationCan'] = "";
	$information['priceInterval'] = "";
	$information['action'] = "";
	$information['shareType'] = "";
	$information['currency_symbol'] = $setting['currency_symbol'];
	$information['sideNameAry'] = $sideNameAry;
	$information['toolType'] = "producttool";
	$information['pickerMode'] = "full";
	$information['printingMode'] = "DTG";
	$information['init_data'] = $init_data_array;
	$information['helpDataUrl'] = $this->pc_rootURL . 'designnbuy/pcstudio/help';
	$information['forgotpassword'] = $setting['forgotpassword'];
	$information['missing_images'] = $missing_images;
    // ashok
    $i = 0;
	if ($isMultiColor == 'yes') {
	    $i = 1;
	    $filter = '';
	} else {
	    $filter = 'url(#colorMat)';
	}

	for ($i  ; $i < $no_of_side; $i++) {
		if ($Image[$i] != "" && $Image[$i] != NULL) {
			do {
				list($imagewidth, $imageheight, $type, $attr) = getimagesize($Image[$i]);
			} while ($imagewidth == '');

			$ratio = $imagewidth / $imageheight;
			if ($imagewidth >= $imageheight) {
				$imagewidth = 400;
				$imageheight = $imagewidth / $ratio;
				if ($imageheight > 485) {
					$imageheight = 485;
					$imagewidth = $ratio * $imageheight;
				}
			} else {
				$imageheight = 485;
				$imagewidth = $ratio * $imageheight;
				if ($imagewidth > 400) {
					$imagewidth = 400;
					$imageheight = $imagewidth / $ratio;
				}
			}
			$display_style = '';

			$information['svgcanvas_images'][$i]['id'] = $i;
			$information['svgcanvas_images'][$i]['filter'] = $filter;
			$information['svgcanvas_images'][$i]['imageurl'] = $Image[$i];
			$information['svgcanvas_images'][$i]['imageheight'] = $imageheight;
			$information['svgcanvas_images'][$i]['imagewidth'] = $imagewidth;
		}
	}

	return $information;
    }

    public function _getProductData($postdata) {	
	$url = $this->pc_rootURL . $this->plateform_solution['product_path'] . '&pid=' . $postdata['product_id'] . '&qty=' . $postdata['qty'] . '&color_id=' . $postdata['color_id'] . '&size_id=' . $postdata['size_id'] . '&extraoptions=' . $postdata['extraoptions'];
		//$url = $this->pc_rootURL . 'index.php?route=designnbuy/initdata/product&pid=' . $postdata['product_id'] . '&qty=' . $postdata['qty'] . '&color_id=' . $postdata['color_id'] . '&size_id=' . $postdata['size_id'] . '&extraoptions=' . $postdata['extraoptions'];
	
    $this->curl->create($url);
	$this->curl->option('returntransfer', 1);
	$data = $this->curl->execute();

	$data = json_decode($data, true);

	$product = $data['product'];
    
	$language_id = $postdata['language_id'];
    
	$pc_product = $this->Designnbuy_common_webservice_model->getProductConfigurationData($product['product_id'], $language_id);

	$pc_3dproduct = $this->Designnbuy_common_webservice_model->getProduct3dData($product['product_id'], $language_id);
    
     // print '<pre>';
     // print_r($pc_product);
     // exit;

	$productData = array();
    
	$productData['productID'] = $product['product_id'];
	$productData['name'] = $product['name'];
	$productData['type'] = $data['productType'];
	$productData['code'] = $product['model'];
	$productData['shortDesc'] = htmlspecialchars_decode($product['description']);
	$productData['longDesc'] = htmlspecialchars_decode($product['description']);
	if (isset($product['main_image']) && $product['main_image'] != '') {
	    $thumbnailImage = $this->pc_rootURL . 'image/' . $product['main_image'];
	} else {
	    $thumbnailImage = '';
	}
      
	$productData['defaultThumb'] = $thumbnailImage;
    $productData['multiColor'] = 'no';
    if($pc_product['is_multicolor'] == '1'){
         $productData['multiColor'] = 'yes';
    }
	
	$productData['baseUnit'] = $pc_product['base_unit'];
	$productData['name_number'] = $pc_product['name_number'];

	$side1Image = '';
	$side2Image = '';
	$side3Image = '';
	$side4Image = '';
	$side5Image = '';
	$side6Image = '';
	$side7Image = '';
	$side8Image = '';
	if (isset($pc_product['side1_product']) && $pc_product['side1_product'] != '') {
	    $side1Image = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side1_product'];
	}
	if (isset($pc_product['side2_product']) && $pc_product['side2_product'] != '') {
	    $side2Image = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side2_product'];
	}
	if (isset($pc_product['side3_product']) && $pc_product['side3_product'] != '') {
	    $side3Image = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side3_product'];
	}
	if (isset($pc_product['side4_product']) && $pc_product['side4_product'] != '') {
	    $side4Image = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side4_product'];
	}
	if (isset($pc_product['side5_product']) && $pc_product['side5_product'] != '') {
	    $side5Image = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side5_product'];
	}
	if (isset($pc_product['side6_product']) && $pc_product['side6_product'] != '') {
	    $side6Image = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side6_product'];
	}
	if (isset($pc_product['side7_product']) && $pc_product['side7_product'] != '') {
	    $side7Image = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side7_product'];
	}
	if (isset($pc_product['side8_product']) && $pc_product['side8_product'] != '') {
	    $side8Image = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side8_product'];
	}
	if (isset($postdata['user']) && $postdata['user'] == 'admin') {
	    $noOfSides = 1;
	} else {
	    $noOfSides = $pc_product['no_of_sides'];
	}
	$productData['noofSides'] = $noOfSides;

	$productImages = array();

	switch ($noOfSides) {
	    case 8:
			$productImages[] = $side1Image;
			$productImages[] = $side2Image;
			$productImages[] = $side3Image;
			$productImages[] = $side4Image;
			$productImages[] = $side5Image;
			$productImages[] = $side6Image;
			$productImages[] = $side7Image;
			$productImages[] = $side8Image;
		break;
	    case 7:
			$productImages[] = $side1Image;
			$productImages[] = $side2Image;
			$productImages[] = $side3Image;
			$productImages[] = $side4Image;
			$productImages[] = $side5Image;
			$productImages[] = $side6Image;
			$productImages[] = $side7Image;
		break;
	    case 6:
			$productImages[] = $side1Image;
			$productImages[] = $side2Image;
			$productImages[] = $side3Image;
			$productImages[] = $side4Image;
			$productImages[] = $side5Image;
			$productImages[] = $side6Image;
		break;
	    case 5:
			$productImages[] = $side1Image;
			$productImages[] = $side2Image;
			$productImages[] = $side3Image;
			$productImages[] = $side4Image;
			$productImages[] = $side5Image;
		break;
	    case 4:
			$productImages[] = $side1Image;
			$productImages[] = $side2Image;
			$productImages[] = $side3Image;
			$productImages[] = $side4Image;
		break;
	    case 3:
			$productImages[] = $side1Image;
			$productImages[] = $side2Image;
			$productImages[] = $side3Image;
		break;
	    case 2:
			$productImages[] = $side1Image;
			$productImages[] = $side2Image;
		break;
	    case 1:
			$productImages[] = $side1Image;
		break;
	    default:
			$productImages[] = $configFrontImage;
		break;
	}
	$productData['productImages'] = $productImages;


 // if($pc_product['is_3d'] == "1"){
	// 	 $pc_3dproduct_mask = $this->Designnbuy_common_webservice_model->getMulticolorProductimagesMask($product['product_id']);
 //        $pc_product = $pc_3dproduct_mask[0];
	//     $maskImageUrls = array();
	// 	 if (isset($pc_product['mask_image']) && $pc_product['mask_image'] != '') {
	// 	     $maskImageUrls[0] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['mask_image'];
	//     } else {
	// 	     $maskImageUrls[0] = null;
	// 	 }
	// 	if (isset($pc_product['mask_image']) && $pc_product['mask_image'] != '') {
	// 	    $maskImageUrls[1] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['mask_image'];
	// 	} else {
	// 	    $maskImageUrls[1] = null;
	// 	}
	// 	if (isset($pc_product['mask_image']) && $pc_product['mask_image'] != '') {
	// 	    $maskImageUrls[2] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['mask_image'];
	// 	} else {
	// 	    $maskImageUrls[2] = null;
	// 	}
	// 	if (isset($pc_product['mask_image']) && $pc_product['mask_image'] != '') {
	// 	    $maskImageUrls[3] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['mask_image'];
	// 	} else {
	// 	    $maskImageUrls[3] = null;
	// 	}
	// 	if (isset($pc_product['mask_image']) && $pc_product['mask_image'] != '') {
	// 	    $maskImageUrls[4] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['mask_image'];
	// 	} else {
	// 	    $maskImageUrls[4] = null;
	// 	}
	// 	if (isset($pc_product['mask_image']) && $pc_product['mask_image'] != '') {
	// 	    $maskImageUrls[5] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['mask_image'];
	// 	} else {
	// 	    $maskImageUrls[5] = null;
	// 	}
	// 	if (isset($pc_product['mask_image']) && $pc_product['mask_image'] != '') {
	// 	    $maskImageUrls[6] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['mask_image'];
	// 	} else {
	// 	    $maskImageUrls[6] = null;
	// 	}
	// 	if (isset($pc_product['mask_image']) && $pc_product['mask_image'] != '') {
	// 	    $maskImageUrls[7] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['mask_image'];
	// 	} else {
	// 	    $maskImageUrls[7] = null;
	// 	}
		
 //   }else{

		$maskImageUrls = array();
		if (isset($pc_product['side1_mask']) && $pc_product['side1_mask'] != '') {
		    $maskImageUrls[0] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side1_mask'];
		} else {
		    $maskImageUrls[0] = null;
		}
		if (isset($pc_product['side2_mask']) && $pc_product['side2_mask'] != '') {
		    $maskImageUrls[1] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side2_mask'];
		} else {
		    $maskImageUrls[1] = null;
		}
		if (isset($pc_product['side3_mask']) && $pc_product['side3_mask'] != '') {
		    $maskImageUrls[2] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side3_mask'];
		} else {
		    $maskImageUrls[2] = null;
		}
		if (isset($pc_product['side4_mask']) && $pc_product['side4_mask'] != '') {
		    $maskImageUrls[3] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side4_mask'];
		} else {
		    $maskImageUrls[3] = null;
		}
		if (isset($pc_product['side5_mask']) && $pc_product['side5_mask'] != '') {
		    $maskImageUrls[4] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side5_mask'];
		} else {
		    $maskImageUrls[4] = null;
		}
		if (isset($pc_product['side6_mask']) && $pc_product['side6_mask'] != '') {
		    $maskImageUrls[5] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side6_mask'];
		} else {
		    $maskImageUrls[5] = null;
		}
		if (isset($pc_product['side7_mask']) && $pc_product['side7_mask'] != '') {
		    $maskImageUrls[6] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side7_mask'];
		} else {
		    $maskImageUrls[6] = null;
		}
		if (isset($pc_product['side8_mask']) && $pc_product['side8_mask'] != '') {
		    $maskImageUrls[7] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side8_mask'];
		} else {
		    $maskImageUrls[7] = null;
		}
		
//  }
    $productData['maskImages'] = $maskImageUrls;

	$overlayImageUrls = array();
	if (isset($pc_product['side1_overlay']) && $pc_product['side1_overlay'] != '') {
	    $overlayImageUrls[0] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side1_overlay'];
	} else {
	    $overlayImageUrls[0] = null;
	}
	if (isset($pc_product['side2_overlay']) && $pc_product['side2_overlay'] != '') {
	    $overlayImageUrls[1] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side2_overlay'];
	} else {
	    $overlayImageUrls[1] = null;
	}
	if (isset($pc_product['side3_overlay']) && $pc_product['side3_overlay'] != '') {
	    $overlayImageUrls[2] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side3_overlay'];
	} else {
	    $overlayImageUrls[2] = null;
	}
	if (isset($pc_product['side4_overlay']) && $pc_product['side4_overlay'] != '') {
	    $overlayImageUrls[3] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side4_overlay'];
	} else {
	    $overlayImageUrls[3] = null;
	}
	if (isset($pc_product['side5_overlay']) && $pc_product['side5_overlay'] != '') {
	    $overlayImageUrls[4] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side5_overlay'];
	} else {
	    $overlayImageUrls[4] = null;
	}
	if (isset($pc_product['side6_overlay']) && $pc_product['side6_overlay'] != '') {
	    $overlayImageUrls[5] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side6_overlay'];
	} else {
	    $overlayImageUrls[5] = null;
	}
	if (isset($pc_product['side7_overlay']) && $pc_product['side7_overlay'] != '') {
	    $overlayImageUrls[6] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side7_overlay'];
	} else {
	    $overlayImageUrls[6] = null;
	}
	if (isset($pc_product['side8_overlay']) && $pc_product['side8_overlay'] != '') {
	    $overlayImageUrls[7] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $pc_product['side8_overlay'];
	} else {
	    $overlayImageUrls[7] = null;
	}
	$productData['overlayImages'] = $overlayImageUrls;

	$productConfigArea = array();
	if (isset($pc_product['no_of_sides']) && $pc_product['no_of_sides'] == '8') {
	    $productConfigArea['side1Area'] = $pc_product['side1_x'] . ',' . $pc_product['side1_y'] . ',' . $pc_product['side1_width'] . ',' . $pc_product['side1_height'] . ',' . $pc_product['side1_output_width'] . ',' . $pc_product['side1_output_height'];
	    $productConfigArea['side2Area'] = $pc_product['side2_x'] . ',' . $pc_product['side2_y'] . ',' . $pc_product['side2_width'] . ',' . $pc_product['side2_height'] . ',' . $pc_product['side2_output_width'] . ',' . $pc_product['side2_output_height'];
	    $productConfigArea['side3Area'] = $pc_product['side3_x'] . ',' . $pc_product['side3_y'] . ',' . $pc_product['side3_width'] . ',' . $pc_product['side3_height'] . ',' . $pc_product['side3_output_width'] . ',' . $pc_product['side3_output_height'];
	    $productConfigArea['side4Area'] = $pc_product['side4_x'] . ',' . $pc_product['side4_y'] . ',' . $pc_product['side4_width'] . ',' . $pc_product['side4_height'] . ',' . $pc_product['side4_output_width'] . ',' . $pc_product['side4_output_height'];
	    $productConfigArea['side5Area'] = $pc_product['side5_x'] . ',' . $pc_product['side5_y'] . ',' . $pc_product['side5_width'] . ',' . $pc_product['side5_height'] . ',' . $pc_product['side5_output_width'] . ',' . $pc_product['side5_output_height'];
	    $productConfigArea['side6Area'] = $pc_product['side6_x'] . ',' . $pc_product['side6_y'] . ',' . $pc_product['side6_width'] . ',' . $pc_product['side6_height'] . ',' . $pc_product['side6_output_width'] . ',' . $pc_product['side6_output_height'];
	    $productConfigArea['side7Area'] = $pc_product['side7_x'] . ',' . $pc_product['side7_y'] . ',' . $pc_product['side7_width'] . ',' . $pc_product['side7_height'] . ',' . $pc_product['side7_output_width'] . ',' . $pc_product['side7_output_height'];
	    $productConfigArea['side8Area'] = $pc_product['side8_x'] . ',' . $pc_product['side8_y'] . ',' . $pc_product['side8_width'] . ',' . $pc_product['side8_height'] . ',' . $pc_product['side8_output_width'] . ',' . $pc_product['side8_output_height'];
	} else if (isset($pc_product['no_of_sides']) && $pc_product['no_of_sides'] == '7') {
	    $productConfigArea['side1Area'] = $pc_product['side1_x'] . ',' . $pc_product['side1_y'] . ',' . $pc_product['side1_width'] . ',' . $pc_product['side1_height'] . ',' . $pc_product['side1_output_width'] . ',' . $pc_product['side1_output_height'];
	    $productConfigArea['side2Area'] = $pc_product['side2_x'] . ',' . $pc_product['side2_y'] . ',' . $pc_product['side2_width'] . ',' . $pc_product['side2_height'] . ',' . $pc_product['side2_output_width'] . ',' . $pc_product['side2_output_height'];
	    $productConfigArea['side3Area'] = $pc_product['side3_x'] . ',' . $pc_product['side3_y'] . ',' . $pc_product['side3_width'] . ',' . $pc_product['side3_height'] . ',' . $pc_product['side3_output_width'] . ',' . $pc_product['side3_output_height'];
	    $productConfigArea['side4Area'] = $pc_product['side4_x'] . ',' . $pc_product['side4_y'] . ',' . $pc_product['side4_width'] . ',' . $pc_product['side4_height'] . ',' . $pc_product['side4_output_width'] . ',' . $pc_product['side4_output_height'];
	    $productConfigArea['side5Area'] = $pc_product['side5_x'] . ',' . $pc_product['side5_y'] . ',' . $pc_product['side5_width'] . ',' . $pc_product['side5_height'] . ',' . $pc_product['side5_output_width'] . ',' . $pc_product['side5_output_height'];
	    $productConfigArea['side6Area'] = $pc_product['side6_x'] . ',' . $pc_product['side6_y'] . ',' . $pc_product['side6_width'] . ',' . $pc_product['side6_height'] . ',' . $pc_product['side6_output_width'] . ',' . $pc_product['side6_output_height'];
	    $productConfigArea['side7Area'] = $pc_product['side7_x'] . ',' . $pc_product['side7_y'] . ',' . $pc_product['side7_width'] . ',' . $pc_product['side7_height'] . ',' . $pc_product['side7_output_width'] . ',' . $pc_product['side7_output_height'];
	} else if (isset($pc_product['no_of_sides']) && $pc_product['no_of_sides'] == '6') {
	    $productConfigArea['side1Area'] = $pc_product['side1_x'] . ',' . $pc_product['side1_y'] . ',' . $pc_product['side1_width'] . ',' . $pc_product['side1_height'] . ',' . $pc_product['side1_output_width'] . ',' . $pc_product['side1_output_height'];
	    $productConfigArea['side2Area'] = $pc_product['side2_x'] . ',' . $pc_product['side2_y'] . ',' . $pc_product['side2_width'] . ',' . $pc_product['side2_height'] . ',' . $pc_product['side2_output_width'] . ',' . $pc_product['side2_output_height'];
	    $productConfigArea['side3Area'] = $pc_product['side3_x'] . ',' . $pc_product['side3_y'] . ',' . $pc_product['side3_width'] . ',' . $pc_product['side3_height'] . ',' . $pc_product['side3_output_width'] . ',' . $pc_product['side3_output_height'];
	    $productConfigArea['side4Area'] = $pc_product['side4_x'] . ',' . $pc_product['side4_y'] . ',' . $pc_product['side4_width'] . ',' . $pc_product['side4_height'] . ',' . $pc_product['side4_output_width'] . ',' . $pc_product['side4_output_height'];
	    $productConfigArea['side5Area'] = $pc_product['side5_x'] . ',' . $pc_product['side5_y'] . ',' . $pc_product['side5_width'] . ',' . $pc_product['side5_height'] . ',' . $pc_product['side5_output_width'] . ',' . $pc_product['side5_output_height'];
	    $productConfigArea['side6Area'] = $pc_product['side6_x'] . ',' . $pc_product['side6_y'] . ',' . $pc_product['side6_width'] . ',' . $pc_product['side6_height'] . ',' . $pc_product['side6_output_width'] . ',' . $pc_product['side6_output_height'];
	} else if (isset($pc_product['no_of_sides']) && $pc_product['no_of_sides'] == '5') {
	    $productConfigArea['side1Area'] = $pc_product['side1_x'] . ',' . $pc_product['side1_y'] . ',' . $pc_product['side1_width'] . ',' . $pc_product['side1_height'] . ',' . $pc_product['side1_output_width'] . ',' . $pc_product['side1_output_height'];
	    $productConfigArea['side2Area'] = $pc_product['side2_x'] . ',' . $pc_product['side2_y'] . ',' . $pc_product['side2_width'] . ',' . $pc_product['side2_height'] . ',' . $pc_product['side2_output_width'] . ',' . $pc_product['side2_output_height'];
	    $productConfigArea['side3Area'] = $pc_product['side3_x'] . ',' . $pc_product['side3_y'] . ',' . $pc_product['side3_width'] . ',' . $pc_product['side3_height'] . ',' . $pc_product['side3_output_width'] . ',' . $pc_product['side3_output_height'];
	    $productConfigArea['side4Area'] = $pc_product['side4_x'] . ',' . $pc_product['side4_y'] . ',' . $pc_product['side4_width'] . ',' . $pc_product['side4_height'] . ',' . $pc_product['side4_output_width'] . ',' . $pc_product['side4_output_height'];
	    $productConfigArea['side5Area'] = $pc_product['side5_x'] . ',' . $pc_product['side5_y'] . ',' . $pc_product['side5_width'] . ',' . $pc_product['side5_height'] . ',' . $pc_product['side5_output_width'] . ',' . $pc_product['side5_output_height'];
	} else if (isset($pc_product['no_of_sides']) && $pc_product['no_of_sides'] == '4') {
	    $productConfigArea['side1Area'] = $pc_product['side1_x'] . ',' . $pc_product['side1_y'] . ',' . $pc_product['side1_width'] . ',' . $pc_product['side1_height'] . ',' . $pc_product['side1_output_width'] . ',' . $pc_product['side1_output_height'];
	    $productConfigArea['side2Area'] = $pc_product['side2_x'] . ',' . $pc_product['side2_y'] . ',' . $pc_product['side2_width'] . ',' . $pc_product['side2_height'] . ',' . $pc_product['side2_output_width'] . ',' . $pc_product['side2_output_height'];
	    $productConfigArea['side3Area'] = $pc_product['side3_x'] . ',' . $pc_product['side3_y'] . ',' . $pc_product['side3_width'] . ',' . $pc_product['side3_height'] . ',' . $pc_product['side3_output_width'] . ',' . $pc_product['side3_output_height'];
	    $productConfigArea['side4Area'] = $pc_product['side4_x'] . ',' . $pc_product['side4_y'] . ',' . $pc_product['side4_width'] . ',' . $pc_product['side4_height'] . ',' . $pc_product['side4_output_width'] . ',' . $pc_product['side4_output_height'];
	} else if (isset($pc_product['no_of_sides']) && $pc_product['no_of_sides'] == '3') {
	    $productConfigArea['side1Area'] = $pc_product['side1_x'] . ',' . $pc_product['side1_y'] . ',' . $pc_product['side1_width'] . ',' . $pc_product['side1_height'] . ',' . $pc_product['side1_output_width'] . ',' . $pc_product['side1_output_height'];
	    $productConfigArea['side2Area'] = $pc_product['side2_x'] . ',' . $pc_product['side2_y'] . ',' . $pc_product['side2_width'] . ',' . $pc_product['side2_height'] . ',' . $pc_product['side2_output_width'] . ',' . $pc_product['side2_output_height'];
	    $productConfigArea['side3Area'] = $pc_product['side3_x'] . ',' . $pc_product['side3_y'] . ',' . $pc_product['side3_width'] . ',' . $pc_product['side3_height'] . ',' . $pc_product['side3_output_width'] . ',' . $pc_product['side3_output_height'];
	} else if (isset($pc_product['no_of_sides']) && $pc_product['no_of_sides'] == '2') {
	    $productConfigArea['side1Area'] = $pc_product['side1_x'] . ',' . $pc_product['side1_y'] . ',' . $pc_product['side1_width'] . ',' . $pc_product['side1_height'] . ',' . $pc_product['side1_output_width'] . ',' . $pc_product['side1_output_height'];
	    $productConfigArea['side2Area'] = $pc_product['side2_x'] . ',' . $pc_product['side2_y'] . ',' . $pc_product['side2_width'] . ',' . $pc_product['side2_height'] . ',' . $pc_product['side2_output_width'] . ',' . $pc_product['side2_output_height'];
	} else {
	    $productConfigArea['side1Area'] = $pc_product['side1_x'] . ',' . $pc_product['side1_y'] . ',' . $pc_product['side1_width'] . ',' . $pc_product['side1_height'] . ',' . $pc_product['side1_output_width'] . ',' . $pc_product['side1_output_height'];
	}

	$productData['Area'] = $productConfigArea;
	$productData['colorId'] = $postdata['color_id'];
	$productData['sizeId'] = $postdata['size_id'];
     
	$productData['allColors'] = $data['option'];
    
    if($productData['multiColor'] == 'yes'){
    	$maskImageUrls = array();
        foreach($productData['allColors']['color'] as $optionId=>$_color){
           $colorImages = $this->Designnbuy_common_webservice_model->getMulticolorProductimagesFormOptionId($product['product_id'],$optionId);
           $imageProductPath = $this->pc_rootURL . 'designnbuy/uploads/productimage/';
        
           foreach($colorImages as $_image){
               if($_image['side_no'] == '1'){
                $productData['allColors']['color'][$optionId]['colorimage'] = $imageProductPath.$_image['image'];
                $productData['allColors']['color'][$optionId]['map_image'] = $imageProductPath.$_image['map_image'];//added by somin
                $productData['allColors']['color'][$optionId]['mask_image'] = $imageProductPath.$_image['mask_image'];//added by somin
               } 
                $productData['allColors']['color'][$optionId]['image'][$_image['side_no']] = $imageProductPath.$_image['image'];
                
                //added by somin
	             if($pc_product['is_3d'] == "1"){
	                 if (isset($_image['mask_image']) && $_image['mask_image'] != '') {
			           $productImages[0] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $_image['image'];
				     } else {
					     $productImages[0] = null;
					 }

	                 if (isset($_image['mask_image']) && $_image['mask_image'] != '') {
			           $maskImageUrls[0] = $this->pc_rootURL . 'designnbuy/uploads/productimage/' . $_image['mask_image'];
				     } else {
					     $maskImageUrls[0] = null;
					 }
				   }

            }
        }
        //added by somin
      if($pc_product['is_3d'] == "1"){
    	 $productData['productImages'] = $productImages;
    	 $productData['maskImages'] = $maskImageUrls;
      }

    }
     
	if ($pc_product['global_side_label'] != '0') {
	    $gloablSideLabels = $this->Designnbuy_common_webservice_model->getGloablSideLabels($language_id);
	    $productData['sideLabels'] = $gloablSideLabels;
	} else {
	    $productData['sideLabels'] = array(
		'0' => $pc_product['side_1_label'],
		'1' => $pc_product['side_2_label'],
		'2' => $pc_product['side_3_label'],
		'3' => $pc_product['side_4_label'],
		'4' => $pc_product['side_5_label'],
		'5' => $pc_product['side_6_label'],
		'6' => $pc_product['side_7_label'],
		'7' => $pc_product['side_8_label'],
	    );
	}

	$printingMethods = array();
	$printingMethods = $this->Designnbuy_common_webservice_model->getPrintingMethodsFromProductId($product['product_id'], $language_id);
	$productData['printingMethods'] = $printingMethods;
	$productData['is_pretemplate'] = $pc_product['is_pretemplate'];
	$productData['is_3d'] = $pc_product['is_3d']; //added by somin
	$productData['map_image'] = $this->pc_rootURL . 'designnbuy/uploads/productimage/'.$pc_3dproduct['map_image'];//added by somin
	$productData['model_3d'] = $this->pc_rootURL . 'designnbuy/uploads/productimage/'.$pc_3dproduct['modal_image'];//added by somin
	$productData['site_Url'] = $this->pc_rootURL; //added by somin
	$postData['colorOptionId'] = $postdata['color_id'];
	$postData['sizeOptionId'] = $postdata['size_id'];
	$postData['qty'] = $postdata['qty'];
	//$postData['design_id'] = $param['design_id'];
	$postData['design_id'] = '';
	$productData['postData'] = $postData;
	return $productData;
    }

    public function getoutlineMatrix($colorCode) {
	$colorwohex = explode("#", $colorCode);
	$color = str_pad($colorwohex[1], 6, "0");
	$rclr = (int) hexdec(substr($color, 0, 2));
	$gclr = (int) hexdec(substr($color, 2, 2));
	$bclr = (int) hexdec(substr($color, 4, 2));
	$matrix = ($rclr / 255) . " 0 0 0 0 ";
	$matrix .= "0 " . ($gclr / 255) . " 0 0 0 ";
	$matrix .= "0 0 " . ($bclr / 255) . " 0 0 ";
	$matrix .= "0 0 0 1 0";
	return $matrix;
    }

 //    public function setSvgSize($init_data, $width, $height) {
	// $init_data = str_replace("&quot;","&#39;",$init_data);
	// $doc = new DOMDocument();
	// //  $dom->preserveWhiteSpace = False;
	// $doc->loadXML(html_entity_decode(htmlspecialchars_decode($init_data)));
	// $doc->documentElement->setAttribute("width", $width);
	// $doc->documentElement->setAttribute("height", $height);
	// $init_data = $doc->saveXML($doc);
	// return $init_data;
 //    }
    public function setSvgSize($init_data, $width, $height) {
	$webpath = $this->pc_rootURL;
	$init_data = str_replace("&quot;","&#39;",$init_data);
	$doc = new DOMDocument();
	//  $dom->preserveWhiteSpace = False;
	$doc->loadXML(html_entity_decode(htmlspecialchars_decode($init_data)));
	foreach ($doc->getElementsByTagNameNS('http://www.w3.org/2000/svg', 'svg') as $element):
		foreach ($element->getElementsByTagName("*") as $tags):			
			if($tags->localName=='image' && $tags->getAttribute('xlink:href')!=''):	
				$imageUrl = $tags->getAttribute('xlink:href');													
				$uploadedImage = explode('designnbuy/',$imageUrl);		
				if($uploadedImage[0]!='' && $uploadedImage[0] != $webpath):	
					$tags->setAttribute('xlink:href',$webpath.'designnbuy/'.$uploadedImage[1]);
				endif;
				
				$templateSrc = $tags->getAttribute('templateSrc');													
				$templateSrcUploadedImage = explode('designnbuy/',$templateSrc);		
				if($templateSrcUploadedImage[0]!='' && $templateSrcUploadedImage[0] != $webpath):	
					$tags->setAttribute('templateSrc',$webpath.'designnbuy/'.$templateSrcUploadedImage[1]);
				endif;
			endif;
		endforeach;
	endforeach;

	$doc->documentElement->setAttribute("width", $width);
	$doc->documentElement->setAttribute("height", $height);
	$init_data = $doc->saveXML($doc);
	return $init_data;
    }

    public function product() {
	$param = $this->input->post();
	$url = $this->pc_rootURL . $this->plateform_solution['product_path'] . '&pid=' . $param['pid'];

	//$url = $this->pc_rootURL . 'index.php?route=designnbuy/initdata/product&pid=' . $param['pid'];
	$this->curl->create($url);
	$this->curl->option('returntransfer', 1);
	$data = $this->curl->execute();
	$data = json_decode($data, true);
	$param['product_id'] = $data['product']['product_id'];
	$param['qty'] = $data['product']['minimum'];

	if ($data['option']['color']) {
	    foreach ($data['option']['color'] as $col) {
		$param['color_id'] = $col['optionID'];
		if ($col['sizes']) {
		    foreach ($col['sizes'] as $size) {
			$param['size_id'] = $size['optionID'];
		    }
		} else {
		    $param['size_id'] = '';
		}
	    }
	} else if ($data['option']['size']) {
	    foreach ($data['option']['size'] as $size) {
		$param['color_id'] = '';
		$param['size_id'] = $size['optionID'];
	    }
	} else {
	    $param['color_id'] = '';
	    $param['size_id'] = '';
	}
	$extraoptions = array();
	$param['extraoptions'] = base64_encode(serialize($extraoptions));
	$language_id = $param['language_id'];

	//$language_id = $this->getLanguageIdBasedOnConnector($languageCode);
	$productData = $this->_getProductData($param);
	$settings = $this->_getSettings($param);
	$designideas = '';
	$clipartCategories = $this->Designnbuy_common_webservice_model->getClipartCategoryList($language_id);
	$fontList = $this->Designnbuy_common_webservice_model->getFontList();
	$configurefeature = $this->Designnbuy_common_webservice_model->getConfigureFeatureData();
	//$userimages = $this->getUserImages();
	$userimages = '';


	$information = $this->successStatus();
	$information['data']['settings'] = $settings;
	$information['data']['clipartCategories'] = $clipartCategories;
	$information['data']['productdata'] = $productData;
	$information['data']['fontlist'] = $fontList;
	$information['data']['configurefeature'] = $configurefeature;
	//$information['data']['userimages'] = $userimages;
	$this->printResult($information);
    }

}