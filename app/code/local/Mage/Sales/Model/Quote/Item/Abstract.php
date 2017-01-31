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
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Quote item abstract model
 *
 * Price attributes:
 *  - price - initial item price, declared during product association
 *  - original_price - product price before any calculations
 *  - calculation_price - prices for item totals calculation
 *  - custom_price - new price that can be declared by user and recalculated during calculation process
 *  - original_custom_price - original defined value of custom price without any convertion
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author      Magento Core Team <core@magentocommerce.com>
 */
abstract class Mage_Sales_Model_Quote_Item_Abstract extends Mage_Core_Model_Abstract implements Mage_Catalog_Model_Product_Configuration_Item_Interface {

    protected $_parentItem = null;
    protected $_children = array();
    protected $_messages = array();

    /**
     * Retrieve Quote instance
     *
     * @return Mage_Sales_Model_Quote
     */
    abstract function getQuote();

    /**
     * Retrieve product model object associated with item
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct() {
	$product = $this->_getData('product');
	if (($product === null) && $this->getProductId()) {
	    $product = Mage::getModel('catalog/product')
		    ->setStoreId($this->getQuote()->getStoreId())
		    ->load($this->getProductId());
	    $this->setProduct($product);
	}

	/**
	 * Reset product final price because it related to custom options
	 */
	$product->setFinalPrice(null);
	if (is_array($this->_optionsByCode)) {
	    $product->setCustomOptions($this->_optionsByCode);
	}
	return $product;
    }

    /**
     * Returns special download params (if needed) for custom option with type = 'file'
     * Needed to implement Mage_Catalog_Model_Product_Configuration_Item_Interface.
     * Return null, as quote item needs no additional configuration.
     *
     * @return null|Varien_Object
     */
    public function getFileDownloadParams() {
	return null;
    }

    /**
     * Specify parent item id before saving data
     *
     * @return  Mage_Sales_Model_Quote_Item_Abstract
     */
    protected function _beforeSave() {
	parent::_beforeSave();
	if ($this->getParentItem()) {
	    $this->setParentItemId($this->getParentItem()->getId());
	}
	return $this;
    }

    /**
     * Set parent item
     *
     * @param  Mage_Sales_Model_Quote_Item $parentItem
     * @return Mage_Sales_Model_Quote_Item
     */
    public function setParentItem($parentItem) {
	if ($parentItem) {
	    $this->_parentItem = $parentItem;
	    $parentItem->addChild($this);
	}
	return $this;
    }

    /**
     * Get parent item
     *
     * @return Mage_Sales_Model_Quote_Item
     */
    public function getParentItem() {
	return $this->_parentItem;
    }

    /**
     * Get chil items
     *
     * @return array
     */
    public function getChildren() {
	return $this->_children;
    }

    /**
     * Add child item
     *
     * @param  Mage_Sales_Model_Quote_Item_Abstract $child
     * @return Mage_Sales_Model_Quote_Item_Abstract
     */
    public function addChild($child) {
	$this->setHasChildren(true);
	$this->_children[] = $child;
	return $this;
    }

    /**
     * Adds message(s) for quote item. Duplicated messages are not added.
     *
     * @param  mixed $messages
     * @return Mage_Sales_Model_Quote_Item_Abstract
     */
    public function setMessage($messages) {
	$messagesExists = $this->getMessage(false);
	if (!is_array($messages)) {
	    $messages = array($messages);
	}
	foreach ($messages as $message) {
	    if (!in_array($message, $messagesExists)) {
		$this->addMessage($message);
	    }
	}
	return $this;
    }

    /**
     * Add message of quote item to array of messages
     *
     * @param   string $message
     * @return  Mage_Sales_Model_Quote_Item_Abstract
     */
    public function addMessage($message) {
	$this->_messages[] = $message;
	return $this;
    }

    /**
     * Get messages array of quote item
     *
     * @param   bool $string flag for converting messages to string
     * @return  array|string
     */
    public function getMessage($string = true) {
	if ($string) {
	    return join("\n", $this->_messages);
	}
	return $this->_messages;
    }

    /**
     * Removes message by text
     *
     * @param string $text
     * @return Mage_Sales_Model_Quote_Item_Abstract
     */
    public function removeMessageByText($text) {
	foreach ($this->_messages as $key => $message) {
	    if ($message == $text) {
		unset($this->_messages[$key]);
	    }
	}
	return $this;
    }

    /**
     * Clears all messages
     *
     * @return Mage_Sales_Model_Quote_Item_Abstract
     */
    public function clearMessage() {
	$this->unsMessage(); // For older compatibility, when we kept message inside data array
	$this->_messages = array();
	return $this;
    }

    /**
     * Retrieve store model object
     *
     * @return Mage_Core_Model_Store
     */
    public function getStore() {
	return $this->getQuote()->getStore();
    }

    /**
     * Checking item data
     *
     * @return Mage_Sales_Model_Quote_Item_Abstract
     */
    public function checkData() {
	$this->setHasError(false);
	$this->clearMessage();

	$qty = $this->_getData('qty');

	try {
	    $this->setQty($qty);
	} catch (Mage_Core_Exception $e) {
	    $this->setHasError(true);
	    $this->setMessage($e->getMessage());
	} catch (Exception $e) {
	    $this->setHasError(true);
	    $this->setMessage(Mage::helper('sales')->__('Item qty declaration error.'));
	}

	try {
	    $this->getProduct()->getTypeInstance(true)->checkProductBuyState($this->getProduct());
	} catch (Mage_Core_Exception $e) {
	    $this->setHasError(true);
	    $this->setMessage($e->getMessage());
	    $this->getQuote()->setHasError(true);
	    $this->getQuote()->addMessage(
		    Mage::helper('sales')->__('Some of the products below do not have all the required options. Please edit them and configure all the required options.')
	    );
	} catch (Exception $e) {
	    $this->setHasError(true);
	    $this->setMessage(Mage::helper('sales')->__('Item options declaration error.'));
	    $this->getQuote()->setHasError(true);
	    $this->getQuote()->addMessage(Mage::helper('sales')->__('Items options declaration error.'));
	}

	return $this;
    }

    /**
     * Get original (not related with parent item) item quantity
     *
     * @return  int|float
     */
    public function getQty() {
	return $this->_getData('qty');
    }

    /**
     * Get total item quantity (include parent item relation)
     *
     * @return  int|float
     */
    public function getTotalQty() {
	if ($this->getParentItem()) {
	    return $this->getQty() * $this->getParentItem()->getQty();
	}
	return $this->getQty();
    }

    /**
     * Calculate item row total price
     *
     * @return Mage_Sales_Model_Quote_Item
     */
    public function calcRowTotal() {
	$qty = $this->getTotalQty();
	// Round unit price before multiplying to prevent losing 1 cent on subtotal
	$total = $this->getStore()->roundPrice($this->getCalculationPriceOriginal()) * $qty;
	$baseTotal = $this->getBaseCalculationPriceOriginal() * $qty;

	$this->setRowTotal($this->getStore()->roundPrice($total));
	$this->setBaseRowTotal($this->getStore()->roundPrice($baseTotal));
	return $this;
    }

    /**
     * Get item price used for quote calculation process.
     * This method get custom price (if it is defined) or original product final price
     *
     * @return float
     */
    public function getCalculationPrice() {
	$price = $this->_getData('calculation_price');
	if (is_null($price)) {
	    if ($this->hasCustomPrice()) {
		$price = $this->getCustomPrice();
	    } else {
		$price = $this->getConvertedPrice();
	    }
	    $this->setData('calculation_price', $price);
	}
	return $price;
    }

    /**
     * Get item price used for quote calculation process.
     * This method get original custom price applied before tax calculation
     *
     * @return float
     */
    public function getCalculationPriceOriginal() {
	$price = $this->_getData('calculation_price');
	if (is_null($price)) {
	    if ($this->hasOriginalCustomPrice()) {
		$price = $this->getOriginalCustomPrice();
	    } else {
		$price = $this->getConvertedPrice();
	    }

	    /* get addon price added by Ajay */
	    if ($this instanceof Mage_Sales_Model_Quote_Address_Item) {
		$itemId = $this->getData('quote_item_id');
		$item = Mage::getModel('sales/quote_item')->load($this->getData('quote_item_id'));
	    } else {
		$itemId = $this->getData('item_id');
		$item = Mage::getModel('sales/quote_item')->load($this->getData('item_id'));
	    }

	    if ($item->getCartDesignId()) {
		$cart_design_id = $item->getCartDesignId();
		$qty = $this->getData('qty');
		$cartdata = Mage::getModel('design/design')->getCartDesigndata($cart_design_id);
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
		$clipartprices = 0;

		$pritingMetohdData = json_decode(html_entity_decode($cartdata['printing_method']), true);
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
			if ($pritingMetohdData['isCustomized']['Side1'] == 'true' || $pritingMetohdData['isCustomized']['Side1'] == '1') {
			    $side1FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side1');
			    
			}
			if ($pritingMetohdData['isCustomized']['Side2'] == 'true' || $pritingMetohdData['isCustomized']['Side2'] == '1') {
			    $side2FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side2');
			}
			if ($pritingMetohdData['isCustomized']['Side3'] == 'true' || $pritingMetohdData['isCustomized']['Side3'] == '1') {
			    $side3FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side3');
			}
			if ($pritingMetohdData['isCustomized']['Side4'] == 'true' || $pritingMetohdData['isCustomized']['Side4'] == '1') {
			    $side4FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side4');
			}
			if ($pritingMetohdData['isCustomized']['Side5'] == 'true' || $pritingMetohdData['isCustomized']['Side5'] == '1') {
			    $side5FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side5');
			}
			if ($pritingMetohdData['isCustomized']['Side6'] == 'true' || $pritingMetohdData['isCustomized']['Side6'] == '1') {
			    $side6FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side6');
			}
			if ($pritingMetohdData['isCustomized']['Side7'] == 'true' || $pritingMetohdData['isCustomized']['Side7'] == '1') {
			    $side7FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side7');
			}
			if ($pritingMetohdData['isCustomized']['Side8'] == 'true' || $pritingMetohdData['isCustomized']['Side8'] == '1') {
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
			    $side7SQArea = Mage::getModel('design/design')->getPrintingMethodQAPrice($printingMethodId, $side7Area, $qty, 'side8');
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
			if (isset($pritingMetohdData['squareArea']['Side1'])) {
			    $side1Colors = intval($pritingMetohdData['totalColors']['Side1']);
			}
			if (isset($pritingMetohdData['squareArea']['Side2'])) {
			    $side2Colors = intval($pritingMetohdData['totalColors']['Side2']);
			}
			if (isset($pritingMetohdData['squareArea']['Side3'])) {
			    $side3Colors = intval($pritingMetohdData['totalColors']['Side3']);
			}
			if (isset($pritingMetohdData['squareArea']['Side4'])) {
			    $side4Colors = intval($pritingMetohdData['totalColors']['Side4']);
			}
			if (isset($pritingMetohdData['squareArea']['Side5'])) {
			    $side5Colors = intval($pritingMetohdData['totalColors']['Side5']);
			}
			if (isset($pritingMetohdData['squareArea']['Side6'])) {
			    $side6Colors = intval($pritingMetohdData['totalColors']['Side6']);
			}
			if (isset($pritingMetohdData['squareArea']['Side7'])) {
			    $side7Colors = intval($pritingMetohdData['totalColors']['Side7']);
			}
			if (isset($pritingMetohdData['squareArea']['Side8'])) {
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
		    $nameprice = 0;
		    $numberprice = 0;
		    if(isset($cartdata['name']) && $cartdata['name'] != '' && $cartdata['name'] != 0) {
			$nameprice = $printingMethodData['name_price'] * $qty;
		    }
		    if(isset($cartdata['number']) && $cartdata['number'] != '' && $cartdata['number'] != 0) {
			$numberprice = $printingMethodData['number_price'] * $qty;
		    }
		    $nameNumberPrice = $nameprice + $numberprice;
		   // $price = $price + (($imageTotalPrice + $printingPrice + $nameNumberPrice)/$qty);
		    
		}
		$printingMethodPrice = 0;
		$printingMethodPrice = $totalQcPrice + $totalSquareAreaPrice + $totalFixedPrice + $imageTotalPrice;
		if(isset($pritingMetohdData['sideWiseClipIDs'])) {		
		    $clipartprices = $this->calculateClipartPrice($pritingMetohdData['sideWiseClipIDs'], $qty);
		}
		$price = $price + (($printingMethodPrice + $clipartprices + $nameNumberPrice) / $qty);
	    }
	    $this->setData('calculation_price', $price);
	}
	return $price;
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

    /**
     * Get calculation price used for quote calculation in base currency.
     *
     * @return float
     */
    public function getBaseCalculationPrice() {
	if (!$this->hasBaseCalculationPrice()) {
	    if ($this->hasCustomPrice()) {
		$price = (float) $this->getCustomPrice();
		if ($price) {
		    $rate = $this->getStore()->convertPrice($price) / $price;
		    $price = $price / $rate;
		}
	    } else {
		$price = $this->getPrice();
	    }
	    $this->setBaseCalculationPrice($price);
	}
	return $this->_getData('base_calculation_price');
    }

    /**
     * Get original calculation price used for quote calculation in base currency.
     *
     * @return float
     */
    public function getBaseCalculationPriceOriginal() {
	if (!$this->hasBaseCalculationPrice()) {
	    if ($this->hasOriginalCustomPrice()) {
		$price = (float) $this->getOriginalCustomPrice();
		if ($price) {
		    $rate = $this->getStore()->convertPrice($price) / $price;
		    $price = $price / $rate;
		}
	    } else {
		$price = $this->getPrice();
	    }
	    /* get addon price added by Ajay */
	    if ($this instanceof Mage_Sales_Model_Quote_Address_Item) {
		$itemId = $this->getData('quote_item_id');
		$item = Mage::getModel('sales/quote_item')->load($this->getData('quote_item_id'));
	    } else {
		$itemId = $this->getData('item_id');
		$item = Mage::getModel('sales/quote_item')->load($this->getData('item_id'));
	    }
	    if ($item->getCartDesignId()) {
		$qty = $this->getData('qty');
		
		$cart_design_id = $item->getCartDesignId();
		$cartdata = Mage::getModel('design/design')->getCartDesigndata($cart_design_id);
		
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
		$clipartprices = 0;

		$pritingMetohdData = Mage::helper('core')->jsonDecode($cartdata['printing_method']);

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
			if ($pritingMetohdData['isCustomized']['Side1'] == 'true' || $pritingMetohdData['isCustomized']['Side1'] == '1') {		    
			    $side1FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side1');
			    
			}
			if ($pritingMetohdData['isCustomized']['Side2'] == 'true' || $pritingMetohdData['isCustomized']['Side2'] == '1') {
			    $side2FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side2');
			}
			if ($pritingMetohdData['isCustomized']['Side3'] == 'true' || $pritingMetohdData['isCustomized']['Side3'] == '1') {
			    $side3FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side3');
			}
			if ($pritingMetohdData['isCustomized']['Side4'] == 'true' || $pritingMetohdData['isCustomized']['Side4'] == '1') {
			    $side4FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side4');
			}
			if ($pritingMetohdData['isCustomized']['Side5'] == 'true' || $pritingMetohdData['isCustomized']['Side5'] == '1') {
			    $side5FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side5');
			}
			if ($pritingMetohdData['isCustomized']['Side6'] == 'true' || $pritingMetohdData['isCustomized']['Side6'] == '1') {
			    $side6FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side6');
			}
			if ($pritingMetohdData['isCustomized']['Side7'] == 'true' || $pritingMetohdData['isCustomized']['Side7'] == '1') {
			    $side7FixedPrice = Mage::getModel('design/design')->getPrintingMethodFixedPrice($printingMethodId, $qty, 'side7');
			}
			if ($pritingMetohdData['isCustomized']['Side8'] == 'true' || $pritingMetohdData['isCustomized']['Side8'] == '1') {
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
			    $side7SQArea = Mage::getModel('design/design')->getPrintingMethodQAPrice($printingMethodId, $side7Area, $qty, 'side8');
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
			if (isset($pritingMetohdData['squareArea']['Side1'])) {
			    $side1Colors = intval($pritingMetohdData['totalColors']['Side1']);
			}
			if (isset($pritingMetohdData['squareArea']['Side2'])) {
			    $side2Colors = intval($pritingMetohdData['totalColors']['Side2']);
			}
			if (isset($pritingMetohdData['squareArea']['Side3'])) {
			    $side3Colors = intval($pritingMetohdData['totalColors']['Side3']);
			}
			if (isset($pritingMetohdData['squareArea']['Side4'])) {
			    $side4Colors = intval($pritingMetohdData['totalColors']['Side4']);
			}
			if (isset($pritingMetohdData['squareArea']['Side5'])) {
			    $side5Colors = intval($pritingMetohdData['totalColors']['Side5']);
			}
			if (isset($pritingMetohdData['squareArea']['Side6'])) {
			    $side6Colors = intval($pritingMetohdData['totalColors']['Side6']);
			}
			if (isset($pritingMetohdData['squareArea']['Side7'])) {
			    $side7Colors = intval($pritingMetohdData['totalColors']['Side7']);
			}
			if (isset($pritingMetohdData['squareArea']['Side8'])) {
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
		    $nameprice = 0;
		    $numberprice = 0;
		    if(isset($cartdata['name']) && $cartdata['name'] != '' && $cartdata['name'] != 0) {
			$nameprice = $printingMethodData['name_price'] * $qty;
		    }
		    if(isset($cartdata['number']) && $cartdata['number'] != '' && $cartdata['number'] != 0) {
			$numberprice = $printingMethodData['number_price'] * $qty;
		    }
		    $nameNumberPrice = $nameprice + $numberprice;
		    
		}
		$printingMethodPrice = 0;
		$printingMethodPrice = $totalQcPrice + $totalSquareAreaPrice + $totalFixedPrice + $imageTotalPrice;
		if(isset($pritingMetohdData['sideWiseClipIDs'])) {		
		    $clipartprices = $this->calculateClipartPrice($pritingMetohdData['sideWiseClipIDs'], $qty);
		}
		$price = $price + (($printingMethodPrice + $clipartprices + $nameNumberPrice) / $qty);
		//$price = $price + (($imageTotalPrice + $printingPrice + $nameNumberPrice)/$qty);
	    }
	    $this->setBaseCalculationPrice($price);
	}
	return $this->_getData('base_calculation_price');
    }

    /**
     * Get whether the item is nominal
     * TODO: fix for multishipping checkout
     *
     * @return bool
     */
    public function isNominal() {
	if (!$this->hasData('is_nominal')) {
	    $this->setData('is_nominal', $this->getProduct() ? '1' == $this->getProduct()->getIsRecurring() : false);
	}
	return $this->_getData('is_nominal');
    }

    /**
     * Data getter for 'is_nominal'
     * Used for converting item to order item
     *
     * @return int
     */
    public function getIsNominal() {
	return (int) $this->isNominal();
    }

    /**
     * Get original price (retrieved from product) for item.
     * Original price value is in quote selected currency
     *
     * @return float
     */
    public function getOriginalPrice() {
	$price = $this->_getData('original_price');
	if (is_null($price)) {
	    $price = $this->getStore()->convertPrice($this->getBaseOriginalPrice());
	    $this->setData('original_price', $price);
	}
	return $price;
    }

    /**
     * Set original price to item (calculation price will be refreshed too)
     *
     * @param   float $price
     * @return  Mage_Sales_Model_Quote_Item_Abstract
     */
    public function setOriginalPrice($price) {
	return $this->setData('original_price', $price);
    }

    /**
     * Get Original item price (got from product) in base website currency
     *
     * @return float
     */
    public function getBaseOriginalPrice() {
	return $this->_getData('base_original_price');
    }

    /**
     * Specify custom item price (used in case whe we have apply not product price to item)
     *
     * @param   float $value
     * @return  Mage_Sales_Model_Quote_Item_Abstract
     */
    public function setCustomPrice($value) {
	$this->setCalculationPrice($value);
	$this->setBaseCalculationPrice(null);
	return $this->setData('custom_price', $value);
    }

    /**
     * Get item price. Item price currency is website base currency.
     *
     * @return decimal
     */
    public function getPrice() {
	return $this->_getData('price');
    }

    /**
     * Specify item price (base calculation price and converted price will be refreshed too)
     *
     * @param   float $value
     * @return  Mage_Sales_Model_Quote_Item_Abstract
     */
    public function setPrice($value) {
	$this->setBaseCalculationPrice(null);
	$this->setConvertedPrice(null);
	return $this->setData('price', $value);
    }

    /**
     * Get item price converted to quote currency
     * @return float
     */
    public function getConvertedPrice() {
	$price = $this->_getData('converted_price');
	if (is_null($price)) {
	    $price = $this->getStore()->convertPrice($this->getPrice());
	    $this->setData('converted_price', $price);
	}
	return $price;
    }

    /**
     * Set new value for converted price
     * @param float $value
     * @return Mage_Sales_Model_Quote_Item_Abstract
     */
    public function setConvertedPrice($value) {
	$this->setCalculationPrice(null);
	$this->setData('converted_price', $value);
	return $this;
    }

    /**
     * Clone quote item
     *
     * @return Mage_Sales_Model_Quote_Item
     */
    public function __clone() {
	$this->setId(null);
	$this->_parentItem = null;
	$this->_children = array();
	$this->_messages = array();
	return $this;
    }

    /**
     * Checking if there children calculated or parent item
     * when we have parent quote item and its children
     *
     * @return bool
     */
    public function isChildrenCalculated() {
	if ($this->getParentItem()) {
	    $calculate = $this->getParentItem()->getProduct()->getPriceType();
	} else {
	    $calculate = $this->getProduct()->getPriceType();
	}

	if ((null !== $calculate) && (int) $calculate === Mage_Catalog_Model_Product_Type_Abstract::CALCULATE_CHILD) {
	    return true;
	}
	return false;
    }

    /**
     * Checking can we ship product separatelly (each child separately)
     * or each parent product item can be shipped only like one item
     *
     * @return bool
     */
    public function isShipSeparately() {
	if ($this->getParentItem()) {
	    $shipmentType = $this->getParentItem()->getProduct()->getShipmentType();
	} else {
	    $shipmentType = $this->getProduct()->getShipmentType();
	}

	if ((null !== $shipmentType) &&
		(int) $shipmentType === Mage_Catalog_Model_Product_Type_Abstract::SHIPMENT_SEPARATELY) {
	    return true;
	}
	return false;
    }

    /**
     * Calculate item tax amount
     *
     * @deprecated logic moved to tax totals calculation model
     * @return  Mage_Sales_Model_Quote_Item
     */
    public function calcTaxAmount() {
	$store = $this->getStore();

	if (!Mage::helper('tax')->priceIncludesTax($store)) {
	    if (Mage::helper('tax')->applyTaxAfterDiscount($store)) {
		$rowTotal = $this->getRowTotalWithDiscount();
		$rowBaseTotal = $this->getBaseRowTotalWithDiscount();
	    } else {
		$rowTotal = $this->getRowTotal();
		$rowBaseTotal = $this->getBaseRowTotal();
	    }

	    $taxPercent = $this->getTaxPercent() / 100;

	    $this->setTaxAmount($store->roundPrice($rowTotal * $taxPercent));
	    $this->setBaseTaxAmount($store->roundPrice($rowBaseTotal * $taxPercent));

	    $rowTotal = $this->getRowTotal();
	    $rowBaseTotal = $this->getBaseRowTotal();
	    $this->setTaxBeforeDiscount($store->roundPrice($rowTotal * $taxPercent));
	    $this->setBaseTaxBeforeDiscount($store->roundPrice($rowBaseTotal * $taxPercent));
	} else {
	    if (Mage::helper('tax')->applyTaxAfterDiscount($store)) {
		$totalBaseTax = $this->getBaseTaxAmount();
		$totalTax = $this->getTaxAmount();

		if ($totalTax && $totalBaseTax) {
		    $totalTax -= $this->getDiscountAmount() * ($this->getTaxPercent() / 100);
		    $totalBaseTax -= $this->getBaseDiscountAmount() * ($this->getTaxPercent() / 100);

		    $this->setBaseTaxAmount($store->roundPrice($totalBaseTax));
		    $this->setTaxAmount($store->roundPrice($totalTax));
		}
	    }
	}

	if (Mage::helper('tax')->discountTax($store) && !Mage::helper('tax')->applyTaxAfterDiscount($store)) {
	    if ($this->getDiscountPercent()) {
		$baseTaxAmount = $this->getBaseTaxBeforeDiscount();
		$taxAmount = $this->getTaxBeforeDiscount();

		$baseDiscountDisposition = $baseTaxAmount / 100 * $this->getDiscountPercent();
		$discountDisposition = $taxAmount / 100 * $this->getDiscountPercent();

		$this->setDiscountAmount($this->getDiscountAmount() + $discountDisposition);
		$this->setBaseDiscountAmount($this->getBaseDiscountAmount() + $baseDiscountDisposition);
	    }
	}

	return $this;
    }

    /**
     * Get item tax amount
     *
     * @deprecated
     * @return  decimal
     */
    public function getTaxAmount() {
	return $this->_getData('tax_amount');
    }

    /**
     * Get item base tax amount
     *
     * @deprecated
     * @return decimal
     */
    public function getBaseTaxAmount() {
	return $this->_getData('base_tax_amount');
    }

    /**
     * Get item price (item price always exclude price)
     *
     * @deprecated
     * @return decimal
     */
    protected function _calculatePrice($value, $saveTaxes = true) {
	$store = $this->getQuote()->getStore();

	if (Mage::helper('tax')->priceIncludesTax($store)) {
	    $bAddress = $this->getQuote()->getBillingAddress();
	    $sAddress = $this->getQuote()->getShippingAddress();

	    $address = $this->getAddress();

	    if ($address) {
		switch ($address->getAddressType()) {
		    case Mage_Sales_Model_Quote_Address::TYPE_BILLING:
			$bAddress = $address;
			break;
		    case Mage_Sales_Model_Quote_Address::TYPE_SHIPPING:
			$sAddress = $address;
			break;
		}
	    }

	    if ($this->getProduct()->getIsVirtual()) {
		$sAddress = $bAddress;
	    }

	    $priceExcludingTax = Mage::helper('tax')->getPrice(
		    $this->getProduct()->setTaxPercent(null), $value, false, $sAddress, $bAddress, $this->getQuote()->getCustomerTaxClassId(), $store
	    );

	    $priceIncludingTax = Mage::helper('tax')->getPrice(
		    $this->getProduct()->setTaxPercent(null), $value, true, $sAddress, $bAddress, $this->getQuote()->getCustomerTaxClassId(), $store
	    );

	    if ($saveTaxes) {
		$qty = $this->getQty();
		if ($this->getParentItem()) {
		    $qty = $qty * $this->getParentItem()->getQty();
		}

		if (Mage::helper('tax')->displayCartPriceInclTax($store)) {
		    $rowTotal = $value * $qty;
		    $rowTotalExcTax = Mage::helper('tax')->getPrice(
			    $this->getProduct()->setTaxPercent(null), $rowTotal, false, $sAddress, $bAddress, $this->getQuote()->getCustomerTaxClassId(), $store
		    );
		    $rowTotalIncTax = Mage::helper('tax')->getPrice(
			    $this->getProduct()->setTaxPercent(null), $rowTotal, true, $sAddress, $bAddress, $this->getQuote()->getCustomerTaxClassId(), $store
		    );
		    $totalBaseTax = $rowTotalIncTax - $rowTotalExcTax;
		    $this->setRowTotalExcTax($rowTotalExcTax);
		} else {
		    $taxAmount = $priceIncludingTax - $priceExcludingTax;
		    $this->setTaxPercent($this->getProduct()->getTaxPercent());
		    $totalBaseTax = $taxAmount * $qty;
		}

		$totalTax = $this->getStore()->convertPrice($totalBaseTax);
		$this->setTaxBeforeDiscount($totalTax);
		$this->setBaseTaxBeforeDiscount($totalBaseTax);

		$this->setTaxAmount($totalTax);
		$this->setBaseTaxAmount($totalBaseTax);
	    }

	    $value = $priceExcludingTax;
	}

	return $value;
    }

}
