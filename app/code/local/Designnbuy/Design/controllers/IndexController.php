<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Sendfriend
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Designnbuy_Design_IndexController extends Mage_Core_Controller_Front_Action {

    /**
     * Initialize product instance
     *
     */
    public $categoryCollection = array();

    protected function _getSession() {
	return Mage::getSingleton('customer/session');
    }

    public function indexAction() {
	$this->loadLayout();
	$this->renderLayout();
    }

    public function productAction() {
	$categoryId = $this->getRequest()->getParam('cid', false);
	$productId = $this->getRequest()->getParam('pid', false);
	$user = $this->getRequest()->getParam('user', false);
	$extraoptions = $this->getRequest()->getParam('extraoptions', false);
	if ($categoryId != '') {
	    echo Mage::getModel('design/design')->getProductsFromCategory($categoryId);
	} else {
	    echo Mage::getModel('design/design')->getProductFromId($productId, $user, $extraoptions);
	}
	die;
    }

    public function getSettingsAction() {
	$data = array();
	$data['basepath'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
	$data['webpath'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
	$data['addToCartUrl'] = Mage::getUrl('checkout/cart/add');
	$data['cartUrl'] = Mage::getUrl('checkout/cart');
	$data['welcomeMessageUrl'] = Mage::getUrl('design/index/welcomeMessage');
	$data['updateTopLinksUrl'] = Mage::getUrl('design/index/updateTopLinks');
	$data['topCartsUrl'] = Mage::getUrl('design/index/topCarts');
	$data['loginUrl'] = Mage::getUrl('customer/account/loginFromTool');
	$data['registrationUrl'] = Mage::getUrl('customer/account/createNewPost');
	$data['loginCheckUrl'] = Mage::getUrl('customer/account/loginCheck');
	$data['productPriceUrl'] = Mage::getUrl('design/index/getrelatedprice');
	$data['formKey'] = Mage::getSingleton('core/session')->getFormKey();
	$currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();
	$currency_symbol = Mage::app()->getLocale()->currency($currency_code)->getSymbol();
	$data['currency_symbol'] = $currency_symbol;
	$data['forgotpassword'] = Mage::getUrl('customer/account/forgotpassword');
	echo Mage::helper('core')->jsonEncode($data);
	die;
    }

    //update welcome message
    public function welcomeMessageAction() {
	$this->loadLayout();
	$Top = Mage::app()->getLayout()->getBlock('header')->getWelcome();
	$this->getResponse()->setBody($Top);
    }

    //update top links
    public function updateTopLinksAction() {
	$this->loadLayout();
	$Top = $this->getLayout()->getBlock('top.links')->toHtml();
	$this->getResponse()->setBody($Top);
    }

    //update top carts
    public function topCartsAction() {
	$this->loadLayout();
	// $Top = $this->getLayout()->getBlock('cartTop')->toHtml();
	$Top = $this->getLayout()->createBlock('cms/block')->setBlockId('header-cart')->toHtml();
	$this->getResponse()->setBody($Top);
    }

    public function getrelatedproductAction() {
	$catid = $this->getRequest()->getParam('category_id', false);
	//echo $catid; exit;
	$storeId = Mage::app()->getStore()->getId();
	echo Mage::getModel('design/design')->getCategoryProducts($catid, $storeId);
	die;
    }

    public function getrelatedpriceAction() {
	$pricingData = Mage::helper('core')->jsonDecode($this->getRequest()->getParam('pricingData', false));

	$productid = $pricingData['productId'];
	$productModel = Mage::getModel('catalog/product');
	$finalPrice = 0;
	$total_cost = 0.0;
	$printingPrices = array();
	$printingPrice = 0;
	$clipartPrices = 0;
	$avgPrintingPrice = 0;
	$totalqty = 0;
	$totalPrintingPrice = 0;
	$totalClipartPrice = 0;
	$totalImageUploadPrice = 0;
	$error = array();
	if (isset($pricingData)) {
	    $configProduct = $productModel->load($productid);
	    if($configProduct->getTypeId() == 'configurable'){
	    $productAttributeOptions = $configProduct->getTypeInstance(true)->getConfigurableAttributesAsArray($configProduct);
	    $option_attr = Mage::getModel('design/design')->getOptionAttr('option_abbreviation');
	    $colorkey = $this->getArray($productAttributeOptions, 'label', $option_attr['color_option']);
	    $sizekey = $this->getArray($productAttributeOptions, 'label', $option_attr['size_option']);
	    }
	    $error = array();
	    $error['message'] = '';
	    if (isset($pricingData['colorId']) && $pricingData['colorId'] != '') {
		$colorId = $pricingData['colorId'];
		$colorAttribute = $productModel->getResource()->getAttribute($productAttributeOptions[$colorkey]['attribute_code']);
		if ($colorAttribute->usesSource()) {
		    $attr = Mage::getModel('catalog/resource_eav_attribute')->load($colorId);
		    $attributeOptions = $attr->getSource()->getAllOptions();
		    $colorLabel = $colorAttribute->getSource()->getOptionText($colorId);
		    $colorName = explode('(', $colorLabel);
		    $colorName = $colorName[0];
		}
		$usedColorCounter = $this->getRequest()->getParam('totalcolor', false);
		$_product = Mage::getModel('catalog/product')->load($productid);
		if (!empty($pricingData['sizesData'])) {
		    $sizeidarray = $pricingData['sizesData'];
		    $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $_product); // for associative simple product
		    $sizeId = '';
		    $qty = '';
		    for ($i = 0; $i < count($sizeidarray); $i++) {
			foreach ($childProducts as $child) {
			    $sizeId = $sizeidarray[$i]['id'];
			    $qty = $sizeidarray[$i]['quantity'];
			    
			    if (($child->getSize() == $sizeId) and ($child->getColor() == $colorId)) {
				$totalqty = $qty + $totalqty;
				$sizeAttribute = $productModel->getResource()->getAttribute($productAttributeOptions[$sizekey]['attribute_code']);

				if ($sizeAttribute->usesSource()) {
				    $sizeName = $sizeAttribute->getSource()->getOptionText($sizeidarray[$i]['id']);
				}

				$availableQty = $child->getStockItem()->getQty();
				$minSaleQty = $child->getStockItem()->getMinSaleQty();
				$maxSaleQty = $child->getStockItem()->getMaxSaleQty();
				$isConfigSetting = $child->getStockItem()->getUseConfigMaxSaleQty();
				if ($isConfigSetting == 0) {
				    if ($qty > $maxSaleQty) {
					$error['message'] = 'The maximum quantity allowed for Color ' . $colorName . ' and Size ' . $sizeName . ' for purchase is ' . $maxSaleQty;
				    }
				}
				if ($qty > $availableQty) {
				    $error['message'] = 'Quantity ' . $qty . ' is not available for Color ' . $colorName . ' and Size ' . $sizeName;
				}

				if ($qty < $minSaleQty) {
				    $error['message'] = "Minimum sale quantity for Color " . $colorName . " and Size " . $sizeName . " is " . $minSaleQty;
				}
			    }
			}
			$finalPrice = Mage::getModel('design/design')->getFinalPrice($qty, $_product, $colorId, $sizeId);
			$colorSizeTotalPrice[$colorName][$sizeName] = Mage::helper('core')->currency($finalPrice * $qty, true, false);
			$total_cost = $total_cost + ($finalPrice * $qty);
			$printingPrices = array();
			$printingPrices = $this->calculatePrintingMethodPrice($pricingData, $qty);
			if (isset($pricingData['printingMethod']['sideWiseClipIDs'])) {
			    $clipartPrices = $this->calculateClipartPrice($pricingData['printingMethod']['sideWiseClipIDs'], $qty);
			}
			$totalClipartPrice = $totalClipartPrice + $clipartPrices;
			
			$totalImageUploadPrice = $totalImageUploadPrice + $printingPrices['imageUploadPrice'];
			$printingPrice = $printingPrice + $printingPrices['printingPrice'];
			$totalPrintingPrice = $printingPrice + $printingPrices['artWorkSetupPrice'] + $totalClipartPrice + $printingPrices['nameNumberPrice'] + $totalImageUploadPrice;
			$avgPrintingPrice = $totalPrintingPrice / $totalqty;
		    }
		} else {
		    $qty = $pricingData['qty'];
		    
		    $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $_product);
		    foreach ($childProducts as $child) {
			if ($child->getColor() == $colorId) {
			    $totalqty = $qty;
			    $availableQty = $child->getStockItem()->getQty();
			    $minSaleQty = $child->getStockItem()->getMinSaleQty();
			    $maxSaleQty = $child->getStockItem()->getMaxSaleQty();
			    $isConfigSetting = $child->getStockItem()->getUseConfigMaxSaleQty();
			}
		    }

		    if ($isConfigSetting == 0) {
			if ($qty > $maxSaleQty) {
			    $error['message'] = 'The maximum quantity allowed for Color ' . $colorName . ' and Size ' . $sizeName . ' for purchase is ' . $maxSaleQty;
			}
		    }
		    if ($qty > $availableQty) {
			$error['message'] = 'Quantity ' . $qty . ' is not available for Color ' . $colorName . ' and Size ' . $sizeName;
		    }

		    if ($qty < $minSaleQty) {
			$error['message'] = "Minimum sale quantity for Color " . $colorName . " and Size " . $sizeName . " is " . $minSaleQty;
		    }
		    $finalPrice = Mage::getModel('design/design')->getFinalPrice($qty, $_product, $colorId, '');
		    
		    $colorSizeTotalPrice[$colorName] = Mage::helper('core')->currency($finalPrice * $qty, true, false);
		    $total_cost = $total_cost + ($finalPrice * $qty);
		    $printingPrices = $this->calculatePrintingMethodPrice($pricingData, $qty);
		    if (isset($pricingData['printingMethod']['sideWiseClipIDs'])) {
			$clipartPrices = $this->calculateClipartPrice($pricingData['printingMethod']['sideWiseClipIDs'], $qty);
		    }
		    $totalClipartPrice = $totalClipartPrice + $clipartPrices;
		    $totalImageUploadPrice = $totalImageUploadPrice + $printingPrices['imageUploadPrice'];
		    $printingPrice = $printingPrice + $printingPrices['printingPrice'];
		    $totalPrintingPrice = $printingPrice + $printingPrices['artWorkSetupPrice'] + $totalClipartPrice + $printingPrices['nameNumberPrice'] + $totalImageUploadPrice;
		    $avgPrintingPrice = $totalPrintingPrice / $totalqty;
		}
	    } else if (!empty($pricingData['sizesData'])) {
		$_product = Mage::getModel('catalog/product')->load($productid);
		$sizeidarray = $pricingData['sizesData'];
		$childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $_product); // for associative simple product

		$sizeId = '';
		$qty = '';

		for ($i = 0; $i < count($sizeidarray); $i++) {
		    foreach ($childProducts as $child) {
			$sizeId = $sizeidarray[$i]['id'];
			$qty = $sizeidarray[$i]['quantity'];
			
			if (($child->getSize() == $sizeId)) {
			    $totalqty = $qty + $totalqty;
			    $sizeAttribute = $productModel->getResource()->getAttribute($productAttributeOptions[$sizekey]['attribute_code']);

			    if ($sizeAttribute->usesSource()) {
				$sizeName = $sizeAttribute->getSource()->getOptionText($sizeidarray[$i]['id']);
			    }

			    $availableQty = $child->getStockItem()->getQty();
			    $minSaleQty = $child->getStockItem()->getMinSaleQty();
			    $maxSaleQty = $child->getStockItem()->getMaxSaleQty();
			    $isConfigSetting = $child->getStockItem()->getUseConfigMaxSaleQty();
			    if ($isConfigSetting == 0) {
				if ($qty > $maxSaleQty) {
				    $error['message'] = 'The maximum quantity allowed for Color ' . $colorName . ' and Size ' . $sizeName . ' for purchase is ' . $maxSaleQty;
				}
			    }
			    if ($qty > $availableQty) {
				$error['message'] = 'Quantity ' . $qty . ' is not available for Color ' . $colorName . ' and Size ' . $sizeName;
			    }

			    if ($qty < $minSaleQty) {
				$error['message'] = "Minimum sale quantity for Color " . $colorName . " and Size " . $sizeName . " is " . $minSaleQty;
			    }
			}
		    }

		    $finalPrice = Mage::getModel('design/design')->getFinalPrice($qty, $_product, '', $sizeId);
		    // echo $sizeId;
		    // exit;
		    $colorSizeTotalPrice[$sizeName] = Mage::helper('core')->currency($finalPrice * $qty, true, false);
		    
		    $total_cost = $total_cost + ($finalPrice * $qty);
		    $printingPrices = $this->calculatePrintingMethodPrice($pricingData, $qty);

		    if (isset($pricingData['printingMethod']['sideWiseClipIDs'])) {
			$clipartPrices = $this->calculateClipartPrice($pricingData['printingMethod']['sideWiseClipIDs'], $qty);
		    }
		    $totalClipartPrice = $totalClipartPrice + $clipartPrices;
		    $totalImageUploadPrice = $totalImageUploadPrice + $printingPrices['imageUploadPrice'];
		    $printingPrice = $printingPrice + $printingPrices['printingPrice'];
		    $totalPrintingPrice = $printingPrice + $printingPrices['artWorkSetupPrice'] + $totalClipartPrice + $printingPrices['nameNumberPrice'] + $totalImageUploadPrice;
		    $avgPrintingPrice = $totalPrintingPrice / $totalqty;
		}
	    } else {
		$_product = Mage::getModel('catalog/product')->load($productid);
		$qty = $pricingData['qty'];
		$totalqty = $qty;
		$availableQty = $_product->getStockItem()->getQty();
		$minSaleQty = $_product->getStockItem()->getMinSaleQty();
		$maxSaleQty = $_product->getStockItem()->getMaxSaleQty();
		$isConfigSetting = $_product->getStockItem()->getUseConfigMaxSaleQty();
		if ($isConfigSetting == 0) {
		    if ($qty > $maxSaleQty) {
			$error['message'] = "Maximum sale quantity for ". $_product->getName() ."  is " . $maxSaleQty;
		    }
		}
		if ($qty > $availableQty) {
		    $error['message'] = $error['message'] = Mage::helper('cataloginventory')->__('The requested quantity for "%s" is not available.', $_product->getName());
		}

		if ($qty < $minSaleQty) {
		    $error['message'] = "Minimum sale quantity for ". $_product->getName() ."  is " . $minSaleQty;
		}
			    
		/*if ($qty < $minSaleQty) {
		    $error['message'] = $error['message'] = Mage::helper('cataloginventory')->__('The requested quantity for "%s" is not available.', $_product->getName());
		}*/
		
		$finalPrice = Mage::getModel('design/design')->getFinalPrice($qty, $_product, null, null);
		$colorSizeTotalPrice = '';
		$colorSizeTotalPrice = Mage::helper('core')->currency($finalPrice * $qty, true, false);
		$total_cost = $total_cost + ($finalPrice * $qty);
		$printingPrices = array();
		$printingPrices = $this->calculatePrintingMethodPrice($pricingData, $qty);
		if (isset($pricingData['printingMethod']['sideWiseClipIDs'])) {
		    $clipartPrices = $this->calculateClipartPrice($pricingData['printingMethod']['sideWiseClipIDs'], $qty);
		}
		$totalClipartPrice = $totalClipartPrice + $clipartPrices;
		$totalImageUploadPrice = $totalImageUploadPrice + $printingPrices['imageUploadPrice'];
		$printingPrice = $printingPrice + $printingPrices['printingPrice'];
		$totalPrintingPrice = $printingPrice + $printingPrices['artWorkSetupPrice'] + $totalClipartPrice + $printingPrices['nameNumberPrice'] + $totalImageUploadPrice;
		$avgPrintingPrice = $totalPrintingPrice / $totalqty;
	    }
	    $product_printing_artwork_included = $total_cost + $totalPrintingPrice;
	    $error['baseTotal'] = Mage::helper('core')->currency($product_printing_artwork_included, true, false);
	    $error['colorSizeTotalPrice'] = $colorSizeTotalPrice;	   
	    $error['printingPrice'] = Mage::helper('core')->currency($printingPrice, true, false);
	    $error['avgPrintingPrice'] = Mage::helper('core')->currency($avgPrintingPrice, true, false);
	    $error['clipartPrice'] = Mage::helper('core')->currency($totalClipartPrice, true, false);
	    $error['namePrice'] = Mage::helper('core')->currency($printingPrices['namePrice'], true, false);
	    $error['numberPrice'] = Mage::helper('core')->currency($printingPrices['numberPrice'], true, false);
	    $error['nameNumberPrice'] = Mage::helper('core')->currency($printingPrices['nameNumberPrice'], true, false);
	    $error['imageUploadPrice'] = Mage::helper('core')->currency($totalImageUploadPrice, true, false);
	    $error['artWorkSetupPrice'] = Mage::helper('core')->currency($printingPrices['artWorkSetupPrice'], true, false);
	    $error['good'] = Mage::helper('core')->currency($product_printing_artwork_included, true, false);
	    
	    echo Mage::helper('core')->jsonEncode($error);
	    die;
	}
    }

    public function calculatePrintingMethodPrice($pricingData, $qty) {

	$side1Colors = 0;
	$side2Colors = 0;
	$side3Colors = 0;
	$side4Colors = 0;
	$side5Colors = 0;
	$side6Colors = 0;
	$side7Colors = 0;
	$side8Colors = 0;

	$side1Area = 0;
	$side2Area = 0;
	$side3Area = 0;
	$side4Area = 0;
	$side5Area = 0;
	$side6Area = 0;
	$side7Area = 0;
	$side8Area = 0;

	$side1QCPrice = 0;
	$side2QCPrice = 0;
	$side3QCPrice = 0;
	$side4QCPrice = 0;
	$side5QCPrice = 0;
	$side6QCPrice = 0;
	$side7QCPrice = 0;
	$side8QCPrice = 0;

	$side1SQArea = 0;
	$side2SQArea = 0;
	$side3SQArea = 0;
	$side4SQArea = 0;
	$side5SQArea = 0;
	$side6SQArea = 0;
	$side7SQArea = 0;
	$side8SQArea = 0;

	$side1QAPrice = 0;
	$side2QAPrice = 0;
	$side3QAPrice = 0;
	$side4QAPrice = 0;
	$side5QAPrice = 0;
	$side6QAPrice = 0;
	$side7QAPrice = 0;
	$side8QAPrice = 0;

	$usedImageCounter = 0;
	$imageFixPrice = 0;
	$imageTotalPrice = 0;
	$printingPrice = 0;
	$totalNumberOfColors = 0;
	$sizeWisePrices = '';
	$totalFixedPrice = 0;
	$pricingLogic = 0;

	$side1FixedPrice = 0;
	$side2FixedPrice = 0;
	$side3FixedPrice = 0;
	$side4FixedPrice = 0;
	$side5FixedPrice = 0;
	$side6FixedPrice = 0;
	$side7FixedPrice = 0;
	$side8FixedPrice = 0;

	$totalFixedPrice = 0;
	$totalQcPrice = 0;
	$totalSquareArea = 0;
	$squareAreaPrice = 0;
	$totalSquareAreaPrice = 0;
	$artWorkSetupPrice = 0;
	$nameNumberPrice = 0;
	$artWorkSetupTotalPrice = 0;
	$nameprice = 0;
	$numberprice = 0;

	$pritingMetohdData = $pricingData['printingMethod'];
	if (isset($pritingMetohdData['printingMethodId']) && $pritingMetohdData['printingMethodId'] != '') {
	    $printingMethodId = $pritingMetohdData['printingMethodId'];
	} else {
	    $printingMethodId = NULL;
	}

	$printingMethodData = Mage::getModel('design/design')->getPrintingMethodsForPricing($printingMethodId);

	if (count($printingMethodData) > 0) {

	    $pricingLogic = $printingMethodData['pricing_logic'];

	    /*
	      $pricingLogic = 1 = Fixed Price;
	      $pricingLogic = 2 = Quantity Color Price;
	      $pricingLogic = 3 = Quantity Area Price
	     */
	    if ($pricingLogic == 1) {
		if ($pritingMetohdData['isCustomized']['Side1'] == 'true') {
		    $side1FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side1');
		}
		if ($pritingMetohdData['isCustomized']['Side2'] == 'true') {
		    $side2FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side2');
		}
		if ($pritingMetohdData['isCustomized']['Side3'] == 'true') {
		    $side3FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side3');
		}
		if ($pritingMetohdData['isCustomized']['Side4'] == 'true') {
		    $side4FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side4');
		}
		if ($pritingMetohdData['isCustomized']['Side5'] == 'true') {
		    $side5FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side5');
		}
		if ($pritingMetohdData['isCustomized']['Side6'] == 'true') {
		    $side6FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side6');
		}
		if ($pritingMetohdData['isCustomized']['Side7'] == 'true') {
		    $side7FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side7');
		}
		if ($pritingMetohdData['isCustomized']['Side8'] == 'true') {
		    $side8FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side8');
		}
		$printingPrice = $side1FixedPrice + $side2FixedPrice + $side3FixedPrice + $side4FixedPrice + $side5FixedPrice + $side6FixedPrice + $side7FixedPrice + $side8FixedPrice;
		$totalFixedPrice = $totalFixedPrice + ($side1FixedPrice + $side2FixedPrice + $side3FixedPrice + $side4FixedPrice + $side5FixedPrice + $side6FixedPrice + $side7FixedPrice + $side8FixedPrice) * $qty;
	    } else if ($pricingLogic == 2) {


		if (isset($pritingMetohdData['totalColors']['Side1'])) {
		    $side1Colors = intval($pritingMetohdData['totalColors']['Side1']);
		}
		if (isset($pritingMetohdData['totalColors']['Side2'])) {
		    $side2Colors = intval($pritingMetohdData['totalColors']['Side2']);
		}
		if (isset($pritingMetohdData['totalColors']['Side3'])) {
		    $side3Colors = intval($pritingMetohdData['totalColors']['Side3']);
		}
		if (isset($pritingMetohdData['totalColors']['Side4'])) {
		    $side4Colors = intval($pritingMetohdData['totalColors']['Side4']);
		}
		if (isset($pritingMetohdData['totalColors']['Side5'])) {
		    $side5Colors = intval($pritingMetohdData['totalColors']['Side5']);
		}
		if (isset($pritingMetohdData['totalColors']['Side6'])) {
		    $side6Colors = intval($pritingMetohdData['totalColors']['Side6']);
		}
		if (isset($pritingMetohdData['totalColors']['Side7'])) {
		    $side7Colors = intval($pritingMetohdData['totalColors']['Side7']);
		}
		if (isset($pritingMetohdData['totalColors']['Side8'])) {
		    $side8Colors = intval($pritingMetohdData['totalColors']['Side8']);
		}

		if ($side1Colors > 0) {

		    $side1QCPrice = Mage::getModel('design/design')->getPrintingMethodQCPrice($printingMethodId, $side1Colors, $qty, 'side1');
		}
		if ($side2Colors > 0) {

		    $side2QCPrice = Mage::getModel('design/design')->getPrintingMethodQCPrice($printingMethodId, $side2Colors, $qty, 'side2');
		}
		if ($side3Colors > 0) {

		    $side3QCPrice = Mage::getModel('design/design')->getPrintingMethodQCPrice($printingMethodId, $side3Colors, $qty, 'side3');
		}
		if ($side4Colors > 0) {

		    $side4QCPrice = Mage::getModel('design/design')->getPrintingMethodQCPrice($printingMethodId, $side4Colors, $qty, 'side4');
		}
		if ($side5Colors > 0) {

		    $side5QCPrice = Mage::getModel('design/design')->getPrintingMethodQCPrice($printingMethodId, $side5Colors, $qty, 'side5');
		}
		if ($side6Colors > 0) {

		    $side6QCPrice = Mage::getModel('design/design')->getPrintingMethodQCPrice($printingMethodId, $side6Colors, $qty, 'side6');
		}
		if ($side7Colors > 0) {

		    $side7QCPrice = Mage::getModel('design/design')->getPrintingMethodQCPrice($printingMethodId, $side7Colors, $qty, 'side7');
		}
		if ($side8Colors > 0) {

		    $side8QCPrice = Mage::getModel('design/design')->getPrintingMethodQCPrice($printingMethodId, $side8Colors, $qty, 'side8');
		}
		$printingPrice = $side1QCPrice + $side2QCPrice + $side3QCPrice + $side4QCPrice + $side5QCPrice + $side6QCPrice + $side7QCPrice + $side8QCPrice;
		$totalQcPrice = $totalQcPrice + ($side1QCPrice + $side2QCPrice + $side3QCPrice + $side4QCPrice + $side5QCPrice + $side6QCPrice + $side7QCPrice + $side8QCPrice) * $qty;
	    } else if ($pricingLogic == 3) {

		if (isset($pritingMetohdData['squareArea']['Side1'])) {
		    $side1Area = floatval($pritingMetohdData['squareArea']['Side1']);
		}
		if (isset($pritingMetohdData['squareArea']['Side2'])) {
		    $side2Area = floatval($pritingMetohdData['squareArea']['Side2']);
		}
		if (isset($pritingMetohdData['squareArea']['Side3'])) {
		    $side3Area = floatval($pritingMetohdData['squareArea']['Side3']);
		}
		if (isset($pritingMetohdData['squareArea']['Side4'])) {
		    $side4Area = floatval($pritingMetohdData['squareArea']['Side4']);
		}
		if (isset($pritingMetohdData['squareArea']['Side5'])) {
		    $side5Area = floatval($pritingMetohdData['squareArea']['Side5']);
		}
		if (isset($pritingMetohdData['squareArea']['Side6'])) {
		    $side6Area = floatval($pritingMetohdData['squareArea']['Side6']);
		}
		if (isset($pritingMetohdData['squareArea']['Side7'])) {
		    $side7Area = floatval($pritingMetohdData['squareArea']['Side7']);
		}
		if (isset($pritingMetohdData['squareArea']['Side8'])) {
		    $side8Area = floatval($pritingMetohdData['squareArea']['Side8']);
		}
		if ($side1Area > 0) {
		    $side1SQArea = Mage::getModel('design/design')->getPrintingMethodQAPrice($printingMethodId, $side1Area, $qty, 'side1');
		}
		if ($side2Area > 0) {
		    $side2SQArea = Mage::getModel('design/design')->getPrintingMethodQAPrice($printingMethodId, $side2Area, $qty, 'side2');
		}
		if ($side3Area > 0) {
		    $side3SQArea = Mage::getModel('design/design')->getPrintingMethodQAPrice($printingMethodId, $side3Area, $qty, 'side3');
		}
		if ($side4Area > 0) {
		    $side4SQArea = Mage::getModel('design/design')->getPrintingMethodQAPrice($printingMethodId, $side4Area, $qty, 'side4');
		}
		if ($side5Area > 0) {
		    $side5SQArea = Mage::getModel('design/design')->getPrintingMethodQAPrice($printingMethodId, $side5Area, $qty, 'side5');
		}
		if ($side6Area > 0) {
		    $side6SQArea = Mage::getModel('design/design')->getPrintingMethodQAPrice($printingMethodId, $side6Area, $qty, 'side6');
		}
		if ($side7Area > 0) {
		    $side7SQArea = Mage::getModel('design/design')->getPrintingMethodQAPrice($printingMethodId, $side7Area, $qty, 'side7');
		}
		if ($side8Area > 0) {
		    $side8SQArea = Mage::getModel('design/design')->getPrintingMethodQAPrice($printingMethodId, $side8Area, $qty, 'side8');
		}

		$printingPrice = $side1SQArea + $side2SQArea + $side3SQArea + $side4SQArea + $side5SQArea + $side6SQArea + $side7SQArea + $side8SQArea;
		$totalSquareAreaPrice = $totalSquareAreaPrice + ($side1SQArea + $side2SQArea + $side3SQArea + $side4SQArea + $side5SQArea + $side6SQArea + $side7SQArea + $side8SQArea) * $qty;
	    }


	    $customizedCount = 0;
	    $totalNumberOfColors = 0;
	    /* For Name Number Price */
	    /* For Artwork Setup Price
	      1 = Fixed
	      2 = Per Color
	     */

	    $artWorkSetUpType = $printingMethodData['artwork_setup_price_type'];
	    $artWorkSetUpPrice = $printingMethodData['artwork_setup_price'];
	    $artWorkSetupTotalPrice = 0;
	    if ($pritingMetohdData['isCustomized']['Side1'] == true)
		$customizedCount++;
	    if ($pritingMetohdData['isCustomized']['Side2'] == true)
		$customizedCount++;
	    if ($pritingMetohdData['isCustomized']['Side3'] == true)
		$customizedCount++;
	    if ($pritingMetohdData['isCustomized']['Side4'] == true)
		$customizedCount++;
	    if ($pritingMetohdData['isCustomized']['Side5'] == true)
		$customizedCount++;
	    if ($pritingMetohdData['isCustomized']['Side6'] == true)
		$customizedCount++;
	    if ($pritingMetohdData['isCustomized']['Side7'] == true)
		$customizedCount++;
	    if ($pritingMetohdData['isCustomized']['Side8'] == true)
		$customizedCount++;


	    if ($pritingMetohdData['isCustomized']['Side1'] == true ||
		    $pritingMetohdData['isCustomized']['Side2'] == true ||
		    $pritingMetohdData['isCustomized']['Side3'] == true ||
		    $pritingMetohdData['isCustomized']['Side4'] == true ||
		    $pritingMetohdData['isCustomized']['Side5'] == true ||
		    $pritingMetohdData['isCustomized']['Side6'] == true ||
		    $pritingMetohdData['isCustomized']['Side7'] == true ||
		    $pritingMetohdData['isCustomized']['Side8'] == true) {
		if (isset($pritingMetohdData['totalColors']['Side1'])) {
		    $side1Colors = intval($pritingMetohdData['totalColors']['Side1']);
		}
		if (isset($pritingMetohdData['totalColors']['Side2'])) {
		    $side2Colors = intval($pritingMetohdData['totalColors']['Side2']);
		}
		if (isset($pritingMetohdData['totalColors']['Side3'])) {
		    $side3Colors = intval($pritingMetohdData['totalColors']['Side3']);
		}
		if (isset($pritingMetohdData['totalColors']['Side4'])) {
		    $side4Colors = intval($pritingMetohdData['totalColors']['Side4']);
		}
		if (isset($pritingMetohdData['totalColors']['Side5'])) {
		    $side5Colors = intval($pritingMetohdData['totalColors']['Side5']);
		}
		if (isset($pritingMetohdData['totalColors']['Side6'])) {
		    $side6Colors = intval($pritingMetohdData['totalColors']['Side6']);
		}
		if (isset($pritingMetohdData['totalColors']['Side7'])) {
		    $side7Colors = intval($pritingMetohdData['totalColors']['Side7']);
		}
		if (isset($pritingMetohdData['totalColors']['Side8'])) {
		    $side8Colors = intval($pritingMetohdData['totalColors']['Side8']);
		}
		$totalNumberOfColors = $side1Colors + $side2Colors + $side3Colors + $side4Colors + $side5Colors + $side6Colors + $side7Colors + $side8Colors;
		if ($artWorkSetUpType == 1) {
		    $artWorkSetupTotalPrice = $artWorkSetUpPrice * $customizedCount;
		} else {
		    $artWorkSetupTotalPrice = $artWorkSetupPrice + ($totalNumberOfColors * $printingMethodData['artwork_setup_price']);
		}
	    }

	    /* Image Price */
	    $imageFixPrice = (float) $printingMethodData['image_price'];
	    $usedImageCounter = $pritingMetohdData['totalImagesUsed']['Side1'] + $pritingMetohdData['totalImagesUsed']['Side2'] + $pritingMetohdData['totalImagesUsed']['Side3'] + $pritingMetohdData['totalImagesUsed']['Side4'] + $pritingMetohdData['totalImagesUsed']['Side5'] + $pritingMetohdData['totalImagesUsed']['Side6'] + $pritingMetohdData['totalImagesUsed']['Side7'] + $pritingMetohdData['totalImagesUsed']['Side8'];
	    $printingPrice = $printingPrice + ($usedImageCounter * $imageFixPrice);
	    $imageTotalPrice = $imageTotalPrice + ($usedImageCounter * $imageFixPrice * $qty);
	    
	    $nameprice = $printingMethodData['name_price'] * $pricingData['totalname'];
	    $numberprice = $printingMethodData['number_price'] * $pricingData['totalnumber'];
	    $nameNumberPrice = $nameprice + $numberprice;
	}
	$printingMethodPrice = array();
	$printingMethodPrice['printingPrice'] = $totalQcPrice + $totalSquareAreaPrice + $totalFixedPrice;
	$printingMethodPrice['nameNumberPrice'] = $nameNumberPrice;
	$printingMethodPrice['namePrice'] = $nameprice;
	$printingMethodPrice['numberPrice'] = $numberprice;
	$printingMethodPrice['imageUploadPrice'] = $imageTotalPrice;
	$printingMethodPrice['artWorkSetupPrice'] = $artWorkSetupTotalPrice;
	return $printingMethodPrice;
    }
    
     public function calculateClipartPrice($clipartdata, $qty) {
	$clipartTotalPrice = 0;
	$side1clipartprice = 0;
	$side2clipartprice = 0;
	$side3clipartprice = 0;
	$side4clipartprice = 0;
	$side5clipartprice = 0;
	$side6clipartprice = 0;
	$side7clipartprice = 0;
	$side8clipartprice = 0;
	if (!empty($clipartdata['Side1'])) {
	    foreach ($clipartdata['Side1'] as $clipartId) {
		$side1clipartprice = $side1clipartprice + Mage::getModel('design/design')->getClipartPrice($clipartId);
	    }
	}
	if (!empty($clipartdata['Side2'])) {
	    foreach ($clipartdata['Side2'] as $clipartId) {
		$side2clipartprice = $side2clipartprice + Mage::getModel('design/design')->getClipartPrice($clipartId);
	    }
	}
	if (!empty($clipartdata['Side3'])) {
	    foreach ($clipartdata['Side3'] as $clipartId) {
		$side3clipartprice = $side3clipartprice + Mage::getModel('design/design')->getClipartPrice($clipartId);
	    }
	}
	if (!empty($clipartdata['Side4'])) {
	    foreach ($clipartdata['Side4'] as $clipartId) {
		$side4clipartprice = $side4clipartprice + Mage::getModel('design/design')->getClipartPrice($clipartId);
	    }
	}
	if (!empty($clipartdata['Side5'])) {
	    foreach ($clipartdata['Side5'] as $clipartId) {
		$side5clipartprice = $side5clipartprice + Mage::getModel('design/design')->getClipartPrice($clipartId);
	    }
	}
	if (!empty($clipartdata['Side6'])) {
	    foreach ($clipartdata['Side6'] as $clipartId) {
		$side6clipartprice = $side6clipartprice + Mage::getModel('design/design')->getClipartPrice($clipartId);
	    }
	}
	if (!empty($clipartdata['Side7'])) {
	    foreach ($clipartdata['Side7'] as $clipartId) {
		$side7clipartprice = $side7clipartprice + Mage::getModel('design/design')->getClipartPrice($clipartId);
	    }
	}
	if (!empty($clipartdata['Side8'])) {
	    foreach ($clipartdata['Side8'] as $clipartId) {
		$side8clipartprice = $side8clipartprice + Mage::getModel('design/design')->getClipartPrice($clipartId);
	    }
	}

	$clipartTotalPrice = ($side1clipartprice + $side2clipartprice + $side3clipartprice + $side4clipartprice + $side5clipartprice + $side6clipartprice + $side7clipartprice + $side8clipartprice) * $qty;
	return $clipartTotalPrice;
    }

    public function getProductCategoryAction() {
	$rootcatId = Mage::app()->getStore()->getRootCategoryId();
	$rootCategory = Mage::getModel('catalog/category')->load($rootcatId);
	$cats = array();
	$cats = Mage::getModel('design/design')->getCategories($rootCategory);
	echo Mage::helper('core')->jsonEncode($cats);
	die;
    }

    public function getOrderDetailsAction() {
	$data = array();
	$orderId = $this->getRequest()->getParam('order_id', false);
	$order = Mage::getModel('sales/order')->load($orderId);
	$orderdata = $order->getData();
	$data['order'] = array(
	    'order_id' => $orderdata['entity_id'],
	    'date_added' => $orderdata['created_at'],
	    'customer_id' => $orderdata['customer_id'],
	    'firstname' => $orderdata['customer_firstname'],
	    'lastname' => $orderdata['customer_lastname'],
	    'total' => $orderdata['base_grand_total']
	);
	$orderItems = $order->getItemsCollection()->addAttributeToSelect('*')->load();
	
	foreach ($orderItems as $item) {
	    $optiondata = array();
	    $itemId = $item->getItemId();
	    $_item = Mage::getModel('sales/order_item')->load($itemId);
	 
	    $product_id = $item->getProductId();
	    $product_price = $item->getPrice();
	    $product_name = $item->getName();
	    $productoptions = $_item->getProductOptions();

	    $productoption = $productoptions['attributes_info'];
	   
	    foreach ($productoption as $option) {
		$optiondata[] = array(
		    'name' => $option['label'],
		    'value' => $option['value']
		);
		
	    }
	    $size_id = 0;
	    $attributes = $productoptions['info_buyRequest']['super_attribute'];
	    foreach($attributes as $key => $value){
		$attr = Mage::getModel('catalog/resource_eav_attribute')->load($key);
		if($attr->getAttributeCode() == 'size') {
		    $size_id = $value;
		}
	    }
	    
	    $designdata = Mage::getModel('design/design')->getOrderDesign($orderId, $itemId);
	    if (!empty($designdata)) {
		$productarray = array(
		    'product_name' => $product_name,
		    'price' => $product_price,
		    'option_data' => $optiondata,
		    'size_id' => $size_id
		);
		$data['orderproducts'][] = array_merge($designdata, $productarray);
	    }
	}
	echo Mage::helper('core')->jsonEncode($data);
	die;
    }
    
    

    public function getArray($results, $field, $value) {
	foreach ($results as $key => $result) {
	    if ($result[$field] === $value)
		return $key;
	}
	return '';
    }

    public function savedesignAction() {
	$session = $this->_getSession();
	if ($this->_getSession()->isLoggedIn()) {
	    $this->loadLayout();
	    $this->renderLayout();
	} else {
	    $session->setAfterAuthUrl(Mage::helper('core/url')->getCurrentUrl());
	    $session->setBeforeAuthUrl(Mage::helper('core/url')->getCurrentUrl());
	    $this->_redirect('customer/account/login/');
	    return $this;
	}
    }

    public function mymediaAction() {
	$session = $this->_getSession();
	if ($this->_getSession()->isLoggedIn()) {
	    $this->loadLayout();
	    $this->renderLayout();
	} else {
	    $session->setAfterAuthUrl(Mage::helper('core/url')->getCurrentUrl());
	    $session->setBeforeAuthUrl(Mage::helper('core/url')->getCurrentUrl());
	    $this->_redirect('customer/account/login/');
	    return $this;
	}
    }

}

?>