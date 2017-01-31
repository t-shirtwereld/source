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
 * @package    Mage_Adminhtml
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Designnbuy_Design_Helper_Data extends Mage_Core_Helper_Abstract {

    protected function _getSession() {
	Mage::getSingleton('core/session', array('name' => 'frontend'));
	$session = Mage::getSingleton('customer/session', array('name' => 'frontend'));
	return $session;
    }

    public function getDesignPageUrl($product) {
	$categoryId = $product->getCategoryId();
	return $this->_getUrl('design/index/index', array(
		    'id' => $product->getId()
		));
    }

    public function getdesignUrl() {
	return $this->_getUrl('design/index/savedesign');
    }

    public function getMessageboardUrl($orderdata) {
	$order_id = $orderdata->getId();
	$language_id = Mage::app()->getStore()->getLocaleCode();
	$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designnbuy/pcstudio_output/message_board?order_id=' . $order_id . '&plateform=magento&siteBaseUrl=' . base64_encode(serialize(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB))).'&language_id=' . $language_id;
	return $url;
    }

    public function isCustomizedOrder($orderdata) {
	$orderId = $orderdata->getId();
	$order = Mage::getModel('sales/order')->load($orderId);
	$allItems = $order->getAllItems();
	$cart_designs = array();
	foreach ($allItems as $item) {
	    $itemId = $item->getItemId();
	    $_item = Mage::getModel('sales/order_item')->load($itemId);
	    $quote = Mage::getModel('sales/quote_item')->load($_item->getQuoteItemId());
	    $cart_design_id = $quote->getCartDesignId();
	    $orderdata = Mage::getModel('design/design')->getCartDesigndata($cart_design_id);
	    if (!empty($orderdata) && $orderdata['designed_id'] != 0) {
		$cart_designs[] = $cart_design_id;
	    }
	}
	if (!empty($cart_designs)) {
	    return true;
	} else {
	    return false;
	}
    }

    public function getDefaultProduct() {
	$productId = 0;
	$customProductAttributeSetId = Mage::helper('configuration')->getCustomProductAttributeSetId();

	if ($customProductAttributeSetId != '') {
	    $productCollection = Mage::getResourceModel('catalog/product_collection')
		    ->AddFieldToFilter('is_customizable', 1)
		    ->AddFieldToFilter('status', 1)
		    ->addAttributeToFilter('type_id', 'configurable')
		    ->addAttributeToFilter('attribute_set_id', $customProductAttributeSetId)
		    ->addAttributeToSelect('*');
	    Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($productCollection);
	    $productCollection = $productCollection->getFirstItem();
	    $productCollectionArray = $productCollection->getData();

	    if (count($productCollectionArray) > 0) {
		$productId = $productCollectionArray['entity_id'];
	    }
	}
	return $productId;
    }

    public function getCustomProductAttributeSetId() {
	$attrSetName = "CustomProduct";
	$attributeSetId = Mage::getModel('eav/entity_attribute_set')
		->load($attrSetName, 'attribute_set_name')
		->getAttributeSetId();
	return $attributeSetId;
    }

    public function removeCartDesignRow($cart_design_id) {
	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	$sql = "SELECT * FROM designnbuy_cart_designs WHERE cart_design_id = '" . $cart_design_id . "'";
	$result = $connection->fetchRow($sql);
	$dirpath = Mage::getBaseDir() . '/designnbuy/assets/images/cartimages/' . $result['designed_id'] . '/';
	$this->deleteDir($dirpath);
	$sql1 = "DELETE FROM designnbuy_cart_designs WHERE cart_design_id = '" . $cart_design_id . "'";
	$connection->query($sql1);
	$sql2 = "DELETE FROM designnbuy_order_design_relation WHERE cart_design_id = '" . $cart_design_id . "'";
	$connection->query($sql2);	
    }

    public function deleteDir($dirPath) {
	if (!is_dir($dirPath)) {
	    throw new InvalidArgumentException("$dirPath must be a directory");
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
    
    public function formatFee($amount){
	    return Mage::helper('design')->__('Artwork Setup Fee');
    }

}

?>