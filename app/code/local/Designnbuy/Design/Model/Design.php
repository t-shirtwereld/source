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
class Designnbuy_Design_Model_Design extends Mage_Core_Model_Abstract {

    protected function _construct() {
	$this->_init('design/design');
    }

    public function getFinalPrice($qty = null, $product, $colorid, $sizeid) {
	if (is_null($qty) && !is_null($product->getCalculatedFinalPrice())) {
	    return $product->getCalculatedFinalPrice();
	}
	$finalPrice = $product->getFinalPrice($qty, $product);
	$finalPrice = Mage::getModel('catalog/product_type_price')->getFinalPrice($qty, $product);
	if ($colorid != '' || $sizeid != '' ) {
	    $baseprice = $finalPrice;
	    $product->getTypeInstance()->setStoreFilter($product->getStore());
	    $_attributes = $product->getTypeInstance(true)->getConfigurableAttributes($product);
	    foreach ($_attributes as $_attribute) {
		foreach ($_attribute->getprices() as $attributeoption) {
		    if ($attributeoption['value_index'] == $sizeid || $attributeoption['value_index'] == $colorid) {
			$finalPrice += $this->getPriceToAdd($attributeoption, $baseprice);
		    }
		}
	    }
	}
	return $finalPrice;
    }

    public function getAttributeOptionPrice($product, $finalPrice, $attributeOptionId) {
	$attributeOptionPrice = 0;
	$product->getTypeInstance()->setStoreFilter($product->getStore());
	$_attributes = $product->getTypeInstance(true)->getConfigurableAttributes($product);
	foreach ($_attributes as $_attribute) {
	    foreach ($_attribute->getprices() as $attributeOption) {
		if ($attributeOption['value_index'] == $attributeOptionId) {
		    $attributeOptionPrice = $this->getPriceToAdd($attributeOption, $finalPrice);
		}
	    }
	}
	return $attributeOptionPrice;
    }

    public function getCustomTierPrice($qutoeqty, $product) {
	$cnt = count($product->getTierPrice());
	if ($cnt > 0) {
	    $tierprices = $product->getTierPrice();
	    foreach ($tierprices as $tierprice) {
		$price_qty = $tierprice['price_qty'];
		if ($qutoeqty >= ceil($price_qty)) {
		    $newtierprice = $tierprice['price'];
		}
	    }
	}
	if (!isset($newtierprice)) {
	    $finalprice = $product->getPrice();
	} else {
	    $finalprice = $newtierprice;
	}
	return $finalprice;
    }

    public function getPriceToAdd($priceInfo, $productPrice) {
	if ($priceInfo['is_percent']) {
	    $ratio = $priceInfo['pricing_value'] / 100;
	    $price = $productPrice * $ratio;
	} else {
	    $price = $priceInfo['pricing_value'];
	}
	return $price;
    }

    public function renderChildrenCategory($catId) {
	$children = Mage::getModel('catalog/category')->load($catId)->getChildren();

	if (!empty($children)) {
	    $categories = explode(',', $children);

	    foreach ($categories as $categoryId) {
		$category = Mage::getModel('catalog/category')->load($categoryId);
		$xmlString .= '<category>';
		$xmlString .= '<catName>' . $category->getName() . '</catName>';
		$xmlString .= '<catID>' . $category->getId() . '</catID>';
		$xmlString .= '<orderNo>' . $category->getPosition() . '</orderNo>';
		$xmlString .= '<catDesc>' . $category->getDescription() . '</catDesc>';
		$categoryImage = Mage::getModel("catalog/category")->load($category->getId())->getImage();
		if ($categoryImage != '')
		    $xmlString .= '<catThumb>' . $path . '/media/catalog/category/' . $categoryImage . '</catThumb>';

		$xmlString .= '<type>' . 'subcategory' . '</type>';
		$this->renderChildrenCategory($categoryId);
		$xmlString .= "</category>";
	    }
	}
	return $xmlString;
    }

    public function getProductFromId($productId, $user, $extraoptions) {
	$product = Mage::getModel('catalog/product')->load($productId);
	if ($product->getTypeId() == 'configurable') {
	    $productData = $this->getConfigurableProductDataFromId($productId, $user, $extraoptions);
	} else {
	    $productData = $this->getSimpleProductDataFromId($productId, $user, $extraoptions);
	}
	return $productData;
    }

