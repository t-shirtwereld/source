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
class Designnbuy_Design_Block_Design extends Mage_Core_Block_Template {

    public function getIframeUrl() {
	$qty = 1;
	$design_id = 0;
	$cart_id = 0;
	$product_id = 0;
	$color_id = 0;
	$size_id = 0;
	$extraoptions = array();
	$option = $_POST['super_attribute'];
	if ($_POST['qty']) {
	    $qty = $_POST['qty'];
	} else {
	    $qty = '1';
	}
	if ($this->getRequest()->getParam('design_id')) {
	    $design_id = $this->getRequest()->getParam('design_id');
	}
	if ($this->getRequest()->getParam('cart_id')) {
	    $cart_id = $this->getRequest()->getParam('cart_id');
	}
	if ($this->getRequest()->getParam('cart_design_id')) {
	    $cart_design_id = $this->getRequest()->getParam('cart_design_id');
	}
	if ($this->getRequest()->getParam('id')) {
	    $product_id = $this->getRequest()->getParam('id');
	}
	if(isset($cart_id) && isset($cart_design_id) && $cart_id != '' && $cart_design_id != '') {
	    $configQuoteData = Mage::getModel('sales/quote_item')->load($cart_id);
	    $configProduct = Mage::getModel('catalog/product')->load($configQuoteData->getProductId());
	    $extra = array();
	    $color_id = 0;
	    $size_id = 0;
	    if($configProduct->getTypeId() == 'configurable'){
		$simpleQuoteData = Mage::getModel('sales/quote_item')->load($cart_id,'parent_item_id');
		$finalQuotedata = Mage::getModel('sales/quote_item_option')->load($simpleQuoteData->getItemId(),'item_id');
		$option = unserialize($finalQuotedata->getValue());	      
		$productId = $simpleQuoteData->getProductId();
		$product = Mage::getModel('catalog/product')->load($productId);	    
			$colorOptionId = $product->getColor();
			$sizeOptionId = $product->getSize();
			foreach($option['super_attribute'] as $option_key => $option_value){
			    if ($option_value == $colorOptionId) {
				$color_id = $colorOptionId;
			    } else if ($option_value == $sizeOptionId) {
				$size_id = $sizeOptionId;
			    } else {
				$extra = $extra + array($option_key => $option_value);
			    }
			}	    								
	    }  
	    $qty = (int)$configQuoteData->getQty();
	    $product_id = $configQuoteData->getProductId();
	    $extraoptions = base64_encode(serialize($extra));
	    $plateform = 'magento';
	    $siteBaseUrl = base64_encode(serialize(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)));
	    $language_id = Mage::app()->getStore()->getLocaleCode();
	    $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designnbuy/pcstudio/editCart?product_id=' . $product_id . '&qty=' . $qty . '&color_id=' . $color_id . '&size_id=' . $size_id . '&extraoptions=' . $extraoptions . '&cart_id=' . $cart_id . '&cart_design_id=' . $cart_design_id . '&plateform=' . $plateform . '&siteBaseUrl=' . $siteBaseUrl . '&language_id=' . $language_id;
	} else if($design_id != '') {
	    $plateform = 'magento';
	    $siteBaseUrl = base64_encode(serialize(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)));
	    $language_id = Mage::app()->getStore()->getLocaleCode();
	    $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designnbuy/pcstudio/editMyDesign?design_id=' . $design_id . '&plateform=' . $plateform . '&siteBaseUrl=' . $siteBaseUrl . '&language_id=' . $language_id;
	} else {
	$configProduct = Mage::getModel('catalog/product')->load($product_id);
	$extra = array();
	$color_id = 0;
	$size_id = 0;
	if($configProduct->getTypeId() == 'configurable'){
	    $productAttributeOptions = $configProduct->getTypeInstance(true)->getConfigurableAttributesAsArray($configProduct);
	    $option_attr = Mage::getModel('design/design')->getOptionAttr('option_abbreviation');
	    $colorkey = $this->getArray($productAttributeOptions, 'label', $option_attr['color_option']);
	    $sizekey = $this->getArray($productAttributeOptions, 'label', $option_attr['size_option']);
	    $colorAttributeId = Mage::getModel('eav/config')->getAttribute('catalog_product', $productAttributeOptions[$colorkey]['attribute_code'])->getAttributeId();
	    $sizeAttributeId = Mage::getModel('eav/config')->getAttribute('catalog_product', $productAttributeOptions[$sizekey]['attribute_code'])->getAttributeId();	    
	    
	    foreach ($option as $option_id => $value) {
		if ($option_id == $colorAttributeId) {
		    $color_id = $value;
		} else if ($option_id == $sizeAttributeId) {
		    $size_id = $value;
		} else {
		    $extra = $extra + array($option_id => $value);
		}
	    }
	}
	$extraoptions = base64_encode(serialize($extra));
	$plateform = 'magento';
	$siteBaseUrl = base64_encode(serialize(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)));
	$language_id = Mage::app()->getStore()->getLocaleCode();	
	    $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)  . 'designnbuy/pcstudio?product_id=' . $product_id . '&qty=' . $qty . '&color_id=' . $color_id . '&size_id=' . $size_id . '&extraoptions=' . $extraoptions . '&plateform=' . $plateform . '&siteBaseUrl=' . $siteBaseUrl . '&language_id=' . $language_id;
	}
	
	return $url;
    }
    
    public function getSaveDesignIframeUrl() {
	$plateform = 'magento';
	$siteBaseUrl = base64_encode(serialize(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)));
	$customerData = Mage::getSingleton('customer/session')->getCustomer();
	$customer_id = $customerData->getId();
	$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designnbuy/pcstudio_media/mydesign?customer_id=' . $customer_id . '&plateform=' . $plateform . '&siteBaseUrl=' . $siteBaseUrl;
	return $url;
    }

    public function getMyMediaIframeUrl() {
	$plateform = 'magento';
	$siteBaseUrl = base64_encode(serialize(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)));
	$customerData = Mage::getSingleton('customer/session')->getCustomer();
	$customer_id = $customerData->getId();
	$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designnbuy/pcstudio_media/mymedia?customer_id=' . $customer_id . '&plateform=' . $plateform . '&siteBaseUrl=' . $siteBaseUrl;
	return $url;
    }
    public function getArray($results, $field, $value) {
	foreach ($results as $key => $result) {
	    if ($result[$field] === $value)
		return $key;
	}
	return '';
    }

}