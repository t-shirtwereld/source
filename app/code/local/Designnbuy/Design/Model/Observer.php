<?php

class Designnbuy_Design_Model_Observer {

    public function invoiceSaveAfter(Varien_Event_Observer $observer) {
	$invoice = $observer->getEvent()->getInvoice();
	if ($invoice->getBaseSetupfeeAmount()) {
	    $order = $invoice->getOrder();
	    $order->setSetupfeeAmountInvoiced($order->getSetupfeeAmountInvoiced() + $invoice->getSetupfeeAmount());
	    $order->setBaseSetupfeeAmountInvoiced($order->getBaseSetupfeeAmountInvoiced() + $invoice->getBaseSetupfeeAmount());
	}
	return $this;
    }

    public function creditmemoSaveAfter(Varien_Event_Observer $observer) {
	/* @var $creditmemo Mage_Sales_Model_Order_Creditmemo */
	$creditmemo = $observer->getEvent()->getCreditmemo();
	if ($creditmemo->getSetupfeeAmount()) {
	    $order = $creditmemo->getOrder();
	    $order->setSetupfeeAmountRefunded($order->getSetupfeeAmountRefunded() + $creditmemo->getSetupfeeAmount());
	    $order->setBaseSetupfeeAmountRefunded($order->getBaseSetupfeeAmountRefunded() + $creditmemo->getBaseSetupfeeAmount());
	}
	return $this;
    }

    public function updatePaypalTotal($evt) {
	$cart = $evt->getPaypalCart();
	$cart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_SUBTOTAL, $cart->getSalesEntity()->getSetupfeeAmount());
    }

    public function saveOrderAfter($observer) {
	if (Mage::getSingleton('customer/session')->isLoggedIn()) {
	    // Load the customer's data
	    $customer = Mage::getSingleton('customer/session')->getCustomer();
	    $this->_customerId = $customer->getEntityId();
	}
	if (isset($observer['orders'])) {

	    $orders = $observer['orders'];
	    foreach ($orders as $order) {
		$orderIncrementId = $order['increment_id'];
		$orderData = Mage::getModel('sales/order')
			->getCollection()
			->addAttributeToFilter('increment_id', $orderIncrementId)
			->getFirstItem();

		$this->copyCartToOrderDesigns($orderData->getId());
	    }
	} else {
	    $this->copyCartToOrderDesigns($observer->getEvent()->getOrder()->getId());
	}
    }

    public function copyCartToOrderDesigns($orderId) {
	$order = Mage::getModel('sales/order')->load($orderId);
	// Get the items from the order
	$allItems = $order->getAllItems();
	$customProductAttributeSetId = $this->getCustomProductAttributeSetId();
	$compareId = array();
	foreach ($allItems as $item) {
	    $itemId = $item->getItemId();
	    $_item = Mage::getModel('sales/order_item')->load($itemId);
	    $quote = Mage::getModel('sales/quote_item')->load($_item->getQuoteItemId());
	    $cart_design_id = $quote->getCartDesignId();
	    $order_product_id = $quote->getProductId();
	    $_product = Mage::getModel('catalog/product')->load($order_product_id);

	    if ($cart_design_id != '' && $cart_design_id != 0 && $_product->getIsCustomizable() && ($_product->getAttributeSetId() == $customProductAttributeSetId || $_product->getTypeId() != 'configurable')) {
		if (!in_array($cart_design_id, $compareId)) {
		    $order_design_id = Mage::getModel('design/design')->copyRowToOrderDesign($cart_design_id);
		    $order_design_relation_id = Mage::getModel('design/design')->addOrderDesignRelation($order_design_id, $orderId, $itemId, $cart_design_id);
		    $cartdesign = Mage::getModel('design/design')->getCartDesignData($cart_design_id);
		    $src = Mage::getBaseDir() . '/designnbuy/assets/images/cartimages/' . $cartdesign['designed_id'] . '/';
		    $dst = Mage::getBaseDir() . '/designnbuy/assets/images/orderimages/' . $cartdesign['designed_id'] . '/';
		    $this->recurse_copy($src, $dst);
		    $compareId[] = $cart_design_id;
		}
	    }
	}
    }

    public function recurse_copy($src, $dst) {
	$dir = opendir($src);
	if (!is_dir($dst)) {
	    @mkdir($dst, 0777);
	    while (false !== ( $file = readdir($dir))) {
		if (( $file != '.' ) && ( $file != '..' )) {
		    if (is_dir($src . '/' . $file)) {
			recurse_copy($src . '/' . $file, $dst . '/' . $file);
		    } else {
			copy($src . '/' . $file, $dst . '/' . $file);
		    }
		}
	    }
	}
	closedir($dir);
    }

    public function getCustomProductAttributeSetId() {
	$attrSetName = "CustomProduct";
	$attributeSetId = Mage::getModel('eav/entity_attribute_set')
		->load($attrSetName, 'attribute_set_name')
		->getAttributeSetId();
	return $attributeSetId;
    }

}