    public function getConfigurableProductDataFromId($productId, $user = '', $extra_option = '') {
	$postSession = Mage::getSingleton('core/session')->getPostSession();
	// $productId = $this->getProductId();
	$extraoptions = array();
	if (!empty($extra_option)) {
	    $extraoptions = unserialize(base64_decode($extra_option));
	}

	$data = array();
	$storeId = Mage::app()->getStore()->getStoreId();
	$customProductAttributeSetId = Mage::helper('design')->getCustomProductAttributeSetId();

	/* If product id not exist in case of design idea then get the first product id from collection */
	$productCollection = Mage::getModel('catalog/product')
		->getCollection()
		->addAttributeToFilter('entity_id', $productId)
		->AddFieldToFilter('is_customizable', 1)
		->addAttributeToFilter('type_id', 'configurable')
		->addAttributeToFilter('attribute_set_id', $customProductAttributeSetId)
		->addAttributeToSelect('*');
	$productCount = count($productCollection->getData());

	if ($productCount == 0 && $user != '') {
	    $productCollection = Mage::getModel('catalog/product')
		    ->getCollection()
		    ->AddFieldToFilter('is_customizable', 1)
		    ->AddFieldToFilter('status', 1)
		    ->addAttributeToFilter('type_id', 'configurable')
		    ->addAttributeToFilter('attribute_set_id', $designtoolAttributeSetId)
		    ->addAttributeToSelect('*');

	    Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($productCollection);
	    $firstProduct = $productCollection->getFirstItem();
	    $productId = $firstProduct->getEntityId();
	}

	$configProductId = $productId;

	$configProduct = Mage::getModel('catalog/product')->load($configProductId);

	$isMulticolor = $configProduct->getMulticolor();
	$specialprice = $configProduct->getSpecialPrice();
	$price = $configProduct->getPrice();
	if ($specialprice != '') {
	    $productprice = $specialprice;
	} else {
	    $productprice = $price;
	}

	$data['product']['product_id'] = $configProduct->getEntityId();
	$data['product']['name'] = $configProduct->getName();
	$data['product']['model'] = $configProduct->getSku();
	$data['product']['price'] = $productprice;
	$data['product']['is_customizable'] = $configProduct->getIsCustomizable();
	$data['product']['minimum'] = $configProduct->getStockItem()->getMinSaleQty();

	$data['product']['description'] = htmlspecialchars($configProduct->getShortDescription());

	if ($configProduct->getThumbnail() == 'no_selection' or $configProduct->getThumbnail() == '')
	    $data['product']['main_image'] = '';
	else
	    $data['product']['main_image'] = $configProduct->getThumbnail();

	if ($configProduct->getStatus() == '1')
	    $status = 'yes';
	else
	    $status = 'no';

	if ($isMulticolor == '1')
	    $data['product']['multiColor'] = 'yes';
	else
	    $data['product']['multiColor'] = 'no';

	$configurableProduct = Mage::getModel('catalog/product_type_configurable')->setProduct($configProduct);
	$configProduct->getTypeInstance()->setStoreFilter($configProduct->getStore());
	$productAttributeOptions = $configProduct->getTypeInstance(true)->getConfigurableAttributesAsArray($configProduct);

	$option_attr = $this->getOptionAttr('option_abbreviation');
	$colorkey = $this->getArray($productAttributeOptions, 'label', $option_attr['color_option']);
	$sizekey = $this->getArray($productAttributeOptions, 'label', $option_attr['size_option']);
	/* $attributeOptions = array();
	  $isColorAttribute = false;
	  $isSizeAttribute = false;

	  foreach ($productAttributeOptions as $productAttribute) {
	  if ($productAttribute['attribute_code'] == 'color'):
	  $isColorAttribute = true;
	  $data['product']['colorId'] = $productAttribute['attribute_id'];
	  endif;

	  if ($productAttribute['attribute_code'] == 'size'):
	  $isSizeAttribute = true;
	  $data['product']['sizeId'] = $productAttribute['attribute_id'];
	  endif;

	  foreach ($productAttribute['values'] as $attribute) {
	  $attributeOptions[$attribute['value_index']] = $attribute;
	  }
	  } */

	/* get associate product collection with status enabled products */
	$childProductCollection = $configurableProduct->getUsedProductCollection()
		->addAttributeToSelect('*')
		->addFilterByRequiredOptions();
	if ($user == '') {
	    $childProductCollection->addAttributeToFilter('status', array('eq' => 1));
	}
	//Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($childProductCollection);						
	/* filter associate products collection by "in stock" product */
	if ($user == '') {
	    Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($childProductCollection);
	}
	/* get All product ids of associate products */
	$childProdIds = $childProductCollection->getAllIds();

	//if($isColorAttribute == 1) {
	/* Get product collection by color attribute */
	$associateProductCollection = Mage::getModel('catalog/product')->getCollection()
		->AddAttributeToSelect('*')
		->addAttributeToFilter('type_id', 'simple')
		->AddFieldToFilter('entity_id', $childProdIds)
		->addOrder('entity_id', 'ASC');
	if ($user == '') {
	    $associateProductCollection->AddFieldToFilter('status', 1);
	}
	if ($isMulticolor == '1') {
	    $associateProductCollection->addAttributeToFilter('front_image', array('notnull' => '', 'neq' => 'no_selection'));
	}

	//   print "<pre>"; print_r($productAttributeOptions); exit;
	$allColors = array();
	$addtocartparam = array();
	if ($colorkey !== '' && $sizekey !== '') {
	    $data['productType'] = "configurable";
	    $associateProductCollection->groupbyAttribute($productAttributeOptions[$colorkey]['attribute_code']);
	    foreach ($associateProductCollection as $_associateProduct) {

		$productModel = Mage::getModel('catalog/product');
		$associateProduct = $productModel->load($_associateProduct->getEntityId());
		$colorAttribute = $productModel->getResource()->getAttribute($productAttributeOptions[$colorkey]['attribute_code']);
		$colorId = $associateProduct->getColor();
		if ($colorAttribute->usesSource()) {
		    $allOptions = $colorAttribute->getSource()->getAllOptions(true, true);
		    foreach ($allOptions as $option) {
			if ($option['value'] == $colorId) {
			    $colorLabel = $option['label'];
			    break;
			}
		    }
		    $colorName = explode('(', $colorLabel);
		    $colorText = $colorName[0];
		    $colorTemp = array_reverse($colorName);
		    $colorName = explode(')', $colorTemp[0]);
		}
		$colors['optionID'] = $colorId;

		if ($isMulticolor == 1) {
		    $colorImage = $associateProduct->getColorImage();
		    if ($colorImage == 'no_selection' or $colorImage == '')
			$colorImage = '';
		    else
			$colorImage = Mage::getModel('catalog/product_media_config')->getMediaUrl($colorImage);

		    $frontColorImage = $associateProduct->getFrontImage();
		    if ($frontColorImage == 'no_selection' or $frontColorImage == '')
			$frontColorImage = '';
		    else
			$frontColorImage = Mage::getModel('catalog/product_media_config')->getMediaUrl($frontColorImage);

		    $backColorImage = $associateProduct->getBackImage();
		    if ($backColorImage == 'no_selection' or $backColorImage == '')
			$backColorImage = '';
		    else
			$backColorImage = Mage::getModel('catalog/product_media_config')->getMediaUrl($backColorImage);

		    $leftColorImage = $associateProduct->getLeftImage();
		    if ($leftColorImage == 'no_selection' or $leftColorImage == '')
			$leftColorImage = '';
		    else
			$leftColorImage = Mage::getModel('catalog/product_media_config')->getMediaUrl($leftColorImage);

		    $rightColorImage = $associateProduct->getRightImage();
		    if ($rightColorImage == 'no_selection' or $rightColorImage == '')
			$rightColorImage = '';
		    else
			$rightColorImage = Mage::getModel('catalog/product_media_config')->getMediaUrl($rightColorImage);

		    $colors['colorimage'] = $colorImage;
		    $images[1] = $frontColorImage;
		    $images[2] = $backColorImage;
		    $images[3] = $leftColorImage;
		    $images[4] = $rightColorImage;
		    $colors['image'] = $images;
		}
		else {
		    $colors['optionName'] = $colorName[0];
		}
		$optionPrice = 0; //$this->getAttributeOptionPrice($configProduct,$colorId);
		$colors['priceModifier'] = $optionPrice;
		$colors['colorName'] = $colorText;

		$sizeProductCollection = Mage::getModel('catalog/product')->getCollection()
			->AddAttributeToSelect('*')
			->addAttributeToFilter('type_id', 'simple')
			->addAttributeToFilter('color', $colorId)
			->AddFieldToFilter('entity_id', $childProdIds)
			->groupbyAttribute('size');
		if ($user == '') {
		    $sizeProductCollection->AddFieldToFilter('status', 1);
		}

		$sizes = array();
		$size = array();
		foreach ($sizeProductCollection as $sizeProduct) {

		    $child = Mage::getModel('catalog/product')->load($sizeProduct->getId());
		    $availableQty = $child->getStockItem()->getQty();
		    $minSaleQty = $child->getStockItem()->getMinSaleQty();
		    $isConfigSetting = $child->getStockItem()->getUseConfigMaxSaleQty();
		    $maxSaleQty = $child->getStockItem()->getMaxSaleQty();
		    $minQty = min($availableQty, $maxSaleQty);
		    $sizeAttribute = $productModel->getResource()->getAttribute($productAttributeOptions[$sizekey]['attribute_code']);
		    $sizeId = $sizeProduct->getSize();
		    if ($sizeAttribute->usesSource()) {
			$sizeLabel = $sizeAttribute->getSource()->getOptionText($sizeId);
		    }

		    $addtocartparam = array(
			'productID' => $configProduct->getEntityId(),
			$productAttributeOptions[$colorkey]['attribute_id'] => $colorId,
			$productAttributeOptions[$sizekey]['attribute_id'] => $sizeId
		    );
		    foreach ($extraoptions as $extra_id => $extra_value) {
			$addtocartparam[$extra_id] = $extra_value;
		    }

		    $size['productID'] = $sizeProduct->getId();
		    $size['minQty'] = $minSaleQty;
		    $size['maxQty'] = $minQty;
		    $size['optionID'] = $sizeId;
		    $size['optionName'] = $sizeLabel;
		    $size['priceModifier'] = 0; //intval ($priceInfo['pricing_value']);	
		    $size['addtocartparam'] = $addtocartparam;

		    $sizes[] = $size;
		}

		$colors['sizes'] = $sizes;
		$allColors['color'][$colorId] = $colors;
	    }
	    $data['option'] = $allColors;
	} else if ($colorkey !== '') {
	    $data['productType'] = "onlycolor";
	    $associateProductCollection->groupbyAttribute($productAttributeOptions[$colorkey]['attribute_code']);
	    foreach ($associateProductCollection as $_associateProduct) {

		$productModel = Mage::getModel('catalog/product');
		$associateProduct = $productModel->load($_associateProduct->getEntityId());
		$colorAttribute = $productModel->getResource()->getAttribute($productAttributeOptions[$colorkey]['attribute_code']);
		$colorId = $associateProduct->getColor();
		if ($colorAttribute->usesSource()) {
		    $allOptions = $colorAttribute->getSource()->getAllOptions(true, true);
		    foreach ($allOptions as $option) {
			if ($option['value'] == $colorId) {
			    $colorLabel = $option['label'];
			    break;
			}
		    }
		    $colorName = explode('(', $colorLabel);
		    $colorText = $colorName[0];
		    $colorTemp = array_reverse($colorName);
		    $colorName = explode(')', $colorTemp[0]);
		}

		$child = Mage::getModel('catalog/product')->load($_associateProduct->getId());
		$availableQty = $child->getStockItem()->getQty();
		$minSaleQty = $child->getStockItem()->getMinSaleQty();
		$isConfigSetting = $child->getStockItem()->getUseConfigMaxSaleQty();
		$maxSaleQty = $child->getStockItem()->getMaxSaleQty();
		$minQty = min($availableQty, $maxSaleQty);
		$colors['productID'] = $_associateProduct->getId();
		$colors['minQty'] = $minSaleQty;
		$colors['maxQty'] = $minQty;
		$colors['optionID'] = $colorId;

		if ($isMulticolor == 1) {
		    $colorImage = $associateProduct->getColorImage();
		    if ($colorImage == 'no_selection' or $colorImage == '')
			$colorImage = '';
		    else
			$colorImage = Mage::getModel('catalog/product_media_config')->getMediaUrl($colorImage);

		    $frontColorImage = $associateProduct->getFrontImage();
		    if ($frontColorImage == 'no_selection' or $frontColorImage == '')
			$frontColorImage = '';
		    else
			$frontColorImage = Mage::getModel('catalog/product_media_config')->getMediaUrl($frontColorImage);

		    $backColorImage = $associateProduct->getBackImage();
		    if ($backColorImage == 'no_selection' or $backColorImage == '')
			$backColorImage = '';
		    else
			$backColorImage = Mage::getModel('catalog/product_media_config')->getMediaUrl($backColorImage);

		    $leftColorImage = $associateProduct->getLeftImage();
		    if ($leftColorImage == 'no_selection' or $leftColorImage == '')
			$leftColorImage = '';
		    else
			$leftColorImage = Mage::getModel('catalog/product_media_config')->getMediaUrl($leftColorImage);

		    $rightColorImage = $associateProduct->getRightImage();
		    if ($rightColorImage == 'no_selection' or $rightColorImage == '')
			$rightColorImage = '';
		    else
			$rightColorImage = Mage::getModel('catalog/product_media_config')->getMediaUrl($rightColorImage);

		    $colors['colorimage'] = $colorImage;
		    $images[1] = $frontColorImage;
		    $images[2] = $backColorImage;
		    $images[3] = $leftColorImage;
		    $images[4] = $rightColorImage;
		    $colors['image'] = $images;
		}
		else {
		    $colors['optionName'] = $colorName[0];
		}
		$optionPrice = 0; //$this->getAttributeOptionPrice($configProduct,$colorId);
		$colors['priceModifier'] = $optionPrice;
		$colors['colorName'] = $colorText;
		$addtocartparam = array(
		    'productID' => $configProduct->getEntityId(),
		    $productAttributeOptions[$colorkey]['attribute_id'] => $colorId
		);
		foreach ($extraoptions as $extra_id => $extra_value) {
		    $addtocartparam[$extra_id] = $extra_value;
		}
		$colors['addtocartparam'] = $addtocartparam;
		$allColors['color'][$colorId] = $colors;
	    }

	    $data['option'] = $allColors;
	} else if ($sizekey !== '') {
	    $data['productType'] = "onlysize";
	    $allSizes = array();
	    $size = array();
	    $associateProductCollection->groupbyAttribute($productAttributeOptions[$sizekey]['attribute_code']);
	    foreach ($associateProductCollection as $sizeProduct) {

		$productModel = Mage::getModel('catalog/product');
		$associateProduct = $productModel->load($sizeProduct->getEntityId());
		$sizeAttribute = $productModel->getResource()->getAttribute($productAttributeOptions[$sizekey]['attribute_code']);
		$child = Mage::getModel('catalog/product')->load($sizeProduct->getId());
		$availableQty = $child->getStockItem()->getQty();
		$minSaleQty = $child->getStockItem()->getMinSaleQty();
		$isConfigSetting = $child->getStockItem()->getUseConfigMaxSaleQty();
		$maxSaleQty = $child->getStockItem()->getMaxSaleQty();
		$minQty = min($availableQty, $maxSaleQty);

		$sizeId = $sizeProduct->getSize();
		if ($sizeAttribute->usesSource()) {
		    $sizeLabel = $sizeAttribute->getSource()->getOptionText($sizeId);
		}

		$size['productID'] = $sizeProduct->getId();
		$size['minQty'] = $minSaleQty;
		$size['maxQty'] = $minQty;
		$size['optionID'] = $sizeId;
		$size['optionName'] = $sizeLabel;
		$size['priceModifier'] = 0; //intval ($priceInfo['pricing_value']);	
		$addtocartparam = array(
		    'productID' => $configProduct->getEntityId(),
		    $productAttributeOptions[$sizekey]['attribute_id'] => $sizeId
		);
		foreach ($extraoptions as $extra_id => $extra_value) {
		    $addtocartparam[$extra_id] = $extra_value;
		}
		$size['addtocartparam'] = $addtocartparam;
		$allSizes = $size;
		$sizes['size'][$sizeId] = $allSizes;
	    }

	    $data['option'] = $sizes;
	} else {
	    $data['productType'] = "simple";
	    $data['option']['addtocartparam'] = array('productID' => $configProduct->getEntityId());
	}

	$postSession = Mage::getSingleton('core/session')->getPostSession();
	return Mage::helper('core')->jsonEncode($data);
    }

