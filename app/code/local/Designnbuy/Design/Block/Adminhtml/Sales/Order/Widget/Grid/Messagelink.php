<?php

class Designnbuy_Design_Block_Adminhtml_Sales_Order_Widget_Grid_Messagelink extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
	return ($this->_getLink($row));
    }

    protected function _getLink(Varien_Object $row) {
	$order_id = $row->getId();
	$order = Mage::getModel('sales/order')->load($order_id);
	$allItems = $order->getAllItems();
	foreach ($allItems as $item) {
	    $itemId = $item->getItemId();
	    $_item = Mage::getModel('sales/order_item')->load($itemId);
	    $quote = Mage::getModel('sales/quote_item')->load($_item->getQuoteItemId());
	    $cart_design_id = $quote->getCartDesignId();
	    $orderdata = Mage::getModel('design/design')->getCartDesigndata($cart_design_id);
	    if (!empty($orderdata) && $orderdata['designed_id'] != 0) {
		$storeid = Mage::app()->getStore()->getId();
		$language_id = 'en_US';	
		$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designnbuy/pcstudio_output/message_board_admin?order_id=' . $order_id . '&plateform=magento&siteBaseUrl=' . base64_encode(serialize(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB))). '&language_id=' . $language_id;
		return "<a href='" . $url . "' class='message_board' data-fancybox-type='iframe'>" . Mage::helper('sales')->__('Message Board') . "</a>";
	    }
	}
    }
}