    public function getSimpleProductDataFromId($productId, $user = '', $extra_option = '') {
	
	// $productId = $this->getProductId();
	$storeId = Mage::app()->getStore()->getStoreId();
	$customProductAttributeSetId = Mage::helper('design')->getCustomProductAttributeSetId();
	/* If product id not exist in case of design idea then get the first product id from collection */

	$productCollection = Mage::getModel('catalog/product')
		->getCollection()
		->addAttributeToFilter('entity_id', $productId)
		->AddFieldToFilter('is_customizable', 1)
		->addAttributeToFilter('type_id', 'simple')
		->addAttributeToFilter('attribute_set_id', $customProductAttributeSetId)
		->addAttributeToSelect('*');
	$productCount = count($productCollection->getData());

	if ($productCount == 0 && $user != '') {
	    $productCollection = Mage::getModel('catalog/product')
		    ->getCollection()
		    ->AddFieldToFilter('is_customizable', 1)
		    ->AddFieldToFilter('status', 1)
		    ->addAttributeToFilter('type_id', 'configurable')
		    ->addAttributeToFilter('attribute_set_id', $designtoolAttributeSetId)
		    ->addAttributeToSelect('*');

	    Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($productCollection);
	    $firstProduct = $productCollection->getFirstItem();
	    $productId = $firstProduct->getEntityId();
	}

	$configProductId = $productId;

	$productData = array();
	$configProduct = Mage::getModel('catalog/product')->load($configProductId);

	//$isMulticolor = $configProduct->getMulticolor();

	$specialprice = $configProduct->getSpecialPrice();
	$price = $configProduct->getPrice();
	if ($specialprice != '') {
	    $productprice = $specialprice;
	} else {
	    $productprice = $price;
	}
	$data['productType'] = "simple";
	$data['product']['product_id'] = $configProduct->getEntityId();
	$data['product']['name'] = $configProduct->getName();
	$data['product']['model'] = $configProduct->getSku();
	$data['product']['price'] = $productprice;
	$data['product']['is_customizable'] = $configProduct->getIsCustomizable();
	$data['product']['minimum'] = $configProduct->getStockItem()->getMinSaleQty();

	$data['product']['description'] = htmlspecialchars($configProduct->getShortDescription());
	if ($configProduct->getThumbnail() == 'no_selection' or $configProduct->getThumbnail() == '')
	    $data['product']['main_image'] = '';
	else
	    $data['product']['main_image'] = $configProduct->getThumbnail();

	if ($configProduct->getStatus() == '1')
	    $status = 'yes';
	else
	    $status = 'no';

	$data['option']['addtocartparam'] = array('productID' => $configProduct->getEntityId());
	$postSession = Mage::getSingleton('core/session')->getPostSession();
	return Mage::helper('core')->jsonEncode($data);
    }

    public function getCategories($ParentCategory) {
	//$customProductAttributeSetId = Mage::helper('configuration')->getCustomProductAttributeSetId();
	$return = array();
	foreach (explode(',', $ParentCategory->getChildren()) as $categoryId) {
	    $category = Mage::getModel('catalog/category')->load($categoryId);
	    $_productCollection = Mage::getResourceModel('catalog/product_collection')
		    ->addAttributeToSelect('*')
		    // ->addFieldToFilter('attribute_set_id', $customProductAttributeSetId)
		    ->addCategoryFilter($category)
		    ->load();
	    $Category = Mage::getModel('catalog/category')->load($categoryId);
	    $return[$categoryId] = array(
		'id' => $Category->getId(),
		'name' => $Category->getName(),
		'productcount' => $_productCollection->count(),
	    );
	    if ($Category->getChildren()) {
		$return[$categoryId]['children'] = $this->getCategories($Category, $return);
	    } else {
		$return[$categoryId]['children'] = array();
	    }
	}
	return $return;
    }

    public function getCategoryProducts($categoryId, $storeId) {
	$catIds = explode(',',$categoryId);
	$productList = array();
	foreach($catIds as $catId) {
	$path = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
	$catagory_model = Mage::getModel('catalog/category')->load($catId);
	$products = Mage::getResourceModel('catalog/product_collection')
		->setStoreId($storeId)
		->addCategoryFilter($catagory_model) //category filter
		->AddFieldToFilter('is_customizable', 1)
		->AddFieldToFilter('status', 1)
		->addAttributeToSelect('*');

	$products->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
	
	$productMediaConfig = Mage::getModel('catalog/product_media_config');

	foreach ($products as $_product) {
	    $includeinlist = true;
	    $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product);
	    if ($_product->getis_salable() && $_product->getStockItem()->getis_in_stock()) {

		if ($_product->getTypeId() == 'configurable') {
		    $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $_product); // for associative simple product
		    $includeinlist = false;
		    foreach ($childProducts as $child) {
			if ($child->getStatus() == 1) {
			    if ($child->getStockItem()->getManageStock() == 1) {
				if ($child->getStockItem()->getQty() > 0 and $child->getStockItem()->getIsInStock() > 0) {
				    $includeinlist = true;
				    break;
				}
			    } else {
				$includeinlist = true;
				break;
			    }
			}
		    }
		}
		if ($includeinlist) {
		    $smallImageUrl = $productMediaConfig->getMediaUrl($_product->getSmallImage());		    
		    $product_id = $_product->getId();
		    $productList[$product_id] = array(
			'id' => $_product->getId(),
			'name' => $_product->getName(),
			'url' => Mage::helper('design')->getDesignPageUrl($_product) . 'tstamp/' . time(),
			'image' => (string) $smallImageUrl
		    );
		}
	    }
	}
	}

	return Mage::helper('core')->jsonEncode($productList);
    }

    public function getOptionAttr($attr) {
	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	$sql = "SELECT * FROM designnbuy_product_advance_configuration WHERE name = '" . $attr . "'";
	$row = $connection->fetchRow($sql); //fetchRow($sql), fetchOne($sql),...
	return Mage::helper('core')->jsonDecode($row['value']);
    }

    public function getArray($results, $field, $value) {
	foreach ($results as $key => $result) {
	    if ($result[$field] === $value)
		return $key;
	}
	return '';
    }

    public function getPrintingMethodFixedPrice($printingMethodId, $qty, $side) {
	$price = 0;
	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	$sql = "SELECT f.*,qr.* FROM designnbuy_printing_methods_fixedprice as f , designnbuy_qranges as qr WHERE f.printing_method_id = '" . $printingMethodId . "' AND f.quantity_range_id = qr.qrange_id AND qr.quantity_range_from <= '" . $qty . "' AND qr.quantity_range_to >= '" . $qty . "' ";

	$fixedPriceData = $connection->fetchRow($sql);

	if ($fixedPriceData) {
	    if ($side == 'side1') {
		$price = $fixedPriceData['first_side_price'];
	    }
	    if ($side == 'side2') {
		$price = $fixedPriceData['second_side_price'];
	    }
	    if ($side == 'side3') {
		$price = $fixedPriceData['third_side_price'];
	    }
	    if ($side == 'side4') {
		$price = $fixedPriceData['fourth_side_price'];
	    }
	    if ($side == 'side5') {
		$price = $fixedPriceData['fifth_side_price'];
	    }
	    if ($side == 'side6') {
		$price = $fixedPriceData['sixth_side_price'];
	    }
	    if ($side == 'side7') {
		$price = $fixedPriceData['seventh_side_price'];
	    }
	    if ($side == 'side8') {
		$price = $fixedPriceData['eighth_side_price'];
	    }
	}
	return $price;
    }

    public function getPrintingMethodQCPrice($printingMethodId, $noOfColors, $qty, $side) {
	$price = 0;
	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	$sql = "SELECT q.*,qr.*,c.* FROM designnbuy_printing_methods_qcprice as q , designnbuy_qranges as qr, designnbuy_color_counters as c WHERE q.printing_method_id = '" . $printingMethodId . "' AND q.quantity_range_id = qr.qrange_id AND q.color_counter_id = c.color_counter_id AND qr.quantity_range_from <= '" . $qty . "' AND qr.quantity_range_to >= '" . $qty . "' AND c.color_counter = '" . $noOfColors . "' ";
	$qcPriceData = $connection->fetchRow($sql);
	if ($qcPriceData) {
	    if ($side == 'side1') {
		$price = $qcPriceData['first_side_price'];
	    }
	    if ($side == 'side2') {
		$price = $qcPriceData['second_side_price'];
	    }
	    if ($side == 'side3') {
		$price = $qcPriceData['third_side_price'];
	    }
	    if ($side == 'side4') {
		$price = $qcPriceData['fourth_side_price'];
	    }
	    if ($side == 'side5') {
		$price = $qcPriceData['fifth_side_price'];
	    }
	    if ($side == 'side6') {
		$price = $qcPriceData['sixth_side_price'];
	    }
	    if ($side == 'side7') {
		$price = $qcPriceData['seventh_side_price'];
	    }
	    if ($side == 'side8') {
		$price = $qcPriceData['eighth_side_price'];
	    }
	}
	return $price;
    }

    public function getPrintingMethodQAPrice($printingMethodId, $area, $qty, $side) {
	$price = 0;

	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	$sql = "SELECT q.*,qr.*,s.* FROM designnbuy_printing_methods_qaprice as q , designnbuy_qranges as qr, designnbuy_sqarea as s WHERE q.printing_method_id = '" . $printingMethodId . "' AND q.quantity_range_id = qr.qrange_id AND q.sqarea_id = s.sqarea_id AND qr.quantity_range_from <= '" . $qty . "' AND qr.quantity_range_to >= '" . $qty . "' AND s.square_area >= '" . $area . "' ORDER BY s.square_area ASC ";
	$qaPriceData = $connection->fetchRow($sql);
	if ($qaPriceData) {
	    if ($side == 'side1') {
		$price = $qaPriceData['first_side_price'];
	    }
	    if ($side == 'side2') {
		$price = $qaPriceData['second_side_price'];
	    }
	    if ($side == 'side3') {
		$price = $qaPriceData['third_side_price'];
	    }
	    if ($side == 'side4') {
		$price = $qaPriceData['fourth_side_price'];
	    }
	    if ($side == 'side5') {
		$price = $qaPriceData['fifth_side_price'];
	    }
	    if ($side == 'side6') {
		$price = $qaPriceData['sixth_side_price'];
	    }
	    if ($side == 'side7') {
		$price = $qaPriceData['seventh_side_price'];
	    }
	    if ($side == 'side8') {
		$price = $qaPriceData['eighth_side_price'];
	    }
	}
	return $price;
    }

    public function getClipartPrice($clipartId) {
	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	$sql = "SELECT * FROM designnbuy_cliparts WHERE clipart_id = '" . $clipartId . "'";
	$clipartprice = $connection->fetchRow($sql);
	return $clipartprice['clipart_price'];
    }

    public function getPrintingMethodsForPricing($printing_method_id) {
	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	$sql = "SELECT * FROM designnbuy_printing_methods WHERE printing_method_id = '" . $printing_method_id . "' ";
	return $connection->fetchRow($sql);
    }

    public function getCartDesigndata($cart_design_id) {
	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	$sql = "SELECT * FROM designnbuy_cart_designs WHERE cart_design_id = '" . $cart_design_id . "'";
	return $connection->fetchRow($sql);
    }

    public function copyRowToOrderDesign($cart_design_id) {
	$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
	$sql = "INSERT designnbuy_order_designs (designed_id,product_id,compare_id,customization_unique_id,product_options_id,no_of_sides,printing_method,notes,side1_svg,side1_png,side1_otherdata,side2_svg,side2_png,side2_otherdata,side3_svg,side3_png,side3_otherdata,side4_svg,side4_png,side4_otherdata,side5_svg,side5_png,side5_otherdata,side6_svg,side6_png,side6_otherdata,side7_svg,side7_png,side7_otherdata,side8_svg,side8_png,side8_otherdata,name,number,name_number_data)SELECT designed_id,product_id,compare_id,customization_unique_id,product_options_id,no_of_sides,printing_method,notes,side1_svg,side1_png,side1_otherdata,side2_svg,side2_png,side2_otherdata,side3_svg,side3_png,side3_otherdata,side4_svg,side4_png,side4_otherdata,side5_svg,side5_png,side5_otherdata,side6_svg,side6_png,side6_otherdata,side7_svg,side7_png,side7_otherdata,side8_svg,side8_png,side8_otherdata,name,number,name_number_data FROM designnbuy_cart_designs WHERE cart_design_id = '" . $cart_design_id . "'";
	$connection->query($sql);
	$lastInsertId = $connection->lastInsertId();
	return $lastInsertId;
    }

    public function addOrderDesignRelation($order_design_id, $order_id, $order_product_id, $cart_design_id) {
	$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
	$sql = "INSERT INTO designnbuy_order_design_relation SET order_design_id = '" . (int) $order_design_id . "', order_id = '" . (int) $order_id . "', order_product_id = '" . (int) $order_product_id . "',cart_design_id = '" . $cart_design_id . "'";
	$connection->query($sql);
	$lastInsertId = $connection->lastInsertId();
	return $lastInsertId;
    }

    public function getOrderDesign($order_id, $order_product_id) {
	$data = array();
	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	$sql = "SELECT * FROM designnbuy_order_design_relation WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'";
	$res = $connection->fetchRow($sql);
	$sql1 = "SELECT * FROM designnbuy_order_designs WHERE order_design_id = '" . $res['order_design_id'] . "'";
	$data = $connection->fetchRow($sql1);
	$printingmethod = json_decode($data['printing_method'], true);
	$sql2 = "SELECT p.*,l.name,l.alert_message FROM designnbuy_printing_methods as p,designnbuy_printing_methods_lang as l WHERE p.printing_method_id = '" . $printingmethod['printingMethodId'] . "' AND p.printing_method_id = l.printing_method_id AND l.language_id = '1'";
	$pm = $connection->fetchRow($sql2);
	if (!empty($data)) {
	    $data['printing_method_name'] = $pm['name'];
	}
	return $data;
    }

}

?>